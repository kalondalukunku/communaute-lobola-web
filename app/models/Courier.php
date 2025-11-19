<?php
class Courier extends Model {
    
    protected $table = "couriers";

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($CourierId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE courier_id = :courier_id");
        $stmt->execute(['courier_id' => $CourierId]);
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

    public function cheminDossierPdf($typeDoc)
    {
        $annee = date('Y');
        $mois = date('m_F');
        $entreprise = COMPANY_NAME_;

        $pathDossier = 'storage/uploads/pdf/' . "{$annee}/{$mois}/{$entreprise}/{$typeDoc}";
        if(!is_dir($pathDossier)) 
        {
            mkdir($pathDossier, 0777, true);
        }

        return $pathDossier;
    }

    public function generteNomFichierPdf($typeDoc)
    {
        $codeEntreprise = "ABK-". COMPANY_NAME_;
        $date = date('Y-m-d');
        $heure = date('H\hi\ms\s');
        $unique = strtoupper(bin2hex(random_bytes(5)));

        return "{$codeEntreprise}_{$typeDoc}_{$date}_{$heure}_{$unique}.pdf";
    }
    
    public function chiffreePdf($filePdf, $filePdfChiffree, $key)
    {            
        $data = file_get_contents($filePdf);
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if(file_put_contents($filePdfChiffree, $iv . $encrypted)) return true;
    }

    public function dechiffreePdf($filePdf, $filePdfChiffree, $key)
    {
        $raw = file_get_contents($filePdfChiffree);
        $iv = substr($raw, 0,16);
        $encryptedData = substr($raw, 16);
    
        $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key,OPENSSL_RAW_DATA, $iv);
        if(file_put_contents($filePdf,$decrypted)) return true;                
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
        $sql = "SELECT $select FROM $this->table WHERE date_reception IS NOT NULL AND $where = :$where";
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
}