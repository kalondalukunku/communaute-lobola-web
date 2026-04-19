<?php 

    require_once APP_PATH . 'models/Enseignement.php';
    require_once APP_PATH . 'models/Serie.php';
    require_once APP_PATH . 'models/Vues.php';

Class MusiqueController extends Controller {

    private $EnseignementModel;
    private $SerieModel;
    private $VuesModel;

    public function __construct()
    {
        $this->EnseignementModel = new Enseignement();
        $this->SerieModel = new Serie();
        $this->VuesModel = new Vues();
    }

    
    public function index()
    {
        $data = [
            'title' => SITE_NAME .' | Musique',
            'description' => 'Tous les musiques de la Communauté LOBOLA',
        ];
        $this->view('musique/index', $data);
    }
}