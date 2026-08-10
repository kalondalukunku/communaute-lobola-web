<?php
class HelpController extends Controller
{
    public function index()
    {
        $data = [
            'title' => SITE_NAME . ' | Centre d’aide',
            'description' => 'Découvrez les tutoriels pas à pas pour résoudre vos problèmes.'
        ];

        $this->view('help/index', $data);
    }

    public function integration()
    {
        $data = [
            'title' => SITE_NAME . ' | Tutoriel - Intégration',
            'description' => 'Guide pas à pas pour faire son intégration.'
        ];

        $this->view('help/integration', $data);
    }

    public function engagement()
    {
        $data = [
            'title' => SITE_NAME . ' | Tutoriel - Engagement',
            'description' => 'Découvrez comment valider votre engagement.'
        ];

        $this->view('help/engagement', $data);
    }

    public function reabonnement()
    {
        $data = [
            'title' => SITE_NAME . ' | Tutoriel - Réabonnement',
            'description' => 'Apprenez à renouveler votre abonnement.'
        ];

        $this->view('help/reabonnement', $data);
    }

    public function motdepasse()
    {
        $data = [
            'title' => SITE_NAME . ' | Tutoriel - Mot de passe',
            'description' => 'Récupérez l’accès à votre compte.'
        ];

        $this->view('help/motdepasse', $data);
    }

    public function paiement()
    {
        $data = [
            'title' => SITE_NAME . ' | Tutoriel - Paiement',
            'description' => 'Résolvez les problèmes liés à vos paiements.'
        ];

        $this->view('help/paiement', $data);
    }
}
