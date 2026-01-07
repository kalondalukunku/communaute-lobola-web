<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Engagement.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'helpers/SendMail.php';

class MembreController extends Controller 
{    
    private $MembreModel;  
    private $EngagementModel;
    private $EnseignementModel;
    private $SendMailModel;

    public function __construct()
    {
        // Auth::requireLogin('user');
        // Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->EnseignementModel = new Enseignement();
        $this->SendMailModel = new SendMail();
 
    }

    public function index() 
    {

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
        ];

        $this->view('membre/index', $data);
    }

    public function engagement() 
    {

        $data = [
            'title' => SITE_NAME .' | Engagement',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['c_lobola_engagement']))
        {
            $nom_postnom = Utils::sanitize(trim($_POST['nom_postnom'] ?? ''));
            $sexe = Utils::sanitize(trim($_POST['sexe'] ?? ''));
            $phone_number = Utils::sanitize(trim($_POST['phone_number'] ?? ''));
            $adresse = Utils::sanitize(trim($_POST['adresse'] ?? ''));
            $date_naissance = Utils::sanitize(trim($_POST['date_naissance'] ?? ''));
            $montant = (int) Utils::sanitize(trim($_POST['montant'] ?? ''));
            $modalite_engagement = Utils::sanitize(trim($_POST['modalite_engagement'] ?? ''));
            $devise = Utils::sanitize(trim($_POST['devise'] ?? ''));

            // var_dump($_POST);die;

            if(!$nom_postnom || !$sexe || !$date_naissance || !$montant || !$modalite_engagement || !$devise || !$phone_number || !$adresse)
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('membre/engagement',  $data);
                return;
            }

            if(!in_array($modalite_engagement, ARRAY_TYPE_ENGAGEMENT)) 
            {
                Session::setFlash('error', "Entrée correctement la modalité d'engagement.");
                $this->view('membre/engagement',  $data);
                return;
            }

            if(!in_array($devise, ARRAY_TYPE_DEVISE)) 
            {
                Session::setFlash('error', "Entrée correctement la devise.");
                $this->view('membre/engagement',  $data);
                return;
            }

            if(!in_array($sexe, ARRAY_TYPE_SEXE)) 
            {
                Session::setFlash('error', "Entrée correctement le sexe.");
                $this->view('membre/engagement',  $data);
                return;
            }

            if($devise === ARRAY_TYPE_DEVISE[1] && $montant < 10 || $devise === ARRAY_TYPE_DEVISE[2] && $montant < 10) 
            {
                Session::setFlash('error', "Le montant de l'engagement doit être au minimum de 10$devise.");
                $this->view('membre/engagement',  $data);
                return;
            } 
            elseif ($devise === ARRAY_TYPE_DEVISE[0] && $montant < 22500) {
                Session::setFlash('error', "Le montant de l'engagement doit être au minimum de 22500 CDF.");
                $this->view('membre/engagement',  $data);
                return;
            }

            $date_expiration = Utils::getExpiryDateEngagement();

            $dataAddMembre = [  
                // 'member_id'            => $membreId,
                'nom_postnom'          => $nom_postnom,
                'sexe'                 => $sexe,
                'phone_number'         => $phone_number,
                'adresse'              => $adresse,
                'date_naissance'       => $date_naissance,
            ];
            $dataAddEngagement = [
                // 'engagement_id'        => $engagementId,
                // 'member_id'            => $membreId,
                'montant'              => $montant,
                'modalite_engagement'  => $modalite_engagement,
                // 'status'               => 'en_attente',
                'date_expiration'      => $date_expiration,
                'devise'               => $devise
            ];

            if (!empty($_FILES['engagement_file']['name']))
            {
                $file = $_FILES['engagement_file'];
                $filename = $file['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedTypes = [
                    'application/pdf',
                    'application/x-pdf',
                    'application/acrobat',
                    'applications/vnd.pdf',
                    'text/pdf',
                    'text/x-pdf',    
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/msword'
                ];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi du document");
                    $this->view('membre/engagement', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('membre/engagement', $data);
                    return;
                }

                $membreId = Utils::generateUuidV4();
                $engagementId = Utils::generateUuidV4();
                $pathDossier = $this->EnseignementModel->cheminDossierPdf($membreId, "engagement");
                $nomFichier = $this->EnseignementModel->generteNomFichierPdf($membreId, $ext);
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;
                $pathFileEnc = $fichierPath .".enc";
                $doc_header_type = ARRAY_DOC_HEADER_TYPE[$ext] ?? 'application/octet-stream';

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('membre/engagement',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $resultUpload1 = $this->EnseignementModel->chiffreePdf($uploadPath, $pathFileEnc, CLEF_CHIFFRAGE_FILE);

                        if ($resultUpload1 === true)
                        {
                            $dataAddEngagement['engagement_id']         = $engagementId;
                            $dataAddEngagement['member_id']             = $membreId;
                            $dataAddEngagement['document_path']         = $pathFileEnc;
                            $dataAddEngagement['document_ext']          = $ext;
                            $dataAddEngagement['document_header_type']  = $doc_header_type;
                            unset($uploadPath);
                            $resultUpload1 = true;
                        }
                        else {
                            Session::setFlash('error', "Impossible d'enregistrer le document.");
                            $this->view('membre/engagement', ['data' => $data]);
                            return;
                        }
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('membre/engagement', ['data' => $data]);
                    return;
                }
            }
            
            if (!empty($_FILES['photo_file']['name']))
            {
                $file = $_FILES['photo_file'];
                $filename = $file['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi du document");
                    $this->view('membre/engagement', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('membre/engagement', $data);
                    return;
                }

                $pathDossier = $this->EnseignementModel->cheminDossierPdf($membreId, "avatar");
                $nomFichier = $membreId .'.'. $ext;
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('membre/engagement',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $dataAddMembre['member_id']     = $membreId;
                        $dataAddMembre['path_profile']  = $fichierPath;
                        $resultUpload2 = true;                   
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('membre/engagement', ['data' => $data]);
                    return;
                }
            }

            if($resultUpload1 && $resultUpload2)
            {
                if($this->MembreModel->insert($dataAddMembre) && $this->EngagementModel->insert($dataAddEngagement))
                {
                    Session::setFlash('success', "Engagement enregistré avec succès. Vous serez contacté pour la suite du processus.");
                    Utils::redirect('../membre/attente/'. $membreId);
                }
                else {
                    Session::setFlash('error', "Une erreur est survenue lors de l'enregistrement de votre engagement. Veuillez réessayez plutard.");
                    $this->view('membre/engagement',  $data);
                    return;
                }
            }
        
        }

        $this->view('membre/engagement', $data);
    }
    
    public function attente($membreId) 
    {

        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('/membre/engagement');
            return;
        }

        if($Membre->status === ARRAY_STATUS_MEMBER[2]) {
            Utils::redirect('/');
            return;
        }
        
        $data = [
            'title' => SITE_NAME .' | Engagement',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'membre' => $Membre,
        ];

        $this->view('membre/attente', $data);
    }
}
