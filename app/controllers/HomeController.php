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
        // Auth::requireLogin('membre') ?? Auth::requireLogin('enseignant');
        // Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->EnseignementModel = new Enseignement();
    }

    public function index()
    {
        Auth::requireLogin(['membre','enseignant']);

        $Enseignements = $this->EnseignementModel->all();

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'Enseignements' => $Enseignements,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}