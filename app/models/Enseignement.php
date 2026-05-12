<?php
class Enseignement extends Model {
    
    protected $table = "teachings";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'enseignement_id')
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
    
    public function findWithSerie($serieId, $sessionId, $categoryId)
    {
        $stmt = $this->db->prepare("SELECT
                                    E.*,
                                    ST.is_active,
                                    ST.session_id,
                                    S.*,
                                    S.nom AS nom_serie 
                                    FROM {$this->table} E
                                    INNER JOIN session_teachings ST ON E.enseignement_id = ST.enseignement_id
                                    INNER JOIN series S ON E.serie_id = S.serie_id  
                                    WHERE E.serie_id = :serie_id AND ST.session_id = :session_id AND ST.is_active = 1 AND E.category_id = :category_id
                                    ORDER BY E.created_at ASC");
        $stmt->execute([
            'serie_id' => $serieId,
            'session_id' => $sessionId,
            'category_id' => $categoryId
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function find($enseignantId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE enseignement_id = :enseignement_id ORDER BY created_at ASC");
        $stmt->execute(['enseignement_id' => $enseignantId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function all($categoryId)
    {
        $stmt = $this->db->prepare("SELECT 
                                    E.*, 
                                    S.*, 
                                    S.nom AS nom_serie 
                                    FROM {$this->table} E
                                    INNER JOIN series S ON E.serie_id = S.serie_id  
                                    WHERE E.category_id = :category_id
                                    ORDER BY E.created_at DESC");
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function allWithView($sessionId, $search = null)
    {
        $params = [
            ':sessionId' => $sessionId
        ];
        
        // On initialise la clause WHERE avec le filtrage par session (obligatoire)
        $whereClause = " WHERE ST.session_id = :sessionId ";

        // On ajoute la condition de recherche si elle est fournie
        if (!empty($search)) {
            $whereClause .= " AND (E.title LIKE :search OR S.nom LIKE :search) ";
            $params[':search'] = '%' . $search . '%';
        }

        $sql = "SELECT 
                    E.*, 
                    ST.is_active,
                    ST.session_id,
                    S.nom AS nom_serie,
                    (
                        SELECT COUNT(*) 
                        FROM enseignement_vues EV 
                        WHERE EV.enseignement_id = E.enseignement_id
                    ) AS total_vues
                FROM {$this->table} AS E
                INNER JOIN session_teachings ST ON E.enseignement_id = ST.enseignement_id
                INNER JOIN series S ON E.serie_id = S.serie_id
                {$whereClause}
                ORDER BY E.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // public function allWithView($sessionId, $search = null)
    // {
    //     $params = [];
    //     $whereClause = "";

    //     // On prépare la condition WHERE si une recherche est fournie
    //     if (!empty($search)) {
    //         // On utilise COLLATE sur le paramètre pour qu'il s'adapte à la colonne, 
    //         // ou on force tout en unicode_ci pour la cohérence.
    //         $whereClause = " WHERE (E.title LIKE :search   
    //                         OR S.nom LIKE :search  ) ";
    //         $params[':search'] = '%' . $search . '%';
    //     }

    //     $sql = "SELECT 
    //                 E.*, 
    //                 S.nom AS nom_serie,
    //                 (
    //                     SELECT COUNT(*) 
    //                     FROM enseignement_vues EV 
    //                     WHERE EV.enseignement_id = E.enseignement_id
    //                 ) AS total_vues
    //             FROM {$this->table} E
    //             INNER JOIN series S ON E.serie_id = S.serie_id
    //             {$whereClause}
    //             ORDER BY E.created_at DESC";

    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute($params);
        
    //     return $stmt->fetchAll(PDO::FETCH_OBJ);
    // }

    public function findAll($enseignantId)
    {
        $stmt = $this->db->prepare("SELECT 
                                    *,
                                    S.*,
                                    S.nom AS nom_serie 
                                    FROM {$this->table} E
                                    INNER JOIN series S ON E.serie_id = S.serie_id  
                                    WHERE E.enseignant_id = :enseignant_id
                                    ORDER BY E.created_at DESC");
        $stmt->execute(['enseignant_id' => $enseignantId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function cheminDossierPdf($personnelID, $type_file = "engagement")
    {

        $pathDossier = 'storage/uploads/' . "{$type_file}/{$personnelID}";
        if(!is_dir($pathDossier)) 
        {
            mkdir($pathDossier, 0777, true);
        }

        return $pathDossier;
    }

    public function generteNomFichierPdf($typeDoc, $ext = 'pdf')
    {
        $codeEntreprise = "CLLIL-";
        $date = date('Y-m-d');
        $heure = date('H\hi\ms\s');
        $unique = strtoupper(bin2hex(random_bytes(5)));

        return "{$codeEntreprise}_{$typeDoc}_{$date}_{$heure}_{$unique}.{$ext}";
    }

    public function dechiffreePdf($filePdf, $filePdfChiffree, $key)
    {
        $raw = file_get_contents($filePdfChiffree);
        $iv = substr($raw, 0,16);
        $encryptedData = substr($raw, 16);
    
        $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key,OPENSSL_RAW_DATA, $iv);
        if(file_put_contents($filePdf,$decrypted)) return true;                
    }

    public function download_file($chemin, $type_file)
    {
        if(ob_get_level()) ob_end_clean();

        if(file_exists($chemin)) 
        {
            header('Content-Type: ' . $type_file);
            header('Content-Disposition: attechment; filename="'.basename($chemin).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Content-Length: '. filesize($chemin));
            header('Cache-Control: no-cache');
            flush();
            readfile($chemin);
            unlink($chemin);
            exit;
        }
    }

}