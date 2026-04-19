<?php
class Pays extends Model {
    
    protected $table = "pays";


    public function find($find)
    {
        $query = "SELECT * FROM $this->table WHERE pays = :pays  LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['pays' => $find]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function findWhere($where, $find)
    {
        $query = "SELECT * FROM $this->table WHERE $where = :$where  LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([$where => $find]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function getPays() 
    {
        return $this->db->query("SELECT pays, nationalite FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getIndicatifs() 
    {
        return $this->db->query("SELECT indicatif FROM $this->table")->fetchAll(PDO::FETCH_ASSOC);
    }

}