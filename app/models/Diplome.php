<?php
class Diplome extends Model {
    
    protected $table = "diplomes";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function find($personnel_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE personnel_id = :personnel_id ORDER BY created_at ASC");
        $stmt->execute([
            'personnel_id'  => $personnel_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}