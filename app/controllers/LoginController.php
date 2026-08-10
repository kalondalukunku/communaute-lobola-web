<?php
require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Payment.php';

class LoginController extends Controller {

    private $MembreModel;
    private $PaymentModel;

    public function __construct()
    {        
        if (Session::isLogged('membre')) Utils::redirect('membre/profile/'. Session::get('membre')['member_id']);
        $this->MembreModel = new Membre();
        $this->PaymentModel = new Payment();
    }

    public function index() 
    {
        Session::start();
        $cacheKey = 'membre_connexion';

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_login'])) $this->auth($_POST, $cacheKey);
        $this->view('login/index');
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
            $this->view('login/index', ['data' => $data]);
            return;
        }

        $Membre = $this->MembreModel->loginMember($connect, $cacheKey);
        if(!$Membre) {
            Session::setFlash('error', 'Adresse mail ou numéro de téléphone incorrect.');
            $this->view('login/index', ['data' => $data]);
            return;
        }
        $paiement = $this->PaymentModel->getPayment($Membre->member_id, $Membre->engagement_id);

        if($Membre->status !== ARRAY_STATUS_MEMBER[2]) {
            if($Membre->status !== ARRAY_STATUS_MEMBER[0])
            {
                Session::setFlash('error', 'Votre compte n\'est pas activé. Contactez l\'administrateur.');
                $this->view('login/index', ['data' => $data]);
                return;
            }
            
        }

        // var_dump($paiement->payment_prochain); die;

        if ($Membre && password_verify($pswd, $Membre->pswd)) 
        {
            if($paiement->payment_prochain < date('Y-m-d')) $this->MembreModel->update(['bolokele' => '2', 'member_id' => $Membre->member_id]);
            $Membre = $this->MembreModel->loginMember($connect, $cacheKey);

            Cache::set($cacheKey, $Membre);
            Session::set('membre', $Membre);
            Session::setFlash('success', 'Connecté.');
            Utils::redirect('/');
        } else {
            Session::setFlash('error', 'Mot de passe incorrect.');
            $this->view('login/index', ['data' => $data]);
            return;
        }
    }
}
