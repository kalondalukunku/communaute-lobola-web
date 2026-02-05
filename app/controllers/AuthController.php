<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Enseignant.php';
require_once APP_PATH . 'models/Tokens.php';
require_once APP_PATH . 'helpers/SendMail.php';

class AuthController extends Controller {

    private $TokensModel;
    private $MembreModel;
    private $EnseignantModel;
    private $sendEmailModel;

    public function __construct()
    {
        $this->TokensModel = new Tokens();
        $this->MembreModel = new Membre();
        $this->EnseignantModel = new Enseignant();
        $this->sendEmailModel = new SendMail();
    }

    public function index() 
    {
        Utils::redirect('/');
    }

    public function uatvt($membreId) 
    {
        Session::start();
        $cacheKey = 'user_activation';
        // if (Session::isLogged('user')) Utils::redirect('/');
        if(isset($_GET['tk'])){
            $tokenId = $_GET['tk'];

            $Membre = $this->MembreModel->findByMemberId($membreId);
            $tokenDb = $this->TokensModel->find($membreId, $tokenId);

            if(!$Membre || !$tokenDb || $tokenDb->token_id !== $tokenId || $tokenDb->user_id !== $membreId || $Membre->token !== $tokenDb->token || $tokenDb->status !== ARRAY_STATUS_TOKEN[1] || Utils::isTokenExpired($tokenDb->expired_at)) 
            {
                Session::setFlash('error', "Lien d'activation invalide ou expiré.");
                Utils::redirect('/login');
            }

            $data = [
                'membre' => $Membre,
            ];

            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_updt_pswd']))
            {
                $pswd = Utils::sanitize(trim($_POST['pswd'] ?? ''));
                $confirm_pswd = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

                if(!$pswd || !$confirm_pswd)
                {
                    Session::setFlash('error', 'Remplissez correctement le formulaire.');
                    $this->view('auth/uatvt',  $data);
                    return;
                }

                if(strlen($pswd) < 8)
                {
                    Session::setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                    $this->view('auth/uatvt',  $data);
                    return;
                }

                if($pswd !== $confirm_pswd)
                {
                    Session::setFlash('error', 'Les 2 mots de passe ne se correspondent pas.');
                    $this->view('auth/uatvt',  $data);
                    return;
                }

                if(password_verify($pswd, $Membre->pswd))
                {
                    Session::setFlash('error', 'Le nouveau mot de passe doit être différent de l\'ancien.');
                    $this->view('auth/uatvt',  $data);
                    return;
                }

                $hashedPassword = password_hash($pswd, PASSWORD_ARGON2I);

                $dataUpdateMembre = [
                    'status'        => ARRAY_STATUS_MEMBER[2],
                    'pswd'          => $hashedPassword,
                    'token'         => null,
                    'member_id'     => $membreId
                ];

                $dataUpdateToken = [
                    'token_id'  => $tokenDb->token_id,
                    'token'     => NULL,
                    'status'    => ARRAY_STATUS_TOKEN[0], // utilisé
                ];

                if($this->MembreModel->update($dataUpdateMembre, 'member_id') && 
                   $this->TokensModel->update($dataUpdateToken, 'token_id'))
                {
                    $MembreLog = $this->MembreModel->findByMemberId($membreId);
                    Session::set('membre', $MembreLog);
                    Session::setFlash('success', 'Mot de passe mis à jour avec succès. Vous pouvez maintenant vous connecter.');
                    Utils::redirect('../../login');
                }
                else {
                    Session::setFlash('error', "Une erreur est survenue lors de la mise à jour du mot de passe. Veuillez réessayez plutard.");
                    $this->view('auth/uatvt',  $data);
                    return;
                }
            
            }
        } else {
            Session::setFlash('error', "Lien d'activation invalide.");
            Utils::redirect('/');
        }

        $this->view('auth/uatvt');
    }

    public function esgnt($enseignantId) 
    {
        Session::start();
        $cacheKey = 'enseignant_activation';
        
        if(isset($_GET['tk'])){
            $tokenId = $_GET['tk'];

            $Enseignant = $this->EnseignantModel->findByEnseignantId($enseignantId);
            $tokenDb = $this->TokensModel->find($enseignantId, $tokenId);

            if(!$Enseignant || !$tokenDb || $tokenDb->token_id !== $tokenId || $tokenDb->user_id !== $enseignantId || $Enseignant->token !== $tokenDb->token || $tokenDb->status !== ARRAY_STATUS_TOKEN[1] || Utils::isTokenExpired($tokenDb->expired_at)) 
            {
                Session::setFlash('error', "Lien d'activation invalide ou expiré.");
                Utils::redirect('/');
            }

            $data = [
                'enseignant' => $Enseignant,
            ];

            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_enseignant_add_pswd']))
            {
                $pswd = Utils::sanitize(trim($_POST['pswd'] ?? ''));
                $confirm_pswd = Utils::sanitize(trim($_POST['confirm_pswd'] ?? ''));

                if(!$pswd || !$confirm_pswd)
                {
                    Session::setFlash('error', 'Remplissez correctement le formulaire.');
                    $this->view('auth/esgnt',  $data);
                    return;
                }

                if(strlen($pswd) < 8)
                {
                    Session::setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                    $this->view('auth/esgnt',  $data);
                    return;
                }

                if($pswd !== $confirm_pswd)
                {
                    Session::setFlash('error', 'Les 2 mots de passe ne se correspondent pas.');
                    $this->view('auth/esgnt',  $data);
                    return;
                }

                if(password_verify($pswd, $Enseignant->pswd))
                {
                    Session::setFlash('error', 'Le nouveau mot de passe doit être différent de l\'ancien.');
                    $this->view('auth/esgnt',  $data);
                    return;
                }

                $hashedPassword = password_hash($pswd, PASSWORD_ARGON2I);

                $dataUpdateEnseignant = [
                    'status'        => ARRAY_STATUS_ENSEIGNANT[0],
                    'pswd'          => $hashedPassword,
                    'token'         => null,
                    'enseignant_id'     => $enseignantId    
                ];

                $dataUpdateToken = [
                    'token_id'  => $tokenDb->token_id,
                    'token'     => NULL,
                    'status'    => ARRAY_STATUS_TOKEN[0], // utilisé
                ];

                if($this->EnseignantModel->update($dataUpdateEnseignant, 'enseignant_id') && 
                   $this->TokensModel->update($dataUpdateToken, 'token_id'))
                {
                    // $EnseignantLog = $this->EnseignantModel->findByEnseignantId($enseignantId);
                    // Session::set('enseignant', $EnseignantLog);
                    Session::setFlash('success', 'Mot de passe mis à jour avec succès. Vous pouvez maintenant vous connecter.');
                    Utils::redirect('../../enseignant');
                }
                else {
                    Session::setFlash('error', "Une erreur est survenue lors de la mise à jour du mot de passe. Veuillez réessayez plutard.");
                    $this->view('auth/esgnt',  $data);
                    return;
                }
            
            }
        } else {
            Session::setFlash('error', "Lien d'activation invalide.");
            Utils::redirect('/');
        }

        $this->view('auth/esgnt');
    }
}
