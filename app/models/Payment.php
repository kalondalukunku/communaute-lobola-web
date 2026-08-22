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

    public function update(array $datas, string $where = 'pay_id')
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

    public function kpayPaymentMobile($amount, $provider, $phoneNumber, $externalId)
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/init");
        curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "X-API-Key: " . "kpay_live_9b874273960439743a7dbcb13969e0cb1e1e64113c184440",
            "X-Secret-Key: " . "5a043c07d8b5a9510d342cdeb68b1e0a93efa2ca01696bbbb1e090f712d3b643",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode([
            "amount" => $amount,
            "provider" => "$provider",
            "phoneNumber" => $phoneNumber,
            "externalId" => $externalId,
        ]),
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function kpayPaymentGateway($amount, $currency, $externalId, $returnUrl = "https://communaute-lobola.ankhing.com/pay/return")
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/init");
        curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "X-API-Key: " . "kpay_live_27e7219484cecadf718e38669bdfa5129ccf96d536de7e35",
            "X-Secret-Key: " . "a118dc73a23348f7b82749dc4b89b50fe0271754974e91543c9c9f6ffa91cb1c",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode([
            "amount" => $amount,
            "currency" => $currency,
            "externalId" => $externalId,
            "returnUrl" => $returnUrl,
        ]),
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function kpayGetTransactionStatus($transactionId)
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/$transactionId");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "X-API-Key: " . KPAY_API_KEY2,
                "X-Secret-Key: " . KPAY_SECRET_KEY2,
                "Content-Type: application/json",
            ],
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function KpayGetSolde()
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/balance");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "X-API-Key: " . KPAY_API_KEY2,
                "X-Secret-Key: " . KPAY_SECRET_KEY2,
            ],
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function KpayExchangeRate($from, $to)
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/exchange-rate?from=$from&to=$to");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "X-API-Key: " . KPAY_API_KEY2,
                "X-Secret-Key: " . KPAY_SECRET_KEY2,
            ],
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function KpayRetraitGateway($montant, $externalId)
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "X-API-Key: " . KPAY_API_KEY2,
                "X-Secret-Key: " . KPAY_SECRET_KEY2,
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "amount" => $montant,
                "externalId" => $externalId,
                "returnUrl" => SITE_URL . "/admin/comptabilite",
            ]),
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function KpayRetraitUSSD($montant, $externalId)
    {
        $ch = curl_init("https://admin.kpay.site/api/v1/payments/withdraw");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "X-API-Key: " . KPAY_API_KEY2,
                "X-Secret-Key: " . KPAY_SECRET_KEY2,
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "amount" => $montant,
                "externalId" => $externalId,
                "returnUrl" => SITE_URL . "/admin/comptabilite",
            ]),
        ]);
        return json_decode(curl_exec($ch), true);
    }

    public function getPayment($memberId, $engagementId, $order = 'ORDER BY payment_date DESC')
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id AND engagement_id = :engagement_id $order LIMIT 1";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId, 'engagement_id' => $engagementId]);
        return $q->fetch();
    }

    public function getPaymentsByMember($memberId)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id ORDER BY payment_date DESC";
        $q = $this->db->prepare($query);
        $q->execute(['member_id' => $memberId]);
        return $q->fetchAll();
    }

    public function getTotalPayments(): float
    {
        // 1. Définition des taux de change (1 USD = X devise)
        $exchangeRates = [
            'CDF' => 2300, // Ajusté à 2800 ou selon votre input ['CDF' => 2800, 'EUR' => 0.87]
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        // 2. Sélectionner la somme groupée par devise pour minimiser les calculs PHP
        $query = "SELECT devise, SUM(amount) as subtotal 
                FROM $this->table 
                WHERE payment_status = 'Payé'
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

    public function getTotalPaymentsMonth(): float
    {
        // 1. Définition des taux de change (Base 1 USD)
        $exchangeRates = [
            'CDF' => 2300, 
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        /**
         * 2. Sélection groupée par devise pour le mois actuel uniquement.
         * On filtre sur le mois et l'année en cours (CURRENT_DATE).
         * Remplacez 'created_at' par votre colonne de date (ex: 'payment_date' ou 'payment_prochain').
         */
        $query = "SELECT devise, SUM(amount) as subtotal 
                FROM payments 
                WHERE MONTH(payment_date) = MONTH(CURRENT_DATE)
                    AND YEAR(payment_date) = YEAR(CURRENT_DATE)
                    AND payment_status = 'Payé'
                GROUP BY devise";
        
        $q = $this->db->prepare($query);
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);

        $totalInUSD = 0.0;

        // 3. Traitement et conversion
        if ($results) {
            foreach ($results as $row) {
                $currency = strtoupper($row->devise);
                $amount = (float)$row->subtotal;

                if ($currency === 'USD') {
                    $totalInUSD += $amount;
                } elseif (isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0) {
                    // Conversion : Montant / Taux
                    $totalInUSD += ($amount / $exchangeRates[$currency]);
                } else {
                    // Par sécurité, on ne traite pas ou on log si la devise est inconnue
                    // Ici on choisit de ne pas l'ajouter pour ne pas fausser le total USD
                }
            }
        }

        return round($totalInUSD, 2);
    }

    public function getTotalPaymentsYear(): float
    {
        // 1. Définition des taux de change (Base 1 USD)
        $exchangeRates = [
            'CDF' => 2300, 
            'EUR' => 0.87,
            'USD' => 1.0
        ];

        /**
         * 2. Sélection groupée par devise pour l'année actuelle uniquement.
         * On filtre sur l'année en cours (CURRENT_DATE).
         * Remplacez 'created_at' par votre colonne de date (ex: 'payment_date' ou 'payment_prochain').
         */
        $query = "SELECT devise, SUM(amount) as subtotal 
                FROM payments 
                WHERE YEAR(payment_date) = YEAR(CURRENT_DATE)
                    AND payment_status = 'Payé'
                GROUP BY devise";
        
        $q = $this->db->prepare($query);
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);

        $totalInUSD = 0.0;

        // 3. Traitement et conversion
        if ($results) {
            foreach ($results as $row) {
                $currency = strtoupper($row->devise);
                $amount = (float)$row->subtotal;

                if ($currency === 'USD') {
                    $totalInUSD += $amount;
                } elseif (isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0) {
                    // Conversion : Montant / Taux
                    $totalInUSD += ($amount / $exchangeRates[$currency]);
                } else {
                    // Par sécurité, on ne traite pas ou on log si la devise est inconnue
                    // Ici on choisit de ne pas l'ajouter pour ne pas fausser le total USD
                }
            }
        }

        return round($totalInUSD, 2);
    }

    public function getAllPayments()
    {
        $query = "SELECT 
                    *,
                    M.nom_postnom,
                    E.modalite_engagement,
                    CASE 
                        WHEN payment_status = 'Payé' THEN amount
                        ELSE 0
                    END AS amount_paid
                FROM $this->table
                INNER JOIN members M ON $this->table.member_id = M.member_id
                INNER JOIN engagements E ON $this->table.engagement_id = E.engagement_id
                ORDER BY payment_date DESC";
        $q = $this->db->prepare($query);
        $q->execute();
        return $q->fetchAll();
    }
}