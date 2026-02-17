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
    
    public function all()
    {
        $sql = "SELECT s.*, 
                (SELECT COUNT(*) 
                FROM teachings t 
                WHERE t.serie_id COLLATE utf8mb4_unicode_ci = s.serie_id COLLATE utf8mb4_unicode_ci) as enseignements_count
                FROM {$this->table} s
                ORDER BY s.updated_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getSeries() 
    {
        return $this->db->query("SELECT nom FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findByName($serieName)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE nom = :nom LIMIT 1");
        $stmt->execute(['nom' => $serieName]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}