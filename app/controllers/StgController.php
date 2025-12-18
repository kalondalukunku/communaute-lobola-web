<?php
require_once APP_PATH .'models/Document.php';
require_once APP_PATH . 'models/Rapport.php';
require_once APP_PATH . 'models/RapportTransmission.php';
require_once APP_PATH . 'models/Pdf.php';
require_once APP_PATH . 'models/CourierEdit.php';
require_once APP_PATH . 'models/Redirection.php';
require_once APP_PATH . 'helpers/Logger.php';

class StgController extends Controller
{
    private $DocumentModel;
    private $rapportModel;
    private $rapportTransmissionModel;
    private $pdfModel;
    private $courierEditModel;
    private $redirectionModel;
    private $loggerModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        $this->DocumentModel = new Document();
        $this->rapportModel = new Rapport();
        $this->rapportTransmissionModel = new RapportTransmission();
        $this->pdfModel = new PDF();
        $this->courierEditModel = new CourierEdit();
        $this->redirectionModel = new Redirection();
        $this->loggerModel = new Logger();
    }

    public function index()
    {
        Auth::isRole(ARRAY_ROLE_USER[0]); // SG uniquement

        // $allCouriers = $this->courierModel->all();
        $numero = 1;

        $data = [
            'title' => SITE_NAME . ' | Documents',
            'description' => 'Gestion des documents',
            // 'allCouriers' => $allCouriers,
            // 'Courier' => $this->courierModel,
        ];

        $this->view('stg/index', $data);
    }

    public function shw($dcsID)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]); // SG uniquement

        // $dcs = $this->courierModel->findById($dcsID);
        // if (!$dcs) {
        //     $this->view('errors/404');
        //     return;
        // }

        $data = [
            'title' => SITE_NAME . ' | Détails du document',
            'description' => 'Détails du document',
            // 'dcs' => $dcs,
            // 'Courier' => $this->courierModel,
            'Rapport' => $this->rapportModel,
            'RapportTransmission' => $this->rapportTransmissionModel,
            'Pdf' => $this->pdfModel,
            'CourierEdit' => $this->courierEditModel,
            'Redirection' => $this->redirectionModel,
        ];

        $this->view('stg/shw', $data);
    }
}