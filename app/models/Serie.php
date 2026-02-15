<?php
class Serie extends Model {
    
    protected $table = "series";

    
    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }
    
    public function getSeries() 
    {
        return $this->db->query("SELECT nom FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findByName($serieName)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE nom = :nom LIMIT 1");
        $stmt->execute(['nom' => $serieName]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}