<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Engagement.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Tokens.php';
require_once APP_PATH . 'models/ActionsRaisons.php';
require_once APP_PATH . 'models/Pays.php';
require_once APP_PATH . 'helpers/SendMail.php';

class MembreController extends Controller 
{    
    private $MembreModel;  
    private $EngagementModel;
    private $EnseignementModel;
    private $SendMailModel;
    private $TokensModel;
    private $ActionsRaisonsModel;
    private $PaysModel;

    public function __construct()
    {        
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->TokensModel = new Tokens();
        $this->EnseignementModel = new Enseignement();
        $this->ActionsRaisonsModel = new ActionsRaisons();
        $this->PaysModel = new Pays();
        $this->SendMailModel = new SendMail();
 
    }

    public function index() 
    {
        Utils::redirect('/');
    }

    public function integration() 
    {
        $ip = Utils::getUserIP();
        $Membre = $this->MembreModel->findByWhere('ip_address', $ip);
        if($Membre) 
        {
            $RaisonRejet = $this->ActionsRaisonsModel->find($Membre->member_id, ARRAY_ACTIONS_RAISONS[0]);
            if($RaisonRejet)
            {
               $heureTest = Utils::addHours($RaisonRejet->created_at, 48);
               $now = date('Y-m-d H:i:s');
               if($heureTest > $now) 
                {
                    Utils::redirect('itgtrjt/'. $Membre->member_id);
                }
                else {
                    $this->MembreModel->delete($Membre->member_id);
                }
            }
            else {
                Utils::redirect('attitgt/'. $Membre->member_id);
                return;
            }
        }
        
        $dbEmails = $this->MembreModel->getEmails();
        foreach ($dbEmails as $dbEmail) 
        {
            $dbEmails[] = $dbEmail->email;
        }

        $dbPhoneNumbers = $this->MembreModel->getPhoneNumbers();
        foreach ($dbPhoneNumbers as $dbPhoneNumber) 
        {
            $dbPhoneNumbers[] = $dbPhoneNumber->phone_number;
        }

        $data = [
            'title' => SITE_NAME .' | Intégration',
            'description' => "Demande d'intégration à la communauté Lobola",
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['c_lobola_integration']))
        {
            $nom_postnom = Utils::sanitize(trim($_POST['nom_postnom'] ?? ''));
            $sexe = Utils::sanitize(trim($_POST['sexe'] ?? ''));
            $date_naissance = Utils::sanitize(trim($_POST['date_naissance'] ?? ''));
            $domaine_etude = Utils::sanitize(trim($_POST['domaine_etude'] ?? ''));
            $nationalite = Utils::sanitize(trim($_POST['nationalite'] ?? ''));
            $email = filter_var(Utils::sanitize(trim($_POST['email'] ?? '')), FILTER_SANITIZE_EMAIL);
            $niveau_initiation = Utils::sanitize(trim($_POST['niveau_initiation'] ?? ''));
            $motivation = Utils::sanitize(trim($_POST['motivation'] ?? ''));
            $ville = Utils::sanitize(trim($_POST['ville'] ?? ''));
            $phone = Utils::sanitize(trim($_POST['phone'] ?? ''));
            $adresse = Utils::sanitize(trim($_POST['adresse'] ?? ''));

            if(!$nom_postnom || !$sexe || !$date_naissance || !$domaine_etude || !$nationalite || !$email || !$niveau_initiation || !$motivation || !$ville || !$phone || !$adresse)
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('membre/integration',  $data);
                return;
            }

            if(!in_array($sexe, ARRAY_TYPE_SEXE)) 
            {
                Session::setFlash('error', "Entrée correctement le sexe.");
                $this->view('membre/integration',  $data);
                return;
            }
            if(!in_array($niveau_initiation, ARRAY_TYPE_NIVEAU_INITIATION)) 
            {
                Session::setFlash('error', "Entrée correctement le niveau d'initiation.");
                $this->view('membre/integration',  $data);
                return;
            }

