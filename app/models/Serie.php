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
        return $this->db->query("SELECT nom, serie_id FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

}