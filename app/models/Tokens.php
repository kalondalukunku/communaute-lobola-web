<?php
class Tokens extends Model {
    
    protected $table = "tokens";


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

    public function delete($memberId, $tokenId)
    {
        $query = "DELETE FROM $this->table WHERE member_id = :member_id AND token_id = :token_id";
        $q = $this->db->prepare($query);
        return $q->execute(['member_id' => $memberId, 'token_id' => $tokenId]);
    }

    public function find($memberId, $tokenId)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id AND token_id = :token_id ORDER BY expired_at DESC LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId, 'token_id' => $tokenId]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function findByMemberId($memberId)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id  ORDER BY expired_at DESC LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
}