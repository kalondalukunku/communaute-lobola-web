<?php
class LogoutController extends Controller {

    public function index() 
    {
        Session::destroy();
        Cache::delete('user_connexion');
        Utils::redirect('login');
    }
}