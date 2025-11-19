<?php
class Redirection extends Model {

    protected $table = 'redirections';


    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, nom, email, pswd, role) 
                VALUES (:nom, :email, :pswd, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function all() {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC")->fetchAll(PDO::FETCH_OBJ);
    } 

    public function find($CourierId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE courier_id = :courier_id");
        $stmt->execute(['courier_id' => $CourierId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
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

    public function findById(int $id)
    {
        $q = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $q->execute(['id' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
    } 

    public function getAllDataWithArgs(string $where, array|string $param,  ?string $orderBy = 'id', string $order = 'DESC', ?bool $and = false, ?string $andParam = null) {
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

    public function deleteById($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getCouriersPlusFrequents($by, $param, $year, $limit)
    {
        $sql = "SELECT nom_personne_redirigee, COUNT(*) AS total FROM $this->table WHERE $by = :$by AND YEAR(created_at) = $year GROUP BY nom_personne_redirigee ORDER by total DESC LIMIT $limit";
        $q = $this->db->prepare($sql);
        $q->execute([$by => $param]);

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDataWithPourcentage2($year)
    {
        $sql = "SELECT 
                    nom_personne_redirigee, 
                    COUNT(*) AS documents_recus, 
                    SUM(CASE WHEN status = 'traité' THEN 1 ELSE 0 END) AS documents_traite, 
                    CONCAT(
                        LPAD(FLOOR(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)) / 3600), 2), 'h ', 
                        LPAD(FLOOR(MOD(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)), 3600) / 60), 2, '0'), 'min ',
                        LPAD(FLOOR(MOD(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)), 60)), 2, '0'), 'sec '
                    ) AS delai_moyen,
                    ROUND((SUM(CASE WHEN status = 'traité' THEN 1 ELSE 0 END) / COUNT(*)) *100) AS taux_traitement 
                    
                FROM $this->table 
                WHERE nom_personne_redirigee IS NOT NULL AND nom_personne_redirigee != '' AND YEAR(created_at) = $year 
                GROUP BY nom_personne_redirigee 
                ORDER BY documents_recus DESC 
                LIMIT 5";

        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDataServicePerformant($year)
    {
        $sql = "SELECT 
                    nom_personne_redirigee, 
                    COUNT(*) AS documents_traite, 
                    CONCAT(
                        LPAD(FLOOR(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)) / 3600), 2), 'h ', 
                        LPAD(FLOOR(MOD(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)), 3600) / 60), 2, '0'), 'min ',
                        LPAD(FLOOR(MOD(AVG(TIMESTAMPDIFF(SECOND, created_at, date_classement)), 60)), 2, '0'), 'sec '
                    ) AS delai_moyen 
                    
                FROM $this->table 
                WHERE status = 'traité' AND nom_personne_redirigee IS NOT NULL AND nom_personne_redirigee != '' AND date_classement IS NOT NULL AND date_limite IS NOT NULL AND YEAR(created_at) = $year 
                GROUP BY nom_personne_redirigee 
                ORDER BY delai_moyen ASC LIMIT 5";

        $q = $this->db->prepare($sql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDocRedirigerStatutFinal()
    {
        $sql = "SELECT 
                    r.nom_personne_redirigee AS destinataire,
                    COUNT(r.id) AS documents_recus,
                    SUM(CASE WHEN r.status = 'en cours' THEN 1 ELSE 0 END) AS en_cours,
                    SUM(CASE WHEN r.status = 'non traité' THEN 1 ELSE 0 END) AS non_traites,
                    SUM(CASE WHEN r.status = 'traité' THEN 1 ELSE 0 END) AS traites
                FROM $this->table r 
                JOIN couriers c ON r.courier_id = c.courier_id
                GROUP BY r.nom_personne_redirigee
                ORDER BY documents_recus DESC
                LIMIT 5";

        $q = $this->db->prepare($sql);
        $q->execute([]);

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDocEnAttente()
    {
        $sql = "SELECT ref_num, objet, reception_num, date_reception FROM couriers";
        $q = $this->db->prepare($sql);
        $q->execute([]);

        return $q->fetchAll(PDO::FETCH_OBJ);
    }
}