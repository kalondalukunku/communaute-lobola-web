<?php
class Enseignant extends Model {
    
    protected $table = "enseignants";


    public function loginEnseignant($connect, $cacheKey) 
    {
        // if($data = Cache::get($cacheKey)) return $data;

        $stmt = $this->db->prepare(
                                    "SELECT *
                                    FROM {$this->table} 
                                    WHERE email = :email
                                    LIMIT 1");
        $stmt->execute([
            'email'         => $connect,
        ]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        return $user;
    }

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

    public function delete($memberId)
    {
        // Début de la transaction
        $this->db->beginTransaction();

        // 1. Suppression dans la table engagements
        // $queryEngagements = "DELETE FROM engagements WHERE enseignant_id = :enseignant_id";
        // $stmtEng = $this->db->prepare($queryEngagements);
        // $stmtEng->execute(['enseignant_id' => $memberId]);

        // 2. Suppression dans la table principale (enseignant)
        $queryMember = "DELETE FROM {$this->table} WHERE enseignant_id = :enseignant_id";
        $stmtMem = $this->db->prepare($queryMember);
        $stmtMem->execute(['enseignant_id' => $memberId]);
        
        // 3. Suppression dans la table action_raisons
        // $queryMember = "DELETE FROM action_raisons WHERE enseignant_id = :enseignant_id";
        // $stmtMem = $this->db->prepare($queryMember);
        // $stmtMem->execute(['enseignant_id' => $memberId]);

        // Validation des deux opérations
        return $this->db->commit();
    }
    
    public function getEmails() 
    {
        return $this->db->query("SELECT email FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getPhoneNumbers() 
    {
        return $this->db->query("SELECT phone_number FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function all()
    {
        $query = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $q = $this->db->prepare($query);
        $q->execute();
        return $q->fetchAll();
    }

    public function findByEnseignantId($enseignantId)
    {
        $query = "SELECT * FROM $this->table WHERE enseignant_id = :enseignant_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['enseignant_id' => $enseignantId]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

}