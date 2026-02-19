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

        $isGlobalNew = false;
        $lastItem = $Series[0]; // Récupère le dernier élément du tableau
        reset($Series); // Remet le pointeur du tableau au début pour le foreach

        if ($lastItem && !empty($lastItem->created_at)) {
            $lastDate = new DateTime($lastItem->created_at);
            $now = new DateTime();
            $interval = $now->diff($lastDate);
            $totalHours = ($interval->days * 24) + $interval->h;

            // Si le dernier élément a moins de 24h (ajustez 24 selon vos besoins)
            if ($totalHours < 24 && $interval->invert == 1) {
                $isGlobalNew = true;
            }
        }

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'Series' => $Series,
            'isGlobalNew' => $isGlobalNew,
            'VuesModel' => $this->VuesModel,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}