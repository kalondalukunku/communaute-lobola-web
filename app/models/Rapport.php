<?php

class Rapport extends Model
{
    protected $table = 'rapports';
    

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }
    
    public function verifFile($chemin)
    {
        $fichiers = array_diff(scandir($chemin), ['.','..']);

        foreach($fichiers as $fichier)
        {
            if(is_file($chemin . $fichier)) return true;
        }

        return false;
    }

    public function download_file($chemin)
    {
        if(ob_get_level()) ob_end_clean();

        if(file_exists($chemin)) 
        {
            header('Content-Type: application/pdf');
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
    
    public function read_file($chemin)
    {
        if(ob_get_level()) ob_end_clean();

        if(file_exists($chemin)) 
        {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.basename($chemin).'"');
            // header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Content-Length: '. filesize($chemin));
            // header('Cache-Control: no-cache');
            flush();
            readfile($chemin);
            // unlink($chemin);
            exit;
        }
    }

    public function get_data_doc_en_attente($status)
    {
        $sql = "SELECT 
                    c.ref_num, 
                    c.objet, 
                    c.saved_by, 
                    DATE_FORMAT(c.date_reception, '%d/%m/%Y') AS date_reception, 
                    DATE_FORMAT(c.date_limite, '%d/%m/%Y') AS date_limite,
                    c.status, 
                    
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM redirections r WHERE r.courier_id = c.courier_id
                        ) THEN 'Oui'
                        ELSE 'Non'
                    END AS redirige_vers,
                    
                    CASE
                        WHEN c.date_limite IS NULL THEN 'Non défini'
                        WHEN NOW() > c.date_limite THEN 'Dépassé'
                        ELSE CONCAT(TIMESTAMPDIFF(DAY, NOW(), c.date_limite), ' Jrs ', LPAD(TIMESTAMPDIFF(HOUR, NOW(), c.date_limite) % 24, 2, '0'), 'h ', LPAD(TIMESTAMPDIFF(MINUTE, NOW(), c.date_limite) % 60, 2, '0'), 'min')
                    END AS temps_restant

                FROM couriers c
                WHERE date_reception IS NOT NULL AND c.status = :status
                ORDER BY c.date_reception ASC";
        $q = $this->db->prepare($sql);
        $q->execute(['status' => $status]);

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_data_doc_classe()
    {
        $sql = "SELECT
                    c.ref_num,
                    c.objet,
                    c.saved_by,
                    c.dossier_classee,
                    DATE_FORMAT(c.date_reception, '%d/%m/%Y') AS date_reception,
                    DATE_FORMAT(c.date_classement, '%d/%m/%Y') AS date_classement,
                    c.status
                FROM couriers c
                WHERE date_reception IS NOT NULL AND c.status IN ('classé', 'classé sans suite')
                ORDER BY c.date_classement DESC
            ";
        
        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_data_doc_redirections()
    {
        $sql = "SELECT
                    c.ref_num,
                    c.objet,
                    DATE_FORMAT(r.created_at, '%d/%m/%Y') AS date_redirection,
                    r.nom_personne_redirigee AS redirige_vers,
                    r.travail_demande,
                    DATE_FORMAT(r.date_limite, '%d/%m/%Y') AS date_limite,
                    r.status AS statut_redirection,
                    DATE_FORMAT(r.date_classement, '%d/%m/%Y') AS date_traitement,
                
                CASE
                    WHEN r.date_classement IS NULL THEN CONCAT(DATEDIFF(CURDATE(), r.created_at), 'jrs écoulés')
                    ELSE CONCAT(DATEDIFF(r.date_classement, r.created_at), 'jrs pris')
                    END AS delai_traitement
                
                FROM redirections r
                JOIN couriers c ON c.courier_id = r.courier_id
                WHERE date_reception IS NOT NULL
                ORDER BY r.created_at ASC
            ";

        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }
}