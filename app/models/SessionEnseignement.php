<?php
class EnseignementSession extends Model {
    
    protected $table = "session_teachings";
    
    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function allWithView($search = null)
    {
        $params = [];
        $whereClause = "";

        // On prépare la condition WHERE si une recherche est fournie
        if (!empty($search)) {
            // On utilise COLLATE sur le paramètre pour qu'il s'adapte à la colonne, 
            // ou on force tout en unicode_ci pour la cohérence.
            $whereClause = " WHERE (E.title LIKE :search   
                            OR S.nom LIKE :search  ) ";
            $params[':search'] = '%' . $search . '%';
        }

        $sql = "SELECT 
                    E.*, 
                    S.nom AS nom_serie,
                    (
                        SELECT COUNT(*) 
                        FROM enseignement_vues EV 
                        WHERE EV.enseignement_id = E.enseignement_id
                    ) AS total_vues
                FROM {$this->table} E
                INNER JOIN series S ON E.serie_id = S.serie_id
                {$whereClause}
                ORDER BY E.created_at DESC";

        $stmt = $this->db->prepare($sql);
        
        // On exécute
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update(array $datas, string $where = 'st_id')
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

    public function find($sessionId, $enseignementId)
    {
        $query = "SELECT * FROM $this->table WHERE session_id = :session_id AND enseignement_id = :enseignement_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([
            'session_id' => $sessionId,
            'enseignement_id' => $enseignementId
        ]);
        return $q->fetch(PDO::FETCH_OBJ);
    }
}