<?php
class Transactions extends Model {
    
    protected $table = "transactions";

    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'trans_id')
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

    public function find($reference, $externalId)
    {
        $query = "SELECT * FROM $this->table WHERE reference = :reference AND externalId = :externalId";
        $q = $this->db->prepare($query);
        $q->execute(['reference' => $reference, 'externalId' => $externalId]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
}