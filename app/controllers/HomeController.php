<?php
    require_once APP_PATH . 'models/Engagement.php';
    require_once APP_PATH . 'models/Membre.php';
    require_once APP_PATH . 'models/Serie.php';
    require_once APP_PATH . 'models/Enseignement.php';
    require_once APP_PATH . 'models/Vues.php';

class HomeController extends Controller {
    
    private $MembreModel;
    private $EngagementModel;
    private $SerieModel;
    private $EnseignementModel;
    private $VuesModel;

    public function __construct()
    {
        // Auth::requireLogin('membre') ?? Auth::requireLogin('enseignant');
        // Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->SerieModel = new Serie();
        $this->EnseignementModel = new Enseignement();
        $this->VuesModel = new Vues();
    }

    public function index()
    {
        Auth::requireLogin(['membre','enseignant']);

        $Enseignements = $this->EnseignementModel->all();
        $Series = $this->SerieModel->all();

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'Series' => $Series,
            'VuesModel' => $this->VuesModel,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}