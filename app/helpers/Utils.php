<?php
class Utils {
    
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    public static function sanitize($string)
    {
        return htmlspecialchars(strip_tags($string));
    }

    public static function sanitizeToNull($value)
    {
        if($value === 0 || $value === '' || $value === null) return null;

        return $value;
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
}