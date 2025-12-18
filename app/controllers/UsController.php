<?php
require_once APP_PATH . 'models/Admin.php';
require_once APP_PATH . 'models/User.php';
require_once APP_PATH .'models/Document.php';
require_once APP_PATH . 'helpers/Logger.php';

class UsController extends Controller 
{    
    private $userModel;   
    private $DocumentModel;
    private $loggerModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->userModel = new User();
        $this->DocumentModel = new Document();
        $this->loggerModel = new Logger();
 
    }

    public function index() 
    {
        $cacheKey = 'user_administraction';
        // $allUsers = $this->userModel->getUsersCouristeSuperviseur();

        $data = [
            // 'allUsers' => $allUsers,
        ];

        $this->view('us/index', $data);
    }

    public function add() 
    {
        $cacheKey = 'user_connexion';
        $userId = Utils::generateUuidV4();
        $couristeUsers = $this->userModel->getUsersCouristeSuperviseur();
        $nbrCouristeUsers = count($couristeUsers);
        $couristeUsersText = '';
        
        $superviseurUsers = $this->userModel->getAllDataWithArgs('role','superviseur');
        $nbrSuperviseurUsers = count($superviseurUsers);
        
        //recuperer tous les emails
        $dbEmails = $this->userModel->getEmails();
        foreach ($dbEmails as $dbEmail) 
        {
            $dbEmails[] = $dbEmail->email;
        }

        if($nbrCouristeUsers >= NBR_LIMITE_USER_COURISTE) {
            $couristeUsersText = 'disabled';
            Session::setFlash('error', "Vous avez déjà atteint le nombre limite des couristes.");
        }

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'nbrCouristeUsers' => $nbrCouristeUsers,
            'couristeUsersText' => $couristeUsersText,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_add']))
        {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            $role = Utils::sanitize(trim($_POST['role'] ?? ''));
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));
            $pswd = Utils::sanitize(trim($_POST['pswd'] ?? ''));
            
            if($nom === '' || $role === '' || $email === '' || $pswd === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('user/add',  $data);
                return;
            }
            if($role === ARRAY_ROLE_USER[1] && $nbrSuperviseurUsers > 0)
            {
                Session::setFlash('error', "Limite de nombre de superviseur atteint (1).");
                $this->view('user/add',  $data);
                return;
            }
            if(!in_array($role, ARRAY_ROLE_USER)) 
            {
                Session::setFlash('error', "Entrée correctement le rôle de l'utilisateur.");
                $this->view('user/add',  $data);
                return;
            }
            //verifier si l'email existe deja
            if(in_array($email, $dbEmails))
            {
                Session::setFlash('error', "Cette adresse mail existe déjà.");
                $this->view('user/add',  $data);
                return;
            }
            //verifier le password s'il respect la longueur de 8 0 16 ccarateres
            if(!Helper::lengthValidation($pswd, 8, 16)) 
            {
                Session::setFlash('error', "Le mot de passe doit être compris entre 8 à 16 caratères.");
                $this->view('user/add',  $data);
                return;
            }

            $password = password_hash($pswd, PASSWORD_ARGON2I);

            $dataAddUser = [
                'user_id'       => $userId,
                'nom'           => $nom,
                'email'         => $email,
                'role'          => $role,
                'pswd'          => $password,
            ];
            if($this->userModel->create($dataAddUser))
            {
                $dataLogs = [
                    'user_id'       => $userId,
                    'action'        => "Ajout d'un utilisateur réussi",
                    'courier_id'    => null,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Utilisateur ajouté avec succès.');
                    Utils::redirect('../../user');
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $userId,
                    'action'        => "Echec de l'ajout d'un utilisateur",
                    'courier_id'    => null,
                    'resultat'      => '0',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec de l'ajout d'un utilisateur");
                Utils::redirect('../../user');
            }
        }

        $this->view('us/add', $data);
    }

    public function shw($userId) 
    {
        $cacheKey = 'user_connexion';
        
        // $user = $this->userModel->getUsers('user_id', $userId);

        $data = [
            // 'user' => $user,
        ];

        $this->view('us/shw', $data);
    }
}
