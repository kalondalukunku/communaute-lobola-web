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
    
    public function find($serieId)
    {
        $stmt = $this->db->prepare("SELECT
                                    *,
                                    S.*,
                                    S.nom AS nom_serie 
                                    FROM {$this->table} E
                                    INNER JOIN series S ON E.serie_id = S.serie_id COLLATE utf8mb4_unicode_ci
                                    WHERE E.serie_id = :serie_id
                                    ORDER BY E.created_at ASC");
        $stmt->execute(['serie_id' => $serieId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function all()
    {
        $stmt = $this->db->prepare("SELECT 
                                    E.*, 
                                    S.*, 
                                    S.nom AS nom_serie 
                                    FROM {$this->table} E
                                    INNER JOIN series S ON E.serie_id = S.serie_id COLLATE utf8mb4_unicode_ci
                                    ORDER BY E.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findAll($enseignantId)
    {
        $stmt = $this->db->prepare("SELECT 
                                    *,
                                    S.*,
                                    S.nom AS nom_serie 
                                    FROM {$this->table} E
                                    INNER JOIN series S ON E.serie_id = S.serie_id COLLATE utf8mb4_unicode_ci
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