<?php
    require_once APP_PATH . 'models/Engagement.php';
    require_once APP_PATH . 'models/Membre.php';
    require_once APP_PATH . 'models/Serie.php';
    require_once APP_PATH . 'models/Enseignement.php';
    require_once APP_PATH . 'models/Api.php';
    require_once APP_PATH . 'models/Vues.php';
    require_once APP_PATH . 'models/Appels.php';

class ApiController extends Controller {
    
    private $MembreModel;
    private $EngagementModel;
    private $SerieModel;
    private $EnseignementModel;
    private $ApiModel;
    private $VuesModel;
    private $AppelsModel;

    public function __construct()
    {
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->SerieModel = new Serie();
        $this->EnseignementModel = new Enseignement();
        $this->ApiModel = new Api();
        $this->VuesModel = new Vues();
        $this->AppelsModel = new Appels();
    }

    public function enseignement_state_view($enseignementId) 
    {
        
        header('Content-Type: application/json');
        try {
            $enseignement = $this->EnseignementModel->find($enseignementId);
            $serie = $this->SerieModel->find($enseignement->serie_id);
            $new_state = $enseignement->is_active == 1 ? 0 : 1;

            var_dump($serie); die;
            
            $updateSuccess = $this->EnseignementModel->update(['is_active' => $new_state, 'enseignement_id' => $enseignementId], 'enseignement_id');
            if($serie->is_active ==! 1) $this->SerieModel->update(['is_active' => 1, 'serie_id' => $enseignement->serie_id]);
            if($new_state === 0 && $serie->enseignements_count == 0) $this->SerieModel->update(['is_active' => 0, 'serie_id' => $enseignement->serie_id]);

            if ($updateSuccess) {
                echo json_encode([
                    'status' => 'success',
                    'new_state' => $new_state,
                    'message' => 'État mis à jour avec succès'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour en base']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue.']);
            return;
        }
    }

    public function membres($apiKey)
    {
        if($apiKey === API_KEY_CALL_APP) echo $this->ApiModel->getAllUsersAsJson();
        // header("Location: /www.google.com");
    }

    public function set_live_status()
    {
        // 1. Récupérer le contenu JSON envoyé par l'application Flutter
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Vérifier si la donnée 'isLive' est présente
        if (isset($data['isLive'])) {
            $isLive = $data['isLive'] ? 1 : 0;

            // Connexion BDD (Adaptez à votre instance PDO/DB)
            if($this->AppelsModel->insert(['is_live' => $isLive, 'appel_id' => 1]))
            {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'isLive' => (bool)$isLive]);
            }

        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Données isLive manquantes']);
        }
        exit;
    }

    public function get_live_status()
    {
        $result = $this->AppelsModel->getLiveStatus();

        header('Content-Type: application/json');
        echo json_encode([
            'isLive' => $result ? (bool)$result->is_live : false
        ]);
        exit;
    }
   
}