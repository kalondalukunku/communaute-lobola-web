<?php
class Depense extends Model {
    
    protected $table = "depenses";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function getTotalDepenses(): float
    {
        // 1. Définition des taux de change (1 USD = X devise)
        $exchangeRates = [
            'CDF' => 2300, // Ajusté à 2800 ou selon votre input ['CDF' => 2800, 'EUR' => 0.87]
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        // 2. Sélectionner la somme groupée par devise pour minimiser les calculs PHP
        $query = "SELECT devise, SUM(montant) as subtotal 
                FROM $this->table
                GROUP BY devise";
        $q = $this->db->prepare($query);
        $q->execute();
        $results = $q->fetchAll();

        // 3. Convertir chaque sous-total en USD et les additionner
        $totalInUSD = 0;
        foreach ($results as $row) {
            $devise = $row->devise;
            $subtotal = $row->subtotal;
            if (isset($exchangeRates[$devise])) {
                $totalInUSD += $subtotal / $exchangeRates[$devise];
            }
        }

        return round($totalInUSD, 2);
    }

    public function getTotalDepensesMonth(): float
    {
        // 1. Définition des taux de change (1 USD = X devise)
        $exchangeRates = [
            'CDF' => 2300, // Ajusté à 2800 ou selon votre input ['CDF' => 2800, 'EUR' => 0.87]
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        // 2. Sélectionner la somme groupée par devise pour minimiser les calculs PHP
        $query = "SELECT devise, SUM(montant) as subtotal 
                FROM $this->table
                WHERE MONTH(date_depense) = MONTH(CURRENT_DATE()) AND YEAR(date_depense) = YEAR(CURRENT_DATE())
                GROUP BY devise";
        $q = $this->db->prepare($query);
        $q->execute();
        $results = $q->fetchAll();

        // 3. Convertir chaque sous-total en USD et les additionner
        $totalInUSD = 0;
        foreach ($results as $row) {
            $devise = $row->devise;
            $subtotal = $row->subtotal;
            if (isset($exchangeRates[$devise])) {
                $totalInUSD += $subtotal / $exchangeRates[$devise];
            }
        }

        return round($totalInUSD, 2);
    }

    public function getTotalDepensesYear(): float
    {
        // 1. Définition des taux de change (1 USD = X devise)
        $exchangeRates = [
            'CDF' => 2300, // Ajusté à 2800 ou selon votre input ['CDF' => 2800, 'EUR' => 0.87]
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        // 2. Sélectionner la somme groupée par devise pour minimiser les calculs PHP
        $query = "SELECT devise, SUM(montant) as subtotal 
                FROM $this->table
                WHERE YEAR(date_depense) = YEAR(CURRENT_DATE())
                GROUP BY devise";
        $q = $this->db->prepare($query);
        $q->execute();
        $results = $q->fetchAll();

        // 3. Convertir chaque sous-total en USD et les additionner
        $totalInUSD = 0;
        foreach ($results as $row) {
            $devise = $row->devise;
            $subtotal = $row->subtotal;
            if (isset($exchangeRates[$devise])) {
                $totalInUSD += $subtotal / $exchangeRates[$devise];
            }
        }

        return round($totalInUSD, 2);
    }

    public function getAllDepenses()
    {
        $query = "SELECT * FROM $this->table ORDER BY date_depense DESC";
        $q = $this->db->prepare($query);
        $q->execute();
        return $q->fetchAll();
    }
}