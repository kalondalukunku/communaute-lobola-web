<?php
class Utils {

    public static function generateUuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return bin2hex($data);
    }
    
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    public static function sanitize($string)
    {
        return html_entity_decode((htmlspecialchars(strip_tags($string))), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function sanitizeToNull($value)
    {
        if($value === 0 || $value === '' || $value === null) return null;

        return $value;
    }

    public static function generateCodeDirection()
    {
        $prefix = "DIR";
        $uniquePart = strtoupper(bin2hex(random_bytes(3))); // Génère une chaîne aléatoire de 6 caractères hexadécimaux
        return $prefix . $uniquePart;
    }

    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    public static function comparePostWithCourier($post, $courier)
    {
        foreach($post as $key => $value) {
            if($key === 'motif') {
                continue; // on ne traite pas le champ motif ici
            }

            if(!property_exists($courier, $key) || $courier->$key != $value) {
                return false;
            }
        }
        
        return true; // tous les champs sont inchangés
    }

    public static function foreachDataDb($datas, $column)
    {
        foreach ($datas as $data) {
            $datas[] = $data->$column;
        }
        return $datas;
    }

    public static function foreachDataDb2($datas, $datas2, $column)
    {
        foreach ($datas as $data) {
            $datas2[] = $data->$column;
        }
        return $datas2;
    }

    public static function getFileSizeReadable($file)
    {
        if(!file_exists($file)) return false;

        $size = filesize($file);

        $units = ["o", "Ko", "Mo", "Go", "To"];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1)
        {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) ." ". $units[$i];
    }

    public static function getClientIP() 
    {
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($keys as $key) {
            if (isset($_SERVER[$key])) {
                $ipList = explode(',', $_SERVER[$key]); // Si plusieurs IP (X-Forwarded-For)
                foreach ($ipList as $ip) {
                    $ip = trim($ip);
                    // Vérifie que l'IP est valide (IPv4 ou IPv6)
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }

        // Si aucune IP publique trouvée
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function getUserAgent() 
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public static function removeValue(array $array, $value) {
        return array_values(array_filter($array, function($item) use ($value) {
            return $item !== $value;
        }));
    }

    public static function viewDoc($docPostViewId, $docPostViewFileEnc, $courierModel)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_view_doc_pdf'.$docPostViewId]))
        {
            $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($docPostViewFileEnc));
            $pathFilePdf = FILE_VIEW_FOLDER_PATH ."file.pdf";

            $res = $courierModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_FILE);

            if($res === true)
            {
                Utils::redirect('../view_pdf?fl=file.pdf');
                unlink($pathFilePdf);
            }
        }
    }

    public static function hasDataChanged(array $dataArray, $object): bool
    {
        foreach ($dataArray as $key => $newValue) {
            if (!property_exists($object, $key) || $object->$key !== $newValue) {
                return true; 
            }
        }

        return false;
    }

    public static function hasPersonnelDataChanged(array $dataDb, array $submittedData): bool
    {
        foreach ($submittedData as $key => $submittedValue) 
        {
            
            if (!array_key_exists($key, $dataDb)) {
                // Optionnel: si un champ soumis n'est pas dans la BDD, cela pourrait être considéré
                // comme un changement imprévu. Pour cette version simple, on ignore les clés inconnues.
                continue; 
            }

            // 2. Récupérer la valeur actuelle de la BDD
            $dbValue = $dataDb[$key];
            
            // 3. Comparaison stricte (valeur et type). 
            // Si les valeurs sont différentes, un changement est détecté.
            if ($submittedValue !== $dbValue) {
                return true; // Un changement a été trouvé
            }
        }

        // Si la boucle se termine sans trouver de différence
        return false;
    }
    
    public static function getYear($date)
    {
        return date("Y", strtotime($date));
    }

    public static function getAnciennte(string $date_reference): string 
    {
        // --- 1. Validation et Création des Objets DateTime ---
        
        try {
            $dateObjet = new DateTime($date_reference);
        } catch (\Exception $e) {
            return "Erreur: Format de date invalide (doit être YYYY-MM-DD).";
        }

        $aujourdhui = new DateTime();

        // --- 2. Calcul de la différence ---
        
        // Le sens de la différence n'a pas d'importance pour l'affichage de la durée, 
        // donc nous prenons la valeur absolue de l'intervalle.
        $intervalle = $dateObjet->diff($aujourdhui);
        
        // --- 3. Construction du résultat lisible ---

        $parties = [];

        // y = Années
        if ($intervalle->y > 0) {
            $parties[] = $intervalle->y . ' an' . ($intervalle->y > 1 ? 's' : '');
        }
        
        // m = Mois
        if ($intervalle->m > 0) {
            $parties[] = $intervalle->m . ' mois';
        }
        
        // d = Jours
        if ($intervalle->d > 0) {
            $parties[] = $intervalle->d . ' jour' . ($intervalle->d > 1 ? 's' : '');
        }

        // Cas où la date est aujourd'hui
        if (empty($parties)) {
            return "0 jour";
        }

        // Jointure des parties
        return implode(' ', $parties);
    }

    public static function isMajeur($dateNaissance) 
    {
        try {
            $naissance = new DateTime($dateNaissance);
            $aujourdhui = new DateTime();
            $intervalle = $aujourdhui->diff($naissance);
            $ageHabile = $intervalle->y;
            
            return $ageHabile >= 18;
            
        } catch (Exception $e) {
            // En cas de format de date invalide, on peut retourner false ou gérer l'erreur
            return false;
        }
    }

    public static function isExpired(string $date_deli, int $duree_validite_jours): bool 
    {
        // Création de l'objet DateTime pour la date de délivrance
        $date = new DateTime($date_deli);
        
        // Ajout de la durée de validité (P = Period, D = Days)
        $date->add(new DateInterval("P{$duree_validite_jours}D"));
        
        // Date d'aujourd'hui (minuit pour ignorer l'heure actuelle)
        $aujourdhui = new DateTime('today');
        
        // Retourne true si la date calculée est strictement inférieure à aujourd'hui
        return $date < $aujourdhui;
    }

    public static function calculerDateFuture(int $heuresAAjouter, string $format = 'Y-m-d H:i:s'): string 
    {
        $date = new DateTime();
        $date->add(new DateInterval("PT{$heuresAAjouter}H"));
        
        return $date->format($format);
    }

    public static function dechiffreflpdf($classModel, $chemin_fichier_stockage, $namefile = "file.pdf")
    {
        $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($chemin_fichier_stockage));
        $pathFilePdf = FILE_VIEW_FOLDER_PATH . $namefile;

        return $classModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_FILE);
    }

    public static function formatNamePsn($Personnel)
    {
        return str_replace(" ","_",$Personnel->nom .'_'. $Personnel->postnom);
    }

    public static function formatFileSize(int $bytes) 
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' Go';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' Mo';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' Ko';
        }
        return $bytes . ' octets';
    }

    public static function getMonthsByModalite($modalite, $montant) 
    {
        switch ($modalite) {
            case (int) self::getMonthsNumber($modalite) * $montant;
            case (int) self::getMonthsNumber($modalite) * $montant;
            case (int) self::getMonthsNumber($modalite) * $montant;
            case (int) self::getMonthsNumber($modalite) * $montant;
        }
    }

    public static function getMonthsNumber($modalite) 
    {
        switch ($modalite) {
            case ARRAY_TYPE_ENGAGEMENT[0]:  return 1;
            case ARRAY_TYPE_ENGAGEMENT[1]:  return 3;
            case ARRAY_TYPE_ENGAGEMENT[2]:  return 6;
            case ARRAY_TYPE_ENGAGEMENT[3]:  return 12;
        }
    }

    public static function getExpiryDateEngagement($format = 'Y-m-d H:i:s') 
    {
        $now = new DateTimeImmutable();
        $futureDate = $now->modify('+1 year');
        
        return $futureDate->format($format);
    }   

    public static function generateRandomPassword($length = 8) 
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return $password;
    }
}