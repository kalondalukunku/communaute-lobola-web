<?php
class Membre extends Model {
    
    protected $table = "members";
    public $default_per_page = 10;

    public function loginMember($connect, $cacheKey) 
    {
        // if($data = Cache::get($cacheKey)) return $data;

        $stmt = $this->db->prepare(
                                    "SELECT 
                                        M.*,
                                        E.statut AS statut_engagement, 
                                        E.engagement_id, 
                                        E.modalite_engagement, 
                                        E.document_path, 
                                        E.document_ext, 
                                        E.document_header_type, 
                                        E.reference_code, 
                                        E.montant, 
                                        E.devise, 
                                        E.signed_at, 
                                        E.date_expiration 

                                    FROM {$this->table} M
                                    LEFT JOIN
                                        engagements AS E
                                    ON M.member_id = E.member_id 
                                    WHERE M.email = :email OR M.phone_number = :email
                                    LIMIT 1");
        $stmt->execute([
            'email'         => $connect,
        ]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        return $user;
    }

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'member_id')
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

    public function delete($memberId)
    {
        $q = $this->db->prepare("UPDATE $this->table SET status = 'deleted' WHERE member_id = :member_id");
        return $q->execute(['member_id' => $memberId]);
    }

    public function deleteAdmin($memberId)
    {
        // Début de la transaction
        $this->db->beginTransaction();

        // 1. Suppression dans la table engagements
        $queryEngagements = "DELETE FROM engagements WHERE member_id = :member_id";
        $stmtEng = $this->db->prepare($queryEngagements);
        $stmtEng->execute(['member_id' => $memberId]);

        // 2. Suppression dans la table principale (membre)
        $queryMember = "DELETE FROM {$this->table} WHERE member_id = :member_id";
        $stmtMem = $this->db->prepare($queryMember);
        $stmtMem->execute(['member_id' => $memberId]);
        
        // 3. Suppression dans la table action_raisons
        $queryMember = "DELETE FROM action_raisons WHERE member_id = :member_id";
        $stmtMem = $this->db->prepare($queryMember);
        $stmtMem->execute(['member_id' => $memberId]);

        // 4. Suppression de l avatar du membre
        $fileavatar = STORAGE_UPLOAD . "avatar/" . $memberId;
        if(file_exists($fileavatar)) {
            unlink($fileavatar);
        }

        // Validation des deux opérations
        return $this->db->commit();
    }

    public function insert2(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO pays (pays_id, pays, iso2, iso3, phonecode, timezones, latitude, longitude) VALUES(:pays_id, :pays, :iso2, :iso3, :phonecode, :timezones, :latitude, :longitude) ON DUPLICATE KEY UPDATE pays = VALUES(pays)";
        $q = $this->db->prepare($query);
        $this->db->beginTransaction();
    
    foreach ($datas as $item) {
        // On lie uniquement les colonnes autorisées pour éviter les erreurs SQL
        $q->execute([
            ':pays_id'   => $item['id'] ?? null,
            ':pays'      => $item['name'] ?? null,
            ':iso2'      => $item['iso2'] ?? null,
            ':iso3'      => $item['iso3'] ?? null,
            ':phonecode' => $item['phonecode'] ?? null,
            ':timezones' => $item['timezones'] ?? null,
            ':latitude'  => $item['latitude'] ?? null,
            ':longitude' => $item['longitude'] ?? null,
        ]);
    }

    return $this->db->commit();
        // return $q->execute($datas);
    }
    
    public function getEmails() 
    {
        return $this->db->query("SELECT email FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function telechargerContactsGoogle() {
        // 1. Récupération des données (votre requête)
        $membres = $this->db->query("SELECT nom_postnom, email FROM $this->table")->fetchAll(PDO::FETCH_OBJ);

        // 2. Définir les headers HTTP pour forcer le téléchargement du fichier CSV
        $filename = "contacts_lobola_" . date('Y-m-d_H-i') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // 3. Ouvrir le flux de sortie PHP
        $output = fopen('php://output', 'w');

        // 4. Ajouter le BOM UTF-8 (TRÈS IMPORTANT pour que Google et Excel lisent bien les accents comme é, à, etc.)
        fputs($output, "\xEF\xBB\xBF");

        // 5. En-têtes officiels requis par Google Contacts (Format minimal mais robuste)
        $headers = [
            'Name',             // Nom d'affichage
            'Given Name',       // Prénom (utilisé pour le tri)
            'E-mail 1 - Type',  // Type d'email (Travail, Domicile, etc.)
            'E-mail 1 - Value', // L'adresse email
            'Group Membership'  // Le libellé/groupe dans Google Contacts
        ];
        fputcsv($output, $headers, ','); // Google Contacts utilise la virgule comme séparateur par défaut

        // 6. Parcourir les résultats et remplir les lignes du CSV
        foreach ($membres as $membre) {
            $nom = trim($membre->nom_postnom);
            $email = trim($membre->email);

            // On ignore les membres qui n'ont pas d'adresse e-mail (inutile pour Google Contacts)
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            // Création de la ligne
            $ligne = [
                $nom,                  // Name
                $nom,                  // Given Name (Google séparera le nom/prénom lui-même si besoin)
                'Other',               // E-mail 1 - Type (Label de l'email)
                $email,                // E-mail 1 - Value
                'Communauté Lobola'    // Group Membership : Créera un dossier/libellé "Communauté Lobola"
            ];

            // Écrire la ligne dans le fichier CSV
            fputcsv($output, $ligne, ',');
        }

        // 7. Fermer le flux et stopper l'exécution (pour éviter d'ajouter du HTML au fichier)
        fclose($output);
        exit;
    }
    
    public function getEmailsAlert() 
    {
        return $this->db->query("SELECT email FROM $this->table WHERE status = 'active'")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getPhoneNumbers() 
    {
        return $this->db->query("SELECT phone_number FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function all()
    {
        $query = "SELECT M.* FROM $this->table M";
        $q = $this->db->prepare($query);
        $q->execute(); 
        return $q->fetchAll();
    }

    public function allWhere($where, $parameter, $order_where = "nom_postnom", $order_by = "ASC")
    {
        $query = "SELECT M.* FROM $this->table M WHERE $where = :$where ORDER BY $order_where $order_by";
        $q = $this->db->prepare($query);
        $q->execute([$where => $parameter]); 
        return $q->fetchAll();
    }

    public function findByWhere($where, $parameter)
    {
        $query = "SELECT 
                        M.*,
                        E.statut AS statut_engagement, 
                        E.engagement_id, 
                        E.modalite_engagement, 
                        E.document_path, 
                        E.document_ext, 
                        E.document_header_type, 
                        E.reference_code, 
                        E.montant, 
                        E.devise, 
                        E.signed_at, 
                        E.date_expiration 

                    FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.$where = :$where LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([$where => $parameter]); 
        return $q->fetch();
    }

    public function findByMemberId($memberId)
    {
        $query = "SELECT 
                        M.*,
                        E.statut AS statut_engagement, 
                        E.engagement_id, 
                        E.modalite_engagement, 
                        E.document_path, 
                        E.document_ext, 
                        E.document_header_type, 
                        E.reference_code, 
                        E.montant, 
                        E.devise, 
                        E.signed_at, 
                        E.date_expiration 
                    
                    FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.member_id = :member_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId]); 
        return $q->fetch();
    }

    public function findAllMembres(int $page = 1, ?string $search = null, array $conditions = [], ?int $per_page = null, ?string $order_where = "updated_at", ?string $order_by = "ASC"): array
    {
        $limit = $per_page ?? $this->default_per_page ?? 10;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $params = [];
        $whereClauses = [];

        // 1. Gestion des conditions fixes ($conditions)
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "M.$key = :$key";
                $params[$key] = $value;
            }
        }

        // 2. Gestion de la recherche ($search)
        if (!empty($search)) {
            // Note : On ne peut plus chercher dans E.modalite_engagement ici car il n'y a pas de jointure
            $whereClauses[] = "(M.nom_postnom LIKE :search OR M.email LIKE :search OR M.niveau_initiation LIKE :search OR M.phone_number LIKE :search OR M.domaine_etude LIKE :search OR M.nationalite LIKE :search OR M.ville LIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereSql = " WHERE " . implode(" AND ", $whereClauses);

        // 3. Requête pour le nombre total d'enregistrements
        $sql_count = "SELECT COUNT(M.member_id) FROM $this->table M $whereSql";
        
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 4. Requête pour les membres uniquement
        $sql = "SELECT M.* FROM $this->table M 
                $whereSql 
                ORDER BY M.$order_where $order_by 
                LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);
        
        // Liaison des paramètres
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);

        // 5. Retour des données formatées
        return [
            'data'          => $results,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }

    public function findAllEngages(int $page = 1, ?string $search = null, array $conditions = [], ?int $per_page = null): array
    {
        $limit = $per_page ?? $this->default_per_page ?? 10;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $params = [];
        $whereClauses = [];

        // 1. Gestion des conditions fixes ($conditions)
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "M.$key = :$key";
                $params[$key] = $value;
            }
        }

