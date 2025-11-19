<?php
require_once APP_PATH . 'models/Admin.php';
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Courier.php';
require_once APP_PATH . 'helpers/Logger.php';

class UserController extends Controller 
{    
    private $userModel;   
    private $courierModel;
    private $loggerModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->userModel = new User();
        $this->courierModel = new Courier();
        $this->loggerModel = new Logger();
        
        $this->nbrCourierNoTraite = $this->courierModel->nbrCourierTempsEcoule();   
    }

    public function index() 
    {
        $cacheKey = 'user_administraction';
        $allUsers = $this->userModel->getUsersCouristeSuperviseur();
        $numero = 1;

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'allUsers' => $allUsers,
            'numero' => $numero,
        ];

        $this->view('user/index', $data);
    }

    public function add() 
    {
        $cacheKey = 'user_connexion';
        $userId = uniqid();
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

        $this->view('user/add', $data);
    }

    public function edit($userId) 
    {
        $cacheKey = 'user_connexion';
        
        $user = $this->userModel->getUsers('user_id', $userId);

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'user' => $user,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_edit']))
        {
            $nom = Utils::sanitize(trim($_POST['nom'] ?? ''));
            $email = Utils::sanitize(trim($_POST['email'] ?? ''));

            if($nom !== $user->nom || $email !== $user->email) 
            {
                $dataEditUser = [
                    'nom'           => $nom,
                    'email'         => $email,
                    'user_id'       => $userId,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
                if($this->userModel->update($dataEditUser, 'user_id'))
                {
                    $dataLogs = [
                        'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => "Modification d'un utilisateur réussi",
                        'courier_id'    => null,
                        'resultat'      => '1',
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    if ($this->loggerModel->addLog($dataLogs)) 
                    {
                        Session::setFlash('success', 'Utilisateur modifié avec succès.');
                        Utils::redirect('../../user');
                    }
                }
                else {
                    $dataLogs = [
                        'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                        'action'        => "Echec de modification d'un utilisateur",
                        'courier_id'    => null,
                        'resultat'      => '0',
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    $this->loggerModel->addLog($dataLogs);
                    Session::setFlash('error', "Echec de modification d'un utilisateur");
                    Utils::redirect('../../user');
                }
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_delete']))
        {
            if($this->userModel->delete($userId))
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Suppression d'un utilisateur réussi",
                    'courier_id'    => null,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Utilisateur supprimé avec succès.');
                    Utils::redirect('../../user');
                }
            } 
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec de Suppression d'un utilisateur",
                    'courier_id'    => null,
                    'resultat'      => '0',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec de Suppression d'un utilisateur");
                Utils::redirect('../../user');
            }
        }

        $this->view('user/edit', $data);
    }

    public function editpswd() 
    {
        $cacheKey = 'user_connexion';
        
        $userId = $_SESSION[SITE_NAME_SESSION_USER]['user_id'];
        $user = $this->userModel->getUsers('user_id', $userId);

        if(!$user) Utils::redirect(RETOUR_EN_ARRIERE);
        
        $old_password = $user->pswd;

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'user' => $user,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_pswd_user_edit']))
        {
            $old_pswd = Utils::sanitize(trim($_POST['old_pswd'] ?? ''));
            $new_pswd = Utils::sanitize(trim($_POST['new_pswd'] ?? ''));
            $confirm_pswd = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

            if($old_pswd === '' || $new_pswd === '' || $confirm_pswd === '')
            {
                Session::setFlash('error', 'Remplissez correctement le formulaire.');
                $this->view('user/editpswd',  $data);
                return;
            }
            
            //verifier le password s'il respect la longueur de 8 0 16 ccarateres
            if(
                !Helper::lengthValidation($old_pswd, 8, 16) || 
                !Helper::lengthValidation($new_pswd, 8, 16) || 
                !Helper::lengthValidation($confirm_pswd, 8, 16)
            ) {
                Session::setFlash('error', "Tous les mot de passe doit être compris entre 8 à 16 caratères.");
                $this->view('user/editpswd',  $data);
                return;
            }
            if($new_pswd !== $confirm_pswd)
            {
                Session::setFlash('error', "Les deux nouveaux mot de passe ne se correspondent pas.");
                $this->view('user/editpswd',  $data);
                return;
            }
            if(!password_verify($old_pswd, $old_password)) 
            {
                Session::setFlash('error', "Mot de passe actuel incorrect.");
                $this->view('user/editpswd',  $data);
                return;
            }
            elseif (password_verify($new_pswd, $old_password))
            {
                Session::setFlash('error', "Insérez un nouveau mot de passe.");
                $this->view('user/editpswd',  $data);
                return;
            }

            $pswd = password_hash($new_pswd, PASSWORD_ARGON2I);

            $dataEditUser = [
                    'pswd'          => $pswd,
                    'user_id'       => $userId
            ];
            if($this->userModel->update($dataEditUser, 'user_id'))
            {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Modification mot de passe d'un utilisateur réussi",
                    'courier_id'    => null,
                    'resultat'      => '1',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                if ($this->loggerModel->addLog($dataLogs)) 
                {
                    Session::setFlash('success', 'Utilisateur modifié avec succès.');
                    Utils::redirect('/');
                }
            }
            else {
                $dataLogs = [
                    'user_id'       => $_SESSION[SITE_NAME_SESSION_USER]['user_id'],
                    'action'        => "Echec de modification mot de passe d'un utilisateur",
                    'courier_id'    => null,
                    'resultat'      => '0',
                    'date_action'   => date('Y-m-d H:i:s'),
                ];
                $this->loggerModel->addLog($dataLogs);
                Session::setFlash('error', "Echec de modification mot de passe d'un utilisateur");
                Utils::redirect('/');
            }
            
        }

        $this->view('user/editpswd', $data);
    }
}
