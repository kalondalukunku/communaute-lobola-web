<?php

class TypesDocument extends Model
{
    protected $table = 'types_document';
    public $default_per_page = 10;

    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY type_doc_id ASC")->fetchAll(PDO::FETCH_OBJ);
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
    
    public function lastInsertId($id, $param)
    {
        $stmt = $this->db->prepare("SELECT $id FROM {$this->table} WHERE type_doc_id = :type_doc_id LIMIT 1");
        $stmt->execute([
            'type_doc_id'  => $param
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function find($typeDocID)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE type_doc_id = :type_doc_id");
        $stmt->execute(['type_doc_id' => $typeDocID]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByName($nameTypeDoc)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nom_type = :nom_type");
        $stmt->execute(['nom_type' => $nameTypeDoc]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function allTpDcs(int $page = 1, ?string $is_required = null, ?int $per_page = null, ?string $search = null): array
    {
        $limit = $per_page ?? $this->default_per_page;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Initialisation des conditions et des paramètres
        $conditions = [];
        $params = [];

        // Filtre sur le caractère obligatoire
        if ($is_required !== null) {
            $conditions[] = "TD.est_obligatoire = :est_obligatoire";
            $params['est_obligatoire'] = $is_required;
        }

        // Filtre sur la recherche (nom du document par exemple)
        if (!empty($search)) {
            $conditions[] = "TD.nom_type LIKE :search"; // Adaptez 'nom_type_doc' selon votre colonne réelle
            $params['search'] = "%$search%";
        }

        // Construction de la clause WHERE
        $whereClause = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";

        // 1. Requête pour le nombre total d'enregistrements
        $sql_count = "SELECT COUNT(*) FROM $this->table AS TD" . $whereClause;
        $q_count = $this->db->prepare($sql_count);
        $q_count->execute($params);
        $total_records = (int) $q_count->fetchColumn();

        // 2. Requête pour les enregistrements de la page actuelle
        $sql = "SELECT TD.* FROM $this->table AS TD" . $whereClause;
        $sql .= " ORDER BY TD.created_at ASC LIMIT {$limit} OFFSET {$offset}";
        
        $q = $this->db->prepare($sql);

        // Liaison dynamique de tous les paramètres présents
        foreach ($params as $key => $value) {
            $q->bindValue($key, $value, PDO::PARAM_STR);
        }

        $q->execute();
        $alltypesdocs = $q->fetchAll(PDO::FETCH_OBJ);

        // Retourne les données paginées et les métadonnées
        return [
            'alltypesdocs'  => $alltypesdocs,
            'total_records' => $total_records,
            'current_page'  => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total_records / $limit),
            'search_query'  => $search
        ];
    }
}