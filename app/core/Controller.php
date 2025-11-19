<?php
class Controller {
    // charger un model
    public function model($model)
    {
        require_once APP_PATH . 'models/' . $model . '.php';
        return new $model();
    }

    // charger une vue
    public function view($view, $data = [])
    {
        extract($data);
        require_once APP_PATH . 'views/' . $view . '.php';
    }
}