<?php
class RessourcesController extends Controller
{
    public function mavalle()
    {
        $data = [
            'title' => SITE_NAME . ' | Excursion spirituel au Lac Ma Vallée',
            'description' => 'Découvrez les tutoriels pas à pas pour résoudre vos problèmes.'
        ];

        $this->view('ressources/mavalle', $data);
    }
}
