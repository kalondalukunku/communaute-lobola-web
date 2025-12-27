<?php
class Personnel extends Model {

    protected $table = 'personnel';
    public $default_per_page = 10;

    public function loginUser($connect, $cacheKey) 
    {
        if($data = Cache::get($cacheKey)) return $data;

        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([
            'email' => $connect
        ]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        Cache::set($cacheKey, $user);

        return $user;
    }

    public function all() 
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function allWhere($where, $param)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE $where = :$where ORDER BY created_at DESC");
        $stmt->execute([$where => $param]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPresencesToday()
    {
        // On définit la date du jour (YYYY-MM-DD)
        $today = date('Y-m-d');

        $sql = "SELECT * FROM {$this->table} 
                WHERE personnel_id NOT IN (
                    SELECT personnel_id 
                    FROM presence 
                    WHERE DATE(date_presence) = :today
                ) 
                ORDER BY nom ASC";

        $stmt = $this->db->prepare($sql);
        
        // On passe le paramètre comme dans votre exemple original
        $stmt->execute(['today' => $today]);

        // On retourne le résultat sous forme d'objets pour rester cohérent avec votre code
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function allPresences(int $page = 1, ?int $per_page = null, ?string $search = null): array
    {
        // Configuration de la pagination
        $limit = $per_page ?? $this->default_per_page;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Date du jour pour le filtre d'absence
        $today = date('Y-m-d');

        // Initialisation des conditions et paramètres
        // On commence par la condition d'exclusion : l'ID ne doit pas être dans les présences du jour
        $conditions = ["P.personnel_id NOT IN (SELECT personnel_id FROM presence WHERE DATE(date_presence) = :today)"];
        $params = ['today' => $today];

        // Filtre sur la recherche (nom, postnom, matricule)
        if (!empty($search)) {
            $conditions[] = "(P.nom LIKE :search OR P.postnom LIKE :search OR P.matricule LIKE :search)";
            $params['search'] = "%$search%";
        }

        // Construction de la clause WHERE
        $whereClause = " WHERE " . implode(" AND ", $conditions);

        // 1. Requête pour le nombre total d'absents
        $sql_count = "SELECT COUNT(*) FROM {$this->table} AS P" . $whereClause;
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 2. Requête pour les données des personnels absents
        // On sélectionne les colonnes nécessaires de la table personnel (P)
        $sql = "SELECT P.* FROM {$this->table} AS P" . 
                $whereClause . 
                " ORDER BY P.nom ASC LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);

        // Liaison dynamique des paramètres (Recherche et Date)
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, PDO::PARAM_STR);
        }

        $q->execute();
        $precences = $q->fetchAll(PDO::FETCH_OBJ);

        // Retourne les données paginées et les métadonnées (même structure que allUsers)
        return [
            'allPresences'  => $precences,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }

    public function sommeSalaire()
    {
        $query = "SELECT SUM(salaire_base) AS total FROM {$this->table}";
        $result = $this->db->query($query)->fetchColumn();
        
        return $result ? $result : 0;
    }

    public function allPersonnelsWithService(string $statut, int $page = 1, ?int $per_page = null, ?string $search = null): array
    {
        $limit = $per_page ?? $this->default_per_page;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Construction de la clause WHERE pour la recherche
        $searchCondition = "";
        $params = ['statut_emploi' => $statut];

        if (!empty($search)) {
            // On cherche dans le nom, le prénom ou le nom du service
            $searchCondition = " AND (P.nom LIKE :search OR P.postnom LIKE :search OR S.nom_service LIKE :search)";
            $params['search'] = "%$search%";
        }

        // 1. Requête pour le nombre total d'enregistrements (avec filtres)
        // Note: Utilisation de l'INNER JOIN pour le count si on cherche aussi dans le nom du service
        $sql_count = "SELECT COUNT(*) 
                    FROM $this->table AS P 
                    INNER JOIN services AS S ON P.service_id = S.service_id
                    WHERE P.statut_emploi = :statut_emploi $searchCondition";
        
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 2. Requête pour les enregistrements de la page actuelle
        $sql = "SELECT
                    P.*,
                    S.nom_service
                FROM
                    $this->table AS P
                INNER JOIN
                    services AS S
                    ON P.service_id = S.service_id
                WHERE
                    P.statut_emploi = :statut_emploi 
                    $searchCondition
                ORDER BY P.nom ASC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);
        
        // On réutilise le tableau $params qui contient déjà 'statut_emploi' et potentiellement 'search'
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, PDO::PARAM_STR);
        }
        
        $q->execute();
        $personnels = $q->fetchAll(PDO::FETCH_OBJ);

        // Retourne les données paginées et le nombre total d'enregistrements
        return [
            'personnels'    => $personnels,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
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

    public function delete($userId) 
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);
    }
    
    public function getPersonnel ($by, $param) 
    {
        $sql = ("SELECT * FROM $this->table WHERE $by = :$by");
        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function getPersonnelDetails ($by, $param) 
    {
        $sql = "SELECT
                    P.*,
                    S.nom_service,
                    PO.nom_poste,
                    C.nom_complet AS nom_conjoint
                FROM
                    $this->table AS P
                LEFT JOIN
                    services AS S
                    ON P.service_id = S.service_id
                LEFT JOIN 
                    postes_occupes AS PO
                    ON P.poste_actuel = poste_occupe_id
                LEFT JOIN 
                    conjoints AS C
                    ON P.conjoint_actuel_id = conjoint_id
                WHERE
                    P.$by = :$by";

        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function getElement($element) 
    {
        return $this->db->query("SELECT $element FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function statusColors($status) 
    {
        if($status === 'actif') return 'success';
        if($status === 'inactif') return 'danger';
    }

    public function getUsersCouristeSuperviseur(?string $orderBy = null, $order = 'ASC', ?int $limit = null) 
    {
        $sql = "SELECT * FROM $this->table WHERE role IN ('couriste', 'superviseur')";
        if($limit != null) {
            $sql .= " LIMIT $limit";
        }
        if($orderBy != null) {
            $sql .= " ORDER BY $orderBy $order";
        }
        $q = $this->db->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllDataWithArgs(string $where, array|string $param,  ?string $orderBy = 'id', string $order = 'DESC', ?bool $and = false, ?string $andParam = null) 
    {
        $query = "SELECT * FROM $this->table WHERE $where = :$where";

        if($and === true) 
        {
            $query .= " AND $andParam = :$andParam";
            $params = [
                $where      => $param[0],
                $andParam   => $param[1]
            ];
        } else 
        {
            $params = [$where => $param];
        }

        if($orderBy) {
            $query .= " ORDER BY $orderBy $order";
        }

        $q = $this->db->prepare($query);
        $q->execute($params);
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllDataSelect($select)
    {
        return $this->db->query("SELECT $select FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllUsersRoleDirecteur()
    {
        $q = $this->db->prepare("SELECT direction FROM $this->table WHERE role = :role");
        $q->execute(['role' => 'directeur']);
        return $q->fetchAll(PDO::FETCH_OBJ);
    }
}