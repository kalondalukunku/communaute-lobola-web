<?php
class Payment extends Model {
    
    protected $table = "payments";


    public function insert(array $datas)
    {
        $keys = array_keys($datas);
        $query = "INSERT INTO $this->table (". implode(", ", $keys) .") VALUES(:". implode(", :", $keys) .")";
        $q = $this->db->prepare($query);
        return $q->execute($datas);
    }

    public function getPayment($memberId, $engagementId)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id AND engagement_id = :engagement_id LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId, 'engagement_id' => $engagementId]);
        return $q->fetch();
    }

    // public function getTotalPayments()
    // {
    //     $query = "SELECT SUM(amount) as total 
    //             FROM payments 
    //             WHERE payment_prochain > NOW()";
    //     $q = $this->db->prepare($query);
    //     $q->execute();
    //     return $q->fetch()->total;
    // }

    public function getTotalPayments(): float
    {
        // 1. Définition des taux de change (1 USD = X devise)
        $exchangeRates = [
            'CDF' => 2800, // Ajusté à 2800 ou selon votre input ['CDF' => 2800, 'EUR' => 0.87]
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        // 2. Sélectionner la somme groupée par devise pour minimiser les calculs PHP
        $query = "SELECT devise, SUM(amount) as subtotal 
                FROM payments 
                WHERE payment_prochain > NOW()
                GROUP BY devise";
        
        $q = $this->db->prepare($query);
        $q->execute();
        $results = $q->fetchAll();

        $totalInUSD = 0.0;

        // 3. Traitement et conversion
        foreach ($results as $row) {
            $currency = strtoupper($row->devise);
            $amount = (float)$row->subtotal;

            if ($currency === 'USD') {
                $totalInUSD += $amount;
            } elseif (isset($exchangeRates[$currency])) {
                // Conversion : Montant / Taux (ex: 2800 CDF / 2800 = 1 USD)
                $totalInUSD += ($amount / $exchangeRates[$currency]);
            } else {
                // Optionnel : Gérer une devise inconnue (on l'ajoute telle quelle ou on ignore)
                $totalInUSD += $amount;
            }
        }

        return round($totalInUSD, 2);
    }
}