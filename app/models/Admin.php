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

    public function findByEmail($email, $cacheKey = null)
    {
        $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['email' => $email]);
        return $q->fetch();
    }

}