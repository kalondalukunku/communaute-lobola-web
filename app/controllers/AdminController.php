<?php
require_once APP_PATH .'models/Admin.php';

class AdminController extends Controller {

    public function index() 
    {
        $cacheKey = 'admin_administraction';
        Auth::requireLogin('admin');

        $this->view('admin/index');
    }

    public function events()
    {
        $cacheKey = 'admin_events';
        Auth::requireLogin('admin');

        $this->view('admin/events');
    }

    public function login() 
    {
        Session::start();
        $cacheKey = 'admin_connexion';
        if (Session::isLogged('admin')) Utils::redirect('../admin/');

        if($_SERVER['REQUEST_METHOD'] === 'POST') $this->auth($_POST, $cacheKey);
        $this->view('admin/login');
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('../admin/login');
    }

    public function auth($Post, $cacheKey) 
    {
        $adminModel = new Admin();

        $email = Utils::sanitize(trim($Post['email'] ?? ''));
        $password = Utils::sanitize(trim($Post['password'] ?? ''));

        if($email === '' || $password === '')
        {
            Session::setFlash('error', 'Remplissez correctement le formulaire.');
        }

        $admin = $adminModel->findByEmail($email, $cacheKey);

        if ($admin && password_verify($password, $admin->pswd)) {
            Session::set('admin', $admin);
            Session::setFlash('success', 'Connecté.');
            Utils::redirect('admin/');
        } else {
            Session::setFlash('error', 'Email ou mot de passe incorrect.');
        }
    }
}
