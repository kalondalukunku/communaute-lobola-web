<?php
class User extends Model {

    protected $table = 'utilisateurs';

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

    public function create($data) 
    {
        $sql = "INSERT INTO {$this->table} (user_id, nom, email, pswd, role) 
                VALUES (:user_id, :nom, :email, :pswd, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
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
        $sql = ("SELECT * FROM $this->table WHERE $by = :$by");
        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetch(PDO::FETCH_OBJ);
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
}