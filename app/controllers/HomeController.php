<?php
    require_once APP_PATH . 'models/Engagement.php';
    require_once APP_PATH . 'models/Membre.php';
    require_once APP_PATH . 'models/Enseignement.php';

class HomeController extends Controller {
    
    private $MembreModel;
    private $EngagementModel;
    private $EnseignementModel;

    public function __construct()
    {
        // Auth::requireLogin('user'); // protéger toutes les pages
        // Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->EnseignementModel = new Enseignement();
    }

    public function index()
    {
        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
        ];
        $this->view('home/construction', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}