<?php
class DocHistorique extends Model {
    
    protected $table = "documents_historique";

    public function add($datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function getHistoriqueByDocId(string $docId)
    {
        $q = $this->db->prepare("SELECT * FROM $this->table WHERE doc_id = :doc_id ORDER BY id ASC");
        $q->execute(['doc_id' => $docId]);
        return $q->fetchAll(PDO::FETCH_OBJ);
    }
}