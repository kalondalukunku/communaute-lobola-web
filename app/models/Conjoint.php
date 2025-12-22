<?php
class Conjoint extends Model {
    
    protected $table = "conjoints";


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

    public function findByTypeDoc($typeDocId, $personnel_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE type_doc_id = :type_doc_id AND personnel_id = :personnel_id");
        $stmt->execute([
            'type_doc_id'   => $typeDocId,
            'personnel_id'  => $personnel_id
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}