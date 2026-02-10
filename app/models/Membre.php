<?php
class Membre extends Model {
    
    protected $table = "members";
    public $default_per_page = 10;

    public function loginMember($connect, $cacheKey) 
    {
        // if($data = Cache::get($cacheKey)) return $data;

        $stmt = $this->db->prepare(
                                    "SELECT 
                                        M.*,
                                        E.statut AS statut_engagement, 
                                        E.engagement_id, 
                                        E.modalite_engagement, 
                                        E.document_path, 
                                        E.document_ext, 
                                        E.document_header_type, 
                                        E.reference_code, 
                                        E.montant, 
                                        E.devise, 
                                        E.signed_at, 
                                        E.date_expiration 

                                    FROM {$this->table} M
                                    LEFT JOIN
                                        engagements AS E
                                    ON M.member_id = E.member_id 
                                    WHERE M.email = :email OR M.phone_number = :email
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
        $queryEngagements = "DELETE FROM engagements WHERE member_id = :member_id";
        $stmtEng = $this->db->prepare($queryEngagements);
        $stmtEng->execute(['member_id' => $memberId]);

        // 2. Suppression dans la table principale (membre)
        $queryMember = "DELETE FROM {$this->table} WHERE member_id = :member_id";
        $stmtMem = $this->db->prepare($queryMember);
        $stmtMem->execute(['member_id' => $memberId]);
        
        // 3. Suppression dans la table action_raisons
        $queryMember = "DELETE FROM action_raisons WHERE member_id = :member_id";
        $stmtMem = $this->db->prepare($queryMember);
        $stmtMem->execute(['member_id' => $memberId]);

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
        $query = "SELECT M.* FROM $this->table M";
        $q = $this->db->prepare($query);
        $q->execute(); 
        return $q->fetchAll();
    }

    public function findByWhere($where, $parameter)
    {
        $query = "SELECT 
                        M.*,
                        E.statut AS statut_engagement, 
                        E.engagement_id, 
                        E.modalite_engagement, 
                        E.document_path, 
                        E.document_ext, 
                        E.document_header_type, 
                        E.reference_code, 
                        E.montant, 
                        E.devise, 
                        E.signed_at, 
                        E.date_expiration 

                    FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.$where = :$where LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute([$where => $parameter]); 
        return $q->fetch();
    }

    public function findByMemberId($memberId)
    {
        $query = "SELECT 
                        M.*,
                        E.statut AS statut_engagement, 
                        E.engagement_id, 
                        E.modalite_engagement, 
                        E.document_path, 
                        E.document_ext, 
                        E.document_header_type, 
                        E.reference_code, 
                        E.montant, 
                        E.devise, 
                        E.signed_at, 
                        E.date_expiration 
                    
                    FROM $this->table M LEFT JOIN engagements E ON M.member_id = E.member_id WHERE M.member_id = :member_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId]); 
        return $q->fetch();
    }

    // public function findAll(int $page = 1, ?string $search = null, array $conditions = [], ?int $per_page = null): array
    // {
    //     $limit = $per_page ?? $this->default_per_page ?? 10;
    //     $page = max(1, $page);
    //     $offset = ($page - 1) * $limit;

    //     $params = [];
    //     $whereClauses = [];

    //     // 1. Gestion des conditions fixes ($conditions)
    //     if (!empty($conditions)) {
    //         foreach ($conditions as $key => $value) {
    //             $whereClauses[] = "M.$key = :$key";
    //             $params[$key] = $value;
    //         }
    //     }

    //     // 2. Gestion de la recherche ($search)
    //     if (!empty($search)) {
    //         // On cherche dans le nom du membre ou les détails de l'engagement (exemple)
    //         $whereClauses[] = "(M.nom_postnom LIKE :search OR M.email LIKE :search OR E.modalite_engagement LIKE :search)";
    //         $params['search'] = "%$search%";
    //     }

    //     $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

    //     // 3. Requête pour le nombre total d'enregistrements
    //     $sql_count = "SELECT COUNT(DISTINCT M.member_id) 
    //                   FROM $this->table M 
    //                   LEFT JOIN engagements E ON M.member_id = E.member_id 
    //                   $whereSql";
        
    //     $q_count = $this->db->prepare($sql_count);
    //     $q_count->execute($params);
    //     $total_records = (int) $q_count->fetchColumn();

    //     // 4. Requête pour les enregistrements de la page actuelle
    //     $sql = "SELECT 
    //                 M.*,
    //                 E.statut AS statut_engagement, 
    //                 E.engagement_id, 
    //                 E.modalite_engagement, 
    //                 E.document_path, 
    //                 E.document_ext, 
    //                 E.document_header_type, 
    //                 E.reference_code, 
    //                 E.montant, 
    //                 E.devise, 
    //                 E.signed_at, 
    //                 E.date_expiration
                    
    //             FROM $this->table M 
    //             LEFT JOIN engagements E ON M.member_id = E.member_id 
    //             $whereSql 
    //             ORDER BY M.member_id DESC 
    //             LIMIT {$limit} OFFSET {$offset}";
        
    //     $q = $this->db->prepare($sql);
        
    //     // Liaison des paramètres (conditions + search)
    //     foreach ($params as $key => $value) {
    //         $q->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    //     }
        
    //     $q->execute();
    //     $results = $q->fetchAll(PDO::FETCH_OBJ);

    //     // 5. Retour des données formatées
    //     return [
    //         'data'          => $results,
    //         'total_records' => $total_records,
    //         'current_page'  => $page,
    //         'per_page'      => $limit,
    //         'total_pages'   => ceil($total_records / $limit),
    //         'search_query'  => $search
    //     ];
    // }

    public function findAllMembres(int $page = 1, ?string $search = null, array $conditions = [], ?int $per_page = null): array
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
            // Note : On ne peut plus chercher dans E.modalite_engagement ici car il n'y a pas de jointure
            $whereClauses[] = "(M.nom_postnom LIKE :search OR M.email LIKE :search OR M.niveau_initiation LIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereSql = " WHERE " . implode(" AND ", $whereClauses);

        // 3. Requête pour le nombre total d'enregistrements
        $sql_count = "SELECT COUNT(M.member_id) FROM $this->table M $whereSql";
        
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 4. Requête pour les membres uniquement
        $sql = "SELECT M.* FROM $this->table M 
                $whereSql 
                ORDER BY M.nom_postnom ASC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);
        
        // Liaison des paramètres
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

    public function findAllEngages(int $page = 1, ?string $search = null, array $conditions = [], ?int $per_page = null): array
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
            $whereClauses[] = "(M.nom_postnom LIKE :search OR M.email LIKE :search OR E.modalite_engagement LIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

        // 3. Requête pour le nombre total (INNER JOIN garantit qu'on ne compte que les membres avec engagement)
        $sql_count = "SELECT COUNT(DISTINCT M.member_id) 
                    FROM $this->table M 
                    INNER JOIN engagements E ON M.member_id = E.member_id 
                    $whereSql";
        
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 4. Requête pour les enregistrements (INNER JOIN filtre les membres sans engagement)
        $sql = "SELECT 
                    M.*,
                    E.statut AS statut_engagement, 
                    E.engagement_id, 
                    E.modalite_engagement, 
                    E.document_path, 
                    E.document_ext, 
                    E.document_header_type, 
                    E.reference_code, 
                    E.montant, 
                    E.devise, 
                    E.signed_at, 
                    E.date_expiration
                    
                FROM $this->table M 
                INNER JOIN engagements E ON M.member_id = E.member_id 
                $whereSql 
                ORDER BY M.member_id DESC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);
        
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

    public function calculerTauxEngagementApprouve()
    {
        $totalMembres = $this->countAll(["status" => ARRAY_STATUS_MEMBER[2]]);
        if ($totalMembres === 0) {
            return 0;
        }

        $membresEngagesApprouves = $this->countEngagedMembers();

        $taux = ($membresEngagesApprouves / $totalMembres) * 100;

        return round($taux, 2);
    }

}