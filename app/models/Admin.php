<?php
class Admin extends Model {

    protected $table = 'admins';

    public function findByEmail($email, $cacheKey) 
    {
        if($data = Cache::get($cacheKey)) return $data;

        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_OBJ);

        Cache::set($cacheKey, $admin);

        return $admin;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, email, password, role) 
                VALUES (:name, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function all() {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC")->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteById($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}