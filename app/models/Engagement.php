<?php
class Engagement extends Model {
    
    protected $table = "engagements";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'id')
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
    
    public function countAll($conditions = [], $cacheKey = null)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    // Gestion de la clause IN (ex: status => [0, 1])
                    $placeholders = [];
                    foreach ($value as $index => $val) {
                        $paramName = "{$key}_{$index}";
                        $placeholders[] = ":$paramName";
                        $params[$paramName] = $val;
                    }
                    $clauses[] = "$key IN (" . implode(", ", $placeholders) . ")";
                } else {
                    // Gestion de l'égalité classique
                    $clauses[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }
            $query .= implode(" AND ", $clauses);
        }

        $q = $this->db->prepare($query);
        $q->execute($params);
        $result = $q->fetch(PDO::FETCH_OBJ); // Utilisation de FETCH_OBJ pour correspondre à votre accès ->total

        return $result ? (int)$result->total : 0;
    }

}