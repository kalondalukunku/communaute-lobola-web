<?php
require_once APP_PATH . 'models/Rapport.php';
require_once APP_PATH . 'models/Pdf.php';
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Courier.php';
require_once APP_PATH . 'models/Redirection.php';
require_once APP_PATH . 'helpers/Logger.php';

class RapportController extends Controller 
{    
    private $rapportModel;
    private $pdfModel;
    private $userModel;
    private $courierModel;
    private $redirectionModel;
    private $loggerModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->rapportModel = new Rapport();
        $this->pdfModel = new PDF();
        $this->userModel = new User();
        $this->courierModel = new Courier();
        $this->redirectionModel = new Redirection();
        $this->loggerModel = new Logger();
        
        $this->nbrCourierNoTraite = $this->courierModel->nbrCourierTempsEcoule();   
    }

    public function index() 
    {
        $cacheKey = 'user_administraction';
        
        $isFileGlobal = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL);
        $isFileGlobalSuivi = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SUIVI);
        $isFileGlobalEntrant = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_ENTRANT);
        $isFileGlobalSortant = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SORTANT);
        $isFileGlobalDocEnAttente = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_DOC_EN_ATTENTE);
        $isFileGlobalDocClasse = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_DOC_CLASSE);
        $isFileGlobalDocRedirections = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_DOC_REDIRECTIONS);
        $isFileGlobalDocActiviteUser = $this->rapportModel->verifFile(UPLOAD_RAPPORT_FOLDER_PATH_DOC_ACTIVITE_USER);

        $nameFileGlobal = 'rapport_global.pdf';
        $pathFileGlobalPdf = UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL . $nameFileGlobal;
        $pathFileGlobalEnc = $pathFileGlobalPdf .'.enc';

        $nameFileGlobalSuivi = 'rapport_global_-_suivi_des_documents.pdf';
        $pathFileGlobalSuiviPdf = UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SUIVI . $nameFileGlobalSuivi;
        $pathFileGlobalSuiviEnc = $pathFileGlobalSuiviPdf .'.enc';

        $nameFileGlobalEntrant = 'rapport_global_entrants.pdf';
        $pathFileGlobalEntrantPdf = UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_ENTRANT . $nameFileGlobalEntrant;
        $pathFileGlobalEntrantEnc = $pathFileGlobalEntrantPdf .'.enc';

        $nameFileGlobalSortant = 'rapport_global_sortants.pdf';
        $pathFileGlobalSortantPdf = UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SORTANT . $nameFileGlobalSortant;
        $pathFileGlobalSortantEnc = $pathFileGlobalSortantPdf .'.enc';

        $nameFileGlobalDocEnAttente = 'rapport_global_doc_en_attente.pdf';
        $pathFileGlobalDocEnAttentePdf = UPLOAD_RAPPORT_FOLDER_PATH_DOC_EN_ATTENTE . $nameFileGlobalDocEnAttente;
        $pathFileGlobalDocEnAttenteEnc = $pathFileGlobalDocEnAttentePdf .'.enc';

        $nameFileGlobalDocClasse = 'rapport_global_doc_classe.pdf';
        $pathFileGlobalDocClassePdf = UPLOAD_RAPPORT_FOLDER_PATH_DOC_CLASSE . $nameFileGlobalDocClasse;
        $pathFileGlobalDocClasseEnc = $pathFileGlobalDocClassePdf .'.enc';

        $nameFileGlobalDocRedirections = 'rapport_global_doc_redirections.pdf';
        $pathFileGlobalDocRedirectionsPdf = UPLOAD_RAPPORT_FOLDER_PATH_DOC_REDIRECTIONS . $nameFileGlobalDocRedirections;
        $pathFileGlobalDocRedirectionsEnc = $pathFileGlobalDocRedirectionsPdf .'.enc';

        $nameFileGlobalDocActiviteUser = 'rapport_global_doc_activite_user.pdf';
        $pathFileGlobalDocActiviteUserPdf = UPLOAD_RAPPORT_FOLDER_PATH_DOC_ACTIVITE_USER . $nameFileGlobalDocActiviteUser;
        $pathFileGlobalDocActiviteUserEnc = $pathFileGlobalDocActiviteUserPdf .'.enc';

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'isFileGlobal' => $isFileGlobal,
            'isFileGlobalSuivi' => $isFileGlobalSuivi,
            'isFileGlobalEntrant' => $isFileGlobalEntrant,
            'isFileGlobalSortant' => $isFileGlobalSortant,
            'isFileGlobalDocEnAttente' => $isFileGlobalDocEnAttente,
            'isFileGlobalDocClasse' => $isFileGlobalDocClasse,
            'isFileGlobalDocRedirections' => $isFileGlobalDocRedirections,
            'isFileGlobalDocActiviteUser' => $isFileGlobalDocActiviteUser,
        ];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocGlobalAll($this->courierModel,$pathFileGlobalPdf);

            if(!$generateRapport && file_exists($pathFileGlobalPdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalPdf, $pathFileGlobalEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[0],
                        'path' => $pathFileGlobalEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);

                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
            
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalPdf,$pathFileGlobalEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalPdf);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_suivi']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocGlobalSuivi($this->courierModel, $this->redirectionModel, $pathFileGlobalSuiviPdf, '2025');

            if(!$generateRapport && file_exists($pathFileGlobalSuiviPdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalSuiviPdf, $pathFileGlobalSuiviEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[1],
                        'path' => $pathFileGlobalSuiviEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);

                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global de suivi des documents généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global de suivi des documents a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global de suivi des documents.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_suivi_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalSuiviPdf,$pathFileGlobalSuiviEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalSuiviPdf);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_entrant']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocEntrant($this->courierModel,$pathFileGlobalEntrantPdf);

            if(!$generateRapport && file_exists($pathFileGlobalEntrantPdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalEntrantPdf, $pathFileGlobalEntrantEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[1],
                        'path' => $pathFileGlobalEntrantEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);
                    
                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global des documents entrants généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global des documents entrants a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global des documents entrants.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_entrant_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalEntrantPdf,$pathFileGlobalEntrantEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalEntrantPdf);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_sortant']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocSortant($this->courierModel,$pathFileGlobalSortantPdf);

            if(!$generateRapport && file_exists($pathFileGlobalSortantPdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalSortantPdf, $pathFileGlobalSortantEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[1],
                        'path' => $pathFileGlobalSortantEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);

                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global des documents sortants généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global des documents sortants a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                    
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global des documents sortants.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_sortant_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalSortantPdf,$pathFileGlobalSortantEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalSortantPdf);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_doc_en_attente']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocEnAttente($this->rapportModel,$pathFileGlobalDocEnAttentePdf);

            if(!$generateRapport && file_exists($pathFileGlobalDocEnAttentePdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalDocEnAttentePdf, $pathFileGlobalDocEnAttenteEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[1],
                        'path' => $pathFileGlobalDocEnAttenteEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);

                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global des documents en cours de traitement généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global des documents en cours de traitement a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global des documents en cours de traitement.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_doc_en_attente_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalDocEnAttentePdf,$pathFileGlobalDocEnAttenteEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalDocEnAttentePdf);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_doc_classe']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocClasse($this->rapportModel,$pathFileGlobalDocClassePdf);

            if(!$generateRapport && file_exists($pathFileGlobalDocClassePdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalDocClassePdf, $pathFileGlobalDocClasseEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[1],
                        'path' => $pathFileGlobalDocClasseEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);

                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global des documents classés généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global des documents classés a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global des documents classés.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_doc_classe_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalDocClassePdf,$pathFileGlobalDocClasseEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalDocClassePdf);
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_doc_redirections']))
        {
            $submit = array_pop($_POST);

            $generateRapport = $this->pdfModel->GenerateRapportDocRedirections($this->rapportModel,$pathFileGlobalDocRedirectionsPdf);

            if(!$generateRapport && file_exists($pathFileGlobalDocRedirectionsPdf))
            {
                $ress = $this->courierModel->chiffreePdf($pathFileGlobalDocRedirectionsPdf, $pathFileGlobalDocRedirectionsEnc, CLEF_CHIFFRAGE_PDF);

                if($ress === true)
                {
                    $datas = [
                        'type' => ARRAY_TYPE_RAPPORT[1],
                        'path' => $pathFileGlobalDocRedirectionsEnc,
                    ];

                    $result = $this->rapportModel->insert($datas);

                    if($result) 
                    {
                        $dataLogs = [
                            'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                            'action'        => "Rapport global des documents rédirigés généré avec succès.",
                            'courier_id'    => null,
                            'resultat'      => $ress,
                            'date_action'   => date('Y-m-d H:i:s'),
                        ];
                        if ($this->loggerModel->addLog($dataLogs)) 
                        {
                            unlink($pathFileGlobalPdf);
                            Session::setFlash('success', "Rapport global des documents rédirigés a été généré avec succès.");
                            Utils::redirect('rapport');
                        }
                    }
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec lors du génération Rapport global des documents rédirigés.",
                    'courier_id'    => null,
                    'resultat'      => $generateRapport,
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('error', "Une erreur s'est produite, veuillez réessayez plutard.");
                    Utils::redirect('rapport');
                }
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_generated_rapport_global_doc_redirections_download']))
        {
            $res = $this->courierModel->dechiffreePdf($pathFileGlobalDocRedirectionsPdf,$pathFileGlobalDocRedirectionsEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                $this->rapportModel->download_file($pathFileGlobalDocRedirectionsPdf);
            }
        }

        $this->view('rapport/index', $data);
    }
}
