<?php

class RapportTransmission extends Model
{
    protected $table = 'rapport_transmission';
    

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function find($CourierId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE courier_id = :courier_id");
        $stmt->execute(['courier_id' => $CourierId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}