            if($date_naissance >= date('Y-m-d')) 
            {
                Session::setFlash('error', "La date de naissance n'est pas valide.");
                $this->view('membre/integration',  $data);
                return;
            }
            if(in_array($email, $dbEmails))
            {
                Session::setFlash('error', 'Un membre avec cet email existe déjà.');
                $this->view('membre/integration', $data);
                return;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            {
                Session::setFlash('error', "L'adresse email n'est pas valide.");
                $this->view('membre/integration',  $data);
                return;
            }
            if(!str_contains($phone, '+'))
            {
                Session::setFlash('error', "Entrée un numéro de téléphone en commençant par l'indicatif de votre pays. Ex: +243");
                $this->view('membre/integration', $data);
                return;
            }
            if(in_array($phone, $dbPhoneNumbers))
            {
                Session::setFlash('error', 'Un membre avec ce numéro de téléphone existe déjà.');
                $this->view('membre/integration', $data);
                return;
            }

            $dataAddMembre = [  
                'ip_address'           => $ip,
                'nom_postnom'          => $nom_postnom,
                'genre'                => $sexe,
                'date_naissance'       => $date_naissance,
                'domaine_etude'        => $domaine_etude,
                'nationalite'          => $nationalite,
                'email'                => $email,
                'niveau_initiation'    => $niveau_initiation,
                'motivation'           => $motivation,
                'ville'                => $ville,
                'phone_number'         => $phone,
                'adresse'              => $adresse,
                'status'               => ARRAY_STATUS_MEMBER[1],
            ];
            
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
                    $this->view('membre/integration', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('membre/integration', $data);
                    return;
                }

                $membreId = Utils::generateUuidV4();
                $pathDossier = $this->EnseignementModel->cheminDossierPdf($membreId, "avatar");
                $nomFichier = $membreId .'.'. $ext;
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('membre/integration',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $dataAddMembre['member_id']     = $membreId;
                        $dataAddMembre['path_profile']  = $fichierPath;
                        $resultUpload = true;                   
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('membre/integration', ['data' => $data]);
                    return;
                }
            }

