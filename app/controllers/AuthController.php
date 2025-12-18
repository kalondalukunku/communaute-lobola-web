<?php
require_once APP_PATH .'models/User.php';
require_once APP_PATH . 'helpers/Logger.php';

class AuthController extends Controller {

    private $loggerModel;
    private $userModel;
    private $sendEmailModel;

    public function __construct()
    {
        $this->loggerModel = new Logger();
        $this->userModel = new User();
        $this->sendEmailModel = new SendMail();
    }

    // public function index() 
    // {
    //     Session::start();
    //     $cacheKey = 'user_connexion';
    //     if (Session::isLogged('user')) Utils::redirect('/');

    //     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_login'])) $this->auth($_POST, $cacheKey);
    //     $this->view('auth/index');
    // }

    public function uActivation() 
    {
        Session::start();
        $cacheKey = 'user_activation';
        // if (Session::isLogged('user')) Utils::redirect('/');
        if(isset($_GET['tk'])){
            $token = $_GET['tk'];

            $user = $this->userModel->getUsers('token', $token);
            if (!$user || $user->statut === 'active') {
                Session::setFlash('error', "Lien d'activation invalide.");
                Utils::redirect('/');
                return;
            }

            $data = [
                'user' => $user,
            ];

            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_pswd_user_edit']))
            {
                $new_pswd = Utils::sanitize(trim($_POST['new_pswd'] ?? ''));
                $confirm_pswd = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

                if($new_pswd === '' || $confirm_pswd === '')
                {
                    Session::setFlash('error', 'Remplissez correctement le formulaire.');
                    $this->view('auth/uActivation',  $data);
                    return;
                }
                
                //verifier le password s'il respect la longueur de 8 0 16 ccarateres
                if(
                    !Helper::lengthValidation($new_pswd, 8, 16) || 
                    !Helper::lengthValidation($confirm_pswd, 8, 16)
                ) {
                    Session::setFlash('error', "Tous les mot de passe doit être compris entre 8 à 16 caratères.");
                    $this->view('auth/uActivation',  $data);
                    return;
                }
                if($new_pswd !== $confirm_pswd)
                {
                    Session::setFlash('error', "Les deux nouveaux mot de passe ne se correspondent pas.");
                    $this->view('auth/uActivation',  $data);
                    return;
                }

                $pswd = password_hash($new_pswd, PASSWORD_ARGON2I);

                $dataUpdate = [
                    'pswd'      => $pswd,
                    'token'     => null,
                    'statut'    => "active",
                    'user_id'   => $user->user_id,
                ];

                if($this->userModel->update($dataUpdate, 'user_id'))
                {
                    $dataLogs = [
                        'user_id'       => $user->user_id,
                        'action'        => "Activation du compte réussi",
                        'resultat'      => '1',
                        'date_action'   => date('Y-m-d H:i:s'),
                    ];
                    if($this->loggerModel->addLog($dataLogs))
                    {
                        $lien_connexion = BASE_URL . '/login';
                        ob_start();
                        include APP_PATH . 'templates/email/activationCompte2.php';
                        $messageBody = ob_get_clean();

                        if($this->sendEmailModel->sendEmail($user->email, 'Compte activé avec succès - '. SITE_NAME, $messageBody))
                        {
                            Session::setFlash('success', 'Compte activé avec succès. Veuillez vous connecter.');
                            Utils::redirect('/login');
                        } 
                        else {
                            Session::setFlash('error', "Echec de l'envoi de l'email d'activation.");
                            Utils::redirect('../sg/users');
                        }
                    } 
                    else {
                        Session::setFlash('error', "Echec de l'activation du compte.");
                        Utils::redirect('/');
                    }
                }
            }
        } else {
            Session::setFlash('error', "Lien d'activation invalide.");
            Utils::redirect('/');
        }

        $this->view('auth/uActivation');
    }
}
