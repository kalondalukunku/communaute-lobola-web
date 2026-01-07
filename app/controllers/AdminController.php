<?php
require_once APP_PATH . 'models/Admin.php';
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Engagement.php';
require_once APP_PATH . 'models/Payment.php';
require_once APP_PATH . 'helpers/SendMail.php';

class AdminController extends Controller 
{
    private $MembreModel; 
    private $EnseignementModel;
    private $EngagementModel;
    private $adminModel;
    private $PaymentModel;
    private $sendEmailModel;
    
    public function __construct()
    {        
        $this->adminModel = new Admin();
        $this->MembreModel = new Membre();
        $this->EnseignementModel = new Enseignement();
        $this->EngagementModel = new Engagement();
        $this->PaymentModel = new Payment();
        $this->sendEmailModel = new SendMail();
 
    }

    public function dashboard() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        $allMembres = $this->MembreModel->findAll([], [], $cacheKey);
        $NbrAllMembres = $this->MembreModel->countAll(['status' => ARRAY_STATUS_MEMBER[2]], $cacheKey);
        $NbrAllMembresAttente = $this->MembreModel->countAll(['status' => 'pending_engagement', 'status' => 'pending_validation'], $cacheKey);
        $totalPayment = $this->PaymentModel->getTotalPayments();

        $data = [
            'allMembres' => $allMembres,
            'NbrAllMembres' => $NbrAllMembres,
            'NbrAllMembresAttente' => $NbrAllMembresAttente,
            'totalPayment' => $totalPayment,
        ];

