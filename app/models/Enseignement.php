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
    
    public function chiffreePdf($filePdf, $filePdfChiffree, $key)
    {
        $data = file_get_contents($filePdf);
        if ($data === false) {
            error_log("chiffreePdf: Echec de lecture du fichier PDF: " . $filePdf);
            return false;
        }

        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        
        if ($encrypted === false) {
            error_log("chiffreePdf: Echec du chiffrement OpenSSL pour: " . $filePdf);
            return false;
        }

        $result = file_put_contents($filePdfChiffree, $iv . $encrypted);
        
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