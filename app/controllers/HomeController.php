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

        /**
         * LOGIQUE DE DISPARITION AUTOMATIQUE
         * Fenêtre active uniquement du 22 au (22 + 15 jours)
         */
        $showRestriction = false;
        $now = new DateTime(); // Date actuelle
        
        // Liste des mois de début (Février, Avril, Juin, Août, Octobre, Décembre)
        $startMonths = [2, 4, 6, 8, 10, 12];
        
        foreach ($startMonths as $m) {
            $currentYear = (int)$now->format('Y');
            
            // On définit le point de départ : le 22 du mois pair à minuit
            $startDate = new DateTime("$currentYear-$m-21 00:00:00");
            
            // On définit le point de sortie : Exactement 15 jours plus tard
            // Note : PHP gère nativement le passage au mois suivant (ex: 22/02 + 15j = 09/03)
            $endDate = clone $startDate;
            $endDate->modify('+15 days');

            // Vérification de l'intervalle
            if ($now >= $startDate && $now <= $endDate) {
                $showRestriction = true;
                $displayEndDate = $endDate->format('d/m'); // Pour rappel interne si besoin
                break;
            }
        }

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'Series' => $Series,
            'showRestriction' => $showRestriction,
            'VuesModel' => $this->VuesModel,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}