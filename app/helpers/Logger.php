<?php
class Logger extends Model {

    protected $table = "logs";

    const LOG_PATH = BASE_PATH . 'storage/logs/';

    public function addLog($datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    // ecrire un message dans le log
    public static function log($message, $level = 'SUCCESS')
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] [$level] $message" . PHP_EOL;

        // crée un fichier par jour
        $file = self::LOG_PATH . date('Y-m-d') . '.log';
        file_put_contents($file, $logMessage, FILE_APPEND);
    }    

    public function findByCourierId($CourierId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE courier_id = :courier_id");
        $stmt->execute(['courier_id' => $CourierId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Exemples d'utilisation
    public static function info($message)
    {
        self::log($message, 'INFO');
    }

    public static function error($message)
    {
        self::log($message, 'ERROR');
    }
}