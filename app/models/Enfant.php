<?php
class Enfant extends Model {
    
    protected $table = "enfants";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function findByTypeDoc($typeDocId, $personnel_id, $ordre_naissance)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE type_doc_id = :type_doc_id AND personnel_id = :personnel_id AND ordre_naissance = :ordre_naissance");
        $stmt->execute([
            'type_doc_id'       => $typeDocId,
            'personnel_id'      => $personnel_id,
            'ordre_naissance'   => $ordre_naissance
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}