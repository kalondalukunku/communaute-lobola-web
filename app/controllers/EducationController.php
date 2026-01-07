<?php
require_once APP_PATH . 'helpers/SendMail.php';
require_once APP_PATH . 'helpers/Logger.php';

class EducationController extends Controller 
{    
    private $userModel;   
    private $DocumentModel;
    private $PersonnelModel;
    private $RoleModel;
    private $loggerModel;
    private $SendMailModel;

    public function __construct()
    {
        // Auth::requireLogin('user'); // protéger toutes les pages
        // Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->loggerModel = new Logger();
        $this->SendMailModel = new SendMail();
 
    }

    public function index() 
    {

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
        ];

        $this->view('us/index', $data);
    }

    public function add() 
    {
        $cacheKey = 'user_connexion';
        
        //recuperer tous les emails
        $rolesDb = $this->RoleModel->getElement('nom_role');
        foreach ($rolesDb as $r) 
        {
            $rolesDbs[] = $r->nom_role;
        }
        $dbEmails = $this->userModel->getEmails();
        foreach ($dbEmails as $dbEmail) 
        {
            $dbEmails[] = $dbEmail->email;
        }
        $data = [
            'rolesDbs' => $rolesDbs
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mosali_add_us']))
        {
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));
            $role = Utils::sanitize(trim($_POST['role'] ?? ''));
            $matricule = Utils::sanitize(trim($_POST['matricule'] ?? ''));
            
            if($email === '' || $role === '' || $matricule === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('us/add',  $data);
                return;
            }

            $roleDb = $this->RoleModel->findWhere('nom_role', $role);
            $Personnel = $this->PersonnelModel->getPersonnel('matricule', $matricule);

            if(!$Personnel) 
            {
                Session::setFlash('error', "Entrée correctement le matricule du personnel.");
                $this->view('us/add',  $data);
                return;
            }
            if(!in_array($role, $rolesDbs) || !$roleDb) 
            {
                Session::setFlash('error', "Entrée correctement le rôle de l'utilisateur.");
                $this->view('us/add',  $data);
                return;
            }
            //verifier si l'email existe deja
            if($Personnel->email !== null && $Personnel->email !== $email)
            {
                Session::setFlash('error', "L'adresse mail saisi ne correspond pas avec celui du personnel.");
                $this->view('us/add',  $data);
                return;
            }
            if(in_array($email, $dbEmails))
            {
                Session::setFlash('error', "Cette adresse mail existe déjà.");
                $this->view('us/add',  $data);
                return;
            }

            $userId = Utils::generateUuidV4();
            $token = Utils::generateToken();
            $token_expiration = Utils::calculerDateFuture(24);

            $dataAddUser = [
                'user_id'               => $userId,
                'email'                 => $email,
                'matricule_personnel'   => $matricule,
                'role_id'               => $roleDb->role_id,
                'token'                 => $token,
                'token_expiration'      => $token_expiration,
            ];
            if($this->userModel->insert($dataAddUser))
            {
                $lien_activation = BASE_URL . '/auth/uatvt?tk=' . $token;
                ob_start();
                include APP_PATH . 'templates/email/activationCompte.php';
                $messageBody = ob_get_clean();

                if($this->SendMailModel->sendEmail(
                    $email, 
                    'Activation de votre compte sur la plateforme de gestion du personnel '. SITE_NAME, 
                    $messageBody
                )) {
                    Session::setFlash('success', 'Utilisateur ajouté avec succès.');
                    Utils::redirect('../us');
                }

                // $dataLogs = [
                //     'user_id'       => $userId,
                //     'action'        => "Ajout d'un utilisateur réussi",
                //     'courier_id'    => null,
                //     'resultat'      => '1',
                //     'date_action'   => date('Y-m-d H:i:s'),
                // ];
                // if ($this->loggerModel->addLog($dataLogs)) 
                // {
                //     Session::setFlash('success', 'Utilisateur ajouté avec succès.');
                //     Utils::redirect('../../user');
                // }
            }
            // else {
            //     $dataLogs = [
            //         'user_id'       => $userId,
            //         'action'        => "Echec de l'ajout d'un utilisateur",
            //         'courier_id'    => null,
            //         'resultat'      => '0',
            //         'date_action'   => date('Y-m-d H:i:s'),
            //     ];
            //     $this->loggerModel->addLog($dataLogs);
            //     Session::setFlash('error', "Echec de l'ajout d'un utilisateur");
            //     Utils::redirect('../../user');
            // }
        }

        $this->view('us/add', $data);
    }

    public function show($userId) 
    {
        $cacheKey = 'user_connexion';
        
        // $user = $this->userModel->getUsers('user_id', $userId);
        // if(!$user) Utils::redirect("/us");

        // $rolesDb = $this->RoleModel->getElement('nom_role');
        // foreach ($rolesDb as $r) 
        // {
        //     $rolesDbs[] = $r->nom_role;
        // }

        $data = [
            // 'user' => $user,
            // 'rolesDbs' => $rolesDbs
        ];

        $this->view('education/show', $data);
    }
}
