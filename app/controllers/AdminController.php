<?php
require_once APP_PATH . 'models/Admin.php';
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Enseignement.php';
require_once APP_PATH . 'models/Enseignant.php';
require_once APP_PATH . 'models/Engagement.php';
require_once APP_PATH . 'models/Payment.php';
require_once APP_PATH . 'models/Tokens.php';
require_once APP_PATH . 'models/ActionsRaisons.php';
require_once APP_PATH . 'helpers/SendMail.php';

class AdminController extends Controller 
{
    private $MembreModel; 
    private $EnseignementModel;
    private $EnseignantModel;
    private $EngagementModel;
    private $adminModel;
    private $PaymentModel;
    private $TokensModel;
    private $ActionsRaisonsModel;
    private $sendEmailModel;
    
    public function __construct()
    {        

        $this->adminModel = new Admin();
        $this->MembreModel = new Membre();
        $this->EnseignementModel = new Enseignement();
        $this->EnseignantModel = new Enseignant();
        $this->EngagementModel = new Engagement();
        $this->PaymentModel = new Payment();
        $this->TokensModel = new Tokens();
        $this->ActionsRaisonsModel = new ActionsRaisons();
        $this->sendEmailModel = new SendMail();
 
    }

    public function index()
    {
        Utils::redirect('/admin/dashboard');
    }
    
    public function edtpswd()
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';
        $admin = $this->adminModel->find(Session::get('admin')['admin_id'], $cacheKey);
        if(!$admin) {
            Utils::redirect('/admin/dashboard');
            return;
        }

        $data = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_admin_updt_pswd']))
        {
            $old_pswd = Utils::sanitize(trim($_POST['old_pswd'] ?? ''));
            $new_pswd = Utils::sanitize(trim($_POST['new_pswd'] ?? ''));
            $confirm_password = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

            if($old_pswd === '' || $new_pswd === '' || $confirm_password === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('admin/edtpswd',  $data);
                return;
            }
            if(
                !Helper::lengthValidation($old_pswd, 8, 16) || 
                !Helper::lengthValidation($new_pswd, 8, 16) || 
                !Helper::lengthValidation($confirm_password, 8, 16)
            ) {
                Session::setFlash('error', "Tous les mot de passe doit être compris entre 8 à 16 caratères.");
                $this->view('admin/edtpswd',  $data);
                return;
            }
            if($old_pswd === $new_pswd)
            {
                Session::setFlash('error', "Le nouveau mot de passe doit être différent de l'ancien mot de passe.");
                $this->view('admin/edtpswd',  $data);
                return;
            }

             if($new_pswd !== $confirm_password)
            {
                Session::setFlash('error', "Les deux nouveaux mots de passe ne se correspondent pas.");
                $this->view('admin/edtpswd',  $data);
                return;
            }
            if(!password_verify($old_pswd, $admin->pswd))
            {
                Session::setFlash('error', "Ancien mot de passe incorrect.");
                $this->view('admin/edtpswd',  $data);
                return;
            }

            $hashedPassword = password_hash($new_pswd, PASSWORD_ARGON2I);

            $dataEdtPswdUs = [
                'pswd'      => $hashedPassword,
                'admin_id'  => $admin->admin_id,
            ];

            if($this->adminModel->update($dataEdtPswdUs, 'admin_id'))
            {
                Session::setFlash('success', 'Mot de passe modifié avec succès.');
                Utils::redirect('dashboard');
            }
        }

