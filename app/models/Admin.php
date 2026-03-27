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

    public function update(array $datas, string $where = 'admin_id')
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

    public function findByWhere($where, $parameter)
    {
        $query = "SELECT * FROM $this->table WHERE $where = :$where LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([$where => $parameter]); 
        return $q->fetch();
    }

    public function loginAdmin($connect, $cacheKey = null)
    {
        $query = "SELECT * FROM $this->table WHERE email = :email OR nom = :email LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['email' => $connect]);
        return $q->fetch();
    }

    public function all()
    {
        $query = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $q = $this->db->prepare($query);
        $q->execute();
        return $q->fetchAll();
    }

}