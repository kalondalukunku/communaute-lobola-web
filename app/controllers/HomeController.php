<?php
    require_once APP_PATH . 'models/Payment.php';
    require_once APP_PATH . 'models/Category.php';
    require_once APP_PATH . 'models/Session.php';
    require_once APP_PATH . 'models/Serie.php';
    require_once APP_PATH . 'models/Enseignement.php';
    require_once APP_PATH . 'models/Vues.php';

class HomeController extends Controller {
    
    private $SessionModel;
    private $CategoryModel;
    private $SerieModel;
    private $EnseignementModel;
    private $VuesModel;
    private $PaymentModel;

    public function __construct()
    {        
        $this->SessionModel = new Sessions();
        $this->CategoryModel = new Category();
        $this->SerieModel = new Serie();
        $this->EnseignementModel = new Enseignement();
        $this->VuesModel = new Vues();
        $this->PaymentModel = new Payment();
    }

    public function index()
    {
        Auth::requireLogin(['membre','enseignant']);

        $dbCategories = $this->CategoryModel->all();
        $allSessions = $this->SessionModel->all();
        $MaatId = $dbCategories[1]->category_id;
        $BolokeleId = $dbCategories[0]->category_id;
        $lastSession = end($allSessions);
        $SeriesAlwaysOn = null;

        $inSession = Helper::isTodayInSession($lastSession->date_debut, $lastSession->date_fin); 
        if($inSession) {
            $Series = $this->SerieModel->all($MaatId, $lastSession->session_id, true);
        } else {
            $Series = null;
            $SeriesAlwaysOn = $this->SerieModel->findOneWithTeachings('d3fded1cb2174f52891d0f144497f1b3', $MaatId, $lastSession->session_id, true);
        }
        $isOn = true;

        $showRestriction = false;
        $now = new DateTime(); // Date actuelle
        
        $startMonths = [2, 5, 7, 9, 11];
        
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
            'SeriesAlwaysOn' => $SeriesAlwaysOn,
            'showRestriction' => $showRestriction,
            'VuesModel' => $this->VuesModel,
            'isOn' => $isOn,
            'MaatId' => $MaatId,
            'BolokeleId' => $BolokeleId,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}