<?php
class Database {
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo)
        {
            $host = DB_HOST;
            $dbname = DB_NAME;
            $user = DB_USER;
            $pass = DB_PASS;

            self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }
        return self::$pdo;
    }

    public static function decodeData(array $data) {
        return array_map(function($value) {
            if (is_string($value)) {
                // Transforme les &#039; et autres entités en caractères réels
                return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
            return $value;
        }, $data);
    }
}