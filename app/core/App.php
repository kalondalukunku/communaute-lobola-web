<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        $partsUrl = explode('/',$_GET['url']);
        //  Vérifie si le contrôleur existe
        if (isset($url[0]) && file_exists(APP_PATH . 'controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        //  Charge le contrôleur ou affiche 404 si introuvable
        $controllerFile = APP_PATH . 'controllers/' . $this->controller . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $this->controller;
        } else {
            return $this->error404("Contrôleur introuvable : {$this->controller}");
        }

        //  Vérifie si la méthode existe
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                return $this->error404("Méthode inexistante : {$url[1]}");
            }
        }

        //  Récupère les paramètres restants
        $this->params = $url ? array_values($url) : [];

        //  Appel du contrôleur et de la méthode
        try {
            Session::start();
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Throwable $e) {
            // Si une erreur inattendue survient
            $this->error404("Erreur interne : " . $e->getMessage());
        }
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) 
        {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
     
    private function error404($message = null)
    {
        http_response_code(404);

        $errorView = APP_PATH . 'views/errors/404.php';

        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            // Fallback simple si la vue n'existe pas encore
            echo "<h1>404 - Page non trouvée</h1>";
            echo "<p>{$message}</p>";
        }

        exit;
    }
}