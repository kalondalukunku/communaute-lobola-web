<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Serie.php';
require_once APP_PATH . 'models/Category.php';
require_once APP_PATH . 'models/Vues.php';
require_once APP_PATH . 'models/Payment.php';
require_once APP_PATH . 'helpers/SendMail.php';
require_once APP_PATH . 'helpers/Logger.php';

class BolokeleController extends Controller 
{    
    private $VuesModel;
    private $SerieModel;
    private $CategoryModel;
    private $EnseignementModel;
    private $loggerModel;
    private $SendMailModel;
    private $MembreModel;
    private $PaymentModel;
    public function __construct()
    {
        Auth::requireLogin(['membre','enseignant']);
        
        $this->VuesModel = new Vues();
        $this->MembreModel = new Membre();
        $this->PaymentModel = new Payment();
        $this->SerieModel = new Serie();
        $this->CategoryModel = new Category();
        $this->EnseignementModel = new Enseignement();
        $this->loggerModel = new Logger();
        $this->SendMailModel = new SendMail();
 
    }

    public function index() 
    {
        $dbCategories = $this->CategoryModel->all();
        $BolokeleId = $dbCategories[1]->category_id;
        $Series = $this->SerieModel->all($BolokeleId);

        $paiedMembre = null;

        if(isset(Session::get('membre')['member_id'])) {
            $paiedMembre = $this->PaymentModel->getPayment(Session::get('membre')['member_id'], Session::get('membre')['engagement_id']);
        }

        $data = [
            'title' => SITE_NAME .' | BOLOKELE',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz', 
            'Series' => $Series,
            'dbCategories' => $dbCategories,
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

        // $this->VuesModel->enregistrerVueUnique($enseignementId, $serieId, $userId);
        $Series = $this->EnseignementModel->findWithSerie($serieId);
        $nbrSerieViews = $this->VuesModel->countAll(['serie_id' => $serieId]);

        if(!$Series) {
            Session::setFlash('error', "Enseignement introuvable.");
            Utils::redirect('/');
        }

        $paiedMembre = null;

        if(isset(Session::get('membre')['member_id'])) {
            $paiedMembre = $this->PaymentModel->getPayment(Session::get('membre')['member_id'], Session::get('membre')['engagement_id']);
        }

        $message = SITE_URL ."/bolokele/show/{$serieId}\n\n" .
                "EmEm Htp,\n\n" .
                "J'écoute actuellement l'enseignement avancé BOLOKELE : *{$Series[0]->nom_serie}*. \n\n" .
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
            'paiedMembre' => $paiedMembre
        ];

        $this->view('bolokele/show', $data);
    }

    public function add_view($enseignementId)
    {
        $serieId = $_GET['sr'];
        $userId = Session::get('membre')['member_id'] ?? Session::get('enseignant')['enseignant_id'];

        $this->VuesModel->enregistrerVueUnique($enseignementId, $serieId, $userId); 
    }
}
