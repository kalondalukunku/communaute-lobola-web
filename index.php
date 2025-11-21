<?php
    // Afficher les erreurs pendanr le dev (desactiver en prod)
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // chemins vers le dossier du projet
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $projetFolder = rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', __DIR__)), '/');

    define('ADMIN_EMAIL', 'support@ankhing.com');

    define('BASE_URL', $protocol . $domain . $projetFolder);
    define('BASE_PATH', __DIR__ . '/');
    define('APP_PATH', BASE_PATH . 'app/');
    define('CONFIG_PATH', BASE_PATH . 'config/');
    define('SECURITY_PATH', BASE_PATH . 'security/');
    define('STORAGE', BASE_PATH . 'storage/');
    define('ASSETS', BASE_URL . '/assets/');
    define('LIBS', BASE_URL . '/libs/');
    define('STORAGE_UPLOAD', STORAGE . 'uploads/');
    define('STORAGE_GENERATED', STORAGE . 'generated/');
    define('STORAGE_UPLOAD_PDF', STORAGE_UPLOAD . 'pdf/');
    define('STORAGE_UPLOAD_RAPPORT', STORAGE_UPLOAD . 'rapports/');
    define('BASE_PATH_ICON', ASSETS . 'images/logo.png');

    // charger les fichiers de configuration
    require_once CONFIG_PATH . 'config.php';

    // charger les fichiers de configuration
    require_once SECURITY_PATH . 'ips.php';

    // charger les fichiers du MVC
    require_once APP_PATH . 'core/App.php';
    require_once APP_PATH . 'core/Controller.php';
    require_once APP_PATH . 'core/Model.php';
    require_once APP_PATH . 'core/Database.php';
    require_once APP_PATH . 'core/Helper.php';

    // charger les helpers si besoin
    $helpers = ['Session','Auth','Utils'];
    foreach ($helpers as $helper)
    {
        $file = APP_PATH . "helpers/$helper.php";
        if(file_exists($file)) require_once $file;
    }

    // Démarrer l'application
    $app = new App();