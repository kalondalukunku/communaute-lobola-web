<?php
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Courier.php';

class SupervisorController extends Controller 
{    
    private $userModel;   
    private $courierModel;

    private $nbrCourierNoTraite;

    public function __construct()
    {
        Auth::requireLogin('user'); // protéger toutes les pages
        Auth::isRole(ARRAY_ROLE_USER[1]);
        
        $this->userModel = new User();
        $this->courierModel = new Courier();
        
        $this->nbrCourierNoTraite = $this->courierModel->nbrCourierTempsEcoule();   
    }

    public function index() 
    {
        $cacheKey = 'user_administraction';
        $userSupervisor = $this->userModel->getUsers('user_id', $_SESSION[SITE_NAME_SESSION_USER]['user_id']);
        $allCouriers = $this->courierModel->all();
        $numero = 1;

        $data = [
            'nbrCourierNoTraite' => $this->nbrCourierNoTraite,
            'Couriers' => $this->courierModel,
            'userSupervisor' => $userSupervisor,
            'allCouriers' => $allCouriers,
            'numero' => $numero,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bukus_user_supervisor_read_courier']))
        {
            $pathFileEnc = htmlspecialchars_decode(Utils::sanitize($_POST['link_courier']));
            $pathFilePdf = FILE_VIEW_FOLDER_PATH ."file.pdf";

            $res = $this->courierModel->dechiffreePdf($pathFilePdf,$pathFileEnc, CLEF_CHIFFRAGE_PDF);

            if($res === true)
            {
                // echo "<script>
                //     window.open('read?fl=file.pdf','_blank');
                //     window.location.href = 'superviseur';
                // </script>";
                Utils::redirect('courier/view_pdf?fl=file.pdf');

            }
        }

        $this->view('user/supervisor/index', $data);
    }
}
