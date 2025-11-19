<?php
require_once APP_PATH .'models/Courier.php';

class HomeController extends Controller {

    public function index()
    {
        Auth::requireLogin('user');
        Auth::isRole(ARRAY_ROLE_USER[0]);
        
        if ($_GET['url'] !== "") {
            $this->view('errors/404');
            return;
        }

        $Courier = new Courier();
        $allCouriers = $Courier->all();
        $numero = 1;
        $nbrCourierNoTraite = $Courier->nbrCourierTempsEcoule();

        $data = [
            'title' => SITE_NAME .' | Acceuil',
            'description' => 'Lorem jfvbjfbrfbhrfvbhkrfbhk rvirvjrljlrrjrjl zfeuhzuz',
            'allCouriers' => $allCouriers,
            'numero' => $numero,
            'nbrCourierNoTraite' => $nbrCourierNoTraite,
            'Courier' => $Courier,
        ];
        $this->view('home/index', $data);
    }

    public function logout() {
        Session::destroy();
        Utils::redirect('login');
    }
}