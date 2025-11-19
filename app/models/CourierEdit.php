<?php
class CourierEdit extends Model {
    
    protected $table = "courier_edit";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

}