<?php
class Poste extends Model {
    
    protected $table = "postes_occupes";


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

    public function find($personnel_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE personnel_id = :personnel_id");
        $stmt->execute([
            'personnel_id'  => $personnel_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findByName($personnel_id, $nom_poste)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE personnel_id = :personnel_id AND nom_poste = :nom_poste ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([
            'personnel_id'  => $personnel_id,
            'nom_poste'  => $nom_poste
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function lastInsertId($id, $param)
    {
        $stmt = $this->db->prepare("SELECT $id FROM {$this->table} WHERE personnel_id = :personnel_id OR matricule = :matricule ORDER BY created_at LIMIT 1");
        $stmt->execute([
            'personnel_id'  => $param,
            'matricule'  => $param
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}