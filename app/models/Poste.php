<?php
class Poste extends Model {
    
    protected $table = "postes_occupes";


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
    
    public function lastInsertId($id, $param)
    {
        $stmt = $this->db->prepare("SELECT $id FROM {$this->table} WHERE personnel_id = :personnel_id OR matricule = :matricule ORDER BY created_at LIMIT 1");
        $stmt->execute([
            'personnel_id'  => $param,
            'matricule'  => $param
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}