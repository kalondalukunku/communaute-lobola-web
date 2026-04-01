<?php
class Villes extends Model {
    
    protected $table = "villes";


    public function find($find)
    {
        $query = "SELECT * FROM $this->table WHERE ville = :ville  LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['ville' => $find]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function getVilles() 
    {
        return $this->db->query("SELECT ville FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

}