        // 2. Gestion de la recherche ($search)
        if (!empty($search)) {
            $whereClauses[] = "(M.nom_postnom LIKE :search OR M.email LIKE :search OR E.modalite_engagement LIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

        // 3. Requête pour le nombre total (INNER JOIN garantit qu'on ne compte que les membres avec engagement)
        $sql_count = "SELECT COUNT(DISTINCT M.member_id) 
                    FROM $this->table M 
                    INNER JOIN engagements E ON M.member_id = E.member_id 
                    $whereSql";
        
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 4. Requête pour les enregistrements (INNER JOIN filtre les membres sans engagement)
        $sql = "SELECT 
                    M.*,
                    E.statut AS statut_engagement, 
                    E.engagement_id, 
                    E.modalite_engagement, 
                    E.document_path, 
                    E.document_ext, 
                    E.document_header_type, 
                    E.reference_code, 
                    E.montant, 
                    E.devise, 
                    E.signed_at, 
                    E.date_expiration
                    
                FROM $this->table M 
                INNER JOIN engagements E ON M.member_id = E.member_id 
                $whereSql 
                ORDER BY M.member_id DESC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);

        // 5. Retour des données formatées
        return [
            'data'          => $results,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }

    public function countAll($conditions = [], $cacheKey = null)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    // Gestion de la clause IN (ex: status => [0, 1])
                    $placeholders = [];
                    foreach ($value as $index => $val) {
                        $paramName = "{$key}_{$index}";
                        $placeholders[] = ":$paramName";
                        $params[$paramName] = $val;
                    }
                    $clauses[] = "$key IN (" . implode(", ", $placeholders) . ")";
                } else {
                    // Gestion de l'égalité classique
                    $clauses[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }
            $query .= implode(" AND ", $clauses);
        }

        $q = $this->db->prepare($query);
        $q->execute($params);
        $result = $q->fetch(PDO::FETCH_OBJ); // Utilisation de FETCH_OBJ pour correspondre à votre accès ->total

        return $result ? (int)$result->total : 0;
    }

    public function countEngagedMembers(): int
    {
        $query = "SELECT COUNT(M.member_id) as total 
                FROM $this->table M
                INNER JOIN engagements E1 ON M.member_id = E1.member_id
                WHERE E1.engagement_id = (
                    SELECT E2.engagement_id 
                    FROM engagements E2 
                    WHERE E2.member_id = M.member_id 
                    ORDER BY E2.signed_at DESC
                    LIMIT 1
                )
                AND E1.statut = 'Approuvé'";

        try {
            $q = $this->db->prepare($query);
            $q->execute();
            $result = $q->fetch(PDO::FETCH_OBJ);
            
            return (int)($result->total ?? 0);
        } catch (Exception $e) {
            // En cas d'erreur, on retourne 0
            return 0;
        }
    }

    public function calculerTauxEngagementApprouve()
    {
        $totalMembres = $this->countAll(["status" => ARRAY_STATUS_MEMBER[2]]);
        if ($totalMembres === 0) {
            return 0;
        }

        $membresEngagesApprouves = $this->countEngagedMembers();

        $taux = ($membresEngagesApprouves / $totalMembres) * 100;

        return round($taux, 2);
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

    public function getMemberProgress($member_id) 
    {
        // 1. Compter le total des enseignements actifs
        $stmtTotal = $this->db->prepare("SELECT COUNT(*) FROM teachings WHERE is_active = '1'");
        $stmtTotal->execute();
        $totalActive = (int)$stmtTotal->fetchColumn();

        if ($totalActive === 0) return 0;

        // 2. Compter les enseignements uniques vus par ce membre
        // On utilise DISTINCT au cas où un membre aurait cliqué plusieurs fois sur le même enseignement
        $stmtSeen = $this->db->prepare("
            SELECT COUNT(DISTINCT enseignement_id) 
            FROM enseignement_vues
            WHERE user_id = :mid 
            AND enseignement_id IN (SELECT enseignement_id COLLATE utf8mb4_unicode_ci FROM teachings WHERE is_active = '1')
        ");
        $stmtSeen->execute(['mid' => $member_id]);
        $totalSeen = (int)$stmtSeen->fetchColumn();

        // 3. Calculer le pourcentage
        $percentage = ($totalSeen / $totalActive) * 100;

        return min(100, round($percentage)); // Plafonné à 100%
    }

    public function getMembersActivityReport($search = null) 
    {
        // 1. Obtenir le dénominateur (total des enseignements actifs)
        $stmtTotal = $this->db->prepare("SELECT COUNT(*) FROM teachings WHERE is_active = '1'");
        $stmtTotal->execute();
        $totalActive = (int)$stmtTotal->fetchColumn();

        if ($totalActive === 0) {
            return [];
        }

        // 2. Préparation de la condition de recherche
        $searchCondition = "";
        $params = [];
        if (!empty($search)) {
            // Filtre sur le nom ou l'email
            $searchCondition = " AND (m.nom_postnom LIKE :search OR m.email LIKE :search OR m.phone_number LIKE :search OR m.ville LIKE :search OR m.niveau_initiation LIKE :search OR m.genre LIKE :search OR m.domaine_etude LIKE :search) ";
            $params['search'] = '%' . $search . '%';
        }

        // 3. Requête principale avec Jointure et Recherche
        $query = "
            SELECT 
                m.member_id,
                m.nom_postnom,
                m.email,
                m.path_profile,
                m.phone_number,
                m.status,
                COUNT(DISTINCT ev.enseignement_id) as total_seen,
                MAX(ev.viewed_at) as last_seen_date
            FROM members m
            INNER JOIN enseignement_vues ev 
                ON m.member_id COLLATE utf8mb4_unicode_ci = ev.user_id COLLATE utf8mb4_unicode_ci
            WHERE ev.enseignement_id IN (
                SELECT enseignement_id COLLATE utf8mb4_unicode_ci 
                FROM teachings 
                WHERE is_active = '1'
            )
            $searchCondition
            GROUP BY m.member_id
            ORDER BY total_seen DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $membersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = [];

        // 4. Construction du tableau final
        foreach ($membersData as $member) {
            $totalSeen = (int)$member['total_seen'];
            $calcPercentage = ($totalSeen / $totalActive) * 100;

            $report[] = [
                'member_id' => $member['member_id'],
                'nom_postnom' => $member['nom_postnom'],
                'email' => $member['email'],
                'path_profile' => $member['path_profile'],
                'phone_number' => $member['phone_number'],
                'status' => $member['status'],
                'stats' => [
                    'read_count' => $totalSeen,
                    'total_to_read' => $totalActive,
                    'progress_bar' => min(100, round($calcPercentage)),
                    'last_activity' => $member['last_seen_date']
                ]
            ];
        }

        return $report;
    }

    public function getMemberDetailedReport($memberId)
    {
        // 1. Informations de base du membre
        $stmtMember = $this->db->prepare("SELECT member_id, nom_postnom, email, path_profile FROM members WHERE member_id = ?");
        $stmtMember->execute([$memberId]);
        $member = $stmtMember->fetch(PDO::FETCH_ASSOC);

        if (!$member) return null;

        // 2. Récupérer TOUS les enseignements actifs (le référentiel)
        // On récupère le titre, la durée, etc.
        $stmtTeachings = $this->db->prepare("SELECT enseignement_id, title, duration_minutes, is_active, created_at FROM teachings ORDER BY created_at DESC");
        $stmtTeachings->execute();
        $allTeachings = $stmtTeachings->fetchAll(PDO::FETCH_ASSOC);
        $totalActive = count($allTeachings);

        // 3. Récupérer les IDs des enseignements déjà vus par ce membre
        $stmtSeen = $this->db->prepare("SELECT enseignement_id, viewed_at FROM enseignement_vues WHERE user_id = ?");
        $stmtSeen->execute([$memberId]);
        $seenData = $stmtSeen->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC); 
        // fetchGroup permet d'avoir l'id en clé pour une recherche rapide : [ 'id_1' => [[viewed_at => ...]], ... ]

        $readList = [];
        $unReadList = [];
        $lastActivity = null;

        // 4. Ventilation des enseignements
        foreach ($allTeachings as $teaching) {
            $teachingId = $teaching['enseignement_id'];
            
            if (isset($seenData[$teachingId])) {
                // L'enseignement a été vu
                $viewDate = $seenData[$teachingId][0]['viewed_at'];
                $teaching['viewed_at'] = $viewDate;
                $readList[] = $teaching;

                // Suivre la date d'activité la plus récente
                if (!$lastActivity || $viewDate > $lastActivity) {
                    $lastActivity = $viewDate;
                }
            } else {
                // L'enseignement n'a pas été vu
                $unReadList[] = $teaching;
            }
        }

        // 5. Calcul des stats finales
        $readCount = count($readList);
        $progress = ($totalActive > 0) ? round(($readCount / $totalActive) * 100) : 0;

        return [
            'member_id' => $member['member_id'],
            'nom_postnom' => $member['nom_postnom'],
            'email' => $member['email'],
            'path_profile' => $member['path_profile'],
            'stats' => [
                'read_count' => $readCount,
                'unread_count' => count($unReadList),
                'total_to_read' => $totalActive,
                'progress_bar' => min(100, $progress),
                'last_activity' => $lastActivity
            ],
            'enseignements_lus' => $readList,
            'enseignements_non_lus' => $unReadList
        ];
    }

    public function getMemberActivityDetails($member_id) 
    {
        $query = "
            SELECT 
                t.title,
                t.enseignement_id,
                ev.viewed_at
            FROM enseignement_vues ev
            INNER JOIN teachings t ON ev.enseignement_id COLLATE utf8mb4_unicode_ci = t.enseignement_id COLLATE utf8mb4_unicode_ci
            WHERE ev.user_id = :member_id
            AND t.is_active = '1'
            ORDER BY ev.viewed_at DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['member_id' => $member_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}