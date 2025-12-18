<?php
    require_once APP_PATH . 'models/Personnel.php';

class HomeController extends Controller {
    
    private $PersonnelModel;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        // Auth::isRole(ARRAY_ROLE_USER[0]);
        
        $this->PersonnelModel = new Personnel(); 
    }

    public function index()
    {        
        // if ($_GET['url'] !== "") {
        //     $this->view('errors/404');
        //     return;
        // }

        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $search = ($query !== '') ? basename($query) : null;
        $sttGet = basename($_GET['stt'] ?? '');
        $psnPg = (int) basename($_GET['page'] ?? 1);

        // var_dump($allPersonnels['personnels']); die;

        $stt = "";
        
        if($sttGet === 'actif' || !isset($_GET['stt']))
            $stt = ARRAY_PERSONNEL_STATUT_EMPLOI[1];
        elseif($sttGet === 'conge')
            $stt = ARRAY_PERSONNEL_STATUT_EMPLOI[2];
        elseif($sttGet === 'retraite')
            $stt = ARRAY_PERSONNEL_STATUT_EMPLOI[3];
        elseif($sttGet === 'termine')
            $stt = ARRAY_PERSONNEL_STATUT_EMPLOI[4];
        elseif($sttGet === 'inactif')
            $stt = ARRAY_PERSONNEL_STATUT_EMPLOI[0];

        $results = $this->PersonnelModel->allPersonnelsWithService($stt, $psnPg, $this->PersonnelModel->default_per_page, $search);
        $allPersonnels = $results['personnels'];
        $totalrecords = $results['total_records'];
        $currentPage = $results['current_page'];
        $parPage = $results['per_page'];
        $totalPages = $results['total_pages'];

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'allPersonnels' => $allPersonnels,
            'totalrecords' => $totalrecords,
            'currentPage' => $currentPage,
            'parPage' => $parPage,
            'totalPages' => $totalPages,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}