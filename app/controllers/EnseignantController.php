<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Engagement.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Livre.php';
require_once APP_PATH . 'models/Enseignant.php';
require_once APP_PATH . 'models/Serie.php';
require_once APP_PATH . 'models/SerieSession.php';
require_once APP_PATH . 'models/SessionEnseignement.php';
require_once APP_PATH . 'models/Session.php';
require_once APP_PATH . 'models/Category.php';
require_once APP_PATH . 'models/Tokens.php';
require_once APP_PATH . 'helpers/SendMail.php';

class EnseignantController extends Controller 
{    
    private $MembreModel;  
    private $SerieModel;
    private $SerieSessionModel;
    private $SessionEnseignementModel;
    private $SessionModel;
    private $CategoryModel;
    private $EnseignementModel;
    private $LivreModel;
    private $EnseignantModel;
    private $sendEmailModel;
    private $TokensModel;
    
    private $dbCategories;
    private $allSessions;

    public function __construct()
    {        
        $this->MembreModel = new Membre();
        $this->SerieModel = new Serie();
        $this->SerieSessionModel = new SerieSession();
        $this->SessionEnseignementModel = new EnseignementSession();
        $this->SessionModel = new Sessions();
        $this->CategoryModel = new Category();
        $this->TokensModel = new Tokens();
        $this->EnseignementModel = new Enseignement();
        $this->LivreModel = new Livre();
        $this->sendEmailModel = new SendMail();
        $this->EnseignantModel = new Enseignant();

        $this->dbCategories = $this->CategoryModel->all();
        $this->allSessions = $this->SessionModel->all();
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
        $MaatId = $this->dbCategories[1]->category_id;

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        
        if(!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $dbSeries = $this->SerieModel->getSeries($MaatId);
        foreach ($dbSeries as $dbSerie) 
        {
            $dbSeriess[] = $dbSerie->nom;
        }

        $data = [
            'title' => SITE_NAME .' | Ajouter un Enseignant',
            'description' => 'Ajouter un Enseignant',
            'Enseignant' => $Enseignant,
            'dbSeries' => $dbSeriess,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_add_serie'])) 
        {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            if(!$nom)
            {
                Session::setFlash('error', 'Veuillez remplir correctement le formulaire.');
                $this->view('enseignant/add',  $data);
                return;   
            }
            $serieId = Utils::generateUuidV4();

            $dataAddSerie = [
                'serie_id'      => $serieId,
                'nom'           => $nom,
                'category_id'   => $MaatId,
                'updated_at'    => date('Y-m-d H:i:s'),
            ];

            if($this->SerieModel->insert($dataAddSerie)) 
            {
                foreach($this->allSessions as $session) 
                {
                    $dataAddSession = [
                        'session_id'    => $session->session_id,
                        'serie_id'      => $serieId,
                    ];
                    $this->SerieSessionModel->insert($dataAddSession);
                }
                
                Session::setFlash('success', 'Serie ajouté avec succès.');
                Utils::redirect('../add/'.$enseignantId);
            }
            else {
                Session::setFlash('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
                $this->view('enseignant/add',  $data);
                return;
            }

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_add_enseignement'])) {
            // Traitement du formulaire d'ajout d'enseignement
            $titre = Utils::sanitize(trim($_POST['titre'] ?? ''));
            $serie = Utils::sanitize(trim($_POST['serie'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            $duration_minutes = Utils::sanitize(trim($_POST['duration_minutes'] ?? ''));
            $duration_minutes = Utils::formatDuration($duration_minutes);

            if(!$titre) {
                Session::setFlash('error', 'Veuillez remplir correctement le formulaire.');
                $this->view('enseignant/add',  $data);
                return;
            }

            $serieId = $this->SerieModel->findByName($serie)->serie_id;

            $dataAddEnseignement = [
                'enseignant_id'     => $enseignantId,
                'category_id'       => $MaatId,
                'serie_id'          => $serieId,
                'title'             => $titre,
                'description'       => $description,
                'duration_minutes'  => $duration_minutes,
                'updated_at'        => date('Y-m-d H:i:s'),
            ];

            $dataUpdateSerie = [
                'updated_at'        => date('Y-m-d H:i:s'),
                'serie_id'          => $serieId,
            ];

            if (!empty($_FILES['audio_data']['name']))
            {
                $file = $_FILES['audio_data'];
                $filename = $file['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedTypes = ['audio/mpeg', 'audio/wav', 'video/mp4', 'video/3gpp', 'audio/mp4', 'audio/ogg', 'audio/x-ogg', 'audio/webm', 'audio/x-wav', 'audio/aac', 'audio/x-aac', 'audio/flac', 'audio/mp3','audio/x-m4a'];
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
                // if (!in_array($mime, $allowedTypes))
                // {
                //     Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                //     $this->view('enseignant/add', $data);
                //     return;
                // }

                $enseignementId = Utils::generateUuidV4();
                $pathDossier = $this->EnseignementModel->cheminDossierPdf($enseignementId, "enseignements");
                $nomFichier = str_replace([' ',"'","?"], '_', $titre) .'.'. $ext;
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
                if($this->EnseignementModel->insert($dataAddEnseignement) && $this->SerieModel->update($dataUpdateSerie)) 
                {
                    foreach($this->allSessions as $session) 
                    {
                        $dataAddSession = [
                            'session_id'    => $session->session_id,
                            'enseignement_id' => $enseignementId,
                        ];
                        $this->SessionEnseignementModel->insert($dataAddSession);
                    }

                    Session::setFlash('success', 'Enseignement ajouté avec succès.');
                    Utils::redirect('../enseignements/'.$enseignantId);
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

    public function add_bolokele($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        
        if(!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $BolokeleId = $this->dbCategories[0]->category_id;

        $dbSeries = $this->SerieModel->getSeries($BolokeleId);
        foreach ($dbSeries as $dbSerie) 
        {
            $dbSeriess[] = $dbSerie->nom;
        }

        $data = [
            'title' => SITE_NAME .' | Ajouter un Enseignant',
            'description' => 'Ajouter un Enseignant',
            'Enseignant' => $Enseignant,
            'dbSeries' => $dbSeriess,
            'dbCategories' => $this->dbCategories,
        ];

        

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_add_serie'])) 
        {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            if(!$nom)
            {
                Session::setFlash('error', 'Veuillez remplir correctement le formulaire.');
                $this->view('bolokele/add_bolokele',  $data);
                return;   
            }
            $serieId = Utils::generateUuidV4();

            $dataAddSerie = [
                'serie_id'      => $serieId,
                'nom'           => $nom,
                'category_id'   => $BolokeleId,
                'updated_at'    => date('Y-m-d H:i:s'),
            ];

            if($this->SerieModel->insert($dataAddSerie)) 
            {
                foreach($this->allSessions as $session) 
                {
                    $dataAddSession = [
                        'session_id'    => $session->session_id,
                        'serie_id'      => $serieId,
                    ];
                    $this->SerieSessionModel->insert($dataAddSession);
                }

                Session::setFlash('success', 'Serie ajouté avec succès.');
                Utils::redirect('../add_bolokele/'.$enseignantId);
            }
            else {
                Session::setFlash('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
                $this->view('bolokele/add_bolokele',  $data);
                return;
            }

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_add_bolokele_enseignement'])) {
            // Traitement du formulaire d'ajout d'enseignement
            $titre = Utils::sanitize(trim($_POST['titre'] ?? ''));
            $serie = Utils::sanitize(trim($_POST['serie'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            $duration_minutes = Utils::sanitize(trim($_POST['duration_minutes'] ?? ''));
            $duration_minutes = Utils::formatDuration($duration_minutes);

            if(!$titre) {
                Session::setFlash('error', 'Veuillez remplir correctement le formulaire.');
                $this->view('enseignant/add_bolokele',  $data);
                return;
            }

            $serieId = $this->SerieModel->findByName($serie)->serie_id;

            $dataAddEnseignement = [
                'enseignant_id'     => $enseignantId,
                'category_id'       => $BolokeleId,
                'serie_id'          => $serieId,
                'title'             => $titre,
                'description'       => $description,
                'duration_minutes'  => $duration_minutes,
                'updated_at'        => date('Y-m-d H:i:s'),
            ];

            if (!empty($_FILES['audio_data']['name']))
            {
                $file = $_FILES['audio_data'];
                $filename = $file['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedTypes = ['audio/mpeg', 'audio/wav', 'video/mp4', 'video/3gpp', 'audio/mp4', 'audio/ogg', 'audio/x-ogg', 'audio/webm', 'audio/x-wav', 'audio/aac', 'audio/x-aac', 'audio/flac', 'audio/mp3','audio/x-m4a'];
                // Verif erreur d'upload
                if ($file['error'] !== UPLOAD_ERR_OK)
                {
                    Session::setFlash('error', "Erreur lors de l'envoi du document");
                    $this->view('enseignant/add_bolokele', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                // var_dump($mime); die;
                // if (!in_array($mime, $allowedTypes))
                // {
                //     Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                //     $this->view('enseignant/add_bolokele', $data);
                //     return;
                // }

                $enseignementId = Utils::generateUuidV4();
                $pathDossier = $this->EnseignementModel->cheminDossierPdf($enseignementId, "bolokele");
                $nomFichier = str_replace([' ',"'","?"], '_', $titre) .'.'. $ext;
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('enseignant/add_bolokele',  $data);
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
                    $this->view('enseignant/add_bolokele', ['data' => $data]);
                    return;
                }
            }

            if($resultUpload === true) 
            {
                if($this->EnseignementModel->insert($dataAddEnseignement)) 
                {
                    foreach($this->allSessions as $session) 
                    {
                        $dataAddSession = [
                            'session_id'    => $session->session_id,
                            'enseignement_id' => $enseignementId,
                        ];
                        $this->SessionEnseignementModel->insert($dataAddSession);
                    }

                    Session::setFlash('success', 'Enseignement BOLOKELE ajouté avec succès.');
                    Utils::redirect('../enseignements/'.$enseignantId);
                } else {
                    Session::setFlash('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
                    $this->view('enseignant/add_bolokele',  $data);
                    return;
                }
            } else {
                Session::setFlash('error', 'Une erreur est survenue lors de l\'upload du fichier audio. Veuillez réessayer plus tard.');
                $this->view('enseignant/add_bolokele',  $data);
                return;
            }
        }

        $this->view('enseignant/add_bolokele', $data);
    }

    // public function alertEmail($enseignementId)
    // {
    //     $Enseignement = $this->EnseignementModel->findWithSerie($enseignementId);
    //     if(!$Enseignement) {
    //         Utils::redirect('/');
    //     }
    //     // var_dump($Enseignement); die;
    //     $allEmailsMembers = $this->MembreModel->getEmails();
    //     $lien_enseignement = SITE_URL . '/enseignement/show/' . $enseignementId;

    //     foreach($allEmailsMembers as $key => $email)
    //     {
    //         ob_start();
    //         include APP_PATH . 'templates/email/alert_new_enseignement.php';
    //         $messageBody = ob_get_clean();

    //         $this->sendEmailModel->sendEmail(
    //             $email->email, 
    //             'Nouvel Enseignement Disponible - '. SITE_NAME, 
    //             $messageBody
    //         );
    //     }
        
    //     Utils::redirect('../profile/'.$Enseignement->enseignant_id);
    // }

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

    //         $tokenDb = $this->TokensModel->findById($Membre->member_id);
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
    
    private function ensureCategoryByName($name, $description = '')
    {
        $stmt = $this->CategoryModel->db->prepare('SELECT category_id FROM categories WHERE LOWER(name) = LOWER(:name) LIMIT 1');
        $stmt->execute(['name' => $name]);
        $category = $stmt->fetch(PDO::FETCH_OBJ);

        if ($category) {
            return $category->category_id;
        }

        $categoryId = Utils::generateUuidV4();
        $insert = $this->CategoryModel->db->prepare('INSERT INTO categories (category_id, name, description) VALUES (:category_id, :name, :description)');
        $insert->execute([
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $description,
        ]);

        return $categoryId;
    }

    private function buildTeacherSeriesData($categoryId)
    {
        $series = $this->SerieModel->getAll();
        $result = [];

        foreach ($series as $serie) {
            if ($serie->category_id !== $categoryId) {
                continue;
            }

            $teachingsStmt = $this->EnseignementModel->db->prepare('SELECT * FROM teachings WHERE serie_id = :serie_id AND category_id = :category_id ORDER BY created_at ASC');
            $teachingsStmt->execute([
                'serie_id' => $serie->serie_id,
                'category_id' => $categoryId,
            ]);

            $serie->teachings = $teachingsStmt->fetchAll(PDO::FETCH_OBJ);
            $result[] = $serie;
        }

        return $result;
    }

    public function prieres($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $categoryId = $this->ensureCategoryByName('Prières', 'Séries de prières publiées par les enseignants');
        $series = $this->buildTeacherSeriesData($categoryId);

        $data = [
            'title' => SITE_NAME . ' | Mes prières',
            'description' => 'Gestion des prières et séries publiées',
            'Enseignant' => $Enseignant,
            'Series' => $series,
            'categoryId' => $categoryId,
        ];

        $this->view('enseignant/prieres', $data);
    }

    public function add_serie_priere($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $categoryId = $this->ensureCategoryByName('Prières', 'Séries de prières publiées par les enseignants');
        $data = [
            'title' => SITE_NAME . ' | Ajouter une série de prières',
            'description' => 'Créer une série pour organiser les prières audio',
            'Enseignant' => $Enseignant,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            if (!$nom) {
                Session::setFlash('error', 'Veuillez donner un nom à la série de prières.');
                $this->view('enseignant/add_serie_priere', $data);
                return;
            }

            $now = date('Y-m-d H:i:s');
            $serieId = Utils::generateUuidV4();
            $serieInsert = $this->SerieModel->db->prepare(
                'INSERT INTO series (serie_id, nom, category_id, created_at, updated_at)
                 VALUES (:serie_id, :nom, :category_id, :created_at, :updated_at)'
            );
            $ok = $serieInsert->execute([
                'serie_id' => $serieId,
                'nom' => $nom,
                'category_id' => $categoryId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($ok) {
                foreach ($this->allSessions as $session) {
                    $this->SerieSessionModel->insert([
                        'session_id' => $session->session_id,
                        'serie_id' => $serieId,
                    ]);
                }

                Session::setFlash('success', 'Série de prières ajoutée avec succès.');
                Utils::redirect('../add_priere/' . $enseignantId);
                return;
            }

            Session::setFlash('error', 'Une erreur est survenue lors de la création de la série.');
        }

        $this->view('enseignant/add_serie_priere', $data);
    }

    public function add_priere($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $categoryId = $this->ensureCategoryByName('Prières', 'Séries de prières publiées par les enseignants');
        $dbSeries = $this->buildTeacherSeriesData($categoryId);
        $seriesNames = [];
        foreach ($dbSeries as $serie) {
            $seriesNames[] = $serie->nom;
        }

        $data = [
            'title' => SITE_NAME . ' | Ajouter une prière',
            'description' => 'Ajouter une série de prières et un enseignement audio',
            'Enseignant' => $Enseignant,
            'dbSeries' => $seriesNames,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_add_priere_enseignement'])) {
            $titre = Utils::sanitize(trim($_POST['titre'] ?? ''));
            $serie = Utils::sanitize(trim($_POST['serie'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            $durationMinutes = null;

            if (!$titre || !$serie) {
                Session::setFlash('error', 'Veuillez remplir le titre et choisir une série.');
                $this->view('enseignant/add_priere', $data);
                return;
            }

            $serieObj = $this->SerieModel->findByName($serie);
            if (!$serieObj) {
                Session::setFlash('error', 'La série sélectionnée est introuvable.');
                $this->view('enseignant/add_priere', $data);
                return;
            }

            $enseignementId = Utils::generateUuidV4();
            $uploadPath = '';
            if (!empty($_FILES['audio_data']['name'])) {
                $file = $_FILES['audio_data'];
                $fileName = $file['name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['mp3', 'wav', 'm4a', 'aac', 'ogg', 'webm'];
                if (!in_array($ext, $allowed)) {
                    Session::setFlash('error', 'Format audio non autorisé.');
                    $this->view('enseignant/add_priere', $data);
                    return;
                }

                $pathDossier = $this->EnseignementModel->cheminDossierPdf($enseignementId, 'prières');
                $nomFichier = str_replace([' ', "'", '?'], '_', $titre) . '.' . $ext;
                $uploadPath = $pathDossier . '/' . $nomFichier;
                $target = BASE_PATH . $uploadPath;

                if (!move_uploaded_file($file['tmp_name'], $target)) {
                    Session::setFlash('error', 'Impossible d’enregistrer le fichier audio.');
                    $this->view('enseignant/add_priere', $data);
                    return;
                }
            } else {
                Session::setFlash('error', 'Veuillez joindre un fichier audio pour la prière.');
                $this->view('enseignant/add_priere', $data);
                return;
            }

            $teachingInsert = $this->EnseignementModel->db->prepare('INSERT INTO teachings (enseignement_id, enseignant_id, category_id, serie_id, title, description, duration_minutes, audio_url, created_at, updated_at) VALUES (:enseignement_id, :enseignant_id, :category_id, :serie_id, :title, :description, :duration_minutes, :audio_url, :created_at, :updated_at)');
            $ok = $teachingInsert->execute([
                'enseignement_id' => $enseignementId,
                'enseignant_id' => $enseignantId,
                'category_id' => $categoryId,
                'serie_id' => $serieObj->serie_id,
                'title' => $titre,
                'description' => $description,
                'duration_minutes' => $durationMinutes,
                'audio_url' => $uploadPath,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($ok) {
                foreach ($this->allSessions as $session) {
                    $this->SessionEnseignementModel->insert([
                        'session_id' => $session->session_id,
                        'enseignement_id' => $enseignementId,
                    ]);
                }

                Session::setFlash('success', 'Prière ajoutée avec succès.');
                Utils::redirect('../prieres/' . $enseignantId);
                return;
            }

            Session::setFlash('error', 'Une erreur est survenue lors de l’ajout de la prière.');
            $this->view('enseignant/add_priere', $data);
            return;
        }

        $this->view('enseignant/add_priere', $data);
    }

    public function edit_priere($enseignantId, $serieId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $categoryId = $this->ensureCategoryByName('Prières', 'Séries de prières publiées par les enseignants');
        $serie = $this->SerieModel->find($serieId);
        if (!$serie || $serie->category_id !== $categoryId) {
            Session::setFlash('error', 'Cette série de prière est introuvable.');
            Utils::redirect('../prieres/' . $enseignantId);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_edit_priere_serie'])) {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            if (!$nom) {
                Session::setFlash('error', 'Le nom de la série est requis.');
            } else {
                $update = $this->SerieModel->db->prepare('UPDATE series SET nom = :nom, updated_at = :updated_at WHERE serie_id = :serie_id');
                $update->execute([
                    'nom' => $nom,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'serie_id' => $serieId,
                ]);
                Session::setFlash('success', 'Série mise à jour.');
                Utils::redirect('../prieres/' . $enseignantId);
                return;
            }
        }

        $teachingsStmt = $this->EnseignementModel->db->prepare('SELECT * FROM teachings WHERE serie_id = :serie_id AND category_id = :category_id ORDER BY created_at DESC');
        $teachingsStmt->execute(['serie_id' => $serieId, 'category_id' => $categoryId]);

        $data = [
            'title' => SITE_NAME . ' | Modifier une prière',
            'description' => 'Modifier une série de prières',
            'Enseignant' => $Enseignant,
            'Serie' => $serie,
            'Teachings' => $teachingsStmt->fetchAll(PDO::FETCH_OBJ),
        ];

        $this->view('enseignant/edit_priere', $data);
    }

    public function delete_priere($enseignantId, $serieId)
    {
        Auth::requireLogin('enseignant');

        $categoryId = $this->ensureCategoryByName('Prières', 'Séries de prières publiées par les enseignants');
        $serie = $this->SerieModel->find($serieId);
        if ($serie && $serie->category_id === $categoryId) {
            $this->EnseignementModel->db->prepare('DELETE FROM teachings WHERE serie_id = :serie_id AND category_id = :category_id')->execute(['serie_id' => $serieId, 'category_id' => $categoryId]);
            $this->SerieSessionModel->db->prepare('DELETE FROM session_series WHERE serie_id = :serie_id')->execute(['serie_id' => $serieId]);
            $this->SerieModel->db->prepare('DELETE FROM series WHERE serie_id = :serie_id')->execute(['serie_id' => $serieId]);
            Session::setFlash('success', 'Série de prières supprimée.');
        } else {
            Session::setFlash('error', 'Impossible de supprimer cette série.');
        }

        Utils::redirect('../prieres/' . $enseignantId);
    }

    public function livres($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $list = $this->LivreModel->getAll();

        $data = [
            'title' => SITE_NAME . ' | Mes livres',
            'description' => 'Gestion des livres publiés',
            'Enseignant' => $Enseignant,
            'Livres' => $list,
        ];

        $this->view('enseignant/livres', $data);
    }

    public function add_livre($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_add_livre'])) {
            $titre = Utils::sanitize(trim($_POST['titre'] ?? ''));
            $categorie = Utils::sanitize(trim($_POST['categorie'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            $auteur = Utils::sanitize(trim($_POST['auteur'] ?? ''));

            if (!$titre || !$categorie || !$auteur) {
                Session::setFlash('error', 'Veuillez remplir les champs obligatoires du livre.');
                $this->view('enseignant/add_livre', ['Enseignant' => $Enseignant]);
                return;
            }

            $livreId = Utils::generateUuidV4();
            $uploadDir = BASE_PATH . 'storage/uploads/livres/' . $livreId;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $pdfPath = '';
            if (!empty($_FILES['pdf_file']['name'])) {
                $pdfInfo = $_FILES['pdf_file'];
                $pdfExt = strtolower(pathinfo($pdfInfo['name'], PATHINFO_EXTENSION));
                if ($pdfExt !== 'pdf') {
                    Session::setFlash('error', 'Le document du livre doit être un PDF.');
                    $this->view('enseignant/add_livre', ['Enseignant' => $Enseignant]);
                    return;
                }
                $pdfPath = 'storage/uploads/livres/' . $livreId . '/' . str_replace([' ', "'", '?'], '_', $titre) . '.pdf';
                move_uploaded_file($pdfInfo['tmp_name'], BASE_PATH . $pdfPath);
            }

            $coverPath = '';
            if (!empty($_FILES['cover_file']['name'])) {
                $coverInfo = $_FILES['cover_file'];
                $coverExt = strtolower(pathinfo($coverInfo['name'], PATHINFO_EXTENSION));
                if (!in_array($coverExt, ['jpg', 'jpeg', 'png', 'webp'])) {
                    Session::setFlash('error', 'La couverture doit être une image JPG, PNG ou WEBP.');
                    $this->view('enseignant/add_livre', ['Enseignant' => $Enseignant]);
                    return;
                }
                $coverPath = 'storage/uploads/livres/' . $livreId . '/' . str_replace([' ', "'", '?'], '_', $titre) . '.'.$coverExt;
                move_uploaded_file($coverInfo['tmp_name'], BASE_PATH . $coverPath);
            }

            $ok = $this->LivreModel->db->prepare('INSERT INTO livres (livre_id, titre, categorie, description, auteur, url_livre, url_couverture, created_at, updated_at) VALUES (:livre_id, :titre, :categorie, :description, :auteur, :url_livre, :url_couverture, :created_at, :updated_at)')->execute([
                'livre_id' => $livreId,
                'titre' => $titre,
                'categorie' => $categorie,
                'description' => $description,
                'auteur' => $auteur,
                'url_livre' => $pdfPath,
                'url_couverture' => $coverPath,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($ok) {
                Session::setFlash('success', 'Livre ajouté avec succès.');
                Utils::redirect('../livres/' . $enseignantId);
                return;
            }

            Session::setFlash('error', 'Une erreur est survenue lors de l’ajout du livre.');
        }

        $data = [
            'title' => SITE_NAME . ' | Ajouter un livre',
            'description' => 'Ajouter un nouveau livre à la bibliothèque',
            'Enseignant' => $Enseignant,
        ];

        $this->view('enseignant/add_livre', $data);
    }

    public function edit_livre($enseignantId, $livreId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $Livre = (new Livre())->getById($livreId);
        if (!$Livre) {
            Session::setFlash('error', 'Livre introuvable.');
            Utils::redirect('../livres/' . $enseignantId);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_edit_livre'])) {
            $titre = Utils::sanitize(trim($_POST['titre'] ?? ''));
            $categorie = Utils::sanitize(trim($_POST['categorie'] ?? ''));
            $description = Utils::sanitize(trim($_POST['description'] ?? ''));
            $auteur = Utils::sanitize(trim($_POST['auteur'] ?? ''));

            $pdfPath = $Livre->url_livre ?? '';
            $coverPath = $Livre->url_couverture ?? '';

            if (!empty($_FILES['pdf_file']['name'])) {
                $pdfInfo = $_FILES['pdf_file'];
                $pdfExt = strtolower(pathinfo($pdfInfo['name'], PATHINFO_EXTENSION));
                if ($pdfExt !== 'pdf') {
                    Session::setFlash('error', 'Le document du livre doit être un PDF.');
                    $this->view('enseignant/edit_livre', ['Enseignant' => $Enseignant, 'Livre' => $Livre]);
                    return;
                }
                $pdfPath = 'storage/uploads/livres/' . $livreId . '/' . str_replace([' ', "'", '?'], '_', $titre) . '.pdf';
                move_uploaded_file($pdfInfo['tmp_name'], BASE_PATH . $pdfPath);
            }

            if (!empty($_FILES['cover_file']['name'])) {
                $coverInfo = $_FILES['cover_file'];
                $coverExt = strtolower(pathinfo($coverInfo['name'], PATHINFO_EXTENSION));
                if (!in_array($coverExt, ['jpg', 'jpeg', 'png', 'webp'])) {
                    Session::setFlash('error', 'La couverture doit être une image JPG, PNG ou WEBP.');
                    $this->view('enseignant/edit_livre', ['Enseignant' => $Enseignant, 'Livre' => $Livre]);
                    return;
                }
                $coverPath = 'storage/uploads/livres/' . $livreId . '/' . str_replace([' ', "'", '?'], '_', $titre) . '.' . $coverExt;
                move_uploaded_file($coverInfo['tmp_name'], BASE_PATH . $coverPath);
            }

            $update = (new Livre())->db->prepare('UPDATE livres SET titre = :titre, categorie = :categorie, description = :description, auteur = :auteur, url_livre = :url_livre, url_couverture = :url_couverture, updated_at = :updated_at WHERE livre_id = :livre_id');
            $ok = $update->execute([
                'titre' => $titre,
                'categorie' => $categorie,
                'description' => $description,
                'auteur' => $auteur,
                'url_livre' => $pdfPath,
                'url_couverture' => $coverPath,
                'updated_at' => date('Y-m-d H:i:s'),
                'livre_id' => $livreId,
            ]);

            if ($ok) {
                Session::setFlash('success', 'Livre modifié avec succès.');
                Utils::redirect('../livres/' . $enseignantId);
                return;
            }

            Session::setFlash('error', 'Une erreur est survenue lors de la modification.');
        }

        $data = [
            'title' => SITE_NAME . ' | Modifier un livre',
            'description' => 'Modifier un livre existant',
            'Enseignant' => $Enseignant,
            'Livre' => $Livre,
        ];

        $this->view('enseignant/edit_livre', $data);
    }

    public function delete_livre($enseignantId, $livreId)
    {
        Auth::requireLogin('enseignant');

        $Livre = (new Livre())->getById($livreId);
        if ($Livre) {
            (new Livre())->db->prepare('DELETE FROM livres WHERE livre_id = :livre_id')->execute(['livre_id' => $livreId]);
            Session::setFlash('success', 'Livre supprimé.');
        } else {
            Session::setFlash('error', 'Livre introuvable.');
        }

        Utils::redirect('../livres/' . $enseignantId);
    }

    public function ressources($enseignantId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $data = [
            'title' => SITE_NAME . ' | Ressources pédagogiques',
            'description' => 'Centre de gestion des prières, livres et contenus pédagogiques',
            'Enseignant' => $Enseignant,
        ];

        $this->view('enseignant/ressources', $data);
    }

    public function view_priere($enseignantId, $serieId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $categoryId = $this->ensureCategoryByName('Prières', 'Séries de prières publiées par les enseignants');
        $serie = $this->SerieModel->find($serieId);
        if (!$serie || $serie->category_id !== $categoryId) {
            Session::setFlash('error', 'Cette série de prières est introuvable.');
            Utils::redirect('../prieres/' . $enseignantId);
            return;
        }

        $teachingsStmt = $this->EnseignementModel->db->prepare('SELECT * FROM teachings WHERE serie_id = :serie_id AND category_id = :category_id ORDER BY created_at DESC');
        $teachingsStmt->execute(['serie_id' => $serieId, 'category_id' => $categoryId]);

        $data = [
            'title' => SITE_NAME . ' | Détail de la série',
            'description' => 'Détails et audios de la série de prières',
            'Enseignant' => $Enseignant,
            'Serie' => $serie,
            'Teachings' => $teachingsStmt->fetchAll(PDO::FETCH_OBJ),
        ];

        $this->view('enseignant/detail_priere', $data);
    }

    public function view_livre($enseignantId, $livreId)
    {
        Auth::requireLogin('enseignant');

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if (!$Enseignant) {
            Utils::redirect('/');
            return;
        }

        $Livre = (new Livre())->getById($livreId);
        if (!$Livre) {
            Session::setFlash('error', 'Livre introuvable.');
            Utils::redirect('../livres/' . $enseignantId);
            return;
        }

        $data = [
            'title' => SITE_NAME . ' | Détail du livre',
            'description' => 'Détails du livre publié',
            'Enseignant' => $Enseignant,
            'Livre' => $Livre,
        ];

        $this->view('enseignant/detail_livre', $data);
    }

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
            // Session::destroy();
            // Cache::delete('membre_connexion');
            Cache::set($cacheKey, $Enseignant);
            Session::set('enseignant', $Enseignant);
            Session::setFlash('success', 'Connecté.');

            $returnUrl = $_SESSION['redirect_after_login'] ?? '/enseignant/profile/'.$Enseignant->enseignant_id;
            unset($_SESSION['redirect_after_login']);
            Utils::redirect($returnUrl);
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
        Utils::redirect('/enseignant');
    }
}
