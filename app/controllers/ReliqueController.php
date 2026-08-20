<?php

class ReliqueController extends Controller
{
    public function index()
    {
        Auth::requireLogin(['membre','enseignant']);

        $data = [
            'title' => SITE_NAME . ' | Reliques et Objets Cultuels',
            'description' => 'Les reliques et objets cultuels de la tradition spirituelle Kamit.',
        ];

        $this->view('relique/index', $data);
    }
}
