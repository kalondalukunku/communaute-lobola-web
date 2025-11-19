<?php
require_once APP_PATH .'models/User.php';

class LoginController extends Controller {

    public function index() 
    {
        Session::start();
        $cacheKey = 'user_connexion';
        if (Session::isLogged('user')) Utils::redirect('/');

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_login'])) $this->auth($_POST, $cacheKey);
        $this->view('login/index');
    }

    public function auth($Post, $cacheKey) 
    {
        $userModel = new User();

        $connect = Utils::sanitize(trim($Post['connect'] ?? ''));
        $pswd = Utils::sanitize(trim($Post['pswd'] ?? ''));

        $data = [
            'connect'   => $connect,
            'pswd'      => $pswd
        ];

        if($connect === '' || $pswd === '')
        {
            Session::setFlash('error', 'Remplissez correctement le formulaire.');
            $this->view('login/index', ['data' => $data]);
            return;
        }

        $user = $userModel->loginUser($connect, $cacheKey);

        if ($user && password_verify($pswd, $user->pswd)) 
        {
            Session::set('user', $user);
            Session::setFlash('success', 'Connecté.');
            Utils::redirect('/');
        } else {
            Session::setFlash('error', 'Email ou mot de passe incorrect.');
            $this->view('login/index', ['data' => $data]);
            return;
        }
    }
}
