<?php 
    require_once APP_PATH . 'models/Security.php'; 

    $security = new Security();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';


    // Vérifier si IP déjà bloquée
    if ($security->isBlocked($ip)) {
        $security->blockAndRedirect();
    }


    // Détection simple d'injection ou tentative suspecte
    $suspicious_patterns = [
        // SQL injection (mot-clé + commentaires inline)
        'SQL_INJECTION' => "/('|\")|(--|;)|\\b(SELECT|INSERT|DELETE|UPDATE|DROP|UNION|OR|AND|CREATE|ALTER)\\b/i",

        // XSS basique (tags + javascript: + handlers)
        'XSS' => "/(<script\b[^>]*>.*?<\\/script>)|(<[^>]+\\s(on\\w+)\\s*=)|javascript:|<iframe\\b/i",

        // LFI / RFI / path traversal / accès fichiers sensibles
        'LFI_RFI' => "/(\\.{2}\\/)|(\\b(passwd|shadow|etc\\/passwd)\\b)|((php|data|file|zip|http|https):\\/\\/)/i",

        // Command injection (backticks, pipes, & etc)
        'CMD_INJECTION' => "/[`\\|\\$\\&\\;]|\\b(exec|system|shell_exec|passthru|popen)\\b/i"
    ];


    $sources = array_merge($_GET, $_POST);

    // aussi vérifier les keys (certaines attaques ciblent le nom du paramètre)
    foreach ($sources as $key => $value) {

        // vérifier la clé
        if (is_string($key)) {
            foreach ($suspicious_patterns as $type => $pattern) {
                if (preg_match($pattern, $key)) {
                    $security->logAttack($ip, $userAgent, $type . ' (param name)', $key);
                    $security->blockAndRedirect();
                }
            }
        }

        // vérifier la valeur (si c'est une string)
        if (is_string($value) && $value !== '') {
            foreach ($suspicious_patterns as $type => $pattern) {
                if (preg_match($pattern, $value)) {
                    $security->logAttack($ip, $userAgent, $type, $value);
                    $security->blockAndRedirect();
                }
            }
        }

        // si c'est un tableau (e.g. checkbox[]), on le parcourt récursivement
        if (is_array($value)) {
            $flatten = new RecursiveIteratorIterator(new RecursiveArrayIterator($value));
            foreach ($flatten as $subvalue) {
                if (is_string($subvalue)) {
                    foreach ($suspicious_patterns as $type => $pattern) {
                        if (preg_match($pattern, $subvalue)) {
                            $security->logAttack($ip, $userAgent, $type, $subvalue);
                            $security->blockAndRedirect();
                        }
                    }
                }
            }
        }
    }



    /* ================================
    BLOQUER SI ERREUR 403 APACHE
    ================================ */
    http_response_code(200);
    set_error_handler(function() use ($security, $ip, $userAgent, $requestUri) {
        if (http_response_code() === 403) {
            $security->logAttack($ip, $userAgent, 'Forbidden Access Attempt', $requestUri);
            $security->blockAndRedirect();
        }
    });


?>

<?php
// require_once 'ips.php';
// http_response_code(403);
?>
