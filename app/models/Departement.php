<?php

class Departement extends Model
{
    protected $table = 'services';

    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY service_id DESC")->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByid($serviceID)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE service_id = :service_id");
        $stmt->execute(['service_id' => $serviceID]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function find($nomService)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nom_service = :nom_service");
        $stmt->execute(['nom_service' => $nomService]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function getElement($element) 
    {
        return $this->db->query("SELECT $element FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
}