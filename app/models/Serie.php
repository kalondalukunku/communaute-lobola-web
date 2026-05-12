<?php
class Serie extends Model {
    
    protected $table = "series";

    
    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function update(array $datas, string $where = 'serie_id')
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
    
    public function all($categoryId, $sessionId, $isActive = false)
    {
        $whereclause = "";
        if($isActive) {
            $whereclause = "AND ssc.is_active = '1'";
        }
        $sql = "SELECT 
                    s.*, 
                    ssc.updated_at AS serie_session_updated_at,
                    ssc.session_id,
                    t.enseignement_id,
                    t.title AS teaching_title,
                    t.audio_url,
                    t.description,
                    t.duration_minutes,
                    t.created_at AS teaching_created_at,
                    COALESCE(ssc.is_active, 0) AS is_active_for_session,
                    (SELECT COUNT(*) 
                    FROM enseignement_vues ev 
                    WHERE ev.enseignement_id = t.enseignement_id 
                    AND ev.session_id = :session_id) AS total_views
                FROM {$this->table} s
                LEFT JOIN teachings t 
                    ON s.serie_id = t.serie_id 
                LEFT JOIN session_series ssc
                    ON s.serie_id = ssc.serie_id 
                    AND ssc.session_id = :session_id
                WHERE (t.category_id = :category_id OR t.category_id IS NULL)
                    $whereclause    
                ORDER BY s.created_at ASC, t.created_at ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'category_id' => $categoryId,
                'session_id'  => $sessionId
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            $series = [];
            foreach ($results as $row) {
                $serieId = $row->serie_id;
                
                // Initialisation de la série si elle n'existe pas encore dans le tableau
                if (!isset($series[$serieId])) {
                    // On crée une copie de l'objet pour la série
                    $series[$serieId] = (object)[
                        'serie_id' => $row->serie_id,
                        'session_id' => $row->session_id,
                        'nom' => $row->nom,
                        'description' => $row->description,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->serie_session_updated_at,
                        'is_active_for_session' => $row->is_active_for_session,
                        'total_views' => $row->total_views,
                        'teachings' => []
                    ];
                }
                
                // On ajoute l'enseignement à la liste de la série s'il existe
                if ($row->enseignement_id) {
                    $series[$serieId]->teachings[] = (object)[
                        'id' => $row->enseignement_id,
                        'title' => $row->teaching_title,
                        'audio_url' => $row->audio_url,
                        'duration' => $row->duration_minutes,
                        'created_at' => $row->teaching_created_at
                    ];
                }
            }

            // On retourne les valeurs du tableau pour obtenir une liste simple [{}, {}]
            return array_values($series);

        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return [];
        }
    }

    public function findOneWithTeachings($serieId, $categoryId, $sessionId, $isActive = false)
    {
        $whereclause = "";
        if ($isActive) {
            $whereclause = "AND ssc.is_active = '1'";
        }

        $sql = "SELECT 
                    s.serie_id,
                    s.nom,
                    s.created_at,
                    ssc.updated_at AS serie_session_updated_at,
                    ssc.session_id,
                    t.enseignement_id,
                    t.title AS teaching_title,
                    t.audio_url,
                    t.description AS teaching_description,
                    t.duration_minutes,
                    t.created_at AS teaching_created_at,
                    COALESCE(ssc.is_active, 0) AS is_active_for_session,
                    (SELECT COUNT(*) 
                    FROM enseignement_vues ev 
                    WHERE ev.enseignement_id = t.enseignement_id 
                    AND ev.session_id = :session_id) AS total_views
                FROM {$this->table} s
                LEFT JOIN teachings t 
                    ON s.serie_id = t.serie_id 
                LEFT JOIN session_series ssc
                    ON s.serie_id = ssc.serie_id 
                    AND ssc.session_id = :session_id
                WHERE s.serie_id = :serie_id 
                    AND (t.category_id = :category_id OR t.category_id IS NULL)
                    $whereclause    
                ORDER BY t.created_at ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'serie_id'    => $serieId,
                'category_id' => $categoryId,
                'session_id'  => $sessionId
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            if (!$results) {
                return null;
            }

            $serie = null;

            foreach ($results as $row) {
                // Initialisation de l'objet principal une seule fois
                if ($serie === null) {
                    $serie = (object)[
                        'serie_id' => $row->serie_id,
                        'session_id' => $row->session_id,
                        'nom' => $row->nom,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->serie_session_updated_at,
                        'is_active_for_session' => $row->is_active_for_session,
                        'total_views' => $row->total_views,
                        'teachings' => []
                    ];
                }
                
                // Ajout de chaque enseignement trouvé dans le tableau 'teachings'
                if (!empty($row->enseignement_id)) {
                    $serie->teachings[] = (object)[
                        'enseignement_id' => $row->enseignement_id,
                        'serie_id' => $row->serie_id,
                        'title' => $row->teaching_title,
                        'audio_url' => $row->audio_url,
                        'description' => $row->teaching_description,
                        'duration' => $row->duration_minutes,
                        'created_at' => $row->teaching_created_at
                    ];
                }
            }

            return $serie;

        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return null;
        }
    }
    
    public function getSerieSession($serieId, $categoryId, $sessionId, $isActive = false)
    {
        $whereclause = "";
        if($isActive) {
            $whereclause = "AND ssc.is_active = '1'";
        }
        $sql = "SELECT 
                    s.*, 
                    ssc.updated_at AS serie_session_updated_at,
                    ssc.session_id,
                    t.enseignement_id,
                    t.title AS teaching_title,
                    t.audio_url,
                    t.description,
                    t.duration_minutes,
                    t.created_at AS teaching_created_at,
                    COALESCE(ssc.is_active, 0) AS is_active_for_session,
                    (SELECT COUNT(*) 
                    FROM enseignement_vues ev 
                    WHERE ev.enseignement_id = t.enseignement_id 
                    AND ev.session_id = :session_id) AS total_views
                FROM {$this->table} s
                LEFT JOIN teachings t 
                    ON s.serie_id = t.serie_id 
                LEFT JOIN session_series ssc
                    ON s.serie_id = ssc.serie_id 
                    AND ssc.session_id = :session_id
                WHERE s.serie_id = :serie_id AND (t.category_id = :category_id OR t.category_id IS NULL)
                    $whereclause    
                ORDER BY s.created_at ASC, t.created_at ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'category_id' => $categoryId,
                'session_id'  => $sessionId,
                'serie_id' => $serieId
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            var_dump($results); die;
            
            $series = [];
            foreach ($results as $row) {                
                // On ajoute l'enseignement à la liste de la série s'il existe
                if ($row->enseignement_id) {
                    $series[$serieId]->teachings[] = (object)[
                        'id' => $row->enseignement_id,
                        'title' => $row->teaching_title,
                        'audio_url' => $row->audio_url,
                        'duration' => $row->duration_minutes,
                        'created_at' => $row->teaching_created_at
                    ];
                }
            }

            // On retourne les valeurs du tableau pour obtenir une liste simple [{}, {}]
            return array_values($series);

        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return [];
        }
    }
    
    public function getSeries($categoryId) 
    {
        $stmt = $this->db->prepare("SELECT nom FROM $this->table WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getAll() 
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findByName($serieName)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE nom = :nom LIMIT 1");
        $stmt->execute(['nom' => $serieName]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function find($serieID)
    {
        $stmt = $this->db->prepare("SELECT 
                                        s.*,
                                        COUNT(t.enseignement_id) AS enseignements_count
                                    FROM $this->table s
                                    LEFT JOIN teachings t 
                                        ON t.serie_id   = s.serie_id   
                                    LEFT JOIN session_teachings st
                                        ON t.enseignement_id   = st.enseignement_id  
                                        AND st.is_active = '1'
                                    WHERE s.serie_id = :serie_id
                                    GROUP BY s.serie_id
                                    LIMIT 1");
        $stmt->execute(['serie_id' => $serieID]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}