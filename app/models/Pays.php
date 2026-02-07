<?php
class Pays extends Model {
    
    protected $table = "pays";


    public function find($find)
    {
        $query = "SELECT * FROM $this->table WHERE pays = :pays OR indicatif = :indicatif ORDER BY created_at DESC LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['pays' => $find, 'indicatif' => $find]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function getPays() 
    {
        return $this->db->query("SELECT pays FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getIndicatifs() 
    {
        return $this->db->query("SELECT indicatif FROM $this->table")->fetchAll(PDO::FETCH_ASSOC);
    }

}