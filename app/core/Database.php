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
}