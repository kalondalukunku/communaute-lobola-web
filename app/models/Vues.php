<?php
class Vues extends Model {
    
    protected $table = "enseignement_vues";


    public function enregistrerVueUnique($idEnseignement, $idSerie, $idUser) 
    {
        try {
            // 1. On vérifie si une vue existe déjà pour ce couple enseignement/utilisateur
            // Si l'utilisateur est anonyme (null), on peut coupler avec l'IP si nécessaire,
            // mais ici nous suivons strictement vos paramètres.
            
            $sqlCheck = "SELECT COUNT(*) FROM $this->table 
                        WHERE enseignement_id = :id_e 
                        AND user_id = :id_u";
            
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([
                'id_e' => $idEnseignement,
                'id_u' => $idUser
            ]);

            $existeDeja = $stmtCheck->fetchColumn() > 0;

            // 2. Si la vue n'existe pas, on l'enregistre
            if (!$existeDeja) {
                $sqlInsert = "INSERT INTO $this->table (enseignement_id, user_id, serie_id, viewed_at, user_agent, ip_address) 
                            VALUES (:id_e, :id_u, :serie_id, :viewed_at, :user_agent, :ip)";
                
                $stmtInsert = $this->db->prepare($sqlInsert);
                $stmtInsert->execute([
                    'id_e'      => $idEnseignement,
                    'id_u'      => $idUser,
                    'serie_id'  => $idSerie,
                    'user_agent'=> $_SERVER['HTTP_USER_AGENT'] ?? null,
                    'viewed_at' => date('Y-m-d H:i:s'),
                    'ip'        => $_SERVER['REMOTE_ADDR'] // Optionnel : pour garder une trace de l'IP
                ]);

                return true; // Nouvelle vue enregistrée
            }

            return false; // Déjà enregistré, on ne fait rien

        } catch (PDOException $e) {
            // En cas d'erreur, on log l'erreur pour le debug
            error_log("Erreur lors de l'enregistrement de la vue : " . $e->getMessage());
            return false;
        }
    }

    public function countAll($conditions = [], $cacheKey = null)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    // Gestion de la clause IN (ex: status => [0, 1])
                    $placeholders = [];
                    foreach ($value as $index => $val) {
                        $paramName = "{$key}_{$index}";
                        $placeholders[] = ":$paramName";
                        $params[$paramName] = $val;
                    }
                    $clauses[] = "$key IN (" . implode(", ", $placeholders) . ")";
                } else {
                    // Gestion de l'égalité classique
                    $clauses[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }
            $query .= implode(" AND ", $clauses);
        }

        $q = $this->db->prepare($query);
        $q->execute($params);
        $result = $q->fetch(PDO::FETCH_OBJ); // Utilisation de FETCH_OBJ pour correspondre à votre accès ->total

        return $result ? (int)$result->total : 0;
    }

}