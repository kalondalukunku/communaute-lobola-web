<?php
class Membre extends Model {
    
    protected $table = "members";
    public $default_per_page = 10;


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
        $queryEngagements = "DELETE FROM engagements WHERE member_id = :member_id";
        $stmtEng = $this->db->prepare($queryEngagements);
        $stmtEng->execute(['member_id' => $memberId]);

        // 2. Suppression dans la table principale (membre)
        $queryMember = "DELETE FROM {$this->table} WHERE member_id = :member_id";
        $stmtMem = $this->db->prepare($queryMember);
        $stmtMem->execute(['member_id' => $memberId]);

        // Validation des deux opérations
        return $this->db->commit();
    }

    public function findByWhere($where, $parameter)
    {
        $query = "SELECT M.*, E.*, E.statut AS statut_engagement FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.$where = :$where LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([$where => $parameter]); 
        return $q->fetch();
    }

    public function findByMemberId($memberId)
    {
        $query = "SELECT M.*, E.*, E.statut AS statut_engagement FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.member_id = :member_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId]); 
        return $q->fetch();
    }

    public function findAll(int $page = 1, ?string $search = null, array $conditions = [], ?int $per_page = null): array
    {
        $limit = $per_page ?? $this->default_per_page ?? 10;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $params = [];
        $whereClauses = [];

        // 1. Gestion des conditions fixes ($conditions)
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "M.$key = :$key";
                $params[$key] = $value;
            }
        }

        // 2. Gestion de la recherche ($search)
        if (!empty($search)) {
            // On cherche dans le nom du membre ou les détails de l'engagement (exemple)
            $whereClauses[] = "(M.nom_postnom LIKE :search OR M.email LIKE :search OR E.modalite_engagement LIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

        // 3. Requête pour le nombre total d'enregistrements
        $sql_count = "SELECT COUNT(DISTINCT M.member_id) 
                      FROM $this->table M 
                      LEFT JOIN engagements E ON M.member_id = E.member_id 
                      $whereSql";
        
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 4. Requête pour les enregistrements de la page actuelle
        $sql = "SELECT M.*, E.* FROM $this->table M 
                LEFT JOIN engagements E ON M.member_id = E.member_id 
                $whereSql 
                ORDER BY M.member_id DESC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);
        
        // Liaison des paramètres (conditions + search)
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);

        // 5. Retour des données formatées
        return [
            'data'          => $results,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
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

    public function countEngagedMembers(): int
    {
        /**
         * Explication de la requête :
         * 1. On sélectionne les membres de la table 'membres' (M).
         * 2. On utilise une sous-requête pour trouver l'engagement le plus récent (E1) pour chaque membre.
         * 3. On filtre pour ne garder que ceux dont le statut de ce dernier engagement est 'Approuver'.
         */
        $query = "SELECT COUNT(M.member_id) as total 
                FROM $this->table M
                INNER JOIN engagements E1 ON M.member_id = E1.member_id
                WHERE E1.engagement_id = (
                    SELECT E2.engagement_id 
                    FROM engagements E2 
                    WHERE E2.member_id = M.member_id 
                    ORDER BY E2.signed_at DESC
                    LIMIT 1
                )
                AND E1.statut = 'Approuvé'";

        try {
            $q = $this->db->prepare($query);
            $q->execute();
            $result = $q->fetch(PDO::FETCH_OBJ);
            
            return (int)($result->total ?? 0);
        } catch (Exception $e) {
            // En cas d'erreur, on retourne 0
            return 0;
        }
    }

}