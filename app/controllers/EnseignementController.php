<?php
require_once APP_PATH . 'models/Serie.php';
require_once APP_PATH . 'models/SerieSession.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Vues.php';
require_once APP_PATH . 'models/Category.php';
require_once APP_PATH . 'helpers/Logger.php';

class EnseignementController extends Controller 
{    
    private $VuesModel;
    private $EnseignementModel;
    private $SerieModel;
    private $SerieSessionModel;
    private $loggerModel;
    private $CategoryModel;

    public function __construct()
    {
        Auth::requireLogin(['membre','enseignant']);
        
        $this->VuesModel = new Vues();
        $this->EnseignementModel = new Enseignement();
        $this->SerieModel = new Serie();
        $this->SerieSessionModel = new SerieSession();
        $this->loggerModel = new Logger();
        $this->CategoryModel = new Category();
 
    }

    public function index() 
    {
        $enseignantId = $_GET['esgd'] ?? null;
        

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz', 
        ];

        $this->view('enseignement/index', $data);
    }

    public function show($serieId) 
    {
        $isOn = true;
        $cacheKey = 'membre_connexion';
        $userId = Session::get('membre')['member_id'] ?? Session::get('enseignant')['enseignant_id'];
        $dbCategories = $this->CategoryModel->all();
        $BolokeleId = $dbCategories[0]->category_id;
        $MaatId = $dbCategories[1]->category_id;
        $sessionId = $_GET['ssd'] ?? null;

        $Series = $this->SerieModel->findOneWithTeachings($serieId, $MaatId, $sessionId, true);
        $nbrSerieViews = $this->VuesModel->countAll(['serie_id' => $serieId, 'session_id' => $sessionId]);

        if(!$Series) {
            Session::setFlash('error', "Enseignement introuvable.");
            Utils::redirect('/');
        }

        $message = SITE_URL ."/enseignement/show/{$serieId}\n\n" .
                "EmEm Htp Le Shenuti LOBOLA-LO-ILONDO,\n\n" .
                "J'écoute actuellement l'enseignement : *{$Series->nom}*. \n\n" .
                "J'ai une question à ce sujet qui est celle-ci : ... ";

        // Pour l'utiliser dans un lien <a> :
        $urlEncodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/243814126893?text=" . $urlEncodedMessage;

        $data = [
            'Series' => $Series,
            'nbrSerieViews' => $nbrSerieViews,
            'whatsappUrl' => $whatsappUrl,
            'VuesModel' => $this->VuesModel,
            'isOn' => $isOn,
            'sessionId' => $sessionId
        ];

        $this->view('enseignement/show', $data);
    }

    public function add_view($enseignementId)
    {
        $serieId = $_GET['sr'];
        $sessionId = $_GET['ssd'];
        $userId = Session::get('membre')['member_id'] ?? Session::get('enseignant')['enseignant_id'];

        $this->VuesModel->enregistrerVueUnique($enseignementId, $sessionId, $serieId, $userId); 
    }
}
