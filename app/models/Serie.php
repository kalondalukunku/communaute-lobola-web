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

    public function update(array $datas, string $where = 'serie_id')
    {
        $query = "UPDATE $this->table SET ";

        foreach ($datas as $key => $data) 
        {
            if($key !== $where)
            {
                $query .= "$key = :$key, ";
            }
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE $where = :$where";

        $q = $this->db->prepare($query);
        return $q->execute($datas);
    } 
    
    public function all()
    {
        $sql = "SELECT 
                    s.*, 
                    COUNT(t.enseignement_id) AS enseignements_count
                FROM {$this->table} s
                LEFT JOIN teachings t 
                    ON t.serie_id COLLATE utf8mb4_unicode_ci = s.serie_id COLLATE utf8mb4_unicode_ci 
                    AND t.is_active = 1
                WHERE s.is_active = 1
                GROUP BY s.serie_id
                ORDER BY s.created_at DESC";

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
    
    public function find($serieID)
    {
        $stmt = $this->db->prepare("SELECT 
                                        *,
                                        COUNT(t.enseignement_id) AS enseignements_count
                                    FROM $this->table 
                                    LEFT JOIN teachings t 
                                        ON t.serie_id COLLATE utf8mb4_unicode_ci = s.serie_id COLLATE utf8mb4_unicode_ci 
                                        AND t.is_active = 1
                                    WHERE serie_id = :serie_id
                                    GROUP BY s.serie_id
                                    LIMIT 1");
        $stmt->execute(['serie_id' => $serieID]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}