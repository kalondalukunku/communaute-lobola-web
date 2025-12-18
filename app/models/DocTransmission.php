<?php
class DocTransmission extends Model {
    
    protected $table = "documents_transmissions";

    public function add($datas)
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

    public function findBy($field, $value)
    {
        $q = $this->db->prepare("SELECT * FROM $this->table WHERE $field = :value");
        $q->execute(['value' => $value]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function all()
    {
        $q = $this->db->query("SELECT * FROM $this->table ORDER BY id ASC");
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllDocsTransmission($docId)
    {
        $q = $this->db->prepare("SELECT * FROM $this->table WHERE doc_id = :doc_id ORDER BY id ASC");
        $q->execute(['doc_id' => $docId]);
        return $q->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}