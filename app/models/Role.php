<?php
class Role extends Model {
    
    protected $table = "roles";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function find($role_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role_id = :role_id");
        $stmt->execute([
            'role_id'  => $role_id
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findWhere($where, $param)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE $where = :$where");
        $stmt->execute([
            "$where"  => $param
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function getElement($element) 
    {
        return $this->db->query("SELECT $element FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

}