<?php
class Admin extends Model {
    
    protected $table = "admins";


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

    public function find($adminId, $cacheKey = null)
    {
        $query = "SELECT * FROM $this->table WHERE admin_id = :admin_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['admin_id' => $adminId]);
        return $q->fetch();
    }

    public function findByEmail($email, $cacheKey = null)
    {
        $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['email' => $email]);
        return $q->fetch();
    }

}