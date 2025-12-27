<?php
require_once APP_PATH . 'models/Document.php';
require_once APP_PATH . 'models/Personnel.php';
require_once APP_PATH . 'models/Rapport.php';
require_once APP_PATH . 'models/Pdf.php';
require_once APP_PATH . 'helpers/Logger.php';

class RptController extends Controller
{
    private $DocumentModel;
    private $PersonnelModel;
    private $rapportModel;
    private $pdfModel;
    private $loggerModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        $this->DocumentModel = new Document();
        $this->PersonnelModel = new Personnel();
        $this->rapportModel = new Rapport();
        $this->pdfModel = new PDF();
        $this->loggerModel = new Logger();
    }

    public function index()
    {
        Auth::isRole(ARRAY_ROLE_USER[0]); // SG uniquement

        $allPsn = $this->PersonnelModel->allWhere("statut_emploi", ARRAY_PERSONNEL_STATUT_EMPLOI[1]);
        $allDcs = $this->DocumentModel->allWhere("statut_conformite", "Conforme");
        $salaireTotal = $this->PersonnelModel->sommeSalaire();

        $data = [
            'description'   => 'Gestion des documents',
            'allPsn'        => $allPsn,
            'allDcs'        => $allDcs,
            'salaireTotal'  => $salaireTotal,
        ];

        $this->view('rpt/index', $data);
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
            'Pdf' => $this->pdfModel,
        ];

        $this->view('rpt/shw', $data);
    }
}