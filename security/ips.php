<?php
    require_once APP_PATH . 'core/Model.php';
    require_once APP_PATH . 'helpers/SendMail.php';

    $models = new Model();
    $pdo = $models->db;
    $currentPath = $_SERVER['REQUEST_URI'];
 
    if($currentPath !== '/ntrusion')
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
 
    
        function getUserIP() {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    
        function isMalicious($input) {
            $patterns = [
                '/select.+from/i',
                '/union.+select/i',
                '/<script.*?>/i',
                '/(\.\.\/)+/i',
                '/--/i',
                '/drop\s+table/i',
                '/insert\s+into/i',
                '/onmouseover\s*=/i'
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $input)) return $pattern;
            }
            return false;
        }
    
        function logToDB($pdo, $ip, $pattern, $payload) {
            $stmt = $pdo->prepare("INSERT INTO ips_logs (ip_address, user_agent, attack_type, payload) VALUES (?, ?, ?, ?)");
            $stmt->execute([$ip, $_SERVER['HTTP_USER_AGENT'] ?? 'N/A', $pattern, $payload]);
        }
    
        function sendAlert($ip, $pattern, $payload) {
            $subject = "⚠️ Intrusion détectée depuis $ip";
            $message = "🚨 Nouvelle tentative d'intrusion\n\nIP: $ip\nPattern détecté: $pattern\nPayload: $payload\n\nDate: ".date('d/m/Y H:i');
            mail(ADMIN_EMAIL, $subject, $message);
            // send_email(ADMIN_EMAIL, $subject, $message);
        }
    
        function isBlocked($pdo, $ip) {
            $stmt = $pdo->prepare("SELECT blocked_at FROM ips_logs WHERE ip_address = ? ORDER BY blocked_at DESC LIMIT 1");
            $stmt->execute([$ip]);
            $last = $stmt->fetch();
            if ($last) {
                header("Location: /intrusion");
                exit;
            }
        }   
    
        // ⚙️ Exécution
        $allowed = ['fl','ci'];
        $ip = getUserIP();
        isBlocked($pdo, $ip);
    
        foreach ($_GET as $key => $value) {
            if (is_array($value)) continue;
            if (ctype_digit($value) || preg_match('/^[a-zA-Z0-9_-]+$/', $value)) continue;
    
            $pattern = isMalicious($value);
            if ($pattern) {
                logToDB($pdo, $ip, $pattern, $value);
                sendAlert($ip, $pattern, $value);
                header("Location: /intrusion");
                exit;
            }
        }
    
        // 🔎 Analyse POST
        // foreach ($_POST as $key => $value) {
        //   if (is_array($value)) continue;
      
        //   $pattern = isMalicious($value);
        //   if ($pattern) {
        //     logToDB($pdo, $ip, $pattern, $value);
        //     sendAlert($ip, $pattern, $value);
        //     header("Location: /intrusion");
        //     exit;
        //   }
        // }    
        // 📂 Analyse FILES
        foreach ($_FILES as $key => $file) {
            if (!isset($file['name']) || !isset($file['type'])) continue;
    
            $filename = $file['name'];
            $filetype = $file['type'];
    
            // Exemple de détection sur le nom de fichier
            $pattern = isMalicious($filename);
            if ($pattern) {
                logToDB($pdo, $ip, $pattern, $filename);
                sendAlert($ip, $pattern, $filename);
                header("Location: /intrusion");
                exit;
            }
            // Exemple de détection sur le type MIME
            if (!in_array($filetype, ['image/jpeg', 'image/png', 'application/pdf'])) {
                logToDB($pdo, $ip, 'type_mime_non_autorisé', $filetype);
                sendAlert($ip, 'type_mime_non_autorisé', $filetype);
                header("Location: /intrusion");
                exit;   
            }
        }    
    
    }
    