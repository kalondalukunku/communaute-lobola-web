<?php
class SerieSession extends Model {
    
    protected $table = "session_series";

    public function update(array $datas, string $where = 'ss_id')
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
    
    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function find($sessionId, $seried_id)
    {
        $query = "SELECT * FROM $this->table WHERE session_id = :session_id AND serie_id = :serie_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([
            'session_id' => $sessionId,
            'serie_id' => $seried_id
        ]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function getSerieWithTeachings($serieId, $sessionId)
    {
            // 1. Récupérer les informations de la série
            $stmtSerie = $this->db->prepare("
                SELECT * FROM $this->table 
                WHERE serie_id = :serieId 
                AND session_id = :sessionId 
                LIMIT 1
            ");
            
            $stmtSerie->execute([
                'serieId' => $serieId,
                'sessionId' => $sessionId
            ]);
            
            $serie = $stmtSerie->fetch(PDO::FETCH_OBJ);
            // var_dump($serie); die;

            // Si la série n'existe pas, on arrête ici
            if (!$serie) {
                return null;
            }

            // 2. Récupérer tous les enseignements liés à cette série
            $stmtTeachings = $this->db->prepare("
                SELECT * FROM teachings 
                WHERE serie_id = :serieId
                ORDER BY enseignement_id ASC
            ");
            
            $stmtTeachings->execute(['serieId' => $serieId]);
            
            // Ajouter la liste des enseignements dans le tableau de la série
            $serie->teachings = $stmtTeachings->fetchAll(PDO::FETCH_OBJ);

            return $serie;
    }
}