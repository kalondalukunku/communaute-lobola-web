<?php
class PieceIdentite extends Model {
    
    protected $table = "pieces_identite";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function find($personnel_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE personnel_id = :personnel_id");
        $stmt->execute([
            'personnel_id'  => $personnel_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getElement($element) 
    {
        return $this->db->query("SELECT $element FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

}