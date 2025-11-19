<?php
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Courier.php';

class CouristeController extends Controller 
{    
    private $userModel;   
    private $courierModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        Auth::isRole(ARRAY_ROLE_USER[2]);
        
        $this->userModel = new User();
        $this->courierModel = new Courier();
        
        $this->nbrCourierNoTraite = $this->courierModel->nbrCourierTempsEcoule();   
    }

    public function index() 
    {
        $cacheKey = 'user_administraction';
        $userCouriste = $this->userModel->getUsers('user_id', $_SESSION[SITE_NAME_SESSION_USER]['user_id']);
        $numero = 1;

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'userCouriste' => $userCouriste,
            'numero' => $numero,
        ];

        $this->view('user/couriste/index', $data);
    }

    public function show($courierId) 
    {
        $cacheKey = 'user_couriste_show';
        $userCouriste = $this->userModel->getUsers('user_id', $_SESSION[SITE_NAME_SESSION_USER]['user_id']);
        $numero = 1;

        $courier = $this->courierModel->find($courierId);

        if (!$courier) {
            $this->view('errors/404');
            return;
        }

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'userCouriste' => $userCouriste,
            'courier' => $courier,
            'Couriers' => $this->courierModel,
            'numero' => $numero,
        ];

        $this->view('user/couriste/show', $data);
    }
}
