<?php
class LogoutController extends Controller {

    public function index() 
    {
        Session::destroy();
        Cache::delete('membre_connexion');
        Utils::redirect('login');
    }
}