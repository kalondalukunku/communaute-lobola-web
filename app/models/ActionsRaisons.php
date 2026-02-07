<?php
class ActionsRaisons extends Model {
    
    protected $table = "action_raisons";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'action_id')
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

    public function find($memberId, $action)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id AND actions = :actions ORDER BY created_at DESC LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId, 'actions' => $action]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function delete($memberId, $action)
    {
        $query = "DELETE FROM $this->table WHERE member_id = :member_id AND actions = :actions";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId, 'actions' => $action]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

}