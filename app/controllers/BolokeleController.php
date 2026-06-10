<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Serie.php';
require_once APP_PATH . 'models/Session.php';
require_once APP_PATH . 'models/Category.php';
require_once APP_PATH . 'models/Vues.php';
require_once APP_PATH . 'models/Payment.php';
require_once APP_PATH . 'helpers/SendMail.php';
require_once APP_PATH . 'helpers/Logger.php';

class BolokeleController extends Controller 
{    
    private $VuesModel;
    private $SerieModel;
    private $SessionModel;
    private $CategoryModel;
    private $EnseignementModel;
    private $loggerModel;
    private $SendMailModel;
    private $PaymentModel;

    private $dbCategories;
    private $allSessions;

    public function __construct()
    {
        Auth::requireLogin(['membre','enseignant']);
        
        $this->VuesModel = new Vues();
        $this->PaymentModel = new Payment();
        $this->SerieModel = new Serie();
        $this->SessionModel = new Sessions();
        $this->CategoryModel = new Category();
        $this->EnseignementModel = new Enseignement();
        $this->loggerModel = new Logger();
        $this->SendMailModel = new SendMail();

        $this->dbCategories = $this->CategoryModel->all();
        $this->allSessions = $this->SessionModel->all();
 
    }

    public function index() 
    {
        $lastSession = end($this->allSessions);
        $BolokeleId = $this->dbCategories[0]->category_id;
        $Series = $this->SerieModel->all($BolokeleId, $lastSession->session_id, true);

        $paiedMembre = null;

        if(isset(Session::get('membre')['member_id'])) {
            $paiedMembre = $this->PaymentModel->getPayment(Session::get('membre')['member_id'], Session::get('membre')['engagement_id']);
        }
        // var_dump(Session::get('membre')['bolokele']); die;

        $data = [
            'title' => SITE_NAME .' | BOLOKELE',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz', 
            'Series' => $Series,
            'dbCategories' => $this->dbCategories,
            'allSessions' => $this->allSessions,
            'paiedMembre' => $paiedMembre,
            'VuesModel' => $this->VuesModel,
        ];

        $this->view('bolokele/index', $data);
    }

    public function show($serieId) 
    {
        $isOn = true;
        $cacheKey = 'membre_connexion';
        $userId = Session::get('membre')['member_id'] ?? Session::get('enseignant')['enseignant_id'];
        $BolokeleId = $this->dbCategories[0]->category_id;
        $sessionId = $_GET['ssd'] ?? null;

        // $this->VuesModel->enregistrerVueUnique($enseignementId, $serieId, $userId);
        $Series = $this->SerieModel->findOneWithTeachings($serieId, $BolokeleId, $sessionId, true);
        $nbrSerieViews = $this->VuesModel->countAll(['serie_id' => $serieId, 'session_id' => $sessionId]);

        if(!$Series) {
            Session::setFlash('error', "Enseignement introuvable.");
            // Utils::redirect('/');
        }

        $paiedMembre = null;

        if(isset(Session::get('membre')['member_id'])) {
            $paiedMembre = $this->PaymentModel->getPayment(Session::get('membre')['member_id'], Session::get('membre')['engagement_id']);
        }

        $message = SITE_URL ."/bolokele/show/{$serieId}\n\n" .
                "EmEm Htp,\n\n" .
                "J'écoute actuellement l'enseignement avancé BOLOKELE : *{ $Series->nom}*. \n\n" .
                "J'ai une question à ce sujet qui est celle-ci : ... ";

        // Pour l'utiliser dans un lien <a> :
        $urlEncodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/243819889889?text=" . $urlEncodedMessage;

        $data = [
            'Series' => $Series,
            'nbrSerieViews' => $nbrSerieViews,
            'whatsappUrl' => $whatsappUrl,
            'VuesModel' => $this->VuesModel,
            'isOn' => $isOn,
            'sessionId' => $sessionId
        ];

        $this->view('bolokele/show', $data);
    }

    public function add_view($enseignementId)
    {
        $serieId = $_GET['sr'];
        $sessionId = $_GET['ssd'];
        $userId = Session::get('membre')['member_id'] ?? Session::get('enseignant')['enseignant_id'];

        $this->VuesModel->enregistrerVueUnique($enseignementId, $sessionId, $serieId, $userId); 
    }
}
