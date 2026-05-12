<?php
class Sessions extends Model {
    
    protected $table = "session";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function all()
    {
        $query = "SELECT * FROM $this->table";
        $q = $this->db->prepare($query);
        $q->execute(); 
        return $q->fetchAll();
    }

    public function update(array $datas, string $where = 'session_id')
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

    public function find($sessionId)
    {
        $query = "SELECT * FROM $this->table WHERE session_id = :session_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['session_id' => $sessionId]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
}