        $this->view('admin/edtpswd', $data);
    }

    public function dashboard() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $search = ($query !== '') ? basename($query) : null;
        $sttGet = basename($_GET['stt'] ?? '');
        $psnPg = (int) basename($_GET['page'] ?? 1);

        $stt = "";
        
        if($sttGet === 'active' || !isset($_GET['stt']))
            $stt = ARRAY_STATUS_MEMBER[2];
        elseif($sttGet === 'att_engagement')
            $stt = ARRAY_STATUS_MEMBER[0];
        elseif($sttGet === 'att_validation')
            $stt = ARRAY_STATUS_MEMBER[1];
        elseif($sttGet === 'suspended')
            $stt = ARRAY_STATUS_MEMBER[3];
        elseif($sttGet === 'inactive')
            $stt = ARRAY_STATUS_MEMBER[5];

        $results = $this->MembreModel->findAllMembres($psnPg, $search, ['status' => $stt], 10);
        $allMembres = $results['data'];

        $NbrAllMembres = $this->MembreModel->countAll(['status' => ARRAY_STATUS_MEMBER[2]], $cacheKey);
        $NbrAllMembresAttente = $this->MembreModel->countAll(['status' => 'attente_engagement', 'status' => 'attente_integration'], $cacheKey);
        $totalPayment = $this->PaymentModel->getTotalPayments();
        $tauxEngagement = $this->MembreModel->calculerTauxEngagementApprouve();
        $nbrEnseignement = count($this->EnseignementModel->all());

        $data = [
            'allMembres' => $allMembres,
            'NbrAllMembres' => $NbrAllMembres,
            'NbrAllMembresAttente' => $NbrAllMembresAttente,
            'totalPayment' => $totalPayment,
            'tauxEngagement' => $tauxEngagement,
            'nbrEnseignement' => $nbrEnseignement,
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

    public function settings()
    {
        Auth::requireLogin('admin');
        $casheKey = 'admin_administraction';

        $this->view('admin/settings');
    }

    public function membres() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';
        
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $search = ($query !== '') ? basename($query) : null;
        $sttGet = basename($_GET['stt'] ?? '');
        $psnPg = (int) basename($_GET['page'] ?? 1);

        $stt = "";
        
        if($sttGet === 'active' || !isset($_GET['stt']))
            $stt = ARRAY_STATUS_MEMBER[2];
        elseif($sttGet === 'att_validation')
            $stt = ARRAY_STATUS_MEMBER[1];
        elseif($sttGet === 'suspended')
            $stt = ARRAY_STATUS_MEMBER[3];
        elseif($sttGet === 'att_rejete')
            $stt = ARRAY_STATUS_MEMBER[4];
        elseif($sttGet === 'inactive')
            $stt = ARRAY_STATUS_MEMBER[5];

        $results = $this->MembreModel->findAllMembres($psnPg, $search, ['status' => $stt]);
        $allMembres = $results['data'];
        $totalrecords = $results['total_records'];
        $currentPage = $results['current_page'];
        $parPage = $results['per_page'];
        $totalPages = $results['total_pages'];

        $NbrAllMembres = $this->MembreModel->countAll(['status' => ARRAY_STATUS_MEMBER[2]], $cacheKey);
        $NbrAllMembresAttente = $this->MembreModel->countAll(['status' => ARRAY_STATUS_MEMBER[1]], $cacheKey);
        $NbrAllMembresInities = $this->MembreModel->countAll(['niveau_initiation' => [ARRAY_TYPE_NIVEAU_INITIATION[1],ARRAY_TYPE_NIVEAU_INITIATION[2]]], $cacheKey);
        $totalPaymentMonth = $this->PaymentModel->getTotalPaymentsMonth();

        $data = [
            'allMembres' => $allMembres,
            'totalrecords' => $totalrecords,
            'currentPage' => $currentPage,
            'parPage' => $parPage,
            'totalPages' => $totalPages,
            'NbrAllMembres' => $NbrAllMembres,
            'NbrAllMembresAttente' => $NbrAllMembresAttente,
            'totalPaymentMonth' => $totalPaymentMonth,
            'NbrAllMembresInities' => $NbrAllMembresInities
        ];

        foreach($allMembres as $membre) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_vwfl'.$membre->member_id]))
            {
                $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($membre->document_path));
                $pathFilePdf = FILE_VIEW_FOLDER_PATH ."file.". $membre->document_ext;

                $res = $this->MembreModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_FILE);

                if($res === true)
                {
                    Utils::redirect('membre/'.$membre->member_id.'?fl=file.'.$membre->document_ext);
                    unlink($pathFilePdf);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_delete'.$membre->member_id])) 
            {
                $mId = Utils::sanitize(trim($_POST['cllil_membre_id'.$membre->member_id] ?? ''));
                
                if($mId === $membre->member_id)
                {
                    if($this->MembreModel->delete($membre->member_id))
                    {
                        Session::setFlash('success', 'Membre supprimé avec succès.');
                        Utils::redirect('membres');
                    }    
                }
            }
        }

        $this->view('admin/membres', $data);
    }

    public function engages() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';
        
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $search = ($query !== '') ? basename($query) : null;
        $sttGet = basename($_GET['stt'] ?? '');
        $psnPg = (int) basename($_GET['page'] ?? 1);

        $stt = ARRAY_STATUS_MEMBER[2];
        
        if($sttGet === 'active' || !isset($_GET['stt']))
            $stt = ARRAY_STATUS_MEMBER[2];
        elseif($sttGet === 'att_engagement')
            $stt = ARRAY_STATUS_MEMBER[0];

        $results = $this->MembreModel->findAllEngages($psnPg, $search, ['status' => $stt]);
        $AllEngages = $results['data'];
        $totalrecords = $results['total_records'];
        $currentPage = $results['current_page'];
        $parPage = $results['per_page'];
        $totalPages = $results['total_pages'];

        $NbrAllEngages = $this->MembreModel->countAll(['status' => ARRAY_STATUS_MEMBER[2]], $cacheKey);
        $NbrAllEngagesAttente = $this->MembreModel->countAll(['status' => ARRAY_STATUS_MEMBER[0]], $cacheKey);
        $totalPaymentMonth = $this->PaymentModel->getTotalPaymentsMonth();
        $membresEngages = $this->MembreModel->countEngagedMembers();
        $membresEngagesRejetes = $this->EngagementModel->countAll(['statut' => ARRAY_STATUS_ENGAGEMENT[2]], $cacheKey);

        $data = [
            'AllEngages' => $AllEngages,
            'totalrecords' => $totalrecords,
            'currentPage' => $currentPage,
            'parPage' => $parPage,
            'totalPages' => $totalPages,
            'NbrAllEngages' => $NbrAllEngages,
            'NbrAllEngagesAttente' => $NbrAllEngagesAttente,
            'totalPaymentMonth' => $totalPaymentMonth,
            'membresEngages' => $membresEngages,
            'membresEngagesRejetes' => $membresEngagesRejetes
        ];

        foreach($AllEngages as $membre) {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_vwfl'.$membre->member_id]))
            {
                $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($membre->document_path));
                $pathFilePdf = FILE_VIEW_FOLDER_PATH ."file.". $membre->document_ext;

                $res = $this->MembreModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_FILE);

                if($res === true)
                {
                    Utils::redirect('membre/'.$membre->member_id.'?fl=file.'.$membre->document_ext);
                    unlink($pathFilePdf);
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_delete'.$membre->member_id])) 
            {
                $mId = Utils::sanitize(trim($_POST['cllil_membre_id'.$membre->member_id] ?? ''));
                
                if($mId === $membre->member_id)
                {
                    if($this->MembreModel->delete($membre->member_id))
                    {
                        Session::setFlash('success', 'Membre supprimé avec succès.');
                        Utils::redirect('membres');
                    }    
                }
            }
        }

        $this->view('admin/engages', $data);
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

        $Payment = $this->PaymentModel->getPayment($membreId, $Membre->engagement_id);

        $data = [
            'membreId' => $membreId,
            'Membre' => $Membre,
            'Payment' => $Payment,
            'name' => $name
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_integration_approuve'])) 
        {
            if(isset($_POST['cllil_membre_integration_approuve'])) 
            {
                $updateDataMembre = [
                    'member_id' => $membreId,
                    'status' => ARRAY_STATUS_MEMBER[5]
                ];
                if($this->MembreModel->update($updateDataMembre, 'member_id'))
                {
                    // envoi de mail de notification
                    $lien_activation = SITE_URL . '/membre/updt_pswd/' . $membreId;
                    ob_start();
                    include APP_PATH . 'templates/email/integration_active.php';
                    $messageBody = ob_get_clean();

                    if($this->sendEmailModel->sendEmail(
                        $Membre->email, 
                        'Intégration  - '. SITE_NAME, 
                        $messageBody
                    )) 
                    {
                        Session::setFlash('success', 'Membre approuvé avec succès.');
                        Utils::redirect('../membres');
                    }
                } 
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_integration_rejeted'])) 
        {
            $motif = Utils::sanitize(trim($_POST['motif'] ?? ''));
            if(!$motif) {
                Session::setFlash('error', 'Veuillez fournir une raison pour le rejet du membre.');
                $this->view('admin/membre', $data);
                return;
            }
            $dataAddActionRaison = [
                'action_id' => Utils::generateUuidV4(),
                'member_id' => $membreId,
                'admin_id' => Session::get('admin')['admin_id'],
                'actions' => ARRAY_ACTIONS_RAISONS[0],
                'raison' => $motif,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $updateDataMembre = [
                'member_id' => $membreId,
                'status' => ARRAY_STATUS_MEMBER[4]
            ];
            if($this->MembreModel->update($updateDataMembre, 'member_id') && $this->ActionsRaisonsModel->insert($dataAddActionRaison))
            {
                $lien_correction = SITE_URL . '/membre/rjtdmdf/' . $membreId;
                ob_start();
                include APP_PATH . 'templates/email/integration_rejete.php';
                $messageBody = ob_get_clean();

                if($this->sendEmailModel->sendEmail(
                    $Membre->email, 
                    'Rejet de votre intégration  - '. SITE_NAME, 
                    $messageBody
                )) 
                {
                    Session::setFlash('success', "Membre rejeté avec succès.");
                    Utils::redirect('../membres');
                }
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_eng_rejeted'])) 
        {
            $updateData = [
                'member_id' => $membreId,
                'statut' => ARRAY_STATUS_ENGAGEMENT[2]
            ];
            $updateDataMembre = [
                'member_id' => $membreId,
                'status' => ARRAY_STATUS_MEMBER[5]
            ];
            if($this->EngagementModel->update($updateData, 'member_id') && $this->MembreModel->update($updateDataMembre, 'member_id'))
            {
                Session::setFlash('success', "Engagement rejeté avec succès.");
                Utils::redirect('../membres');
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_approuve'])) 
        {
            if(isset($_POST['cllil_membre_approuve'])) {
                
                $statusM = !$Payment ? ARRAY_STATUS_MEMBER[1] : ARRAY_STATUS_MEMBER[2];
                $updateData = [
                    'member_id' => $membreId,
                    'statut' => ARRAY_STATUS_ENGAGEMENT[0]
                ];
                $updateDataMembre = [
                    'member_id' => $membreId,
                    'status' => $statusM
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
                $statusM = $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] ? ARRAY_STATUS_MEMBER[2] : $Membre->status;
                $updateDataMembre = [
                    'member_id' => $membreId,
                    'status' => $statusM
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

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_delete'])) 
        {
            
            if($this->MembreModel->delete($membreId))
            {
                Session::setFlash('success', 'Membre supprimé avec succès.');
                Utils::redirect('../membres');
            }
            
        }

        if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && $Payment)
        {
            if($Membre->status === ARRAY_STATUS_MEMBER[2])
            {
                if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_suspended']))   
                {
                    $updateDataMembre = [
                        'member_id' => $membreId,
                        'status' => ARRAY_STATUS_MEMBER[3]
                    ];
                    if($this->MembreModel->update($updateDataMembre, 'member_id'))
                    {
                        Session::setFlash('success', 'Membre suspendu avec succès.');
                        Utils::redirect('../membres');
                    }
                }   
                if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_desactive'])) 
                {
                    $updateDataMembre = [
                        'member_id' => $membreId,
                        'status' => ARRAY_STATUS_MEMBER[5]
                    ];
                    if($this->MembreModel->update($updateDataMembre, 'member_id'))
                    {
                        Session::setFlash('success', 'Membre désactivé avec succès.');
                        Utils::redirect('../membres');
                    }
                }
            }
            elseif($Membre->status === ARRAY_STATUS_MEMBER[3] || $Membre->status === ARRAY_STATUS_MEMBER[5])
            {
                if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_active'])) 
                {
                    $updateDataMembre = [
                        'member_id' => $membreId,
                        'status' => ARRAY_STATUS_MEMBER[2]
                    ];
                    if($this->MembreModel->update($updateDataMembre, 'member_id'))
                    {
                        Session::setFlash('success', 'Membre réactivé avec succès.');
                        Utils::redirect('../membres');
                    }
                }
            }
        }

        
        $this->view('admin/membre', $data);
    }

    public function addenseignant() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        $data = [];
        
        $dbEmails = $this->EnseignantModel->getEmails();
        foreach ($dbEmails as $dbEmail) 
        {
            $dbEmails[] = $dbEmail->email;
        }

        $dbPhoneNumbers = $this->EnseignantModel->getPhoneNumbers();
        foreach ($dbPhoneNumbers as $dbPhoneNumber) 
        {
            $dbPhoneNumbers[] = $dbPhoneNumber->phone_number;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_admin_add_enseignant'])) 
        {
            $nom_complet = Utils::sanitize(trim($_POST['nom_complet'] ?? ''));
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));
            $phone_number = Utils::sanitize(trim($_POST['phone_number'] ?? ''));
            $biographie = Utils::sanitize(trim($_POST['biographie'] ?? ''));

            if(!$nom_complet || !$email || !$phone_number || !$biographie)
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('admin/addenseignant');
                return;
            }
            if(in_array($email, $dbEmails))
            {
                Session::setFlash('error', 'Un enseignant avec cet email existe déjà.');
                $this->view('admin/addenseignant');
                return;
            }
            if(in_array($phone_number, $dbPhoneNumbers))
            {
                Session::setFlash('error', 'Un enseignant avec ce numéro de téléphone existe déjà.');
                $this->view('admin/addenseignant');
                return;
            }

            $enseignantId = Utils::generateUuidV4();
            $tokenid = Utils::generateUuidV4();
            $token = Utils::generateToken(60);
            $tokenStatus = ARRAY_STATUS_TOKEN[1]; // non utilisé
            $expiryDate = Utils::getExpiryDateToken();
            
            $dataAddToken = [
                'token_id'      => $tokenid,
                'token'         => $token,
                'status'        => $tokenStatus,
                'expired_at'    => $expiryDate,
                'member_id'      => $enseignantId,
            ];

            $dataAddEnseignant = [
                'enseignant_id' => $enseignantId,
                'nom_complet'   => $nom_complet,
                'email'         => $email,
                'phone_number'  => $phone_number,
                'biographie'    => $biographie,
                'token'         => $token
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
                    $this->view('admin/addenseignant', $data);
                    return;
                }
                // verif mime reel
                $mime = mime_content_type($file['tmp_name']);
                if (!in_array($mime, $allowedTypes))
                {
                    Session::setFlash('error', "Format du fichier non autorisé ou mauvais format du fichier autorisé.");
                    $this->view('admin/addenseignant', $data);
                    return;
                }
                $pathDossier = $this->EnseignementModel->cheminDossierPdf($enseignantId, "enseignants");
                $nomFichier = str_replace(' ', '_', $nom_complet) .'.'. $ext;
                $fichierPath = $pathDossier ."/". $nomFichier;
                $uploadPath = BASE_PATH . $fichierPath;

                if(!is_dir($pathDossier)) {
                    if(!mkdir($pathDossier, 0777, true)) 
                    {
                        Session::setFlash('error', "Une erreur est survenue. veuillez réessayez plutard.");
                        $this->view('admin/addenseignant',  $data);
                        return;
                    }
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath))
                {
                    if(file_exists($uploadPath) && filesize($uploadPath) > 0) 
                    {
                        $dataAddEnseignant['path_profile']  = $fichierPath;
                        $resultUpload = true;                   
                    }

                } else {
                    Session::setFlash('error', "Impossible d'enregistrer le document.");
                    $this->view('admin/addenseignant', ['data' => $data]);
                    return;
                }
            }

            if($resultUpload)
            {
                if($this->EnseignantModel->insert($dataAddEnseignant) && $this->TokensModel->insert($dataAddToken))
                {
                    // envoi de mail de notification
                    $lien = SITE_URL . '/auth/esgnt/' . $enseignantId . '?tk=' . $tokenid;
                    ob_start();
                    include APP_PATH . 'templates/email/add_enseignant.php';
                    $messageBody = ob_get_clean();

                    if($this->sendEmailModel->sendEmail(
                        $email, 
                        'Invitation de devenir enseignant - '. SITE_NAME, 
                        $messageBody
                    )) 
                    {
                        Session::setFlash('success', 'Un email d\'invitation a été envoyé à l\'enseignant.');
                        Utils::redirect('enseignants');
                    }
                } else {
                    Session::setFlash('error', "Une erreur est survenue lors de l'ajout de l'enseignant.");
                    $this->view('admin/addenseignant', $data);
                    return;
                }
            } else {
                Session::setFlash('error', "Veuillez ajouter une photo de profil pour l'enseignant.");
                $this->view('admin/addenseignant', $data);;
                return;
            }
        }

        $this->view('admin/addenseignant');
    }

    public function vwenseignant($enseignantId) 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';        

        $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
        if(!$Enseignant) {
            Utils::redirect('../enseignants');
            return;
        }

        $data = [
            'Enseignant' => $Enseignant,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_enpause'])) 
        {
            if(isset($_POST['cllil_enseignant_enpause'])) 
            {
                $updateDataEnseignant = [
                    'enseignant_id' => $enseignantId,
                    'status' => ARRAY_STATUS_ENSEIGNANT[2]
                ];
                if($this->EnseignantModel->update($updateDataEnseignant, 'enseignant_id'))
                {
                    Session::setFlash('success', "Enseignant $Enseignant->nom_complet mis en pause avec succès.");
                    Utils::redirect('../enseignants');
                    // envoi de mail de notification
                    // $lien_activation = SITE_URL . '/membre/updt_pswd/' . $membreId;
                    // ob_start();
                    // include APP_PATH . 'templates/email/integration_active.php';
                    // $messageBody = ob_get_clean();

                    // if($this->sendEmailModel->sendEmail(
                    //     $Membre->email, 
                    //     'Intégration  - '. SITE_NAME, 
                    //     $messageBody
                    // )) 
                    // {
                    //     Session::setFlash('success', 'Membre approuvé avec succès.');
                    //     Utils::redirect('../membres');
                    // }
                } 
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_active'])) 
        {
            if(isset($_POST['cllil_enseignant_active'])) 
            {
                $updateDataEnseignant = [
                    'enseignant_id' => $enseignantId,
                    'status' => ARRAY_STATUS_ENSEIGNANT[0]
                ];
                if($this->EnseignantModel->update($updateDataEnseignant, 'enseignant_id'))
                {
                    Session::setFlash('success', "Enseignant $Enseignant->nom_complet réactiver avec succès.");
                    Utils::redirect('../enseignants');
                    // envoi de mail de notification
                    // $lien_activation = SITE_URL . '/membre/updt_pswd/' . $membreId;
                    // ob_start();
                    // include APP_PATH . 'templates/email/integration_active.php';
                    // $messageBody = ob_get_clean();

                    // if($this->sendEmailModel->sendEmail(
                    //     $Membre->email, 
                    //     'Intégration  - '. SITE_NAME, 
                    //     $messageBody
                    // )) 
                    // {
                    //     Session::setFlash('success', 'Membre approuvé avec succès.');
                    //     Utils::redirect('../membres');
                    // }
                } 
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_delete'])) 
        {
            if(isset($_POST['cllil_enseignant_delete'])) 
            {
                if($this->EnseignantModel->delete($enseignantId))
                {
                    Session::setFlash('success', "Enseignant $Enseignant->nom_complet supprimé avec succès.");
                    Utils::redirect('../enseignants');
                    // envoi de mail de notification
                    // $lien_activation = SITE_URL . '/membre/updt_pswd/' . $membreId;
                    // ob_start();
                    // include APP_PATH . 'templates/email/integration_active.php';
                    // $messageBody = ob_get_clean();

                    // if($this->sendEmailModel->sendEmail(
                    //     $Membre->email, 
                    //     'Intégration  - '. SITE_NAME, 
                    //     $messageBody
                    // )) 
                    // {
                    //     Session::setFlash('success', 'Membre approuvé avec succès.');
                    //     Utils::redirect('../membres');
                    // }
                } 
            }
        }

        $this->view('admin/vwenseignant', $data);
    }

    public function enseignants() 
    {
        Auth::requireLogin('admin');
        $cacheKey = 'admin_administraction';

        $allEnseignants = $this->EnseignantModel->all();
        
        $data = [
            'allEnseignants' => $allEnseignants,
        ];

        $this->view('admin/enseignants', $data);
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
