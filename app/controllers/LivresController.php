<?php
require_once APP_PATH . 'models/Livre.php';
require_once APP_PATH . 'models/LivrePay.php';
require_once APP_PATH . 'models/Payment.php';
require_once APP_PATH . 'models/Transactions.php';

class LivresController extends Controller 
{    
    private $LivreModel;
    private $LivrePayModel;
    private $PaymentModel;
    private $TransactionsModel;

    public function __construct()
    {
        Auth::requireLogin(['membre','enseignant']);
        
        $this->LivreModel = new Livre();
        $this->LivrePayModel = new LivrePay();
        $this->PaymentModel = new Payment();
        $this->TransactionsModel = new Transactions();
 
    }

    public function index() 
    {
        $Livres = $this->LivreModel->getAll();

        $data = [
            'title' => SITE_NAME .' | Bibliothèque',
            'description' => 'La bibliothèque de la Communauté Lobola est un espace où vous pouvez trouver des livres et des ressources pour approfondir votre sagesse ancestrale. Explorez notre collection et découvrez des trésors spirituels qui nourriront votre vie.',
            'Livres' => $Livres,
        ];

        $this->view('livres/index', $data);
    }

    public function show($livreId) 
    {
        $membreId = Session::get('membre')['member_id'];
        $returnUrl = "https://communaute-lobola.ankhing.com/livres/return";

        $Livre = $this->LivreModel->getById($livreId);
        if (!$Livre) {
            Utils::redirect('../../livres');
        }
        $PayLivre = $this->LivrePayModel->getById($livreId, $membreId);

        $ipCountry = Utils::getCountryByIp('169.159.210.166');
        // $ipCountry = Utils::getCountryByIp(Utils::getClientIP());
        $countryCode = strtoupper((string) ($ipCountry['country_code'] ?? ''));
        $memberCountry = Utils::getCountryNameFromCode($countryCode);
        $providerCountries = Utils::getProviderCountriesForMember($memberCountry, PROVIDERS_COUNTRIES);
        // $lien = Utils::getDownloadLinkByCountry($memberCountry);
        $isValableCountry = isset($providerCountries[$memberCountry]) && !empty($providerCountries[$memberCountry]);
        
        $data = [
            'title' => SITE_NAME .' | '. $Livre->titre,
            'description' => 'Découvrez le livre ' . $Livre->titre . ' dans la bibliothèque de la Communauté Lobola.',
            'Livre' => $Livre,
            'PayLivre' => $PayLivre,
            'isValableCountry' => $isValableCountry,
        ];

        // if($providerCountries[$memberCountry] === null || empty($providerCountries[$memberCountry])) {
        //     Session::setFlash('error', 'Aucun opérateur disponible pour votre pays. Veuillez contacter l\'administrateur.');
        //     $this->view('livres/show', $data);
        //     return;
        // }
        
        $localAmount = Utils::convertUsdToLocalCurrency(PRIX_LIVRE, $memberCountry);

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_pay_livre']))
        {
            if($providerCountries[$memberCountry] === null || empty($providerCountries[$memberCountry])) {
                Session::setFlash('error', 'Aucun opérateur disponible pour votre pays. Veuillez contacter l\'administrateur.');
                $this->view('livres/show', $data);
                return;
            }
            $externalId = uniqid('payment_', true); 

            // $DataResponse = $this->PaymentModel->kpayPaymentMobile($localAmount, $operateur, $phoneNumber, $externalId);
            $DataResponse = $this->PaymentModel->kpayPaymentGateway($localAmount, $externalId, $returnUrl);

            $dataTrans = [
                'trans_id' => $DataResponse['id'],
                'member_id' => $membreId,
                'reference' => $DataResponse['reference'],
                'status' => $DataResponse['status'],
                'amount' => $DataResponse['amount'],
                'externalId' => $DataResponse['externalId'],
            ];
            $dataLivrePay = [
                'member_id' => $membreId,
                'livre_id' => $Livre->livre_id,
                'trans_id' => $DataResponse['id'],
                'status' => $DataResponse['status'],
            ];

            $this->TransactionsModel->insert($dataTrans);
            $this->LivrePayModel->insert($dataLivrePay);

            Utils::redirect($DataResponse['gatewayUrl']);
            exit;
        }

        $this->view('livres/show', $data);
    }

    public function return()
    {
        $membreId = Session::get('membre')['member_id'];
        
        $status = strtoupper(trim((string) ($_GET['status'] ?? $_SESSION['kpay_payment_status'])));
        $reference = strtoupper(trim((string) ($_GET['reference'])));
        $externalId = strtoupper(trim((string) ($_GET['externalId'])));

        $dbTransPayment = $this->TransactionsModel->find($reference, $externalId);
        $dbLivrePay = $this->LivrePayModel->getByTransId($dbTransPayment->trans_id);
        $Livre = $this->LivreModel->getById($dbLivrePay->livre_id);

        $transPayment = $this->PaymentModel->kpayGetTransactionStatus($dbTransPayment->trans_id);
        
        if(isset($transPayment['statusCode']) == 404) Utils::redirect('/livres');

        if($transPayment['status'] === ARRAY_KPAY_STATUS[1] && $dbTransPayment->status !== ARRAY_KPAY_STATUS[1])
        {
            $datas = [
                'status' => $transPayment['status'],
                'trans_id' => $dbTransPayment->trans_id,
            ];
            $this->TransactionsModel->update($datas);
        }

        if($transPayment['status'] === ARRAY_KPAY_STATUS[1])
        {   
            $updateLivrePay = [
                'trans_id' => $dbTransPayment->trans_id,
                'livre_id' => $dbLivrePay->livre_id,
                'status' => $transPayment['status'],
            ];

            $this->LivrePayModel->update($updateLivrePay);
        }

        $data = [
            'title' => SITE_NAME .' | Payment Réussi',
            'Livre' => $Livre,
            'status' => $transPayment['status'],
        ];

        $this->view('livres/return', $data);
    }
}
