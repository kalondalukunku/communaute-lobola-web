<?php
class LivrePay extends Model {
    
    protected $table = "livre_paiement";

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'livre_id', $where2 = 'trans_id')
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
        $query .= " WHERE $where = :$where AND $where2 = :$where2";

        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function getAll($membreId)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE member_id = :membreId ORDER BY created_at DESC");
        $stmt->execute(['membreId' => $membreId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id, $membreId)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE livre_id = :id AND member_id = :membreId");
        $stmt->execute(['id' => $id, 'membreId' => $membreId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getByTransId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE trans_id = :id ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}