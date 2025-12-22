<?php
class Document extends Model {
    
    protected $table = "documents_personnel";
    public $default_per_page = 5;

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($docID)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE doc_id = :doc_id");
        $stmt->execute(['doc_id' => $docID]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllDcPsn($personnelID)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                    D.*, 
                    DP.intitule_diplome, 
                    DP.etablissement, 
                    DP.ville, 
                    DP.pays, 
                    DP.niveau_etude, 
                    DP.annee_obtention 

                FROM {$this->table} D 
                    LEFT JOIN diplomes AS DP
                        ON DP.personnel_id = D.personnel_id
                
                WHERE D.personnel_id = :personnel_id
                ORDER BY D.created_at");
                
        $stmt->execute([
            'personnel_id'  => $personnelID
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByTypeDoc($typeDocId, $matricule)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE type_doc_id = :type_doc_id AND matricule = :matricule");
        $stmt->execute([
            'type_doc_id' => $typeDocId,
            'matricule' => $matricule
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'id')
    {
        $query = "UPDATE $this->table SET ";

        foreach ($datas as $key => $data) 
        {
            if($key !== $where)
            {
                $query .= "$key = :$key, ";
            }
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE $where = :$where";

        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function cheminDossierPdf($typeDoc, $personnelID)
    {
        $entreprise = COMPANY_NAME_;

        $pathDossier = 'storage/uploads/pdf/' . "{$entreprise}/{$personnelID}/{$typeDoc}";
        if(!is_dir($pathDossier)) 
        {
            mkdir($pathDossier, 0777, true);
        }

        return $pathDossier;
    }

    public function generteNomFichierPdf($typeDoc)
    {
        $codeEntreprise = "MSL-". COMPANY_NAME_;
        $date = date('Y-m-d');
        $heure = date('H\hi\ms\s');
        $unique = strtoupper(bin2hex(random_bytes(5)));

        return "{$codeEntreprise}_{$typeDoc}_{$date}_{$heure}_{$unique}.pdf";
    }
    
    public function chiffreePdf($filePdf, $filePdfChiffree, $key)
    {            
        // $data = file_get_contents($filePdf);
        // $iv = openssl_random_pseudo_bytes(16);
        // $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        // if(file_put_contents($filePdfChiffree, $iv . $encrypted)) return true;

        // 1. Lecture du contenu du fichier
        $data = file_get_contents($filePdf);
        if ($data === false) {
            // En cas d'échec de lecture (fichier inexistant ou permission)
            error_log("chiffreePdf: Echec de lecture du fichier PDF: " . $filePdf);
            return false;
        }

        // 2. Chiffrement
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        
        // Si openssl_encrypt échoue
        if ($encrypted === false) {
            error_log("chiffreePdf: Echec du chiffrement OpenSSL pour: " . $filePdf);
            return false;
        }

        // 3. Écriture du fichier chiffré (IV + données chiffrées)
        $result = file_put_contents($filePdfChiffree, $iv . $encrypted);
        
        // file_put_contents retourne le nombre d'octets écrits ou FALSE en cas d'échec
        if ($result !== false && $result > 0) {
            return true;
        } else {
            error_log("chiffreePdf: Echec d'écriture du fichier chiffré: " . $filePdfChiffree);
            return false;
        }
    }

    public function dechiffreePdf($filePdf, $filePdfChiffree, $key)
    {
        $raw = file_get_contents($filePdfChiffree);
        $iv = substr($raw, 0,16);
        $encryptedData = substr($raw, 16);
    
        $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key,OPENSSL_RAW_DATA, $iv);
        if(file_put_contents($filePdf,$decrypted)) return true;                
    }

    public function allDcs(int $page = 1, ?string $is_required = null, ?int $per_page = null, ?string $search = null): array
    {
        $limit = $per_page ?? $this->default_per_page;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Initialisation des conditions et des paramètres
        $conditions = [];
        $params = [];

        // Filtre sur le caractère obligatoire
        if ($is_required !== null) {
            $conditions[] = "D.statut_conformite = :statut_conformite";
            $params['statut_conformite'] = $is_required;
        }

        // Filtre sur la recherche (nom du document par exemple)
        if (!empty($search)) {
            $conditions[] = "D.nom_fichier_original LIKE :search"; // Adaptez 'nom_fichier_original_doc' selon votre colonne réelle
            $params['search'] = "%$search%";
        }

        // Construction de la clause WHERE
        $whereClause = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";

        // 1. Requête pour le nombre total d'enregistrements
        $sql_count = "SELECT COUNT(*) FROM $this->table AS D" . $whereClause;
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 2. Requête pour les enregistrements de la page actuelle
        $sql = "SELECT D.* FROM $this->table AS D" . $whereClause;
        $sql .= " ORDER BY D.created_at ASC LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);

        // Liaison dynamique de tous les paramètres présents
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, PDO::PARAM_STR);
        }

        $q->execute();
        $alldocs = $q->fetchAll(PDO::FETCH_OBJ);

        // Retourne les données paginées et les métadonnées
        return [
            'alldocs'       => $alldocs,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }

    public function allDcs2(int $page = 1, ?string $is_required = null, ?int $per_page = null, ?string $search = null): array
    {
        $limit = $per_page ?? $this->default_per_page;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Initialisation des conditions et des paramètres
        $conditions = [];
        $params = [];

        // Filtre sur le caractère obligatoire
        if ($is_required !== null) {
            $conditions[] = "D.statut_conformite = :statut_conformite";
            $params['statut_conformite'] = $is_required;
        }

        // Filtre sur la recherche (nom du fichier ou nom du personnel)
        if (!empty($search)) {
            // Recherche étendue au nom et postnom du personnel
            $conditions[] = "(D.nom_fichier_original LIKE :search OR P.nom LIKE :search OR P.postnom LIKE :search)";
            $params['search'] = "%$search%";
        }

        // Construction de la clause WHERE
        $whereClause = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";

        // Définition de la jointure
        $joinClause = " INNER JOIN personnel AS P ON D.personnel_id = P.personnel_id";

        // 1. Requête pour le nombre total d'enregistrements (avec jointure pour la cohérence des filtres)
        $sql_count = "SELECT COUNT(*) FROM $this->table AS D" . $joinClause . $whereClause;
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 2. Requête pour les enregistrements de la page actuelle
        // On récupère toutes les colonnes de D et spécifiquement nom/postnom de P
        $sql = "SELECT D.*, P.nom AS personnel_nom, P.postnom AS personnel_postnom 
                FROM $this->table AS D" . 
                $joinClause . 
                $whereClause;
        
        $sql .= " ORDER BY D.created_at ASC LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);

        // Liaison dynamique de tous les paramètres présents
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, PDO::PARAM_STR);
        }

        $q->execute();
        $alldocs = $q->fetchAll(PDO::FETCH_OBJ);

        // Retourne les données paginées et les métadonnées
        return [
            'alldocs'       => $alldocs,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }

    public function getDataWithPourcentage($where, $year)
    {
        $sql = ("SELECT 
                    $where, 
                    COUNT(*) AS total_par_statut, 
                    ROUND((COUNT(*) / total.total_documents) * 100) AS pourcentage FROM $this->table,
                    (SELECT COUNT(*) AS total_documents FROM $this->table) AS total
                
                WHERE $where IS NOT NULL AND $where != '' AND YEAR(created_at) = $year 
                GROUP BY $where
                ORDER BY total_par_statut DESC");

        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getDocTypeStatistique($where, $year)
    {
        $sql = "SELECT 
                    $where, 
                    COUNT(*) AS total, 
                    SUM(CASE WHEN status = 'en cours' THEN 1 ELSE 0 END) AS en_traitement,
                    SUM(CASE WHEN status = 'traité' THEN 1 ELSE 0 END) AS traites,
                    ROUND(
                        (SUM(CASE WHEN status = 'traité' THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                        1
                    ) AS taux_traitement,
                    CONCAT(
                        LPAD(FLOOR(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)) / 3600), 2), 'h ', 
                        LPAD(FLOOR(MOD(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)), 3600) / 60), 2, '0'), 'min ',
                        LPAD(FLOOR(MOD(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)), 60)), 2, '0'), 'sec '
                    ) AS delai_moyen

                FROM $this->table
                WHERE $where IS NOT NULL AND $where != '' AND YEAR(created_at) = $year 
                GROUP BY $where
                ORDER BY total DESC";

        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDelaitraiteVSDelaiimparti()
    {
        $sql = "SELECT 
                    ref_num,
                    -- objet,
                    DATE_FORMAT(date_reception, '%d/%m/%Y') AS date_reception,
                    DATE_FORMAT(date_limite, '%d/%m/%Y') AS date_limite,
                    DATE_FORMAT(date_classement, '%d/%m/%Y') AS date_classement,
                    CONCAT(
                        FLOOR(TIMESTAMPDIFF(MINUTE, date_reception, date_classement) / 1440), 'jrs ', 
                        FLOOR(MOD(TIMESTAMPDIFF(MINUTE, date_reception, date_classement), 1440) / 60), 'h ',
                            MOD(TIMESTAMPDIFF(MINUTE, date_reception, date_classement), 60), 'min'
                    ) AS delai_traitement,
                    CONCAT(
                        FLOOR(TIMESTAMPDIFF(MINUTE, date_reception, date_limite) / 1440), 'jrs ', 
                        FLOOR(MOD(TIMESTAMPDIFF(MINUTE, date_reception, date_limite), 1440) / 60), 'h'
                    ) AS delai_imparti,

                    CASE
                        WHEN date_limite IS NULL THEN 'Non défini'
                        WHEN date_classement > date_limite THEN 'Dépassé'
                        ELSE 'Respecté'
                    END AS statut_delai

                    FROM $this->table
                    WHERE status = 'classé'
                    LIMIT 15";

        $q = $this->db->prepare($sql);
        $q->execute();
        
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGlobalCourierMonth()
    {
        $sql = "SELECT 
                    DATE_FORMAT(date_reception, '%m/%Y') AS mois,
                    COUNT(*) AS total_couriers,
                    SUM(CASE WHEN status = 'en cours' THEN 1 ELSE 0 END) AS en_cours,
                    SUM(CASE WHEN status = 'traité' THEN 1 ELSE 0 END) AS traites,
                    SUM(CASE WHEN status = 'classé sans suite' THEN 1 ELSE 0 END) AS classe_sans_suite,
                    SUM(CASE WHEN status = 'classé' THEN 1 ELSE 0 END) AS classes
                
                FROM $this->table
                WHERE date_reception IS NOT NULL
                GROUP BY YEAR(date_reception), MONTH(date_reception)
                ORDER BY YEAR(date_reception), MONTH(date_reception)";
                
        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCourier ($by, $param) 
    {
        $sql = ("SELECT * FROM $this->table WHERE $by = :$by");
        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function getAllDataSelect($select)
    {
        return $this->db->query("SELECT $select FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllDataSelectWhere($select, $where, $param)
    {
        $sql = "SELECT $select FROM $this->table WHERE crated_at IS NOT NULL AND $where = :$where";
        $q = $this->db->prepare($sql);
        $q->execute([$where => $param]);
        
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCourierTempsEcoule() 
    {
        $sql = "SELECT * FROM $this->table WHERE date_limite IS NOT NULL AND date_limite <> '' AND date_limite < NOW() AND status = 'en cours' ORDER BY date_reception DESC";
        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function generateReceptionNum()
    {
        $sql = "SELECT reception_num FROM $this->table WHERE reception_num IS NOT NULL AND category = 'entrant' ORDER BY reception_num DESC LIMIT 1";
        $q = $this->db->prepare($sql);
        $q->execute();
        $last = $q->fetch(PDO::FETCH_OBJ);

        if(!$last || empty($last->reception_num)) return '001';

        $num = (int) $last->reception_num + 1;
        
        return str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function nbrCourierTempsEcoule()
    {
        return count($this->getCourierTempsEcoule());
    }

    public function getCouriersByYear($year)
    {
        $sql = ("SELECT * FROM $this->table WHERE YEAR(created_at) = $year");
        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }
    public function getCouriersAll($by, $param, $year)
    {
        $sql = ("SELECT * FROM $this->table WHERE $by = :$by AND YEAR(created_at) = $year");
        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCouriersAllSelect()
    {
        $sql = "SELECT 
                    provenance, 
                    objet, 
                    ref_num, 
                    reception_num, 
                    category, 
                    type, 
                    priority, 
                    date_reception, 
                    status, 
                    saved_by 
                    
                FROM $this->table 
                WHERE date_reception IS NOT NULL 
                ORDER BY date_reception DESC";

        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function addHeures ($date, $heures) {
        $datetime = new DateTime($date);
        $datetime->modify("+".$heures." hours");
        return $datetime->format('Y-m-d H:i');
    }

    public function showMoratoire($date_limite, $status) 
    {
        if($date_limite !== '') 
        {
            if($date_limite !== null) 
            {
                switch ($status) {
                    case 'en cours':
                        return Helper::tempsRestant($date_limite);
                    
                    case 'traité' || 'classé sans suite' || 'classé':
                        return 'Date limite : '. Helper::formatDate($date_limite);
                }
            }
        } 

        return 'Non défini';
    }

    public function getRecepNum()
    {
        $sql = "SELECT reception_num FROM $this->table WHERE category = 'entrant' ORDER BY id DESC LIMIT 1 OFFSET 1";
        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetch(PDO::FETCH_OBJ);

    }

    public function getAllDocsDr($destinataireId, $search = null)
    {
        // --- 1. GESTION PAGINATION VIA $_GET['p'] ---
        $page = isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0 
                ? intval($_GET['p']) 
                : 1;

        $perPage = 1;
        $offset = ($page - 1) * $perPage;

        // --- 2. BASE SQL ---
        $baseSql = "FROM $this->table d 
                    LEFT JOIN documents_transmissions dt 
                        ON dt.id = ( 
                            SELECT t.id 
                            FROM documents_transmissions t 
                            WHERE t.doc_id = d.doc_id 
                            ORDER BY t.date_envoi DESC LIMIT 1
                        )
                    LEFT JOIN users u 
                        ON u.user_id = dt.expediteur_id
                    WHERE 1 = 1";

        $params = [];

        // --- 3. SEARCH (HTMX) ---
        if (!empty($search)) {
            $baseSql .= " AND (
                            (d.titre IS NOT NULL AND d.titre LIKE ?) 
                            OR (d.objet IS NOT NULL AND d.objet LIKE ?)
                            OR (d.ref_num IS NOT NULL AND d.ref_num LIKE ?)
                            OR (u.nom IS NOT NULL AND u.nom LIKE ?)
                            OR (u.direction IS NOT NULL AND u.direction LIKE ?)
                        )";

            $like = "%{$search}%";
            $params = array_merge($params, [$like, $like, $like, $like, $like]);
        }

        // --- 4. CONDITION PRINCIPALE ---
        $baseSql .= " AND (dt.destinataire_id = ? OR d.expediteur_initial_id = ?)";
        $params[] = $destinataireId;
        $params[] = $destinataireId;

        // --- 5. REQUETE COMPTE TOTAL ---
        $countSql = "SELECT COUNT(*) AS total " . $baseSql;

        $stmtCount = $this->db->prepare($countSql);
        $stmtCount->execute($params);
        $total = (int) $stmtCount->fetch(PDO::FETCH_OBJ)->total;

        // --- 6. REQUETE AVEC LIMIT ---
        $sql = "SELECT 
                    d.*, 
                    dt.id AS transmission_id,
                    dt.doc_id AS transmission_doc_id,
                    dt.id_transmission_retour,
                    dt.role_expediteur AS transmission_role_expediteur,
                    dt.role_destinataire AS transmission_role_destinataire,
                    dt.direction AS transmission_direction,
                    dt.td AS transmission_td,
                    dt.details AS transmission_details,
                    dt.expediteur_id AS transmission_expediteur_id,
                    dt.destinataire_id AS transmission_destinataire_id,
                    dt.date_envoi AS transmission_date_envoi,
                    dt.is_retour AS transmission_is_retour,
                    dt.is_urgent AS transmission_is_urgent,
                    dt.reponse AS transmission_reponse,
                    dt.fichier_enc AS transmission_fichier_enc,
                    dt.description AS transmission_description,
                    dt.date_lecture AS transmission_date_lecture,
                    dt.date_retour AS transmission_date_retour,
                    dt.date_traitement AS transmission_date_traitement,
                    dt.date_classement AS transmission_date_classement,
                    dt.statut AS transmission_statut,
                    u.nom AS expediteur_nom,
                    u.direction AS expediteur_direction
                " 
                . $baseSql . 
                " ORDER BY d.created_at DESC 
                LIMIT $perPage OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        // --- 7. RETURN ---
        return [
            "data"        => $data,
            "total"       => $total,
            "page"        => $page,
            "perPage"     => $perPage,
            "totalPages"  => ceil($total / $perPage)
        ];
    }

    
    // public function getAllDocsDr($destinataireId, $search = null, $page = 1, $perPage = 10)
    // {
    //     // --- 1. Base SQL ---
    //     $baseSql = "FROM $this->table d 
    //                 LEFT JOIN documents_transmissions dt 
    //                     ON dt.id = ( 
    //                         SELECT t.id 
    //                         FROM documents_transmissions t 
    //                         WHERE t.doc_id = d.doc_id 
    //                         ORDER BY t.date_envoi DESC LIMIT 1
    //                     )
    //                 LEFT JOIN users u 
    //                     ON u.user_id = dt.expediteur_id
    //                 WHERE 1 = 1";

    //     $params = [];

    //     // --- 2. SEARCH ---
    //     if (!empty($search)) {
    //         $baseSql .= " AND (
    //                         (d.titre IS NOT NULL AND d.titre LIKE ?) 
    //                         OR (d.objet IS NOT NULL AND d.objet LIKE ?)
    //                         OR (d.ref_num IS NOT NULL AND d.ref_num LIKE ?)
    //                         OR (u.nom IS NOT NULL AND u.nom LIKE ?)
    //                         OR (u.direction IS NOT NULL AND u.direction LIKE ?)
    //                     )";

    //         $like = "%{$search}%";
    //         $params = array_merge($params, [$like, $like, $like, $like, $like]);
    //     }

    //     // --- 3. CONDITION PRINCIPALE ---
    //     $baseSql .= " AND (dt.destinataire_id = ? OR d.expediteur_initial_id = ?)";

    //     $params[] = $destinataireId;
    //     $params[] = $destinataireId;

    //     // --- 4. PAGINATION CALCUL ---
    //     $offset = ($page - 1) * $perPage;

    //     // --- 5. REQUETE POUR COMPTER LE TOTAL ---
    //     $sqlCount = "SELECT COUNT(*) AS total " . $baseSql;

    //     $stmtCount = $this->db->prepare($sqlCount);
    //     $stmtCount->execute($params);
    //     $total = (int)$stmtCount->fetch(PDO::FETCH_OBJ)->total;

    //     // --- 6. REQUETE AVEC LIMIT ---
    //     $sql = "SELECT 
    //                 d.*, 
    //                 dt.id AS transmission_id,
    //                 dt.doc_id AS transmission_doc_id,
    //                 dt.id_transmission_retour,
    //                 dt.role_expediteur AS transmission_role_expediteur,
    //                 dt.role_destinataire AS transmission_role_destinataire,
    //                 dt.direction AS transmission_direction,
    //                 dt.td AS transmission_td,
    //                 dt.details AS transmission_details,
    //                 dt.expediteur_id AS transmission_expediteur_id,
    //                 dt.destinataire_id AS transmission_destinataire_id,
    //                 dt.date_envoi AS transmission_date_envoi,
    //                 dt.is_retour AS transmission_is_retour,
    //                 dt.is_urgent AS transmission_is_urgent,
    //                 dt.reponse AS transmission_reponse,
    //                 dt.fichier_enc AS transmission_fichier_enc,
    //                 dt.description AS transmission_description,
    //                 dt.date_lecture AS transmission_date_lecture,
    //                 dt.date_retour AS transmission_date_retour,
    //                 dt.date_traitement AS transmission_date_traitement,
    //                 dt.date_classement AS transmission_date_classement,
    //                 dt.statut AS transmission_statut,
    //                 u.nom AS expediteur_nom,
    //                 u.direction AS expediteur_direction
    //             " 
    //             . $baseSql . 
    //             " ORDER BY d.created_at DESC 
    //             LIMIT $perPage OFFSET $offset";

    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute($params);

    //     $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    //     // --- 7. RETURN ---
    //     return [
    //         "data" => $data,
    //         "total" => $total,
    //         "page" => $page,
    //         "perPage" => $perPage,
    //         "totalPages" => ceil($total / $perPage)
    //     ];
    // }

    // public function getAllDocsDr($destinataireId, $search = null)
    // {
    //     $sql = "SELECT 
    //                 d.*, 
    //                 dt.id AS transmission_id,
    //                 dt.doc_id AS transmission_doc_id,
    //                 dt.id_transmission_retour,
    //                 dt.role_expediteur AS transmission_role_expediteur,
    //                 dt.role_destinataire AS transmission_role_destinataire,
    //                 dt.direction AS transmission_direction,
    //                 dt.td AS transmission_td,
    //                 dt.details AS transmission_details,
    //                 dt.expediteur_id AS transmission_expediteur_id,
    //                 dt.destinataire_id AS transmission_destinataire_id,
    //                 dt.date_envoi AS transmission_date_envoi,
    //                 dt.is_retour AS transmission_is_retour,
    //                 dt.is_urgent AS transmission_is_urgent,
    //                 dt.reponse AS transmission_reponse,
    //                 dt.fichier_enc AS transmission_fichier_enc,
    //                 dt.description AS transmission_description,
    //                 dt.date_envoi AS transmission_date_envoi,
    //                 dt.date_lecture AS transmission_date_lecture,
    //                 dt.date_retour AS transmission_date_retour,
    //                 dt.date_traitement AS transmission_date_traitement,
    //                 dt.date_classement AS transmission_date_classement,
    //                 dt.statut AS transmission_statut,
    //                 u.nom AS expediteur_nom,
    //                 u.direction AS expediteur_direction

    //             FROM $this->table d 
    //             LEFT JOIN documents_transmissions dt 
    //                 ON dt.id = ( 
    //                     SELECT t.id 
    //                     FROM documents_transmissions t 
    //                     WHERE t.doc_id = d.doc_id 
    //                     ORDER BY t.date_envoi DESC LIMIT 1
    //                 )
    //             LEFT JOIN users u 
    //                 ON u.user_id = dt.expediteur_id
    //             WHERE 1 = 1";

    //     // Ajout SEARCH si non vide
    //     $params = [];

    //     if (!empty($search)) {
    //         $sql .= " AND (
    //                     (d.titre IS NOT NULL AND d.titre LIKE ?) 
    //                     OR (d.objet IS NOT NULL AND d.objet LIKE ?)
    //                     OR (d.ref_num IS NOT NULL AND d.ref_num LIKE ?)
    //                     OR (u.nom IS NOT NULL AND u.nom LIKE ?)
    //                     OR (u.direction IS NOT NULL AND u.direction LIKE ?)
    //                 )";

    //         $like = "%{$search}%";
    //         $params = array_merge($params, [$like, $like, $like, $like, $like]);
    //     }

    //     $sql .= " AND (dt.destinataire_id = ? OR d.expediteur_initial_id = ?)";

    //     $params[] = $destinataireId;  
    //     $params[] = $destinataireId;

    //     $sql .= " ORDER BY d.created_at DESC";

    //     $q = $this->db->prepare($sql);
    //     $q->execute($params);

    //     return $q->fetchAll(PDO::FETCH_OBJ);
    // }

    public function getDocDr($idDocs)
    {
        // 1. Récupérer le document
        $sqlDoc = "SELECT * FROM $this->table WHERE doc_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sqlDoc);
        $stmt->execute([$idDocs]);
        $doc = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$doc) {
            return null; // doc introuvable
        }

        // 2. Récupérer la dernière transmission du document
        $sqlTrans = "SELECT *, id AS transmission_id
            FROM documents_transmissions
            WHERE doc_id = ?
            ORDER BY date_envoi DESC
            LIMIT 1
        ";

        $stmtT = $this->db->prepare($sqlTrans);
        $stmtT->execute([$idDocs]);
        $lastTransmission = $stmtT->fetch(PDO::FETCH_OBJ);

        // 3. Ajouter proprement la transmission comme propriété dynamique
        $doc->transmission = $lastTransmission ?: null;

        return $doc;

        // $sql = "SELECT d.*, dt.*, dt.id AS transmission_id FROM $this->table d LEFT JOIN documents_transmissions dt ON dt.id = ( SELECT t.id FROM documents_transmissions t WHERE t.doc_id = d.doc_id ORDER BY t.date_envoi DESC LIMIT 1 ) WHERE d.doc_id = ?"; $q = $this->db->prepare($sql); $q->execute([$idDocs]); return $q->fetch(PDO::FETCH_OBJ);
    }
}