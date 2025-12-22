<?php
class User extends Model {

    protected $table = 'utilisateurs';
    public $default_per_page = 10;

    public function loginUser($connect, $cacheKey) 
    {
        if($data = Cache::get($cacheKey)) return $data;

        $stmt = $this->db->prepare(
                                    "SELECT 
                                        U.*,
                                        R.nom_role
                                    FROM {$this->table} U
                                    INNER JOIN
                                        roles AS R
                                    ON U.role_id = R.role_id 
                                    WHERE email = :email 
                                    LIMIT 1");
        $stmt->execute([
            'email' => $connect
        ]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        Cache::set($cacheKey, $user);

        return $user;
    }

    public function all() 
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC")->fetchAll(PDO::FETCH_OBJ);
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
    
    public function getUsers ($by, $param) 
    {
        $sql = ("SELECT U.*, R.nom_role, P.nom, P.postnom, P.matricule FROM $this->table AS U LEFT JOIN roles AS R ON R.role_id = U.role_id  LEFT JOIN personnel AS P ON P.matricule = U.matricule_personnel WHERE $by = :$by");
        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetch(PDO::FETCH_OBJ);
    }
    
    public function getElement($element) 
    {
        return $this->db->query("SELECT $element FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getEmails() 
    {
        return $this->db->query("SELECT email FROM $this->table")->fetchAll(PDO::FETCH_OBJ);
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

    public function allUsers(int $page = 1, ?string $is_required = null, ?int $per_page = null, ?string $search = null): array
    {
        $limit = $per_page ?? $this->default_per_page;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Initialisation des conditions et des paramètres
        $conditions = [];
        $params = [];

        // Filtre sur le statut du compte
            if ($is_required !== null) {
                $conditions[] = "U.statut_compte = :statut_compte";
                $params['statut_compte'] = $is_required;
            }

        // Filtre sur la recherche (email, ou infos personnel si disponibles)
        if (!empty($search)) {
            $conditions[] = "(U.email LIKE :search OR P.nom LIKE :search OR P.postnom LIKE :search OR P.matricule LIKE :search OR R.nom_role LIKE :search)";
            $params['search'] = "%$search%";
        }

        // Construction de la clause WHERE
        $whereClause = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";

        // Jointures gauches pour ne perdre aucun utilisateur de la table U
        $joinClause = " LEFT JOIN roles AS R ON U.role_id = R.role_id 
                        LEFT JOIN personnel AS P ON U.matricule_personnel = P.matricule 
                        AND P.statut_emploi != 'Inactif'";

        // 1. Requête pour le nombre total d'enregistrements
        $sql_count = "SELECT COUNT(*) FROM $this->table AS U" . $joinClause . $whereClause;
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 2. Requête pour les enregistrements avec colonnes additionnelles
        $sql = "SELECT U.*, R.nom_role, P.nom, P.postnom 
                FROM $this->table AS U" . 
                $joinClause . 
                $whereClause;
        
        $sql .= " ORDER BY U.created_at ASC LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);

        // Liaison dynamique des paramètres
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, PDO::PARAM_STR);
        }

        $q->execute();
        $allUsers = $q->fetchAll(PDO::FETCH_OBJ);

        // Retourne les données paginées et les métadonnées
        return [
            'allUsers'      => $allUsers,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }

}