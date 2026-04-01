<?php
class Appels extends Model {
    
    protected $table = "appels";

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .") ON DUPLICATE KEY UPDATE is_live = :status";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function getLiveStatus()
    {
        $stmt = $this->db->prepare("SELECT is_live FROM $this->table WHERE appel_id = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}