<?php
class Event extends Model {
    
    protected $table = "events";

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY date ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (title, description, objet, date, duration, emplacement, image)
                VALUES (:title, :description, :objet, :date, :duration, :emplacement, :image)");
        return $stmt->execute([
            'title'         => $data['title'],
            'description'   => $data['description'],
            'objet'         => $data['objet'],
            'date'          => $data['date'],
            'duration'      => $data['duration'],
            'emplacement'   => $data['emplacement'],
            'image'         => $data['image']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}