<?php
require_once APP_PATH . 'models/Membre.php';

class LoginController extends Controller {

    private $MembreModel;

    public function __construct()
    {        
        $this->MembreModel = new Membre();
 
    }

    public function index() 
    {
        Session::start();
        $cacheKey = 'membre_connexion_';
        if (Session::isLogged('membre')) Utils::redirect('membre/profile/'. Session::get('membre')['member_id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_login'])) $this->auth($_POST, $cacheKey);
        $this->view('login/index');
    }

    public function auth($Post, $cacheKey) 
    {
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

        $Membre = $this->MembreModel->loginMember($connect, $cacheKey);
        if(!$Membre) {
            Session::setFlash('error', 'Adresse mail ou numéro de téléphone incorrect.');
            $this->view('login/index', ['data' => $data]);
            return;
        }

        if($Membre->status !== ARRAY_STATUS_MEMBER[2]) {
            Session::setFlash('error', 'Votre compte n\'est pas activé. Contactez l\'administrateur.');
            $this->view('login/index', ['data' => $data]);
            return;
        }

        if ($Membre && password_verify($pswd, $Membre->pswd)) 
        {
            Cache::set($cacheKey, $Membre);
            Session::set('membre', $Membre);
            Session::setFlash('success', 'Connecté.');
            Utils::redirect('membre/profile/'.$Membre->member_id);
        } else {
            Session::setFlash('error', 'Mot de passe incorrect.');
            $this->view('login/index', ['data' => $data]);
            return;
        }
    }
}
