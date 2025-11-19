<?php
require_once APP_PATH . 'models/Courier.php';
require_once APP_PATH . 'models/Rapport.php';
require_once APP_PATH . 'models/RapportTransmission.php';
require_once APP_PATH . 'models/Pdf.php';
require_once APP_PATH . 'models/CourierEdit.php';
require_once APP_PATH . 'models/Redirection.php';
require_once APP_PATH . 'helpers/Logger.php';

class CourierController extends Controller
{
    private $courierModel;
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
        $this->courierModel = new Courier();
        $this->rapportModel = new Rapport();
        $this->rapportTransmissionModel = new RapportTransmission();
        $this->pdfModel = new PDF();
        $this->courierEditModel = new CourierEdit();
        $this->redirectionModel = new Redirection();
        $this->loggerModel = new Logger();

        $this->nbrCourierNoTraite = $this->courierModel->nbrCourierTempsEcoule();
    }

    public function index($courierId = null)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);

        if($courierId !== null)
        {
            $courier = $this->courierModel->find($courierId);

            if($courier)
                $this->view('courier/show', ['courier' => $courier]);
            else {
                Session::setFlash('error', 'Aucun courier trouvé');
                Utils::redirect('/');
            }
        } else {
            Utils::redirect('/');
        }

        $data = [
            // 'courier' => $courier,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite
        ];

        $this->view('courier/index', $data);
    }

    public function show($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);
        $allRedirections = $this->redirectionModel->getAllDataWithArgs('courier_id', $courierId, 'created_at', 'ASC');
        $allLoggers = $this->loggerModel->findByCourierId($courierId);

        $courier = $this->courierModel->find($courierId);

        if (!$courier) {
            $this->view('errors/404');
            return;
        }

        $pathDossier = $this->courierModel->cheminDossierPdf('RAPPORT');
        $nomFichier = $this->courierModel->generteNomFichierPdf('RAPPORT');
        $pathRapportFilePdf = BASE_PATH . $pathDossier ."/". $nomFichier;
        $pathRapportFileEnc = $pathRapportFilePdf .'.enc';
        
        $pathMainCourierFilePdf = str_replace('.enc','', BASE_PATH . $courier->fichier_enc);
        $pathMainCourierFileEnc = BASE_PATH . $courier->fichier_enc;

        $pathMainRapportFilePdf = str_replace('.enc','', $courier->rapport_path);
        $pathMainRapportFileEnc = $courier->rapport_path;

        $pathMainRapportJoinFilePdf = str_replace('.enc','', $this->rapportTransmissionModel->find($courierId)->fichier_enc);
        $pathMainRapportJoinFileEnc = $courier->fichier_enc;

        $data = [
            'courier' => $courier,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
            'allRedirections' => $allRedirections,
            'allLoggers' => $allLoggers,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_traiter_courier']))
        {
            if(count($allRedirections) > 0) {
                foreach($allRedirections as $redirect) 
                {
                    if($redirect->status === ARRAY_STATUS[0]) 
                    {
                        Session::setFlash('error', "Vous ne pouvez pas classer ce document si vous n'avez pas encore classer le celui des certains dirigeant.");
                        $this->view('courier/show',  $data);
                        return;
                    }
                }
            }

            $dataTraitementCourier = [
                'courier_id'                => $courierId,
                'date_traitement'           => date('Y-m-d H:m'),
                'status'                    => ARRAY_STATUS[2],
            ];

            $result = $this->courierModel->update($dataTraitementCourier, 'courier_id');

            if($result)
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Traitement Courier de $courier->provenance réussi",
                    'courier_id'    => $courierId,
                    'resultat'      => $result,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs))
                {
                    Session::setFlash('success', "Le courrier a été traité avec succès");
                    Utils::redirect($courierId);
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec traitement Courier de $courier->provenance",
                    'courier_id'    => $courierId,
                    'resultat'      => $result,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec du traitement courrier");
                Utils::redirect($courierId);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_classer_document_sans_suite']))
        {
            
            $dossier_classee = Utils::sanitize(trim($_POST['dossier_classee'] ?? ''));
            $date_classement = Utils::sanitize(trim($_POST['date_classement'] ?? ''));

            if($dossier_classee === '' || $date_classement === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view("courier/show",  $data);
                return;
            }
                
            $reception_date = strtotime($date_classement);
            $now = time();

            if($reception_date > $now)
            {
                Session::setFlash('error', "Date incorrect. Si l'erreur persiste, veuillez verifier votre fuseau horaire.");
                $this->view("courier/show",  $data);
                return;
            }
            
            $date_classement = str_replace('T', ' ', $date_classement);

            if(count($allRedirections) > 0) {
                foreach($allRedirections as $redirect) 
                {
                    if($redirect->status === ARRAY_STATUS[0]) 
                    {
                        $datas = [
                            'id'                => $redirect->id,
                            'status'            => ARRAY_STATUS[1],
                        ];

                        $res = $this->redirectionModel->update($datas, 'id');

                        if(!$res)
                        {
                            Session::setFlash('error', "Une erreur est survenue lors du traitement, veuillez réessayez svp.");
                            $this->view("courier/show",  $data);
                            break;
                        }
                    }
                    
                }
            }
                
            $dataClassifyCourier = [
                'dossier_classee'   => $dossier_classee,
                'date_classement'   => $date_classement,
                'courier_id'        => $courierId,
                'rapport_path'      => $pathRapportFileEnc,
                'status'            => ARRAY_STATUS[3],
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
            
            if ($this->courierModel->update($dataClassifyCourier, 'courier_id')) 
            {
                $courier = $this->courierModel->getCourier('courier_id', $courierId);
                $allRedirect = $this->redirectionModel->getAllDataWithArgs('courier_id', $courierId, 'created_at', 'ASC');

                $res = $this->pdfModel->GenerateRapportDoc($courier, $allRedirect, $date_classement, $pathRapportFilePdf);
                
                if(file_exists($pathRapportFilePdf) && filesize($pathRapportFilePdf) > 0) 
                {
                    $ress = $this->courierModel->chiffreePdf($pathRapportFilePdf, $pathRapportFileEnc, CLEF_CHIFFRAGE_PDF);

                    if($ress === true) 
                    {
                        unlink($pathRapportFilePdf);
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Classement sans suite d'un Courier réussi",
                            'courier_id'    => $courierId,
                            'resultat'      => '1',
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            Session::setFlash('success', "Ce document a bien été classée sans suite au date du ". Helper::formatDate($date_classement) .".");
                            Utils::redirect('../show/'. $courierId);
                        }                        
                    }  
                }

            } else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec du Classement sans suite d'un Courier",
                    'courier_id'    => $courierId,
                    'resultat'      => "0",
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec lors du Classement sans suite d'un courrier.");
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_classer_document']))
        {
            
            $dossier_classee = Utils::sanitize(trim($_POST['dossier_classee'] ?? ''));
            $date_classement = Utils::sanitize(trim($_POST['date_classement'] ?? ''));

            if($dossier_classee === '' || $date_classement === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view("courier/show",  $data);
                return;
            }
                
            $reception_date = strtotime($date_classement);
            $now = time();

            if($reception_date > $now)
            {
                Session::setFlash('error', "Date incorrect. Si l'erreur persiste, veuillez verifier votre fuseau horaire.");
                $this->view("courier/show",  $data);
                return;
            }
            
            $date_classement = str_replace('T', ' ', $date_classement);

            foreach($allRedirections as $redirect) 
            {
                if($redirect->status === ARRAY_STATUS[0]) 
                {
                    Session::setFlash('error', "Vous ne pouvez pas classer ce document si vous n'avez pas encore classer le celui des certains dirigeant.");
                    $this->view("courier/show",  $data);
                    return;
                }
            }
                
            $dataClassifyCourier = [
                'dossier_classee'   => $dossier_classee,
                'date_classement'   => $date_classement,
                'courier_id'        => $courierId,
                'rapport_path'      => $pathRapportFileEnc,
                'status'            => ARRAY_STATUS[4],
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
            
            if ($this->courierModel->update($dataClassifyCourier, 'courier_id')) 
            {
                $courier = $this->courierModel->getCourier('courier_id', $courierId);
                $allRedirect = $this->redirectionModel->getAllDataWithArgs('courier_id', $courierId, 'created_at', 'ASC');

                $res = $this->pdfModel->GenerateRapportDoc($courier, $allRedirect, $date_classement, $pathRapportFilePdf);
                
                if(file_exists($pathRapportFilePdf) && filesize($pathRapportFilePdf) > 0) 
                {
                    $ress = $this->courierModel->chiffreePdf($pathRapportFilePdf, $pathRapportFileEnc, CLEF_CHIFFRAGE_PDF);

                    if($ress === true) 
                    {
                        unlink($pathRapportFilePdf);
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Classement d'un Courier réussi",
                            'courier_id'    => $courierId,
                            'resultat'      => '1',
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            Session::setFlash('success', "Ce document a bien été classée au date du ". Helper::formatDate($date_classement) .".");
                            Utils::redirect('../show/'. $courierId);
                        }                        
                    }  
                }

            } else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec du Classement d'un Courier",
                    'courier_id'    => $courierId,
                    'resultat'      => "0",
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec lors du Classement d'un courrier.");
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_download_file']))
        {
            $res = $this->courierModel->dechiffreePdf($pathMainCourierFilePdf,$pathMainCourierFileEnc, CLEF_CHIFFRAGE_PDF);

            if ($res === true) $this->rapportModel->download_file($pathMainCourierFilePdf);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_download_rapport']))
        {
            $res = $this->courierModel->dechiffreePdf($pathMainRapportFilePdf,$pathMainRapportFileEnc, CLEF_CHIFFRAGE_PDF);

            if ($res === true) $this->rapportModel->download_file($pathMainRapportFilePdf);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_download_rapport_join']))
        {
            $res = $this->courierModel->dechiffreePdf($pathMainRapportJoinFilePdf,$pathMainRapportJoinFileEnc, CLEF_CHIFFRAGE_PDF);

            if ($res === true) $this->rapportModel->download_file($pathMainRapportJoinFilePdf);
        }
    
        foreach($allRedirections as $redirect)
        {
            if(isset($_POST['download_file_transfert_to'. $redirect->id]))
            {
                $pathRedirectPdf = str_replace('.enc','', BASE_PATH . $redirect->fichier_enc);
                $pathRedirectEnc = BASE_PATH . $redirect->fichier_enc;

                $res = $this->courierModel->dechiffreePdf($pathRedirectPdf,$pathRedirectEnc, CLEF_CHIFFRAGE_PDF);

                if($res === true)
                {
                    $this->rapportModel->download_file($pathRedirectPdf);
                }
            }
        }

        $this->view('courier/show', $data);
    }

    public function create()
    {
        Auth::isRole(ARRAY_ROLE_USER[0], ARRAY_ROLE_USER[2]);

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_create_courier'])) 
        {
            $courier_id = uniqid();
            $pathDossier = $this->courierModel->cheminDossierPdf('COURRIER');
            $nomFichier = $this->courierModel->generteNomFichierPdf('COURRIER');
            $fichierPath = $pathDossier ."/". $nomFichier;
            $uploadPath = BASE_PATH . $fichierPath;
            $pathFileEnc = $fichierPath .".enc";

            $category = Utils::sanitize(trim($_POST['category'] ?? ''));
            $type = Utils::sanitize(trim($_POST['type'] ?? ''));

            $dataCreateCourier = [
                'category'     => $category,
                'type'         => $type,
            ];

            if($category === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('courier/create',  $data);
                return;
            }
            
            if(!in_array($category, ARRAY_CATEGORIES))
            {
                Session::setFlash('error', 'Entrée correctement la catégorie de courier.');
                $this->view('courier/create',  $data);
                return;
            }
            if(!in_array($type, ARRAY_TYPE))
            {
                Session::setFlash('error', "Entrée correctement le type de courier.");
                $this->view('courier/create',  $data);
                return;
            }

            if(!is_dir($pathDossier)) {
                if(!mkdir($pathDossier, 0777, true)) 
                {
                    Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                    $this->view('courier/create',  $data);
                    return;
                }
            }

            // verif file
            if (!empty($_FILES['bukus_courier_fichier_enc']['name']))
            {
                $file = $_FILES['bukus_courier_fichier_enc'];
                $allowedTypes = ['application/pdf'];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi de l'image");
                    $this->view('courier/create', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé.");
                    $this->view('courier/create', $data);
                    return;
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $ress = $this->courierModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($ress === true)
                        {
                            unlink($uploadPath);
                            $dataCreateCourier['courier_id']     = $courier_id;
                            $dataCreateCourier['folder_path']    = $pathDossier;
                            $dataCreateCourier['fichier_enc']    = $pathFileEnc;
                            $dataCreateCourier['saved_by']       = $_SESSION[SITE_NAME_SESSION_USER]['nom'];
                        }
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer l'image.");
                    $this->view('courier/create', ['data' => $data]);
                    return;
                }
            }

            if ($ress === true && $this->courierModel->insert($dataCreateCourier)) 
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Ajout d'un Courier étape 1 réussi",
                    'courier_id'    => $courier_id,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Courrier ajouté avec succès. Complètez les informations manquantes !');
                    Utils::redirect('create2/'. $courier_id);
                }

            } else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec d'ajout d'un Courier étape 1",
                    'courier_id'    => $courier_id,
                    'resultat'      => $ress,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec lors de l'ajout d'un courrier étape 1.");
            }
        }
        $this->view('courier/create', $data);
    }

    public function create2($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0], ARRAY_ROLE_USER[2]);

        $courier = $this->courierModel->find($courierId);
        $dbRecepNum = $this->courierModel->getAllDataSelect('reception_num');
        $receptionNumDisabled = '';

        if (!$courier) {
            $this->view('errors/404');
            return;
        }
        if($courier->date_reception !== null) Utils::redirect('../show/'. $courierId);
        
        if($courier->category === ARRAY_CATEGORIES[0]) 
        {
            $lastRecepNum = (int) $this->courierModel->getRecepNum()->reception_num;
            if($lastRecepNum !== null && $lastRecepNum !== false) $reception_num_generated = $lastRecepNum + 1;
            $receptionNumDisabled = 'disabled';
        }
        
        //recuperer tous les emails
        $dbRecepNum = $this->courierModel->getAllDataSelect('reception_num');
        $dbRecepNum = Utils::foreachDataDb($dbRecepNum, 'reception_num');
        //recuperer tous les emails
        $dbRefNum = $this->courierModel->getAllDataSelect('ref_num');
        $dbRefNum = Utils::foreachDataDb($dbRefNum, 'ref_num');

        $data = [
            'courier' => $courier,
            'dbRecepNum' => $dbRecepNum,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_create2_courier'])) 
        {
            foreach ($_POST as $key => $p)
            {
                if($key == 'bukus_create2_courier') unset($_POST[$key]);
                else $data[$key] = $p;
            }

            if(isset($_POST['moratoire'])) $moratoire = Utils::sanitize(trim($_POST['moratoire'] ?? ''));
            if(isset($_POST['destination'])) $destination = Utils::sanitize(trim($_POST['destination'] ?? ''));
            $provenance = Utils::sanitize(trim($_POST['provenance'] ?? ''));
            $objet = Utils::sanitize(trim($_POST['objet'] ?? ''));
            $ref_num = Utils::sanitize(trim($_POST['ref_num'] ?? ''));
            $priority = Utils::sanitize(trim($_POST['priority'] ?? ''));
            $transmission = Utils::sanitize(trim($_POST['transmission'] ?? ''));
            if(isset($_POST['date_depart'])) $date_depart = Utils::sanitize(trim($_POST['date_depart'] ?? ''));
            if(isset($_POST['date_retour'])) $date_retour = Utils::sanitize(trim($_POST['date_retour'] ?? ''));
            $date_reception = Utils::sanitize(trim($_POST['date_reception'] ?? ''));
            
            if(count($dbRecepNum) === 0) {
                $reception_num = Utils::sanitize(trim($_POST['reception_num'] ?? ''));
            }
            else {
                if($courier->category === ARRAY_CATEGORIES[0]) {
                    $reception_num = $reception_num_generated;
                }
                else {
                    $reception_num = Utils::sanitize(trim($_POST['reception_num'] ?? ''));
                }
            }

            if($priority === '' || $provenance === '' || $objet === '' || $ref_num === '' || $date_reception === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('courier/create2',  $data);
                return;
            }
            
            if(!in_array($priority, ARRAY_PRIORITY))
            {
                Session::setFlash('error', 'Entrée correctement la priorité de courier.');
                $this->view('courier/create2',  $data);
                return;
            }

            if($courier->category === 'sortant' && $courier->type === 'interne' && !in_array($transmission, ARRAY_TRANSMISSION))
            {
                Session::setFlash('error', 'Entrée correctement la transmission de courier.');
                $this->view('courier/create2',  $data);
                return;
            }

            $reception_date = strtotime($date_reception);
            if(isset($_POST['date_depart'])) $depart_date = strtotime($date_depart);
            if(isset($_POST['date_retour'])) $retour_date = strtotime($date_retour);
            $now = time();

            if($reception_date > $now)
            {
                Session::setFlash('error', "Date incorrect. Si l'erreur persiste, veuillez verifier votre fuseau horaire.");
                $this->view('courier/create2',  $data);
                return;
            }

            if(isset($_POST['date_depart']) && $depart_date < $now && $transmission === ARRAY_TRANSMISSION[1] 
                || isset($_POST['date_retour']) && $retour_date < $now && $transmission === ARRAY_TRANSMISSION[1])
            {
                Session::setFlash('error', "Date de départ/de retour incorrect. Si l'erreur persiste, veuillez verifier votre fuseau horaire.");
                $this->view('courier/create2',  $data);
                return;
            }
            //formater la variable
            $date_reception = str_replace('T', ' ', $date_reception);
            //recupere la valeur date limite
            isset($moratoire) 
                ? $date_limite = $this->courierModel->addHeures($date_reception, $moratoire) 
                : $date_limite = null;
            //assigner la variable ou a null
            if(isset($_POST['destination'])) $destination = Utils::sanitizeToNull($destination); else $destination = null;
            //assigner la variable ou a null
            $reception_num = Utils::sanitizeToNull($reception_num);
            //assigner la variable ou a null
            if(isset($_POST['moratoire'])) $moratoire = Utils::sanitizeToNull($moratoire); else $moratoire = null;
            //verifier si le numero de reception existe deja
            if(isset($_POST['reception_num']) && $reception_num !== null) 
            { 
                if(in_array($reception_num, $dbRecepNum))
                {
                    Session::setFlash('error', "Ce numéro de réception existe déjà.");
                    $this->view('courier/create2',  $data);
                    return;
                }
            }
            //verifier si le referencement existe deja
            if($courier->category !== ARRAY_CATEGORIES[1])
            { 
                if(in_array($ref_num, $dbRefNum)) 
                {
                    Session::setFlash('error', "Ce numéro de référencement existe déjà.");
                    $this->view('courier/create2',  $data);
                    return;
                } 
            }

            $dataCreateCourier = [
                'courier_id'            => $courierId,
                'provenance'            => $provenance,
                'destination'           => $destination,
                'objet'                 => $objet,
                'priority'              => $priority,
                'transmission'          => $transmission,
                'date_depart'           => $date_depart,
                'date_retour'           => $date_retour,
                'ref_num'               => $ref_num,
                'reception_num'         => $reception_num,
                'date_reception'        => $date_reception,
                'date_limite'           => $date_limite,
                'moratoire'             => $moratoire,
            ];

            if ($this->courierModel->update($dataCreateCourier, 'courier_id')) 
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Ajout d'un Courier étape 2 réussi",
                    'courier_id'    => $courierId,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Utils::redirect('?clear_db=1');
                    Session::setFlash('success', 'Courrier ajouté avec succès. Complètez les informations manquantes !');
                    ($_SESSION[SITE_NAME_SESSION_USER]['role'] === 'couriste') ? Utils::redirect('../../couriste/show/'. $courierId) : Utils::redirect('../show/'. $courierId) ;
                
                    
                }

            } else {
                Session::setFlash('error', "Echec lors de l'ajout d'un courrier étape 1.");
            }
        }
        else {
            foreach ($courier as $key => $c) {
                $data[$key] = $c;
            }
        }
        $this->view('courier/create2', $data);
    }

    public function edit($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);

        $courier = $this->courierModel->find($courierId);
        if (!$courier) {
            $this->view('errors/404');
            return;
        }

        $data = [
            'courier' => $courier,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_edit_courier']))
        {
            foreach ($_POST as $key => $p)
            {
                if($key == 'bukus_edit_courier') unset($_POST[$key]);
                else $data[$key] = $p;
            }

            if (Utils::comparePostWithCourier($_POST, $courier) === true)
            {
                Session::setFlash('success', 'Passé.');
                Utils::redirect('../show/'. $courierId);
            } 

            $moratoire = Utils::sanitize(trim($_POST['moratoire'] ?? ''));
            $destination = Utils::sanitize(trim($_POST['destination'] ?? ''));
            $provenance = Utils::sanitize(trim($_POST['provenance'] ?? ''));
            $objet = Utils::sanitize(trim($_POST['objet'] ?? ''));
            $ref_num = Utils::sanitize(trim($_POST['ref_num'] ?? ''));
            $reception_num = Utils::sanitize(trim($_POST['reception_num'] ?? ''));
            $motif = Utils::sanitize(trim($_POST['motif'] ?? ''));
            
            $dataInsertCourier = [
                'moratoire'     => $moratoire,
                'destination'   => $destination,
                'provenance'    => $provenance,
                'objet'         => $objet,
                'ref_num'       => $ref_num,
                'reception_num' => $reception_num,
                'updated_at'    => date('Y-m-d H:i:s'),
                'courier_id'    => $courierId,
            ];

            $result = $this->courierModel->update($dataInsertCourier, 'courier_id');

            if ($result)
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => 'Modification Courier réussi',
                    'courier_id'    => $courierId,
                    'resultat'      => $result,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);

                $dataInsertCourierEdit = [
                    'courier_id'  => $courierId,
                    'edited_by'   => $_SESSION[SITE_NAME_SESSION_USER]['nom'],
                    'motif'       => $motif
                ];

                $result2 = $this->courierEditModel->insert($dataInsertCourierEdit);

                if ($result2)
                {
                    $dataLogs2 = [
                        'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => 'Modification Courier réussi.',
                        'courier_id'    => $courierId,
                        'resultat'      => $result2,
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    $this->loggerModel->addLog($dataLogs2);
                    Session::setFlash('success', "Le document a été modifié avec succès par ". $_SESSION[SITE_NAME_SESSION_USER]['nom']);
                    Utils::redirect('../show/'. $courierId);
                }
                else {
                    $dataLogs2 = [
                        'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => 'Modification courier echoué.',
                        'courier_id'    => $courierId,
                        'resultat'      => $result2,
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    $this->loggerModel->addLog($dataLogs2);
                    Session::setFlash('error', "Une erreur est survenue lors du modification du document. Réessayez plutard!");
                    Utils::redirect('../show/'. $courierId);
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => 'Modification courier echoué.',
                    'courier_id'    => $courierId,
                    'resultat'      => $result,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Une erreur est survenue lors du modification du document. Réessayez plutard!");
                Utils::redirect('../show/'. $courierId);
            }

            $this->view('courier/edit', $data);
        } 
        else {
            foreach ($courier as $key => $c) {
                $data[$key] = $c;
            }
        }

        $this->view('courier/edit', $data);
    }

    public function join_rapport($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);

        $courier = $this->courierModel->find($courierId);
        
        if (!$courier) {
            $this->view('errors/404');
            return;
        }
        
        if($courier->status === ARRAY_STATUS[3] || $courier->status === ARRAY_STATUS[4]) Utils::redirect('../show/'. $courierId);     
        if($courier->transmission !== ARRAY_TRANSMISSION[1]) Utils::redirect('../show/'. $courierId);
        if($courier->rapport_loin !== "1") Utils::redirect('../show/'. $courierId);
    
        $data = [
            'courier' => $courier,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_courier_joindre_rapport']))
        {            
            $pathDossier = $this->courierModel->cheminDossierPdf('RAPPORT');
            $nomFichier = $this->courierModel->generteNomFichierPdf('RAPPORT-ODS');
            $fichierPath = $pathDossier ."/". $nomFichier;
            $uploadPath = BASE_PATH . $fichierPath;
            $pathFileEnc = $fichierPath .".enc";

            $dataJoinRapportCourier = [
                "courier_id" => $courierId,
                "type" => $courier->transmission,
            ];
            
            // verif file
            if (!empty($_FILES['bukus_courier_fichier_enc']['name']))
            {
                $file = $_FILES['bukus_courier_fichier_enc'];
                $allowedTypes = ['application/pdf'];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi de l'image");
                    $this->view("courier/classify",  $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé.");
                    $this->view("courier/classify",  $data);
                    return;
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $ress = $this->courierModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                        if ($ress === true)
                        {
                            unlink($uploadPath);
                            $dataJoinRapportCourier['fichier_enc']     = $pathFileEnc;
                        }
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer l'image.");
                    $this->view("courier/classify",  $data);
                    return;
                }
            }                

            if (
                $ress === true 
                && $this->rapportTransmissionModel->insert($dataJoinRapportCourier)
                && $this->courierModel->update(['rapport_join' => 1, 'courier_id' => $courierId], 'courier_id')
                ) 
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Enregistrement du rapport d'un Courier réussi",
                    'courier_id'    => $courierId,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Rapport enregistré avec succès.');
                    Utils::redirect('../show/'. $courierId);
                }

            } else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec d'enregistrement du rapport d'un Courier",
                    'courier_id'    => $courierId,
                    'resultat'      => $ress,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec lors d'enregistrement du rapport d'un courrier.");
            }
        }

        $this->view('courier/join_rapport', $data);

    }

    public function send_to($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);

        $courier = $this->courierModel->find($courierId);
        $courierRedirect = $this->redirectionModel->find($courierId);
        if (!$courier) {
            $this->view('errors/404');
            return;
        }
        
        if(isset($_GET['id'])) 
        {
            $courier_send_id = $_GET['id'];
            $courierRedirectID = $this->redirectionModel->findById($courier_send_id);
        } else {
            $courier_send_id = null;
            $courierRedirectID = null;
        }
        if(isset($_GET['id']) && !$courierRedirectID) Utils::redirect('../show/'. $courierId);

        if($courier->status === ARRAY_STATUS[3] || $courier->status === ARRAY_STATUS[4]) Utils::redirect('../show/'. $courierId);     

        $data = [
            'courier' => $courier,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_send_courier_to']))
        {
            foreach ($_POST as $key => $p)
            {
                if($key == 'bukus_send_courier_to') unset($_POST[$key]);
                else $data[$key] = $p;
            }

            $nom_personne_redirigee = Utils::sanitize(trim($_POST['nom_personne_redirigee'] ?? ''));
            $moratoire = Utils::sanitize(trim($_POST['moratoire'] ?? ''));
            $travail_demande = Utils::sanitize(trim($_POST['travail_demande'] ?? ''));
            isset($_GET['id'])
                ? $motif = Utils::sanitize(trim($_POST['motif'] ?? ''))
                : $motif = null;

            if($nom_personne_redirigee === '' || $moratoire === '' || $travail_demande === '' )
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('courier/create2',  $data);
                return;
            }

            $now = Date('Y-m-d H:i');
            $date_limite = $this->courierModel->addHeures($now, $moratoire);
            
            $dataInsertCourier = [
                'nom_personne_redirigee'    => $nom_personne_redirigee,
                'moratoire'                 => $moratoire,
                'travail_demande'           => $travail_demande,
                'date_limite'               => $date_limite,
                'courier_id'                => $courierId,
                'edited_by'                 => $_SESSION[SITE_NAME_SESSION_USER]['nom'],
            ];

            $result = $this->redirectionModel->insert($dataInsertCourier);

            if ($result)
            {
                if(isset($_GET['id']))
                {
                    $datas = [
                        'motif'                 => $motif,
                        'status'                => ARRAY_STATUS[1],
                        'id'                    => $courier_send_id
                    ];
                    $res = $this->redirectionModel->update($datas);

                    if($res)
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Transfert Courier de $courier->provenance chez $nom_personne_redirigee réussi",
                            'courier_id'    => $courierId,
                            'resultat'      => $res,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        $this->loggerModel->addLog($dataLogs);
                        Session::setFlash('success', "Le document a été transferé avec succès chez $nom_personne_redirigee par ". $_SESSION[SITE_NAME_SESSION_USER]['nom']);
                        Utils::redirect('../show/'. $courierId);
                    } 
                    else {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Echec de transfert Courier de $courier->provenance chez $nom_personne_redirigee",
                            'courier_id'    => $courierId,
                            'resultat'      => $res,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        Session::setFlash('error', "Echec lors du transfert du courrier chez $nom_personne_redirigee par ". $_SESSION[SITE_NAME_SESSION_USER]['nom']);
                        Utils::redirect('../show/'. $courierId);
                        $this->loggerModel->addLog($dataLogs);
                    }
                }

                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Envoi Courier de $courier->provenance chez $nom_personne_redirigee réussi",
                    'courier_id'    => $courierId,
                    'resultat'      => $result,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('success', "Le document a été envoyé avec succès chez $nom_personne_redirigee par ". $_SESSION[SITE_NAME_SESSION_USER]['nom']);
                Utils::redirect('../show/'. $courierId);
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec envoi Courier de $courier->provenance chez $nom_personne_redirigee",
                    'courier_id'    => $courierId,
                    'resultat'      => $result,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec lors de l'envoi du courrier chez $nom_personne_redirigee par ". $_SESSION[SITE_NAME_SESSION_USER]['nom']);
                Utils::redirect('../show/'. $courierId);
            }
            $this->view('courier/send_to', $data);
        } 

        $this->view('courier/send_to', $data);
    }

    public function classify($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);

        $courier = $this->courierModel->find($courierId);
        $courierRedirect = $this->redirectionModel->find($courierId);
        if (!$courier) {
            $this->view('errors/404');
            return;
        }
        
        if(isset($_GET['id']))
        {
            $courier_send_id = $_GET['id'];
            $courierRedirectID = $this->redirectionModel->findById($courier_send_id);

            if($courierRedirectID->status === ARRAY_STATUS[2]) Utils::redirect('../show/'. $courierId);
        
            $data = [
                'courier' => $courier,
                'courierRedirect' => $courierRedirectID,
                'courier_send_id' => $courier_send_id,
                'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
                'Couriers' => $this->courierModel,
            ];

            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_pretraiter_courier_send_to']))
            {
                foreach ($_POST as $key => $p)
                {
                    if($key == 'bukus_create2_courier') unset($_POST[$key]);
                    else $data[$key] = $p;
                }
                
                $dossier_classee = Utils::sanitize(trim($_POST['dossier_classee'] ?? ''));
                $date_classement = Utils::sanitize(trim($_POST['date_classement'] ?? ''));
                
                $dataClassifyCourier = [
                    'dossier_classee'   => $dossier_classee,
                    'date_classement'   => $date_classement,
                ];

                if($dossier_classee === '' || $date_classement === '')
                {
                    Session::setFlash('error', 'Remplissez correctement le formulaire.');
                    $this->view("courier/classify",  $data);
                    return;
                }
                    
                $reception_date = strtotime($date_classement);
                $now = time();

                if($reception_date > $now)
                {
                    Session::setFlash('error', "Date incorrect. Si l'erreur persiste, veuillez verifier votre fuseau horaire.");
                    $this->view("courier/classify",  $data);
                    return;
                }
                
                $date_classement = str_replace('T', ' ', $date_classement);
                $pathDossier = $this->courierModel->cheminDossierPdf('COURRIER');
                $nomFichier = $this->courierModel->generteNomFichierPdf('COURRIER');
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";
                
                // verif file
                if (!empty($_FILES['bukus_courier_fichier_enc']['name']))
                {
                    $file = $_FILES['bukus_courier_fichier_enc'];
                    $allowedTypes = ['application/pdf'];
                    // Verif erreur d'upload
                    if ($file['error'] !== UPLOAD_ERR_OK)
                    {
                        Session::setFlash('error', "Erreur lors de l'envoi de l'image");
                        $this->view("courier/classify",  $data);
                        return;
                    }
                    // verif mime reel
                    $mime = mime_content_type($file['tmp_name']);
                    if (!in_array($mime, $allowedTypes))
                    {
                        Session::setFlash('error', "Format du fichier non autorisé.");
                        $this->view("courier/classify",  $data);
                        return;
                    }

                    if (move_uploaded_file($file['tmp_name'], $uploadPath))
                    {
                        if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                        {
                            $ress = $this->courierModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_PDF);

                            if ($ress === true)
                            {
                                unlink($uploadPath);
                                $dataClassifyCourier['dossier_classee'] = $dossier_classee;
                                $dataClassifyCourier['date_classement'] = $date_classement;
                                $dataClassifyCourier['fichier_enc']     = $pathFileEnc;
                                $dataClassifyCourier['status']          = ARRAY_STATUS[2];
                                $dataClassifyCourier['id']              = $courier_send_id;
                                $dataClassifyCourier['edited_by']       = $_SESSION[SITE_NAME_SESSION_USER]['nom'];
                            }
                        }

                    } else {
                        Session::setFlash('error', "Impossible d'enregistrer l'image.");
                        $this->view("courier/classify",  $data);
                        return;
                    }
                }                

                if ($ress === true && $this->redirectionModel->update($dataClassifyCourier)) 
                {
                    $dataLogs = [
                        'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => "Pre-traitement d'un Courier réussi",
                        'courier_id'    => $courierId,
                        'resultat'      => '1',
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    if ($this->loggerModel->addLog($dataLogs)) 
                    {
                        Utils::redirect('?clear_db=1');
                        echo "<script>deletePdfUpload();</script>";
                        Session::setFlash('success', 'Courrier pre-traité avec succès.');
                        Utils::redirect('../show/'. $courierId);
                    }

                } else {
                    $dataLogs = [
                        'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => "Echec du pre-traitement d'un Courier",
                        'courier_id'    => $courierId,
                        'resultat'      => $ress,
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    $this->loggerModel->addLog($dataLogs);
                    Session::setFlash('error', "Echec lors du pre-traitement d'un courrier.");
                }
            }

        } else {
            $courier_send_id = null;
            $courierRedirectID = null;
        }
        if(isset($_GET['id']) && !$courierRedirectID) Utils::redirect('../show/'. $courierId);

        if($courier->status === ARRAY_STATUS[3] || $courier->status === ARRAY_STATUS[4]) Utils::redirect('../show/'. $courierId);     

        $this->view('courier/classify', $data);

    }

    public function details($courierId)
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);

        $courier = $this->courierModel->find($courierId);
        if (!$courier) {
            $this->view('errors/404');
            return;
        }
        
        if(isset($_GET['id'])) 
        {
            $courier_send_id = $_GET['id'];
            $courierRedirectID = $this->redirectionModel->findById($courier_send_id);

            if ($courierRedirectID->status === ARRAY_STATUS[2])
            {
                $debut = new DateTime($courierRedirectID->created_at);
                $fin = new DateTime($courierRedirectID->date_classement);
                $interval = $debut->diff($fin)->format("%hh %imin %ss");
            }
            else {
                $interval = null;
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_download_file_transfert_to'. $courier_send_id]))
            {
                $pathRedirectPdf = str_replace('.enc','', BASE_PATH . $courierRedirectID->fichier_enc);
                $pathRedirectEnc = BASE_PATH . $courierRedirectID->fichier_enc;

                $res = $this->courierModel->dechiffreePdf($pathRedirectPdf,$pathRedirectEnc, CLEF_CHIFFRAGE_PDF);

                if($res === true)
                {
                    $this->rapportModel->download_file($pathRedirectPdf);
                }
            }

        } else {
            $courier_send_id = null;
            $courierRedirectID = null;
            $interval = null;
        }
        if(isset($_GET['id']) && !$courierRedirectID) Utils::redirect('../show/'. $courierId);

        if($courier->status === ARRAY_STATUS[3] || $courier->status === ARRAY_STATUS[4]) Utils::redirect('../show/'. $courierId);     


        $data = [
            'courier' => $courier,
            'courierRedirect' => $courierRedirectID,
            'courier_send_id' => $courier_send_id,
            'interval' => $interval,
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
        ];

        $this->view('courier/details', $data);

    }

    public function view_pdf()
    {
        require BASE_PATH . 'security/ips.php';
    
        $name = basename($_GET['fl']);
        $pathFilePdf = FILE_VIEW_FOLDER_PATH . $name;

        if (!file_exists($pathFilePdf)) {
            header('Location: /supervisor');
            exit;
        }

        $data = [
            'pathFilePdf' => $pathFilePdf,
        ];
        
        $this->view('courier/view_pdf', $data);
    }

    public function notreat()
    {
        Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $allCouriersTempsEcoule = $this->courierModel->getCourierTempsEcoule();
        $numero = 1;

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
            'allCouriersTempsEcoule' => $allCouriersTempsEcoule,
            'numero' => $numero,
        ]; 
        
        $this->view('courier/notreat', $data);
    }
}