<?php
class Category extends Model {
    
    protected $table = "categories";

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .") ON DUPLICATE KEY UPDATE is_live = :status";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function all()
    {
        return $this->db->query("SELECT * FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
}