            if($resultUpload)
            {
                if($this->MembreModel->insert($dataAddMembre))
                {
                    Session::setFlash('success', "Votre demande d'intégration a été enregistrée avec succès.");
                    Utils::redirect('attitgt/'. $membreId);
                }
                else {
                    Session::setFlash('error', "Une erreur est survenue lors de l'enregistrement de votre engagement. Veuillez réessayez plutard.");
                    $this->view('membre/integration',  $data);
                    return;
                }
            }
        
        }

        $this->view('membre/integration', $data);
    }

    public function attitgt($membreId)
    {   
        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[2]) {
            Utils::redirect('/');
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[4]) {
            Utils::redirect('../itgtrjt/'. $membreId);
            return;
        }
        if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2]) {
            Utils::redirect('../rjtd/'. $membreId);
            return;
        }
        $data = [
            'title' => SITE_NAME .' | Attente d\'approbation',
            'description' => 'Attente d\'approbation de votre intégration',
            'membre' => $Membre,
        ];

        $this->view('membre/attitgt', $data);
    }

    public function itgtrjt($membreId)
    {   
        $Membre = $this->MembreModel->findByMemberId($membreId);
        $RaisonRejet = $this->ActionsRaisonsModel->find($membreId, ARRAY_ACTIONS_RAISONS[0]);

        $heureTest = Utils::addHours($RaisonRejet->created_at, 48);
        $now = date('Y-m-d H:i:s');
        if($now > $heureTest) 
        {
            if($this->MembreModel->delete($Membre->member_id)) Utils::redirect('../integration');
        }
        if(!$Membre && !$RaisonRejet) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[1]) {
            Utils::redirect('../attitgt/'. $membreId);
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[2]) {
            Utils::redirect('/');
            return;
        }
        $data = [
            'title' => SITE_NAME .' | Intégration rejétée',
            'description' => 'Demande de votre intégration rejétée',
            'membre' => $Membre,
            'RaisonRejet' => $RaisonRejet,
        ];

        $this->view('membre/itgtrjt', $data);
    }

    public function rjtdmdf($membreId)
    {
        $Membre = $this->MembreModel->findByMemberId($membreId);
        $RaisonRejet = $this->ActionsRaisonsModel->find($membreId, ARRAY_ACTIONS_RAISONS[0]);

        $heureTest = Utils::addHours($RaisonRejet->created_at, 48);
        $now = date('Y-m-d H:i:s');
        if($now > $heureTest) 
        {
            if($this->MembreModel->delete($Membre->member_id)) Utils::redirect('../integration');
        }
        if(!$Membre && !$RaisonRejet) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[1]) {
            Utils::redirect('../attitgt/'. $membreId);
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[2]) {
            Utils::redirect('/');
            return;
        }
        $data = [
            'title' => SITE_NAME .' | Modification des informations incorrectes',
            'description' => 'Votre demande d\'engagement a été rejétée',
            'membre' => $Membre,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_rjtdmdf']))
        {
            $nom_postnom = Utils::sanitize(trim($_POST['nom_postnom'] ?? ''));
            $sexe = Utils::sanitize(trim($_POST['sexe'] ?? ''));
            $date_naissance = Utils::sanitize(trim($_POST['date_naissance'] ?? ''));
            $domaine_etude = Utils::sanitize(trim($_POST['domaine_etude'] ?? ''));
            $nationalite = Utils::sanitize(trim($_POST['nationalite'] ?? ''));
            $email = filter_var(Utils::sanitize(trim($_POST['email'] ?? '')), FILTER_SANITIZE_EMAIL);
            $niveau_initiation = Utils::sanitize(trim($_POST['niveau_initiation'] ?? ''));
            $motivation = Utils::sanitize(trim($_POST['motivation'] ?? ''));
            $ville = Utils::sanitize(trim($_POST['ville'] ?? ''));
            $phone = Utils::sanitize(trim($_POST['phone'] ?? ''));
            $adresse = Utils::sanitize(trim($_POST['adresse'] ?? ''));

            if(!$nom_postnom || !$sexe || !$date_naissance || !$domaine_etude || !$nationalite || !$email || !$niveau_initiation || !$motivation || !$ville || !$phone || !$adresse)
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('membre/integration',  $data);
                return;
            }

            if(!in_array($sexe, ARRAY_TYPE_SEXE)) 
            {
                Session::setFlash('error', "Entrée correctement le sexe.");
                $this->view('membre/integration',  $data);
                return;
            }
            if(!in_array($niveau_initiation, ARRAY_TYPE_NIVEAU_INITIATION)) 
            {
                Session::setFlash('error', "Entrée correctement le niveau d'initiation.");
                $this->view('membre/integration',  $data);
                return;
            }

            if($date_naissance >= date('Y-m-d')) 
            {
                Session::setFlash('error', "La date de naissance n'est pas valide.");
                $this->view('membre/integration',  $data);
                return;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            {
                Session::setFlash('error', "L'adresse email n'est pas valide.");
                $this->view('membre/integration',  $data);
                return;
            }
            if(!str_contains($phone, '+'))
            {
                Session::setFlash('error', "Entrée un numéro de téléphone en commençant par l'indicatif de votre pays. Ex: +243");
                $this->view('membre/integration', $data);
                return;
            }

            $submitted_data = [  
                'nom_postnom'          => $nom_postnom,
                'genre'                => $sexe,
                'date_naissance'       => $date_naissance,
                'domaine_etude'        => $domaine_etude,
                'nationalite'          => $nationalite,
                'email'                => $email,
                'niveau_initiation'    => $niveau_initiation,
                'motivation'           => $motivation,
                'ville'                => $ville,
                'phone_number'         => $phone,
                'adresse'              => $adresse,
                'status'               => ARRAY_STATUS_MEMBER[1],
                'member_id'            => $membreId,
            ];
            $dataUpdateAction = [
                'status' => ARRAY_ACTIONS_RAISONS_STATUS[0], 
                'action_id' => $RaisonRejet->action_id
            ];
            
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
                    $this->view('membre/integration', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('membre/integration', $data);
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
                        $this->view('membre/integration',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $this->MembreModel->update([
                            'image_profile' => $nomFichier,
                            'member_id' => $membreId
                        ], 'member_id');        
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('membre/integration', ['data' => $data]);
                    return;
                }
            }

            if($this->MembreModel->update($submitted_data, 'member_id') && $this->ActionsRaisonsModel->update($dataUpdateAction))
            {
                Session::setFlash('success', "Votre demande de réintégration a été envoyée avec succès.");
                Utils::redirect('attitgt/'. $membreId);
            }
            else {
                Session::setFlash('error', "Une erreur est survenue lors de l'enregistrement de votre engagement. Veuillez réessayez plutard.");
                $this->view('membre/integration',  $data);
                return;
            }
            
        
        }

        $this->view('membre/rjtdmdf', $data);
    }

    public function engagement($membreId) 
    {
        Auth::requireLogin('membre');

        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2]) {
            Utils::redirect('../rjtd/'. $membreId);
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[1]) {
            Utils::redirect('../attitgt/'. $membreId);
            return;
        }
        if($Membre->engagement_id && $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1]) {
            Utils::redirect('../attente/'. $membreId);
            return;
        }
        if($Membre->niveau_initiation !== ARRAY_TYPE_NIVEAU_INITIATION[2]) {
            Utils::redirect('../profile/'. $membreId);
            return;
        }

        $data = [
            'title' => SITE_NAME .' | Engagement',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['c_lobola_engagement']))
        {
            $montant = (int) Utils::sanitize(trim($_POST['montant'] ?? ''));
            $modalite_engagement = Utils::sanitize(trim($_POST['modalite_engagement'] ?? ''));
            $devise = Utils::sanitize(trim($_POST['devise'] ?? ''));

            if(!$montant || !$modalite_engagement || !$devise)
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

            $dataUpdateMembre = [
                'status' => ARRAY_STATUS_MEMBER[0], // attente_integration
                'member_id' => $membreId
            ];

            $dataAddEngagement = [
                'montant'              => $montant,
                'modalite_engagement'  => $modalite_engagement,
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

            if($resultUpload1 && $this->MembreModel->update($dataUpdateMembre, 'member_id'))
            {
                if($this->EngagementModel->insert($dataAddEngagement))
                {
                    Session::setFlash('success', "Engagement enregistré avec succès. Vous serez contacté pour la suite du processus.");
                    Utils::redirect('attente/'. $membreId);
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
        Auth::requireLogin('membre');

        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->status === ARRAY_STATUS_MEMBER[1]) {
            Utils::redirect('../attitgt/'. $membreId);
            return;
        }
        if(!$Membre->engagement_id && $Membre->status !== ARRAY_STATUS_MEMBER[0]) {
            Utils::redirect('../engagement/'. $membreId);
            return;
        }
        if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2])
        {
            Utils::redirect('../rjtd/'. $membreId);
            return;
        }
        if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1])
        {
            Utils::redirect('../profile/'. $membreId);
            return;
        }
        
        $data = [
            'title' => SITE_NAME .' | En attente d\'engagement',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'membre' => $Membre,
        ];

        $this->view('membre/attente', $data);
    }

    public function rjtd($membreId) 
    {

        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('/membre/integration');
            return;
        }

        if($Membre->status === ARRAY_STATUS_MEMBER[2]) {
            Utils::redirect('/');
            return;
        }

        if($Membre->statut_engagement !== ARRAY_STATUS_ENGAGEMENT[2])
        {
            Utils::redirect('../attente/'. $membreId);
            return;
        }
        
        $data = [
            'title' => SITE_NAME .' | Engagement Rejeté',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'Membre' => $Membre,
        ];

        $this->view('membre/rjtd', $data);
    }

    public function updt_pswd($membreId) 
    {
        $Membre = $this->MembreModel->findByMemberId($membreId);
        
        if(!$Membre) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->status !== ARRAY_STATUS_MEMBER[5]) {
            Utils::redirect('../integration');
            return;
        }
        $data = [
            'title' => SITE_NAME .' | Mise à jour du mot de passe',
            'description' => 'Mise à jour du mot de passe',
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_updt_pswd']))
        {
            $pswd = Utils::sanitize(trim($_POST['pswd'] ?? ''));
            $confirm_pswd = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

            if(!$pswd || !$confirm_pswd)
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('membre/updt_pswd',  $data);
                return;
            }
            
            if(strlen($pswd) < 8 || strlen($confirm_pswd) < 8)
            {
                Session::setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                $this->view('membre/updt_pswd',  $data);
                return;
            }

            if($pswd !== $confirm_pswd)
            {
                Session::setFlash('error', 'Les mots de passe ne correspondent pas.');
                $this->view('membre/updt_pswd',  $data);
                return;
            }

            $hashedPassword = password_hash($pswd, PASSWORD_ARGON2I);

            $dataUpdateMembre = [
                'pswd' => $hashedPassword,
                'status' => ARRAY_STATUS_MEMBER[2], // active
                'member_id' => $membreId
            ];

            if($this->MembreModel->update($dataUpdateMembre, 'member_id'))
            {
                $MembreLog = $this->MembreModel->findByMemberId($membreId);
                Session::set('membre', $MembreLog);
                Session::setFlash('success', 'Mot de passe mis à jour avec succès.');
                Utils::redirect('../profile/'. $membreId);
            }
            else {
                Session::setFlash('error', "Une erreur est survenue lors de la mise à jour du mot de passe. Veuillez réessayez plutard.");
                $this->view('membre/updt_pswd',  $data);
                return;
            }
        
        }

        $this->view('membre/updt_pswd', $data);
    }

    public function profile($membreId)
    {
        Auth::requireLogin('membre');

        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('../integration');
            return;
        }
        if($Membre->status !== ARRAY_STATUS_MEMBER[2]) {
            Utils::redirect('../integration');
            return;
        }

        $data = [
            'title' => SITE_NAME .' | Profil de '. $Membre->nom_postnom,
            'description' => 'Mon Profil',
            'Membre' => $Membre,
        ];

        $this->view('membre/profile', $data);
    }

    public function forgot_pswd() 
    {
        $data = [
            'title' => SITE_NAME .' | Mot de passe oublié',
            'description' => 'Mot de passe oublié',
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_forgot_pswd']))
        {
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));

            if(!$email)
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('membre/forgot_pswd',  $data);
                return;
            }

            $Membre = $this->MembreModel->findByWhere('email', $email);
            if(!$Membre)
            {
                Session::setFlash('error', 'L\'adresse email n\'est pas correcte.');
                $this->view('membre/forgot_pswd',  $data);
                return;
            }

            $tokenDb = $this->TokensModel->findByMemberId($Membre->member_id);
            if($tokenDb) $this->TokensModel->delete($Membre->member_id, $tokenDb->token_id);
            
            $tokenid = Utils::generateUuidV4();
            $token = Utils::generateToken(60);
            $tokenStatus = ARRAY_STATUS_TOKEN[1]; // non utilisé
            $expiryDate = Utils::getExpiryDateToken();

            $dataAddToken = [
                'token_id'      => $tokenid,
                'token'         => $token,
                'status'        => $tokenStatus,
                'expired_at'    => $expiryDate,
                'user_id'       => $Membre->member_id,
            ];

            $dataUpdateMembre = [
                'token'         => $token,
                'member_id'     => $Membre->member_id,
            ];

            if($this->TokensModel->insert($dataAddToken) && $this->MembreModel->update($dataUpdateMembre, 'member_id'))
            {
                // Envoi de l'email
                $lien_reset = SITE_URL . '/auth/uatvt/' . $Membre->member_id . '?tk=' . $tokenid;
                ob_start();
                include APP_PATH . 'templates/email/forgot_pswd.php';
                $messageBody = ob_get_clean();

                if($this->SendMailModel->sendEmail(
                    $Membre->email, 
                    'Réinitialisation de votre mot de passe - '. SITE_NAME, 
                    $messageBody
                )) 
                {
                    Session::setFlash('success', 'Un email de réinitialisation a été envoyé à votre adresse email.');
                    Utils::redirect('../login');
                }
            }
            else {
                Session::setFlash('error', "Une erreur est survenue. Veuillez réessayez plutard.");
                $this->view('membre/forgot_pswd',  $data);
                return;
            }
        
        }

        $this->view('membre/forgot_pswd', $data);
    }
}