        $this->view('admin/dashboard', $data);
    }

    public function add() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_admin_add'])) 
        {
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));

            if($email === '' || $nom === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
            }
            else {
                $existingAdmin = $this->adminModel->findByEmail($email, $cacheKey);
                if ($existingAdmin) 
                {
                    Session::setFlash('error', 'Un administrateur avec cet email existe déjà.');
                } 
                else {
                    $defaultPassword = Utils::generateRandomPassword(10);
                    $hashedPassword = password_hash($defaultPassword, PASSWORD_ARGON2I);

                    $adminData = [
                        'admin_id' => Utils::generateUuidV4(),
                        'nom' => $nom,
                        'email' => $email,
                        'pswd' => $hashedPassword,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    
                    $messageBody = "<p>Bonjour $nom,</p>
                    <p>Votre compte administrateur a été créé avec succès sur ". SITE_NAME .".</p>
                    <p>Voici vos informations de connexion :</p>
                    <ul style='margin-bottom: 15px;'>
                        <li><strong>Email :</strong> $email</li>
                        <li><strong>Mot de passe :</strong> $defaultPassword</li>
                    </ul>
                    <p style='text-align: center; margin-top: 20px; margin-bottom: 20px; '><a href='" . SITE_URL . "/admin/login' style='text-decoration: none; padding: 13px; border-radius: 5px; background-color: #cfbb30; color: #000f0e;'>Connexion</a></p>
                    <p style='margin-top: 15px;'>Nous vous recommandons de changer votre mot de passe après votre première connexion pour des raisons de sécurité.</p>
                    <p>Cordialement,<br>L'équipe de ". SITE_NAME ."</p>";

                    if ($this->adminModel->insert($adminData)) 
                    {
                        if($this->sendEmailModel->sendEmail(
                            $email, 
                            'Création de votre compte administrateur - '. SITE_NAME,
                            $messageBody
                        )) {
                            Session::setFlash('success', "Administrateur ajouté avec succès. Mot de passe par défaut : $defaultPassword");
                            Utils::redirect('/admin/dashboard');
                        }
                        
                    } else {
                        Session::setFlash('error', "Une erreur est survenue lors de l'ajout de l'administrateur.");
                    }
                }
            }
        }

        $this->view('admin/add');
    }

    public function membres() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        $allMembres = $this->MembreModel->findAll([], [], $cacheKey);
        $NbrAllMembres = $this->MembreModel->countAll([], $cacheKey);
        $NbrAllMembresAttente = $this->MembreModel->countAll(['status' => 'pending_engagement', 'status' => 'pending_validation'], $cacheKey);

        $data = [
            'allMembres' => $allMembres,
            'NbrAllMembres' => $NbrAllMembres,
            'NbrAllMembresAttente' => $NbrAllMembresAttente
        ];

        foreach($allMembres as $membre) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_vwfl'.$membre->member_id]))
            {
                $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($membre->document_path));
                $pathFilePdf = FILE_VIEW_FOLDER_PATH ."file.". $membre->document_ext;

                $res = $this->EnseignementModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_FILE);

                if($res === true)
                {
                    Utils::redirect('membre/'.$membre->member_id.'?fl=file.'.$membre->document_ext);
                    unlink($pathFilePdf);
                }
            }
        }

        $this->view('admin/membres', $data);
    }

    public function membre($membreId) 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';        
        $name = basename($_GET['fl'] ?? '');
        $pathFilePdf = FILE_VIEW_FOLDER_PATH . $name;

        if (!$name && !file_exists($pathFilePdf)) {
            Utils::redirect(RETOUR_EN_ARRIERE);
            exit;
        }

        $Membre = $this->MembreModel->findByMemberId($membreId);
        if(!$Membre) {
            Utils::redirect('../membres');
            return;
        }

        $nextPayment = $this->PaymentModel->getPayment($membreId, $Membre->engagement_id);

        $data = [
            'membreId' => $membreId,
            'Membre' => $Membre,
            'nextPayment' => $nextPayment,
            'name' => $name
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_approuve'])) 
        {
            if(isset($_POST['cllil_membre_approuve'])) {
                // Approve engagement logic here
                $updateData = [
                    'member_id' => $membreId,
                    'statut' => ARRAY_STATUS_ENGAGEMENT[0]
                ];
                $updateDataMembre = [
                    'member_id' => $membreId,
                    'status' => ARRAY_STATUS_MEMBER[1]
                ];
                if($this->EngagementModel->update($updateData, 'member_id') && $this->MembreModel->update($updateDataMembre, 'member_id'))
                {
                    Session::setFlash('success', 'Engagement approuvé avec succès.');
                    Utils::redirect('../membres');
                }
                
            } 
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_validate'])) 
        {
            if(isset($_POST['cllil_membre_validate'])) 
                {
                $updateDataMembre = [
                    'member_id' => $membreId,
                    'status' => ARRAY_STATUS_MEMBER[2]
                ];
                $payID = Utils::generateUuidV4();
                $paymentProchain = date('Y-m-d', strtotime("+". Utils::getMonthsNumber($Membre->modalite_engagement) ." months"));

                $dataAddPayement = [
                    'pay_id' => $payID,
                    'member_id' => $membreId,
                    'engagement_id' => $Membre->engagement_id,
                    'amount' => Utils::getMonthsNumber($Membre->modalite_engagement) * $Membre->montant,
                    'devise' => $Membre->devise,
                    'payment_status' => ARRAY_PAYMENT_STATUS[1],
                    'payment_prochain' => $paymentProchain,
                    'verified_by_admin_id' => Session::get('admin')['admin_id'],
                ];
                if($this->MembreModel->update($updateDataMembre, 'member_id') && $this->PaymentModel->insert($dataAddPayement))
                {
                    Session::setFlash('success', 'Membre activé avec succès.');
                    Utils::redirect('../membres');
                }
                
            } 
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_download_engagement'.$membreId]))
            {
                $name_file = strtolower($Membre->nom_postnom .'_-_engagement.'. $Membre->document_ext);

                $res = Utils::dechiffreflpdf($this->EnseignementModel, $Membre->document_path, $name_file);
                $pathFilePdf = FILE_VIEW_FOLDER_PATH . $name_file;

                if($res === true && file_exists($pathFilePdf))
                {
                    $this->EnseignementModel->download_file($pathFilePdf, $Membre->document_header_type);
                }
            }

        $this->view('admin/membre', $data);
    }

    public function enseignements() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        $allMembres = $this->MembreModel->findAll([], [], $cacheKey);
        $NbrAllMembres = $this->MembreModel->countAll([], $cacheKey);
        $NbrAllMembresAttente = $this->MembreModel->countAll(['status' => 'pending_engagement', 'status' => 'pending_validation'], $cacheKey);

        $data = [
            'allMembres' => $allMembres,
            'NbrAllMembres' => $NbrAllMembres,
            'NbrAllMembresAttente' => $NbrAllMembresAttente
        ];

        $this->view('admin/index', $data);
    }

    public function login() 
    {
        Session::start();
        $cacheKey = 'admin_connexion';
        if (Session::isLogged('admin')) Utils::redirect('../admin/');

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_admin_login'])) $this->auth($_POST, $cacheKey);
        $this->view('admin/login');
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('../admin/login');
    }

    public function auth($Post, $cacheKey) 
    {
        $email = Utils::sanitize(trim($Post['email'] ?? ''));
        $password = Utils::sanitize(trim($Post['pswd'] ?? ''));

        if($email === '' || $password === '')
        {
            Session::setFlash('error', 'Remplissez correctement le formulaire.');
        }

        $admin = $this->adminModel->findByEmail($email, $cacheKey);

        if ($admin && password_verify($password, $admin->pswd)) {
            Session::set('admin', $admin);
            Session::setFlash('success', 'Connecté.');
            Utils::redirect('../admin/dashboard');
        } else {
            Session::setFlash('error', 'Email ou mot de passe incorrect.');
        }
    }
}
