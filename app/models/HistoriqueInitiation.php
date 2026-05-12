<?php
class HistoriqueInitiation extends Model {
    
    protected $table = "initiation_history";

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function all()
    {
        return $this->db->query("SELECT * FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($membreId, $sessionId) 
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE member_id = :member_id AND session_id = :session_id");
        $stmt->execute(['member_id' => $membreId, 'session_id' => $sessionId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}