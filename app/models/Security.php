<?php
    require_once APP_PATH . 'core/Model.php';

class Security extends Model
{
    protected string $table = 'ips_logs';

    /* ==============================
        Vérifier si IP bloquée
    ============================== */
    public function isBlocked(string $ip): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE ip_address = :ip LIMIT 1");
        $stmt->execute(['ip' => $ip]);
        return $stmt->rowCount() > 0;
    }

    /* ==============================
        Enregistrer attaque
    ============================== */
    public function logAttack($ip, $userAgent, $type, $payload)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (ip_address, user_agent, attack_type, payload, blocked_at)
        VALUES (:ip, :ua, :type, :payload, NOW())");


        $stmt->execute([
        'ip' => $ip,
        'ua' => $userAgent,
        'type' => $type,
        'payload' => $payload
        ]);


        $this->notifyAdmin($ip, $type, $payload);
    }

    /* ==============================
        Redirection sécurisée
    ============================== */
    protected function notifyAdmin($ip, $type, $payload)
    {
        $to = ADMIN_EMAIL;
        $subject = "🚨 Tentative d'intrusion détectée";
        $message = "Une intrusion a été détectée :\n\nIP : $ip\nType : $type\nPayload : $payload\nDate : " . date('Y-m-d H:i:s');
        @mail($to, $subject, $message);
    }


    public function blockAndRedirect()
    {
        header('Location: /intrusion');
        exit;
    }
}
