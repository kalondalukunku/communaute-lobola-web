<?php
    require_once APP_PATH . 'models/Personnel.php';
    require_once APP_PATH . 'models/Departement.php';
    require_once APP_PATH . 'models/TypesDocument.php';
    require_once APP_PATH . 'models/Document.php';
    require_once APP_PATH . 'models/HistoriqueDocument.php';
    require_once APP_PATH . 'models/Diplome.php';
    require_once APP_PATH . 'models/Poste.php';
    require_once APP_PATH . 'models/PieceIdentite.php';
    require_once APP_PATH . 'models/Conjoint.php';
    require_once APP_PATH . 'models/Enfant.php';
    require_once APP_PATH . 'helpers/Logger.php';

class PsnController extends Controller 
{    
    private $PersonnelModel;   
    private $DepartementModel;   
    private $TypesDocumentModel;   
    private $DocumentModel;
    private $HistoriqueDocumentModel;
    private $DiplomeModel;
    private $PosteModel;
    private $PieceIdentiteModel;
    private $ConjointModel;
    private $EnfantModel;
    private $loggerModel;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->PersonnelModel = new Personnel();
        $this->DepartementModel = new Departement();
        $this->TypesDocumentModel = new TypesDocument();
        $this->DocumentModel = new Document();
        $this->HistoriqueDocumentModel = new HistoriqueDocument();
        $this->DiplomeModel = new Diplome();
        $this->PosteModel = new Poste();
        $this->PieceIdentiteModel = new PieceIdentite();
        $this->ConjointModel = new Conjoint();
        $this->EnfantModel = new Enfant();
        $this->loggerModel = new Logger();  
    }

    public function index() 
    {
        $cacheKey = 'user_administraction';
        // $allUsers = $this->PersonnelModel->getPersonnelCouristeSuperviseur();

        $data = [
            // 'allUsers' => $allUsers,
        ];

        $this->view('psn/index', $data);
    }

    public function add() 
    {
        $cacheKey = 'user_connexion';
        
        //recuperer tous les emails
        $dbEmails = $this->PersonnelModel->getElement('email');
        foreach ($dbEmails as $dbEmail) 
        {
            $dbEmails[] = $dbEmail->email;
        }
        
        //recuperer tous les emails
        $dbMatricules = $this->PersonnelModel->getElement('matricule');
        foreach ($dbMatricules as $dbMatricule) 
        {
            $dbMatricules[] = $dbMatricule->matricule;
        }

        $data = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_add_psn_step_one']))
        {
            $firstName = Utils::sanitize(trim($_POST['firstName'] ?? ''));
            $lastName = Utils::sanitize(trim($_POST['lastName'] ?? ''));
            $dateOfBirth = Utils::sanitize(trim($_POST['dateOfBirth'] ?? ''));
            $sexe = Utils::sanitize(trim($_POST['sexe'] ?? ''));
            $maritalStatus = Utils::sanitize(trim($_POST['maritalstatus'] ?? ''));
            $address = Utils::sanitize(trim($_POST['address'] ?? ''));
            $phone = Utils::sanitize(trim($_POST['phone'] ?? ''));
            $personalEmail = Utils::sanitize(trim($_POST['personalEmail'] ?? ''));
            
            if($firstName === '' || $lastName === '' || $dateOfBirth === '' || $maritalStatus === '' || $phone === '' || $address === '' || $sexe === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire 2.');
                $this->view('psn/add',  $data);
                return;
            }
            if(!in_array($sexe, ARRAY_SEXE)) 
            {
                Session::setFlash('error', "Entrée correctement le sexe du personnel.");
                $this->view('psn/add',  $data);
                return;
            }
            if(!in_array($maritalStatus, ARRAY_MARIAL_STATUS)) 
            {
                Session::setFlash('error', "Entrée correctement l'état civil du personnel.");
                $this->view('psn/add',  $data);
                return;
            }
            //verifier si l'email existe deja
            if($personalEmail && in_array($personalEmail, $dbEmails))
            {
                Session::setFlash('error', "Cette adresse mail existe déjà.");
                $this->view('psn/add',  $data);
                return;
            }

            if(!Utils::isMajeur($dateOfBirth))
            {
                Session::setFlash('error', "Le personnel ne doit pas avoir moins de 18 ans.");
                $this->view('psn/add',  $data);
                return;
            }

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

        $this->view('psn/add', $data);
    }

    public function add2($personnelID) 
    {
        $cacheKey = 'user_connexion';
        
        $Personnel = $this->PersonnelModel->getPersonnel('personnel_id', $personnelID);
        if(!$Personnel) Utils::redirect('/');
        
        //recuperer tous les emails
        $dbMatricules = $this->PersonnelModel->getElement('matricule');
        foreach ($dbMatricules as $dbMatricule) 
        {
            $dbMatricules[] = $dbMatricule->matricule;
        }

        $data = [
            'Personnel' => $Personnel,
            // 'couristeUsersText' => $couristeUsersText,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_add_psn_step_two']))
        {
            $employeeId = Utils::sanitize(trim($_POST['employeeId'] ?? ''));
            $hireDate = Utils::sanitize(trim($_POST['hireDate'] ?? ''));
            $jobTitle = Utils::sanitize(trim($_POST['jobTitle'] ?? ''));
            $department = Utils::sanitize(trim($_POST['department'] ?? ''));
            $grade = Utils::sanitize(trim($_POST['grade'] ?? ''));
            $salaireBase = Utils::sanitize(trim($_POST['salaire_base'] ?? ''));
            $primeType = Utils::sanitize(trim($_POST['primeType'] ?? ''));
            $nbrChild = Utils::sanitize(trim($_POST['nbr_child'] ?? ''));
            $workEmail = Utils::sanitize(trim($_POST['workEmail'] ?? ''));
            $manager = Utils::sanitize(trim($_POST['manager'] ?? null));
            
            if($employeeId === '' || $hireDate === '' || $jobTitle === '' || $department === '' || $grade === '' || $salaireBase === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('psn/add2',  $data);
                return;
            }
            if(!in_array($department, ARRAY_DEPARTMENTS)) 
            {
                Session::setFlash('error', "Entrée correctement le Service / Direction du personnel.");
                $this->view('psn/add2',  $data);
                return;
            }
            if(!in_array($primeType, ARRAY_PRIMES)) 
            {
                Session::setFlash('error', "Entrée correctement le type prime du personnel.");
                $this->view('psn/add2',  $data);
                return;
            }
            //verifier si l'email existe deja
            if(in_array($employeeId, $dbMatricules))
            {
                Session::setFlash('error', "Ce matricule existe déjà.");
                $this->view('psn/add2', $data);
                return;
            }
            
            $serviceDB = $this->DepartementModel->find($department);
            if(!$serviceDB)
            {
                Session::setFlash('error', "Choisissez correctement le Service / Direction valide.");
                $this->view('psn/add2',  $data);
                return;
            }

            if($manager !== "")
            {
                if(!$this->PersonnelModel->getPersonnel('matricule', $manager))
                {
                    Session::setFlash('error', "Entrée correctement le matricule du Séperieur Hiérarchique de ce personnel.");
                    $this->view('psn/add2',  $data);
                    return;
                }
                
            } else $manager = null;

            $posteActuelId = Utils::generateUuidV4();
            $dataAddPsnStpTwoPoste = [
                'poste_occupe_id'       => $posteActuelId,
                'matricule'             => $employeeId,
                'nom_poste'             => $jobTitle,
                'service_id'            => (int) $serviceDB->service_id,
                'date_debut'            => $hireDate
            ];

            $res = $this->PosteModel->insert($dataAddPsnStpTwoPoste);

            if($res)
            {
                $dataAddPsnStpTwo = [
                    'personnel_id'               => $personnelID,
                    'matricule'             => $employeeId,
                    'date_engagement'           => $hireDate,
                    'poste_actuel'          => $posteActuelId,
                    'service_id'            => $serviceDB->service_id,
                    'salaire_base'          => $salaireBase,
                    'type_prime'          => $primeType,
                    'grade'         => $grade,
                    'nbr_enfant'         => $nbrChild,
                    'email_pro'             => $workEmail,
                    'id_chef_hierarchique'  => $manager
                ];

                if($this->PersonnelModel->update($dataAddPsnStpTwo, 'personnel_id'))
                {
                    Session::setFlash('success', "Les infos profesionnels du personnel $Personnel->nom a été ajouté avec succès.");
                    Utils::redirect("../add3/". $personnelID);
                }
                else {
                    Session::setFlash('error', "Echec de l'ajout des infos profesionnels du personnel $Personnel->nom");
                }
            }
        }

        $this->view('psn/add2', $data);
    }

    public function add3($personnelID) 
    {
        $cacheKey = 'user_connexion';
        
        $Personnel = $this->PersonnelModel->getPersonnel('personnel_id', $personnelID);
        if(!$Personnel) Utils::redirect('/');
        
        //recuperer tous les emails
        $dbMatricules = $this->PersonnelModel->getElement('matricule');
        foreach ($dbMatricules as $dbMatricule) 
        {
            $dbMatricules[] = $dbMatricule->matricule;
        }

        $allTypesDocs = $this->TypesDocumentModel->all();

        $firstDocumentsPsn = $this->DocumentModel->findByTypeDoc($allTypesDocs[0]->type_doc_id, $Personnel->matricule);
        $secondDocumentsPsn = $this->DocumentModel->findByTypeDoc($allTypesDocs[1]->type_doc_id, $Personnel->matricule);
        $thirdDocumentsPsn = $this->DocumentModel->findByTypeDoc($allTypesDocs[2]->type_doc_id, $Personnel->matricule);
        $mariageDocumentsPsn = $this->ConjointModel->findByTypeDoc($allTypesDocs[7]->type_doc_id, $Personnel->personnel_id);

        // var_dump($firstDocumentsPsn);

        $data = [
            'Personnel' => $Personnel,
            'firstDocumentsPsn' => $firstDocumentsPsn,
            'secondDocumentsPsn' => $secondDocumentsPsn,
            'thirdDocumentsPsn' => $thirdDocumentsPsn,
            'mariageDocumentsPsn' => $mariageDocumentsPsn,
        ];

        $successDocEnfantUploaded = 0;
        for ($i=1; $i <= $Personnel->nbr_enfant ; $i++) 
        { 
            $keyArray = "enfantDocumentPsn" . $i;
            $enfantsDocumentsPsn = $this->EnfantModel->findByTypeDoc($allTypesDocs[8]->type_doc_id, $Personnel->personnel_id, $i);
            $data[$keyArray] = $enfantsDocumentsPsn;
            if($enfantsDocumentsPsn) $successDocEnfantUploaded += 1;
        }

        if($firstDocumentsPsn 
            && $secondDocumentsPsn 
            && $thirdDocumentsPsn 
            || $Personnel->nbr_enfant === null 
            && $Personnel->nbr_enfant === $successDocEnfantUploaded) 
        {
            $dataAddPsnStpThreePsn = [
                'statut_emploi' => ARRAY_PERSONNEL_STATUT_EMPLOI[1],
                'personnel_id'       => $personnelID
            ];

            // if($this->PersonnelModel->update($dataAddPsnStpThreePsn, 'personnel_id')) Utils::redirect("../shw/". $personnelID); 
        }
    

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_psn_step_three_doc_1']))
        {  
            $docID = Utils::generateUuidV4();
            $type_document = Utils::sanitize(trim($_POST['type_document'] ?? ''));
            $numero_document = Utils::sanitize(trim($_POST['numero_document'] ?? ''));
            $date_delivrance = Utils::sanitize(trim($_POST['date_delivrance'] ?? ''));
            $date_expiration = Utils::sanitize(trim($_POST['date_expiration'] ?? ''));
            
            if($type_document === '' || $numero_document === '' || $date_delivrance === '' || $date_expiration === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('psn/add3',  $data);
                return;
            }
            if(!in_array($type_document, ARRAY_IDENTITY_PIECES)) 
            {
                Session::setFlash('error', "Choisissez correctement le type de document du personnel $Personnel->nom.");
                $this->view('psn/add3',  $data);
                return;
            }

            $TypeDoc = $this->TypesDocumentModel->findByName($type_document);
            if(!$TypeDoc)
            {
                Session::setFlash('error', "Choisissez correctement le type de document du personnel $Personnel->nom.");
                $this->view('psn/add3',  $data);
                return;
            }

            if($TypeDoc->duree_validite_jours !== null && Utils::isExpired($date_delivrance, $TypeDoc->duree_validite_jours) !== false)
            {
                Session::setFlash('error', "La pièce d'identité du personnel $Personnel->nom est déjà expiré.");
                $this->view('psn/add3',  $data);
                return;
            }

            $dataAddPsnStpThreePieceID = [
                'personnel_id'     => $personnelID,
                'doc_id'        => $docID,
                'type_document'     => $type_document,
                'numero_document'     => $numero_document,
                'date_delivrance'     => $date_delivrance,
                'date_expiration'     => $date_expiration
            ];

            $dataAddPsnStpThree = [
                'doc_id'        => $docID,
                'matricule'     => $Personnel->matricule,
            ];
                // verif file
            if (!empty($_FILES['mosali_doc_identity']['name']))
            {
                $file = $_FILES['mosali_doc_identity'];
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
            
                $pathDossier = $this->DocumentModel->cheminDossierPdf($allTypesDocs[0]->nom_type, $personnelID);
                $nomFichier = $this->DocumentModel->generteNomFichierPdf($allTypesDocs[0]->nom_type);
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";

                $date_telechargement = date('Y-m-d H:i:s');
                
                if($allTypesDocs[0]->duree_validite_jours !== null) 
                    $date_expiration = Utils::ajouterJoursAujourdhui($allTypesDocs[0]->duree_validite_jours);
                else
                    $date_expiration = null;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('psn/add3',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $res = $this->DocumentModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($res === true)
                        {
                            $dataAddPsnStpThree['type_doc_id']              = $allTypesDocs[0]->type_doc_id;
                            $dataAddPsnStpThree['date_telechargement']      = $date_telechargement;
                            $dataAddPsnStpThree['date_expiration']          = $date_expiration;
                            $dataAddPsnStpThree['nom_fichier_original']     = $allTypesDocs[0]->nom_type;
                            $dataAddPsnStpThree['chemin_fichier_stockage']  = $fichierPath;

                            $res = $this->DocumentModel->insert($dataAddPsnStpThree);
                            if($res)
                            {
                                $dataAddPsnStpThreeDcsHisto = [
                                    'doc_id'        => $docID,
                                    'version_id'     => 1,
                                    'date_action'     => date('Y-m-d H:i:s'),
                                    'type_action'     => ARRAY_TYPE_ACTION_HISTO_DOC[0],
                                    'chemin_fichier_stockage'     => $fichierPath,
                                    'user_id'     => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                                ];
                                $ress = $this->HistoriqueDocumentModel->insert($dataAddPsnStpThreeDcsHisto);
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
            else{
                Session::setFlash('error', "Erreur lors de l'envoi du document");
                $this->view('psn/add3', $data);
                return;
            }

            if($ress === true && $this->PieceIdentiteModel->insert($dataAddPsnStpThreePieceID))
            {
                Session::setFlash('success', "La pièce d'identité du personnel $Personnel->nom a été ajouté avec succès.");
                Utils::redirect($personnelID);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout de la pièce d'identité du personnel $Personnel->nom");
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_psn_step_three_doc_2']))
        {       
            $docID = Utils::generateUuidV4();
            $niveau_etude = Utils::sanitize(trim($_POST['niveau_etude'] ?? ''));
            $intitule_diplome = Utils::sanitize(trim($_POST['intitule_diplome'] ?? ''));
            $etablissement = Utils::sanitize(trim($_POST['etablissement'] ?? ''));
            $ville = Utils::sanitize(trim($_POST['ville'] ?? ''));
            $pays = Utils::sanitize(trim($_POST['pays'] ?? ''));
            $annee_obtention = Utils::sanitize(trim($_POST['annee_obtention'] ?? ''));
            
            if($niveau_etude === '' || $intitule_diplome === '' || $etablissement === '' || $ville === '' || $pays === '' || $annee_obtention === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('psn/add3',  $data);
                return;
            }
            if(!in_array($niveau_etude, ARRAY_STUDY_LEVEL)) 
            {
                Session::setFlash('error', "Choisissez correctement le niveau d'étude du personnel $Personnel->nom.");
                $this->view('psn/add3',  $data);
                return;
            }

            $dataAddPsnStpThreeDiplome = [
                'personnel_id'     => $Personnel->personnel_id,
                'doc_id'        => $docID,
                'niveau_etude'     => $niveau_etude,
                'intitule_diplome'     => $intitule_diplome,
                'etablissement'     => $etablissement,
                'ville'     => $ville,
                'pays'     => $pays,
                'annee_obtention'     => $annee_obtention,
            ];

            $dataAddPsnStpThree = [
                'doc_id'        => $docID,
                'matricule'     => $Personnel->matricule,
            ];
                // verif file
            if (!empty($_FILES['mosali_doc_diploma']['name']))
            {
                $file = $_FILES['mosali_doc_diploma'];
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
            
                $pathDossier = $this->DocumentModel->cheminDossierPdf($allTypesDocs[1]->nom_type, $personnelID);
                $nomFichier = $this->DocumentModel->generteNomFichierPdf($allTypesDocs[1]->nom_type);
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";

                $date_telechargement = date('Y-m-d H:i:s');
                
                if($allTypesDocs[1]->duree_validite_jours !== null) 
                    $date_expiration = Utils::ajouterJoursAujourdhui($allTypesDocs[1]->duree_validite_jours);
                else
                    $date_expiration = null;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('psn/add3',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $res = $this->DocumentModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($res === true)
                        {
                            $dataAddPsnStpThree['type_doc_id']              = $allTypesDocs[1]->type_doc_id;
                            $dataAddPsnStpThree['date_telechargement']      = $date_telechargement;
                            $dataAddPsnStpThree['date_expiration']          = $date_expiration;
                            $dataAddPsnStpThree['nom_fichier_original']     = $allTypesDocs[1]->nom_type;
                            $dataAddPsnStpThree['chemin_fichier_stockage']  = $fichierPath;

                            $res = $this->DocumentModel->insert($dataAddPsnStpThree);
                            if($res)
                            {
                                $dataAddPsnStpThreeDcsHisto = [
                                    'doc_id'        => $docID,
                                    'version_id'     => 1,
                                    'date_action'     => date('Y-m-d H:i:s'),
                                    'type_action'     => ARRAY_TYPE_ACTION_HISTO_DOC[0],
                                    'chemin_fichier_stockage'     => $fichierPath,
                                    'user_id'     => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                                ];
                                $ress = $this->HistoriqueDocumentModel->insert($dataAddPsnStpThreeDcsHisto);
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
            else{
                Session::setFlash('error', "Erreur lors de l'envoi du document");
                $this->view('psn/add3', $data);
                return;
            }

            if($ress === true && $this->DiplomeModel->insert($dataAddPsnStpThreeDiplome))
            {
                Session::setFlash('success', "Le diplôme principal du personnel $Personnel->nom a été ajouté avec succès.");
                Utils::redirect($personnelID);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout du diplôme principal du personnel $Personnel->nom");
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_psn_step_three_doc_3']))
        {
            $docID = Utils::generateUuidV4();
            $dataAddPsnStpThree = [
                'doc_id'        => $docID,
                'matricule'     => $Personnel->matricule,
            ];
                // verif file
            if (!empty($_FILES['mosali_doc_contract']['name']))
            {
                $file = $_FILES['mosali_doc_contract'];
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
            
                $pathDossier = $this->DocumentModel->cheminDossierPdf($allTypesDocs[2]->nom_type, $personnelID);
                $nomFichier = $this->DocumentModel->generteNomFichierPdf($allTypesDocs[2]->nom_type);
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";

                $date_telechargement = date('Y-m-d H:i:s');
                
                if($allTypesDocs[2]->duree_validite_jours !== null) 
                    $date_expiration = Utils::ajouterJoursAujourdhui($allTypesDocs[2]->duree_validite_jours);
                else
                    $date_expiration = null;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('psn/add3',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $res = $this->DocumentModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($res === true)
                        {
                            $dataAddPsnStpThree['type_doc_id']              = $allTypesDocs[2]->type_doc_id;
                            $dataAddPsnStpThree['date_telechargement']      = $date_telechargement;
                            $dataAddPsnStpThree['date_expiration']          = $date_expiration;
                            $dataAddPsnStpThree['nom_fichier_original']     = $allTypesDocs[2]->nom_type;
                            $dataAddPsnStpThree['chemin_fichier_stockage']  = $fichierPath;

                            $res = $this->DocumentModel->insert($dataAddPsnStpThree);
                            if($res)
                            {
                                $dataAddPsnStpThreeDcsHisto = [
                                    'doc_id'        => $docID,
                                    'version_id'     => 1,
                                    'date_action'     => date('Y-m-d H:i:s'),
                                    'type_action'     => ARRAY_TYPE_ACTION_HISTO_DOC[0],
                                    'chemin_fichier_stockage'     => $fichierPath,
                                    'user_id'     => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                                ];
                                $ress = $this->HistoriqueDocumentModel->insert($dataAddPsnStpThreeDcsHisto);
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
            else{
                Session::setFlash('error', "Erreur lors de l'envoi du document");
                $this->view('psn/add3', $data);
                return;
            }
                
            if($ress === true)
            {
                Session::setFlash('success', "Le contrat de travail du personnel $Personnel->nom a été ajouté avec succès.");
                Utils::redirect($personnelID);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout de le contrat de travail du personnel $Personnel->nom");
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_psn_step_three_conjoint']))
        {            
            $nom_complet = Utils::sanitize(trim($_POST['nom_complet'] ?? ''));
            $profession = Utils::sanitize(trim($_POST['profession'] ?? ''));
            $date_naissance = Utils::sanitize(trim($_POST['date_naissance'] ?? ''));
            $date_mariage = Utils::sanitize(trim($_POST['date_mariage'] ?? ''));
            
            if($nom_complet === '' || $profession === '' || $date_naissance === '' || $date_mariage === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('psn/add3',  $data);
                return;
            }

            $dataAddPsnStpThreeConjoint = [
                'personnel_id'     => $Personnel->personnel_id,
                'nom_complet'     => $nom_complet,
                'profession'     => $profession,
                'date_naissance'     => $date_naissance,
                'date_mariage'     => $date_mariage,
            ];
            // verif file
            if (!empty($_FILES['mosali_doc_conjoint']['name']))
            {
                $file = $_FILES['mosali_doc_conjoint'];
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
            
                $pathDossier = $this->DocumentModel->cheminDossierPdf($allTypesDocs[7]->nom_type, $personnelID);
                $nomFichier = $this->DocumentModel->generteNomFichierPdf($allTypesDocs[7]->nom_type);
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";

                $date_telechargement = date('Y-m-d H:i:s');
                
                if($allTypesDocs[2]->duree_validite_jours !== null) 
                    $date_expiration = Utils::ajouterJoursAujourdhui($allTypesDocs[7]->duree_validite_jours);
                else
                    $date_expiration = null;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('psn/add3',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $ress = $this->DocumentModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($ress === true)
                        {
                            $dataAddPsnStpThreeConjoint['type_doc_id']              = $allTypesDocs[7]->type_doc_id;
                            $dataAddPsnStpThreeConjoint['date_telechargement']      = $date_telechargement;
                            $dataAddPsnStpThreeConjoint['date_expiration']          = $date_expiration;
                            $dataAddPsnStpThreeConjoint['nom_fichier_original']     = $allTypesDocs[7]->nom_type;
                            $dataAddPsnStpThreeConjoint['chemin_fichier_stockage']  = $fichierPath;
                            unlink($uploadPath);
                        }
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('psn/add3', ['data' => $data]);
                    return;
                }
            }
            else{
                Session::setFlash('error', "Erreur lors de l'envoi du document");
                $this->view('psn/add3', $data);
                return;
            }

            if($ress === true && $this->ConjointModel->insert($dataAddPsnStpThreeConjoint))
            {
                Session::setFlash('success', "L'acte de mariage de $nom_complet, mari(e) du personnel $Personnel->nom a été ajouté avec succès.");
                Utils::redirect($personnelID);
            }
            else {
                Session::setFlash('error', "Echec de l'ajout de l'acte de mariage de $nom_complet, mari(e) du personnel $Personnel->nom");
            }
        }

        for($i = 1; $i <= $Personnel->nbr_enfant; $i++)
        {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_psn_step_three_enfant'. $i]))
            {            
                $nom_complet = Utils::sanitize(trim($_POST['nom_complet'] ?? ''));
                $sexe = Utils::sanitize(trim($_POST['sexe'] ?? ''));
                $scolarise = Utils::sanitize(trim($_POST['scolarise'] ?? ''));
                $date_naissance = Utils::sanitize(trim($_POST['date_naissance'] ?? ''));
                
                if($nom_complet === '' || $scolarise === '' || $date_naissance === '' || $sexe === '')
                {
                    Session::setFlash('error', 'Remplissez correctement le formulaire.');
                    $this->view('psn/add3',  $data);
                    return;
                }
                if(!in_array($sexe, ARRAY_SEXE)) 
                {
                    Session::setFlash('error', "Choisissez correctement le sexe de l'enfant  N° $i du personnel $Personnel->nom.");
                    $this->view('psn/add3',  $data);
                    return;
                }
                if(!in_array($scolarise, ARRAY_SCOLARISE)) 
                {
                    Session::setFlash('error', "Choisissez correctement la scolarité de l'enfant N° $i du personnel $Personnel->nom.");
                    $this->view('psn/add3',  $data);
                    return;
                }

                $date_naissance_y = date('Y', strtotime($date_naissance));
                if((date('Y') - $date_naissance_y) < 3 && $scolarise === ARRAY_SCOLARISE[0])
                {
                    Session::setFlash('error', "A l'age de l'enfant N° $i ne peut pas être scolarisé.");
                    $this->view('psn/add3',  $data);
                    return;
                }

                $dataAddPsnStpThreeEnfant = [
                    'personnel_id'     => $Personnel->personnel_id,
                    'nom_complet_enfant'     => $nom_complet,
                    'sexe'     => $sexe,
                    'ordre_naissance'     => $i,
                    'scolarise'     => $scolarise,
                    'date_naissance'     => $date_naissance,
                ];
                // verif file
                if (!empty($_FILES['mosali_doc_enfant']['name']))
                {
                    $file = $_FILES['mosali_doc_enfant'];
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
                
                    $pathDossier = $this->DocumentModel->cheminDossierPdf(str_replace(" ","_",$allTypesDocs[8]->nom_type), $personnelID);
                    $nomFichier = $this->DocumentModel->generteNomFichierPdf(str_replace(" ","_",$allTypesDocs[8]->nom_type) . "_ENFANT_");
                    $fichierPath = $pathDossier ."/". $nomFichier;
                    $uploadPath = BASE_PATH . $fichierPath;
                    $pathFileEnc = $fichierPath .".enc";

                    $date_telechargement = date('Y-m-d H:i:s');
                    
                    if($allTypesDocs[2]->duree_validite_jours !== null) 
                        $date_expiration = Utils::ajouterJoursAujourdhui($allTypesDocs[8]->duree_validite_jours);
                    else
                        $date_expiration = null;

                    if(!is_dir($pathDossier)) {
                        if(!mkdir($pathDossier, 0777, true)) 
                        {
                            Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                            $this->view('psn/add3',  $data);
                            return;
                        }
                    }

                    if (move_uploaded_file($file['tmp_name'], $uploadPath))
                    {
                        if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                        {
                            $ress = $this->DocumentModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                            if ($ress === true)
                            {
                                $dataAddPsnStpThreeEnfant['type_doc_id']              = $allTypesDocs[8]->type_doc_id;
                                $dataAddPsnStpThreeEnfant['date_telechargement']      = $date_telechargement;
                                $dataAddPsnStpThreeEnfant['date_expiration']          = $date_expiration;
                                $dataAddPsnStpThreeEnfant['nom_fichier_original']     = $allTypesDocs[8]->nom_type;
                                $dataAddPsnStpThreeEnfant['chemin_fichier_stockage']  = $fichierPath;
                                unlink($uploadPath);
                            }
                        }

                    } else {
                        Session::setFlash('error', "Impossible d'enregistrer le document.");
                        $this->view('psn/add3', ['data' => $data]);
                        return;
                    }
                }
                else{
                    Session::setFlash('error', "Erreur lors de l'envoi du document");
                    $this->view('psn/add3', $data);
                    return;
                }

                if($ress === true && $this->EnfantModel->insert($dataAddPsnStpThreeEnfant))
                {
                    Session::setFlash('success', "L'acte de naissance de $nom_complet, l'enfant N° $i du personnel $Personnel->nom a été ajouté avec succès.");
                    Utils::redirect($personnelID);
                }
                else {
                    Session::setFlash('error', "Echec de l'ajout de l'acte de naissance de $nom_complet, l'enfant N° $i du personnel $Personnel->nom");
                }
            }
        }

        $this->view('psn/add3', $data);
    }

    public function edt($personnelID) 
    {
        $cacheKey = 'user_connexion';
        
        $Personnel = $this->PersonnelModel->getPersonnel('personnel_id', $personnelID);
        if(!$Personnel) Utils::redirect('/');

        $departmentDb = $this->DepartementModel->findByid($Personnel->service_id);

        $data = [
            'Personnel' => $Personnel,
            'firstName' => $Personnel->nom,
            'lastName' => $Personnel->postnom,
            'dateOfBirth' => $Personnel->date_naissance,
            'maritalStatus' => $Personnel->statut_marital,
            'address' => $Personnel->adresse,
            'phone' => $Personnel->telephone,
            'employeeId' => $Personnel->matricule,
            'hireDate' => $Personnel->date_naissance,
            'jobTitle' => $Personnel->poste_actuel,
            'department' => $departmentDb->nom_service,
            'primeType' => $Personnel->type_prime,
            'grade' => $Personnel->grade,
            'workEmail' => $Personnel->email_pro,
            // 'manager' => $Personnel->email,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_edt_psn_step_one']))
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
                $this->view('psn/add',  $data);
                return;
            }
            if(!in_array($maritalStatus, ARRAY_MARIAL_STATUS)) 
            {
                Session::setFlash('error', "Entrée correctement l'état civil du personnel.");
                $this->view('psn/add',  $data);
                return;
            }

            // Rassemblement des données soumises dans un tableau pour une boucle facile
            $submitted_data = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'dateOfBirth' => $dateOfBirth,
                'maritalStatus' => $maritalStatus,
                'address' => $address,
                'phone' => $phone,
                'personalEmail' => $personalEmail,
            ];

            $is_identical = Utils::hasPersonnelDataChanged($data, $submitted_data);

            if($is_identical)
            {
                    $dataEdtPsnStpOne = [
                        'personnel_id'           => $personnelID,
                        'nom'               => $firstName,
                        'postnom'           => $lastName,
                        'date_naissance'    => $dateOfBirth,
                        'statut_marital'    => $maritalStatus,
                        'adresse'           => $address,
                        'telephone'         => $phone,
                        'email'             => $personalEmail,
                ];

                if($this->PersonnelModel->update($dataEdtPsnStpOne, 'personnel_id'))
                {
                    Session::setFlash('success', 'Personnel modifé avec succès.');
                    Utils::redirect('../shw/'. $personnelID);
                }
                else {
                    Session::setFlash('error', "Echec de la modification d'un personnel");
                }
            }
            else Utils::redirect('../shw/'. $personnelID);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mutu_add_psn_step_two']))
        {
            $employeeId = Utils::sanitize(trim($_POST['employeeId'] ?? ''));
            $hireDate = Utils::sanitize(trim($_POST['hireDate'] ?? ''));
            $jobTitle = Utils::sanitize(trim($_POST['jobTitle'] ?? ''));
            $department = Utils::sanitize(trim($_POST['department'] ?? ''));
            $primeType = Utils::sanitize(trim($_POST['primeType'] ?? ''));
            $grade = Utils::sanitize(trim($_POST['grade'] ?? ''));
            $workEmail = Utils::sanitize(trim($_POST['workEmail'] ?? ''));
            $manager = Utils::sanitize(trim($_POST['manager'] ?? ''));
            
            if($employeeId === '' || $hireDate === '' || $jobTitle === '' || $department === '' || $grade === '' || $primeType === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('psn/add',  $data);
                return;
            }
            if(!in_array($department, ARRAY_DEPARTMENTS)) 
            {
                Session::setFlash('error', "Entrée correctement le Service / Direction du personnel.");
                $this->view('psn/add',  $data);
                return;
            }
            if(!in_array($primeType, ARRAY_PRIMES)) 
            {
                Session::setFlash('error', "Entrée correctement le type contrat du personnel.");
                $this->view('psn/add',  $data);
                return;
            }
            
            $submitted_data = [
                'employeeId' => $employeeId,
                'hireDate' => $hireDate,
                'jobTitle' => $jobTitle,
                'department' => $department,
                'primeType' => $primeType,
                'grade' => $grade,
                'workEmail' => $workEmail,
            ];

            $is_identical = Utils::hasPersonnelDataChanged($data, $submitted_data);

            if($is_identical)
            {
                $dataEdtPsnStpTwo = [
                    'personnel_id'               => $personnelID,
                    'matricule'             => $employeeId,
                    'date_engagement'           => $hireDate,
                    'poste_actuel'          => $jobTitle,
                    'service_id'            => $departmentDb->service_id,
                    'type_prime'          => $primeType,
                    'grade'         => $grade,
                    'email_pro'             => $workEmail
                    // 'id_chef_hierarchique'  => $ChefServiceID
                ];

                if($this->PersonnelModel->update($dataEdtPsnStpTwo, 'personnel_id'))
                {
                    Session::setFlash('success', "Les infos profesionnels du personnel $Personnel->nom a été ajouté avec succès.");
                    Utils::redirect("../shw/". $personnelID);
                }
                else {
                    Session::setFlash('error', "Echec de l'ajout des infos profesionnels du personnel $Personnel->nom");
                }
            }
            else Utils::redirect($personnelID);
        }

        $this->view('psn/edt', $data);
    }

    public function shw($personnelID) 
    {
        $cacheKey = 'user_connexion';
        
        $Personnel = $this->PersonnelModel->getPersonnelDetails('personnel_id', $personnelID);
        if(!$Personnel) Utils::redirect('/');

        $anciennete = Utils::getAnciennte($Personnel->date_engagement);
        $pieceIdentite = $this->PieceIdentiteModel->find($personnelID)[0];
        $diplomes = $this->DiplomeModel->find($personnelID);

        $data = [
            'Personnel' => $Personnel,
            'pieceIdentite' => $pieceIdentite,
            'diplomes' => $diplomes,
            'anciennete' => $anciennete,
        ];

        $this->view('psn/shw', $data);
    }

    public function edit($personnelID) 
    {
        $cacheKey = 'user_connexion';
        
        $user = $this->PersonnelModel->getPersonnel('personnel_id', $personnelID);

        $data = [
            'user' => $user,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_edit']))
        {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));

            if($nom !== $user->nom || $email !== $user->email) 
            {
                $dataEditUser = [
                    'nom'           => $nom,
                    'email'         => $email,
                    'personnel_id'       => $personnelID,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
                if($this->PersonnelModel->update($dataEditUser, 'personnel_id'))
                {
                    $dataLogs = [
                        'personnel_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => "Modification d'un utilisateur réussi",
                        'courier_id'    => null,
                        'resultat'      => '1',
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    if ($this->loggerModel->addLog($dataLogs)) 
                    {
                        Session::setFlash('success', 'Utilisateur modifié avec succès.');
                        Utils::redirect('../../user');
                    }
                }
                else {
                    $dataLogs = [
                        'personnel_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => "Echec de modification d'un utilisateur",
                        'courier_id'    => null,
                        'resultat'      => '0',
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    $this->loggerModel->addLog($dataLogs);
                    Session::setFlash('error', "Echec de modification d'un utilisateur");
                    Utils::redirect('../../user');
                }
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_delete']))
        {
            if($this->PersonnelModel->delete($personnelID))
            {
                $dataLogs = [
                    'personnel_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Suppression d'un utilisateur réussi",
                    'courier_id'    => null,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Utilisateur supprimé avec succès.');
                    Utils::redirect('../../user');
                }
            } 
            else {
                $dataLogs = [
                    'personnel_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec de Suppression d'un utilisateur",
                    'courier_id'    => null,
                    'resultat'      => '0',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec de Suppression d'un utilisateur");
                Utils::redirect('../../user');
            }
        }

        $this->view('user/edit', $data);
    }

    public function editpswd() 
    {
        $cacheKey = 'user_connexion';
        
        $userId = $_SESSION[SITE_NAME_SESSION_USER]['user_id'];
        $user = $this->PersonnelModel->getPersonnel('personnel_id', $userId);

        if(!$user) Utils::redirect(RETOUR_EN_ARRIERE);
        
        $old_password = $user->pswd;

        $data = [
            'user' => $user,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_pswd_user_edit']))
        {
            $old_pswd = Utils::sanitize(trim($_POST['old_pswd'] ?? ''));
            $new_pswd = Utils::sanitize(trim($_POST['new_pswd'] ?? ''));
            $confirm_pswd = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

            if($old_pswd === '' || $new_pswd === '' || $confirm_pswd === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('user/editpswd',  $data);
                return;
            }
            
            //verifier le password s'il respect la longueur de 8 0 16 ccarateres
            if(
                !Helper::lengthValidation($old_pswd, 8, 16) || 
                !Helper::lengthValidation($new_pswd, 8, 16) || 
                !Helper::lengthValidation($confirm_pswd, 8, 16)
            ) {
                Session::setFlash('error', "Tous les mot de passe doit être compris entre 8 à 16 caratères.");
                $this->view('user/editpswd',  $data);
                return;
            }
            if($new_pswd !== $confirm_pswd)
            {
                Session::setFlash('error', "Les deux nouveaux mot de passe ne se correspondent pas.");
                $this->view('user/editpswd',  $data);
                return;
            }
            if(!password_verify($old_pswd, $old_password)) 
            {
                Session::setFlash('error', "Mot de passe actuel incorrect.");
                $this->view('user/editpswd',  $data);
                return;
            }
            elseif (password_verify($new_pswd, $old_password))
            {
                Session::setFlash('error', "Insérez un nouveau mot de passe.");
                $this->view('user/editpswd',  $data);
                return;
            }

            $pswd = password_hash($new_pswd, PASSWORD_ARGON2I);

            $dataEditUser = [
                    'pswd'          => $pswd,
                    'personnel_id'       => $userId
            ];
            if($this->PersonnelModel->update($dataEditUser, 'personnel_id'))
            {
                $dataLogs = [
                    'personnel_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Modification mot de passe d'un utilisateur réussi",
                    'courier_id'    => null,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Utilisateur modifié avec succès.');
                    Utils::redirect('/');
                }
            }
            else {
                $dataLogs = [
                    'personnel_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec de modification mot de passe d'un utilisateur",
                    'courier_id'    => null,
                    'resultat'      => '0',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec de modification mot de passe d'un utilisateur");
                Utils::redirect('/');
            }
            
        }

        $this->view('user/editpswd', $data);
    }
}
