<?php
    require_once APP_PATH . 'models/Personnel.php';
    require_once APP_PATH . 'models/TypesDocument.php';
    require_once APP_PATH . 'helpers/Logger.php';

class DcsController extends Controller
{
    private $PersonnelModel; 
    private $TypesDocumentModel;
    private $loggerModel;


    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        $this->PersonnelModel = new Personnel();
        $this->TypesDocumentModel = new TypesDocument();
        $this->loggerModel = new Logger();
    }

    public function index()
    {
        Auth::isRole(ARRAY_ROLE_USER[0]); // SG uniquement

        // $allCouriers = $this->DocumentModel->all();

        $data = [
            'title' => SITE_NAME . ' | Documents',
            'description' => 'Gestion des documents',
            // 'allCouriers' => $allCouriers,
            // 'Courier' => $this->DocumentModel,
        ];

        $this->view('dcs/index', $data);
    }

    public function add() 
    {
        $cacheKey = 'user_connexion';

        $data = [
            // 'nbrCouristeUsers' => $nbrCouristeUsers,
            // 'couristeUsersText' => $couristeUsersText,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_add_psn_step_one']))
        {
            $firstName = Utils::sanitize(trim($_POST['firstName'] ?? ''));
            $lastName = Utils::sanitize(trim($_POST['lastName'] ?? ''));
            $dateOfBirth = Utils::sanitize(trim($_POST['dateOfBirth'] ?? ''));
            $maritalStatus = Utils::sanitize(trim($_POST['maritalStatus'] ?? ''));
            $address = Utils::sanitize(trim($_POST['address'] ?? ''));
            $phone = Utils::sanitize(trim($_POST['phone'] ?? ''));
            $personalEmail = Utils::sanitize(trim($_POST['personalEmail'] ?? ''));
            
            if($firstName === '' || $lastName === '' || $dateOfBirth === '' || $maritalStatus === '' || $phone === '' || $address === '' || $personalEmail === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('dcs/edttpdcs',  $data);
                return;
            }
            if(!in_array($maritalStatus, ARRAY_MARIAL_STATUS)) 
            {
                Session::setFlash('error', "Entrée correctement l'état civil du personnel.");
                $this->view('dcs/addtpdcs',  $data);
                return;
            }
            //verifier si l'email existe deja
            // if(in_array($personalEmail, $dbEmails))
            // {
            //     Session::setFlash('error', "Cette adresse mail existe déjà.");
            //     $this->view('dcs/addtpdcs',  $data);
            //     return;
            // }

            $personnelID = Utils::generateUuidV4();

            $dataAddPsnStpOne = [
                'personnel_id'           => $personnelID,
                'nom'               => $firstName,
                'postnom'           => $lastName,
                'date_naissance'    => $dateOfBirth,
                'statut_marital'    => $maritalStatus,
                'adresse'           => $address,
                'telephone'         => $phone,
                'email'             => $personalEmail,
                'created_at'        => date('Y-m-d H:i:s')
            ];

            if($this->PersonnelModel->insert($dataAddPsnStpOne))
            {
                Session::setFlash('success', 'Personnel ajouté avec succès.');
                Utils::redirect('add2/'. $personnelID);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout d'un personnel");
            }
        }

        $this->view('dcs/add', $data);
    }

    public function addtpdcs() 
    {
        $cacheKey = 'user_connexion';

        $data = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_add_tp_dcs']))
        {
            $nom_type = Utils::sanitize(trim($_POST['nom_type'] ?? ''));
            $est_obligatoire = Utils::sanitize(trim($_POST['est_obligatoire'] ?? ''));
            $category = Utils::sanitize(trim($_POST['category'] ?? ''));
            $duree_validite_jours = Utils::sanitize(trim($_POST['duree_validite_jours'] ?? ''));
            $delai_alerte_jours = Utils::sanitize(trim($_POST['delai_alerte_jours'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            
            if($nom_type === '' || $est_obligatoire === '' || $category === '' || $description === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('dcs/addtpdcs',  $data);
                return;
            }
            if(!in_array($est_obligatoire, ARRAY_ISREQUIRED)) 
            {
                Session::setFlash('error', "Choisissez correctement si le document est obligatoire ou pas.");
                $this->view('dcs/addtpdcs',  $data);
                return;
            }
            if(!in_array($category, ARRAY_DOC_CATEGORY)) 
            {
                Session::setFlash('error', "Choisissez correctement la catégorie du document.");
                $this->view('dcs/addtpdcs',  $data);
                return;
            }

            $duree_validite_jours = Utils::sanitizeToNull($duree_validite_jours);
            $delai_alerte_jours = Utils::sanitizeToNull($delai_alerte_jours);

            $type_doc_id = Utils::generateUuidV4();

            $dataAddTpDcs = [
                'type_doc_id'           => $type_doc_id,
                'nom_type'           => $nom_type,
                'est_obligatoire'               => $est_obligatoire,
                'category'               => $category,
                'description'           => $description,
                'duree_validite_jours'    => $duree_validite_jours,
                'delai_alerte_jours'    => $delai_alerte_jours
            ];

            if($this->TypesDocumentModel->insert($dataAddTpDcs))
            {
                Session::setFlash('success', 'Le type de document ajouté avec succès.');
                Utils::redirect('../dcs/shwtpdcs/'. $type_doc_id);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout d'un le type de document");
            }
        }

        $this->view('dcs/addtpdcs', $data);
    }

    public function shwtpdcs($typeDocId) 
    {
        $cacheKey = 'user_connexion';

        $typeDoc = $this->TypesDocumentModel->find($typeDocId);
        if(!$typeDoc) Utils::redirect('../dcs');

        $data = [
            'typeDoc' => $typeDoc,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_add_tp_dcs']))
        {
            $nom_type = Utils::sanitize(trim($_POST['nom_type'] ?? ''));
            $est_obligatoire = Utils::sanitize(trim($_POST['est_obligatoire'] ?? ''));
            $duree_validite_jours = Utils::sanitize(trim($_POST['duree_validite_jours'] ?? 0));
            $delai_alerte_jours = Utils::sanitize(trim($_POST['delai_alerte_jours'] ?? 0));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            
            if($nom_type === '' || $est_obligatoire === '' || $description === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('dcs/addtpdcs',  $data);
                return;
            }
            if(!in_array($est_obligatoire, ARRAY_ISREQUIRED)) 
            {
                Session::setFlash('error', "Choisissez correctement si le document est obligatoire ou pas.");
                $this->view('dcs/addtpdcs',  $data);
                return;
            }

            $type_doc_id = Utils::generateUuidV4();

            $dataAddTpDcs = [
                'type_doc_id'           => $type_doc_id,
                'nom_type'           => $nom_type,
                'est_obligatoire'               => $est_obligatoire,
                'description'           => $description,
                'duree_validite_jours'    => $duree_validite_jours,
                'delai_alerte_jours'    => $delai_alerte_jours
            ];

            if($this->TypesDocumentModel->insert($dataAddTpDcs))
            {
                Session::setFlash('success', 'Personnel ajouté avec succès.');
                Utils::redirect('../dcs/edttpdcs/'. $type_doc_id);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout d'un personnel");
            }
        }

        $this->view('dcs/shwtpdcs', $data);
    }

    public function edttpdcs($typeDocId) 
    {
        $cacheKey = 'user_connexion';

        $typeDoc = $this->TypesDocumentModel->find($typeDocId);
        if(!$typeDoc) Utils::redirect('../dcs');

        $data = [
            'typeDoc' => $typeDoc,
            'type_doc_id'           => $typeDoc->type_doc_id,
            'nom_type'           => $typeDoc->nom_type,
            'category'               => $typeDoc->category,
            'est_obligatoire'               => $typeDoc->est_obligatoire,
            'description'           => $typeDoc->description,
            'duree_validite_jours'    => $typeDoc->duree_validite_jours,
            'delai_alerte_jours'    => $typeDoc->delai_alerte_jours
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_edt_tp_dcs']))
        {
            $nom_type = Utils::sanitize(trim($_POST['nom_type'] ?? ''));
            $category = Utils::sanitize(trim($_POST['category'] ?? ''));
            $est_obligatoire = Utils::sanitize(trim($_POST['est_obligatoire'] ?? ''));
            $duree_validite_jours = (int) Utils::sanitize(trim($_POST['duree_validite_jours'] ?? ''));
            $delai_alerte_jours = (int) Utils::sanitize(trim($_POST['delai_alerte_jours'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));

            // var_dump($_POST); die;
            
            if($nom_type === '' || $est_obligatoire === '' || $category === '' || $description === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('dcs/edttpdcs',  $data);
                return;
            }
            if(!in_array($est_obligatoire, ARRAY_ISREQUIRED)) 
            {
                Session::setFlash('error', "Choisissez correctement si le document est obligatoire ou pas.");
                $this->view('dcs/edttpdcs',  $data);
                return;
            }
            if(!in_array($category, ARRAY_DOC_CATEGORY)) 
            {
                Session::setFlash('error', "Choisissez correctement la catégorie du document.");
                $this->view('dcs/edttpdcs',  $data);
                return;
            }

            $dataEdtTpDcs = [
                'type_doc_id'           => $typeDocId,
                'nom_type'           => $nom_type,
                'category'               => $category,
                'est_obligatoire'               => $est_obligatoire,
                'description'           => $description,
                'duree_validite_jours'    => $duree_validite_jours,
                'delai_alerte_jours'    => $delai_alerte_jours
            ];

            $is_identical = Utils::hasDataChanged($dataEdtTpDcs, $typeDoc);
            if($is_identical)
            {
                if($this->TypesDocumentModel->update($dataEdtTpDcs, 'type_doc_id'))
                {
                    Session::setFlash('success', 'le type de docuemnt modifié avec succès.');
                    Utils::redirect('../shwtpdcs/'. $typeDocId);
                }
                else {
                    Session::setFlash('error', "Echec de la modification d'un le type de docuemnt");
                }
            }
            else {
                Utils::redirect('../shwtpdcs/'. $typeDocId);
            }
        }

        $this->view('dcs/edttpdcs', $data);
    }

    public function tpdcs() 
    {
        $cacheKey = 'user_connexion';

        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $search = ($query !== '') ? basename($query) : null;
        $isrqrdGet = basename($_GET['isrqrd'] ?? '');
        $psnPg = (int) basename($_GET['page'] ?? 1);
        
        if(!isset($_GET['isrqrd'])|| $isrqrdGet === 'all')
            $isrqrd = null;
        elseif($isrqrdGet === 'oui')
            $isrqrd = ARRAY_ISREQUIRED[0];
        elseif($isrqrdGet === 'non')
            $isrqrd = ARRAY_ISREQUIRED[1];

        $results = $this->TypesDocumentModel->allTpDcs($psnPg, $isrqrd, $this->TypesDocumentModel->default_per_page, $search);
        $alltypesdocs = $results['alltypesdocs'];
        $totalrecords = $results['total_records'];
        $currentPage = $results['current_page'];
        $parPage = $results['per_page'];
        $totalPages = $results['total_pages'];

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'alltypesdocs' => $alltypesdocs,
            'totalrecords' => $totalrecords,
            'currentPage' => $currentPage,
            'parPage' => $parPage,
            'totalPages' => $totalPages,
        ];

        $this->view('dcs/tpdcs', $data);
    }

    public function shw($dcsID)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]); // SG uniquement

        // $dcs = $this->DocumentModel->findById($dcsID);
        // if (!$dcs) {
        //     $this->view('errors/404');
        //     return;
        // }

        $data = [
            'title' => SITE_NAME . ' | Détails du document',
            'description' => 'Détails du document',
            // 'dcs' => $dcs,
        ];

        $this->view('dcs/shw', $data);
    }
}