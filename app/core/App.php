<?php

// Assurez-vous que APP_PATH est défini dans votre index.php ou config.
// Ex: define('APP_PATH', dirname(__DIR__) . '/');

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        // 1. Récupère et nettoie les parties de l'URL de manière sécurisée.
        $url = $this->parseUrl();
        
        // ATTENTION : La ligne dangereuse `$partsUrl = explode('/',$_GET['url']);` 
        // a été supprimée, car elle provoquait une erreur si $_GET['url'] n'existait pas (racine du site).

        // 2. Vérifie et charge le contrôleur
        if (isset($url[0])) {
            $potentialController = ucfirst($url[0]) . 'Controller';
            $controllerFile = APP_PATH . 'controllers/' . $potentialController . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $potentialController;
                unset($url[0]);
            } 
            // Si le contrôleur spécifié n'existe pas, $this->controller reste 'HomeController'.
        }

        // 3. Charger le fichier du contrôleur.
        $controllerFile = APP_PATH . 'controllers/' . $this->controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $this->controller;
        } else {
            $this->error404("Contrôleur introuvable : {$this->controller}");
            return;
        }

        // 4. Vérifie si la méthode existe
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->error404("Méthode inexistante : {$url[1]}");
                return;
            }
        }

        // 5. Récupère les paramètres restants
        $this->params = $url ? array_values($url) : [];

        // 6. Appel du contrôleur et de la méthode
        try {
            // Vérification de l'existence de la classe Session avant l'appel (pour plus de robustesse)
            if (class_exists('Session') && method_exists('Session', 'start')) {
                 Session::start();
            }
            
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Throwable $e) {
            // Intercepte toute erreur (y compris les E_NOTICE transformées en Exception)
            $this->error404("Erreur interne non gérée : " . $e->getMessage());
        }
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) 
        {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        // Retourne un tableau vide si aucune URL n'est définie
        return [];
    }
      
    private function error404($message = null)
    {
        // Envoie le code de réponse HTTP 404
        http_response_code(404);

        $errorView = APP_PATH . 'views/errors/404.php';

        if (file_exists($errorView)) {
            // Définit une variable $message pour qu'elle soit disponible dans la vue 404.php
            // La vue 404.php doit l'utiliser : <p><?= $message; 
            require_once $errorView;
        } else {
            echo "<h1>404 - Page non trouvée</h1>";
            echo "<p>Raison : {$message}</p>";
        }

        exit;
    }
}