<?php
class Membre extends Model {
    
    protected $table = "members";


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

    public function findByMemberId($memberId)
    {
        $query = "SELECT M.*, E.* FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.member_id = :member_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId]); 
        return $q->fetch();
    }

    public function findAll($conditions = [], $options = [], $cacheKey = null)
    {
        $query = "SELECT M.*, E.* FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id";
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $key => $value) {
                $clauses[] = "$key = :$key";
            }
            $query .= implode(" AND ", $clauses);
        }

        // Handle options like ORDER BY, LIMIT, OFFSET
        if (isset($options['order'])) {
            $query .= " ORDER BY " . $options['order'];
        }
        if (isset($options['limit'])) {
            $query .= " LIMIT " . (int)$options['limit'];
        }
        if (isset($options['offset'])) {
            $query .= " OFFSET " . (int)$options['offset'];
        }

        $q = $this->db->prepare($query);
        $q->execute($conditions);
        return $q->fetchAll();
    }

    public function countAll($conditions = [], $cacheKey = null)
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $key => $value) {
                $clauses[] = "$key = :$key";
            }
            $query .= implode(" AND ", $clauses);
        }

        $q = $this->db->prepare($query);
        $q->execute($conditions);
        $result = $q->fetch();

        return $result ? (int)$result->total : 0;
    }

}