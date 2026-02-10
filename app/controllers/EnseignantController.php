<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Engagement.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Enseignant.php';
require_once APP_PATH . 'models/Tokens.php';
require_once APP_PATH . 'helpers/SendMail.php';

class EnseignantController extends Controller 
{    
    private $MembreModel;  
    private $EngagementModel;
    private $EnseignementModel;
    private $EnseignantModel;
    private $SendMailModel;
    private $TokensModel;

    public function __construct()
    {        
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->TokensModel = new Tokens();
        $this->EnseignementModel = new Enseignement();
        $this->SendMailModel = new SendMail();
        $this->EnseignantModel = new Enseignant();

    }

    public function index() 
    {
        Session::start();
        $cacheKey = 'enseignant_connexion';
        if (Session::isLogged('enseignant')) Utils::redirect('enseignant/profile/'. Session::get('enseignant')->enseignant_id);

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_login'])) $this->auth($_POST, $cacheKey);
        $this->view('enseignant/index');
    }

    public function profile($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if(!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $allEnseignements = $this->EnseignementModel->findAll($enseignantId);

        $data = [
            'title' => SITE_NAME .' | Profil de '. $Enseignant->nom_complet,
            'description' => 'Mon Profil',
            'Enseignant' => $Enseignant,
            'allEnseignements' => $allEnseignements,
        ];

        $this->view('enseignant/profile', $data);
    }

    public function enseignements($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);

        if(!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $allEnseignements = $this->EnseignementModel->findAll($enseignantId);

        $data = [
            'title' => SITE_NAME .' | Mes Enseignements',
            'description' => 'Liste de mes enseignements',
            'Enseignements' => $allEnseignements,
            'Enseignant' => $Enseignant,
        ];

        $this->view('enseignant/enseignement', $data);
    }

    public function add($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if(!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $data = [
            'title' => SITE_NAME .' | Ajouter un Enseignant',
            'description' => 'Ajouter un Enseignant',
            'Enseignant' => $Enseignant,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_add_enseignement'])) {
            // Traitement du formulaire d'ajout d'enseignement
            $titre = Utils::sanitize(trim($_POST['titre'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));

            if(!$titre) {
                Session::setFlash('error', 'Veuillez remplir correctement le formulaire.');
                $this->view('enseignant/add',  $data);
                return;
            }

            $dataAddEnseignement = [
                'title'             => $titre,
                'description'       => $description,
                'enseignant_id'     => $enseignantId,
                // Ajoutez d'autres champs nécessaires ici
            ];

            if (!empty($_FILES['audio_data']['name']))
            {
                $file = $_FILES['audio_data'];
                $filename = $file['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedTypes = ['audio/mpeg', 'audio/wav', 'audio/mp4', 'audio/ogg', 'audio/webm', 'audio/x-wav', 'audio/aac', 'audio/flac', 'audio/mp3'];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi du document");
                    $this->view('enseignant/add', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                // var_dump($mime); die;
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('enseignant/add', $data);
                    return;
                }

                $enseignementId = Utils::generateUuidV4();
                $pathDossier = $this->EnseignementModel->cheminDossierPdf($enseignementId, "enseignements");
                $nomFichier = str_replace([' ',"'"], '_', $titre) .'.'. $ext;
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('enseignant/add',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $dataAddEnseignement['enseignement_id']   = $enseignementId;
                        $dataAddEnseignement['audio_url']       = $fichierPath;
                        $resultUpload = true;                   
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('enseignant/add', ['data' => $data]);
                    return;
                }
            }

            if($resultUpload === true) 
            {
                if($this->EnseignementModel->insert($dataAddEnseignement)) {
                    Session::setFlash('success', 'Enseignement ajouté avec succès.');
                    Utils::redirect('../profile/'.$Enseignant->enseignant_id);
                } else {
                    Session::setFlash('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
                    $this->view('enseignant/add',  $data);
                    return;
                }
            } else {
                Session::setFlash('error', 'Une erreur est survenue lors de l\'upload du fichier audio. Veuillez réessayer plus tard.');
                $this->view('enseignant/add',  $data);
                return;
            }
        }

        $this->view('enseignant/add', $data);
    }

    // public function forgot_pswd() 
    // {
    //     $data = [
    //         'title' => SITE_NAME .' | Mot de passe oublié',
    //         'description' => 'Mot de passe oublié',
    //     ];

    //     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_forgot_pswd']))
    //     {
    //         $email = Utils::sanitize(trim($_POST['email'] ?? ''));

    //         if(!$email)
    //         {
    //             Session::setFlash('error', 'Remplissez correctement le formulaire.');
    //             $this->view('membre/forgot_pswd',  $data);
    //             return;
    //         }

    //         $Membre = $this->MembreModel->findByWhere('email', $email);
    //         if(!$Membre)
    //         {
    //             Session::setFlash('error', 'L\'adresse email n\'est pas correcte.');
    //             $this->view('membre/forgot_pswd',  $data);
    //             return;
    //         }

    //         $tokenDb = $this->TokensModel->findByMemberId($Membre->member_id);
    //         if($tokenDb) $this->TokensModel->delete($Membre->member_id, $tokenDb->token_id);
            
    //         $tokenid = Utils::generateUuidV4();
    //         $token = Utils::generateToken(60);
    //         $tokenStatus = ARRAY_STATUS_TOKEN[1]; // non utilisé
    //         $expiryDate = Utils::getExpiryDateToken();

    //         $dataAddToken = [
    //             'token_id'      => $tokenid,
    //             'token'         => $token,
    //             'status'        => $tokenStatus,
    //             'expired_at'    => $expiryDate,
    //             'member_id'       => $Membre->member_id,
    //         ];

    //         $dataUpdateMembre = [
    //             'token'         => $token,
    //             'member_id'     => $Membre->member_id,
    //         ];

    //         if($this->TokensModel->insert($dataAddToken) && $this->MembreModel->update($dataUpdateMembre, 'member_id'))
    //         {
    //             // Envoi de l'email
    //             $lien_reset = SITE_URL . '/auth/uatvt/' . $Membre->member_id . '?tk=' . $tokenid;
    //             ob_start();
    //             include APP_PATH . 'templates/email/forgot_pswd.php';
    //             $messageBody = ob_get_clean();

    //             if($this->SendMailModel->sendEmail(
    //                 $Membre->email, 
    //                 'Réinitialisation de votre mot de passe - '. SITE_NAME, 
    //                 $messageBody
    //             )) 
    //             {
    //                 Session::setFlash('success', 'Un email de réinitialisation a été envoyé à votre adresse email.');
    //                 Utils::redirect('../login');
    //             }
    //         }
    //         else {
    //             Session::setFlash('error', "Une erreur est survenue. Veuillez réessayez plutard.");
    //             $this->view('membre/forgot_pswd',  $data);
    //             return;
    //         }
        
    //     }

    //     $this->view('membre/forgot_pswd', $data);
    // }
    
    public function auth($Post, $cacheKey) 
    {
        $connect = Utils::sanitize(trim($Post['connect'] ?? ''));
        $pswd = Utils::sanitize(trim($Post['pswd'] ?? ''));

        $data = [
            'connect'   => $connect,
            'pswd'      => $pswd
        ];

        if($connect === '' || $pswd === '')
        {
            Session::setFlash('error', 'Remplissez correctement le formulaire.');
            $this->view('enseignant/index', ['data' => $data]);
            return;
        }

        $Enseignant = $this->EnseignantModel->loginEnseignant($connect, $cacheKey);
        if(!$Enseignant) {
            Session::setFlash('error', 'Adresse mail ou numéro de téléphone incorrect.');
            $this->view('enseignant/index', ['data' => $data]);
            return;
        }

        if($Enseignant->status !== ARRAY_STATUS_ENSEIGNANT[0]) {
            Session::setFlash('error', 'Votre compte n\'est pas activé. Contactez l\'administrateur.');
            $this->view('enseignant/index', ['data' => $data]);
            return;
        }

        if ($Enseignant && $pswd === $Enseignant->pswd)
        {
            Cache::set($cacheKey, $Enseignant);
            Session::set('enseignant', $Enseignant);
            Session::setFlash('success', 'Connecté.');
            Utils::redirect('/enseignant/profile/'.$Enseignant->enseignant_id);
        } else {
            Session::setFlash('error', 'Mot de passe incorrect.');
            $this->view('enseignant/index', ['data' => $data]);
            return;
        }
    }

    public function logout() 
    {
        Session::destroy();
        Cache::delete('enseignant_connexion');
        Utils::redirect('../enseignant');
    }
}
