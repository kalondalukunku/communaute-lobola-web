<?php
    require_once APP_PATH . 'models/Personnel.php';
    require_once APP_PATH . 'models/TypesDocument.php';
    require_once APP_PATH . 'models/Document.php';
    require_once APP_PATH . 'models/HistoriqueDocument.php';
    require_once APP_PATH . 'helpers/Logger.php';

class DcsController extends Controller
{
    private $PersonnelModel; 
    private $TypesDocumentModel;
    private $DocumentModel;
    private $HistoriqueDocumentModel;
    private $loggerModel;


    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        $this->PersonnelModel = new Personnel();
        $this->TypesDocumentModel = new TypesDocument();
        $this->DocumentModel = new Document();
        $this->HistoriqueDocumentModel = new HistoriqueDocument();
        $this->loggerModel = new Logger();
    }

    public function index()
    {
        Auth::isRole(ARRAY_ROLE_USER[0]); // SG uniquement

        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $search = ($query !== '') ? basename($query) : null;
        $isrqrdGet = basename($_GET['isrqrd'] ?? '');
        $psnPg = (int) basename($_GET['page'] ?? 1);

        $results = $this->DocumentModel->allDcs2($psnPg, null, $this->DocumentModel->default_per_page, $search);
        $alldocs = $results['alldocs'];
        $totalrecords = $results['total_records'];
        $currentPage = $results['current_page'];
        $parPage = $results['per_page'];
        $totalPages = $results['total_pages'];

        $data = [
            'title' => SITE_NAME . ' | Documents',
            'description' => 'Gestion des documents',
            'alldocs' => $alldocs,
            'totalrecords' => $totalrecords,
            'currentPage' => $currentPage,
            'parPage' => $parPage,
            'totalPages' => $totalPages,
        ];

        foreach($alldocs as $d)
        {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_vwfl'.$d->id]))
            {
                $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($d->chemin_fichier_stockage));
                $pathFilePdf = FILE_VIEW_FOLDER_PATH ."file.pdf";

                $res = $this->DocumentModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_PDF);

                if($res === true)
                {
                    Utils::redirect('dcs/vwfl?fl=file.pdf');
                    unlink($pathFilePdf);
                }
            }
        }
            

        $this->view('dcs/index', $data);
    }

    public function adddc($personnelID) 
    {
        $cacheKey = 'user_connexion';

        $Personnel = $this->PersonnelModel->getPersonnelDetails('personnel_id', $personnelID);
        if(!$Personnel) Utils::redirect('/');

        if(!$_GET['tpdc']) Utils::redirect('/');
        
        $typeDocId = $_GET['tpdc'];

        $typeDoc = $this->TypesDocumentModel->find($typeDocId);
        if(!$typeDoc) Utils::redirect('../dcs');

        $data = [
            'Personnel' => $Personnel,
            'typeDoc' => $typeDoc,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_doc']))
        {
            if (!empty($_FILES['mosali_doc_psn']['name']))
            {
                $file = $_FILES['mosali_doc_psn'];
                $allowedTypes = ['application/pdf'];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi du document");
                    $this->view('psn/add3', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('psn/add3', $data);
                    return;
                }
            
                $pathDossier = $this->DocumentModel->cheminDossierPdf($typeDoc->nom_type, $personnelID);
                $nomFichier = $this->DocumentModel->generteNomFichierPdf($typeDoc->nom_type);
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";

                $date_telechargement = date('Y-m-d H:i:s');
                
                if($typeDoc->duree_validite_jours === null) $date_expiration = null;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('psn/add3',  $data);
                        return;
                    }
                }

                $docID = Utils::generateUuidV4();
                $dataAddDocPsn = [
                    'doc_id'        => $docID,
                    'personnel_id'  => $personnelID,
                    'matricule'     => $Personnel->matricule,
                ];

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $res = $this->DocumentModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($res === true)
                        {
                            $dataAddDocPsn['type_doc_id']              = $typeDoc->type_doc_id;
                            $dataAddDocPsn['date_telechargement']      = $date_telechargement;
                            $dataAddDocPsn['date_expiration']          = $date_expiration;
                            $dataAddDocPsn['nom_fichier_original']     = $typeDoc->nom_type;
                            $dataAddDocPsn['chemin_fichier_stockage']  = $pathFileEnc;

                            $res = $this->DocumentModel->insert($dataAddDocPsn);
                            if($res)
                            {
                                $dataAddDocPsnDcsHisto = [
                                    'doc_id'        => $docID,
                                    'version_id'     => 1,
                                    'date_action'     => date('Y-m-d H:i:s'),
                                    'type_action'     => ARRAY_TYPE_ACTION_HISTO_DOC[0],
                                    'chemin_fichier_stockage'     => $pathFileEnc,
                                    'user_id'     => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                                ];
                                $ress = $this->HistoriqueDocumentModel->insert($dataAddDocPsnDcsHisto);
                                if($ress) unlink($uploadPath);
                            }
                        }
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('psn/add3', ['data' => $data]);
                    return;
                }
            }

            if($ress)
            {
                Session::setFlash('success', "Le document $typeDoc->nom_type du personnel $Personnel->nom a été ajouté avec succès.");
                Utils::redirect("../../psn/shw/".$personnelID);
            }
        }

        $this->view('dcs/adddc', $data);
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

    public function vwfl()
    {
        require BASE_PATH . 'security/ips.php';
    
        $name = basename($_GET['fl']);
        $pathFilePdf = FILE_VIEW_FOLDER_PATH . $name;
        $urlFile = ASSETS .'uploads/document/'. $name;

        if (!file_exists($pathFilePdf)) {
            Utils::redirect(RETOUR_EN_ARRIERE);
            exit;
        }

        $data = [
            'urlFile' => $urlFile,
            'pathFilePdf' => $pathFilePdf,
        ];

        $this->view('dcs/vwfl', $data);
    }
}