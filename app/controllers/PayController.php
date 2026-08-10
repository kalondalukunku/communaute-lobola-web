<?php

require_once APP_PATH . 'models/Membre.php';
require_once APP_PATH . 'models/Payment.php';
require_once APP_PATH . 'models/Transactions.php';

class PayController extends Controller
{
    private const KPAY_INIT_URL = 'https://admin.kpay.site/api/v1/payments/init';
    private const KPAY_STATUS_URL = 'https://admin.kpay.site/api/v1/payments/';

    private $MembreModel;
    private $PaymentModel;
    private $TransactionsModel;

    public static $providerCountries = [
        'BENIN' => ['dial_code' => '+229', 'providers' => ['MTN_MOMO_BEN' => 'MTN MOMO', 'MOOV_BEN' => 'Moov']],
        'BURKINA FASO' => ['dial_code' => '+226', 'providers' => ['MOOV_BFA' => 'Moov', 'ORANGE_BFA' => 'Orange']],
        'CAMEROUN' => ['dial_code' => '+237', 'providers' => ['MTN_MOMO_CMR' => 'MTN MOMO', 'ORANGE_CMR' => 'Orange']],
        'CÔTE D’IVOIRE' => ['dial_code' => '+225', 'providers' => ['MTN_MOMO_CIV' => 'MTN MOMO', 'ORANGE_CIV' => 'Orange', 'WAVE_CIV' => 'Wave']],
        'RD CONGO' => ['dial_code' => '+243', 'providers' => ['VODACOM_MPESA_COD' => 'Vodacom', 'AIRTEL_COD' => 'Airtel', 'ORANGE_COD' => 'Orange']],
        'ÉTHIOPIE' => ['dial_code' => '+251', 'providers' => ['MPESA_ETH' => 'M-Pesa']],
        'GABON' => ['dial_code' => '+241', 'providers' => ['AIRTEL_GAB' => 'Airtel', 'MOOV_GAB' => 'Moov']],
        'GHANA' => ['dial_code' => '+233', 'providers' => ['MTN_MOMO_GHA' => 'MTN MOMO', 'AIRTELTIGO_GHA' => 'Airtel Tigo', 'VODAFONE_GHA' => 'Vodafone']],
        'KENYA' => ['dial_code' => '+254', 'providers' => ['MPESA_KEN' => 'M-Pesa']],
        'LESOTHO' => ['dial_code' => '+266', 'providers' => ['MPESA_LSO' => 'M-Pesa']],
        'MALAWI' => ['dial_code' => '+265', 'providers' => ['AIRTEL_MWI' => 'Airtel', 'TNM_MWI' => 'TNM']],
        'MOZAMBIQUE' => ['dial_code' => '+258', 'providers' => ['MOVITEL_MOZ' => 'Movitel', 'VODACOM_MOZ' => 'Vodacom']],
        'NIGERIA' => ['dial_code' => '+234', 'providers' => ['AIRTEL_NGA' => 'Airtel', 'MTN_MOMO_NGA' => 'MTN MOMO', 'GLO_NGA' => 'Glo', '9MOBILE_NGA' => '9mobile']],
        'CONGO' => ['dial_code' => '+242', 'providers' => ['AIRTEL_COG' => 'Airtel', 'MTN_MOMO_COG' => 'MTN MOMO']],
        'RWANDA' => ['dial_code' => '+250', 'providers' => ['AIRTEL_RWA' => 'Airtel', 'MTN_MOMO_RWA' => 'MTN MOMO']],
        'SÉNÉGAL' => ['dial_code' => '+221', 'providers' => ['FREE_SEN' => 'Free', 'ORANGE_SEN' => 'Orange', 'WAVE_SEN' => 'Wave']],
        'SIERRA LEONE' => ['dial_code' => '+232', 'providers' => ['ORANGE_SLE' => 'Orange']],
        'TANZANIE' => ['dial_code' => '+255', 'providers' => ['AIRTEL_TZA' => 'Airtel', 'VODACOM_TZA' => 'Vodacom', 'TIGO_TZA' => 'Tigo', 'HALOTEL_TZA' => 'Halotel']],
        'OUGANDA' => ['dial_code' => '+256', 'providers' => ['AIRTEL_OAPI_UGA' => 'Airtel', 'MTN_MOMO_UGA' => 'MTN MOMO']],
        'ZAMBIE' => ['dial_code' => '+260', 'providers' => ['AIRTEL_OAPI_ZMB' => 'Airtel', 'MTN_MOMO_ZMB' => 'MTN MOMO', 'ZAMTEL_ZMB' => 'Zamtel']],
    ];

    public function __construct()
    {
        $this->MembreModel = new Membre();
        $this->PaymentModel = new Payment();
        $this->TransactionsModel = new Transactions();
        
        Auth::requireLogin('membre');
    }

    public function afrik_pay()
    {
        $membreId = Session::get('membre')['member_id'];
        $Membre = $this->MembreModel->findByMemberId($membreId);

        if(!$Membre) {
            Utils::redirect('/');
        }
        $paiement = $this->PaymentModel->getPayment($membreId, $Membre->engagement_id);

        $ipCountry = Utils::getCountryByIp('169.159.210.166');
        // $ipCountry = Utils::getCountryByIp(Utils::getClientIP());
        $countryCode = strtoupper((string) ($ipCountry['country_code'] ?? ''));
        $memberCountry = Utils::getCountryNameFromCode($countryCode);
        $providerCountries = Utils::getProviderCountriesForMember($memberCountry, PROVIDERS_COUNTRIES);
        // $countryKey = Utils::normalizeCountryName($memberCountry);

        if($providerCountries === []) {
            // Session::setFlash('error', 'Aucun opérateur disponible pour votre pays. Veuillez contacter l\'administrateur.');
            Utils::redirect('/membre/repaiement/'. $membreId);
        }

        // conversion USD to local currency based on country
        $montant = Utils::getMonthsNumber($Membre->modalite_engagement) * $Membre->montant;
        $localAmount = Utils::convertUsdToLocalCurrency($montant, $memberCountry);
        // $countryData = $providerCountries[$countryKey] ?? null;

        $data = [
            'title' => 'Paiement USSD KPAY',
            'description' => 'Abonnement USSD pour la Communauté Lobola.',
            'Membre' => $Membre,
            'montant' => $montant,
            'memberCountry' => $memberCountry,
            'providerCountries' => $providerCountries,
        ];

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cllil_membre_pay_engagement']))
        {
            $externalId = uniqid('payment_', true);
            $phoneNumber = Utils::sanitize(trim($_POST['phoneNumber'] ?? ''));   
            $operateur = Utils::sanitize(trim($_POST['operateur'] ?? ''));   

            // $DataResponse = $this->PaymentModel->kpayPaymentMobile($localAmount, $operateur, $phoneNumber, $externalId);
            $DataResponse = $this->PaymentModel->kpayPaymentGateway($localAmount, $externalId);
            $dataTrans = [
                'trans_id' => $DataResponse['id'],
                'member_id' => $membreId,
                'reference' => $DataResponse['reference'],
                'status' => $DataResponse['status'],
                'amount' => $DataResponse['amount'],
                'externalId' => $DataResponse['externalId'],
            ];
            $this->TransactionsModel->insert($dataTrans);
            Utils::redirect($DataResponse['gatewayUrl']);
            exit;
        }

        $this->view('pay/afrik_pay', $data);
        
    }

    public function return()
    {
        $membreId = Session::get('membre')['member_id'];
        $Membre = $this->MembreModel->findByMemberId($membreId);
        $paiement = $this->PaymentModel->getPayment($membreId, $Membre->engagement_id);

        if(!$Membre) {
            Utils::redirect('/');
        }

        $status = strtoupper(trim((string) ($_GET['status'] ?? $_SESSION['kpay_payment_status'])));
        $reference = strtoupper(trim((string) ($_GET['reference'])));
        $externalId = strtoupper(trim((string) ($_GET['externalId'])));

        $dbTransPayment = $this->TransactionsModel->find($reference, $externalId);

        $transPayment = $this->PaymentModel->kpayGetTransactionStatus($dbTransPayment->trans_id);
        
        if(isset($transPayment['statusCode']) == 404) Utils::redirect('afrik_pay');

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
            if(isset($paiement))
            {
                if (isset($paiement->trans_id) == $dbTransPayment->trans_id) 
                {
                    if ($transPayment['status'] === ARRAY_KPAY_STATUS[1]) Utils::redirect('/bolokele');
                    else Utils::redirect('afrik_pay');
                }

                $dbdate = $paiement->payment_prochain ?? date('Y-m-d');
                $date = new DateTime($dbdate);
                $date->modify("+". Utils::getMonthsNumber($Membre->modalite_engagement) ." months");
                $paymentProchain = $date->format('Y-m-d');
            }
            
            $payID = Utils::generateUuidV4();
            $paymentProchain = date('Y-m-d', strtotime("+". Utils::getMonthsNumber($Membre->modalite_engagement) ." months"));

            $dataAddPayement = [
                'pay_id' => $payID,
                'trans_id' => $dbTransPayment->trans_id,
                'member_id' => $membreId,
                'engagement_id' => $Membre->engagement_id,
                'amount' => Utils::getMonthsNumber($Membre->modalite_engagement) * $Membre->montant,
                'devise' => $Membre->devise,
                'payment_status' => ARRAY_PAYMENT_STATUS[1],
                'payment_prochain' => $paymentProchain,
            ];
            $updateDataMembre = [
                'member_id' => $membreId,
                'bolokele' => 1,
                'status' => ARRAY_STATUS_MEMBER[2]
            ];

            // if (
                $this->PaymentModel->insert($dataAddPayement) ;
                $this->MembreModel->update($updateDataMembre, 'member_id');
            // ) Utils::redirect('/bolokele');
        }

        $data = [
            'title' => 'Paiement USSD KPAY',
            'description' => 'Résultat du paiement USSD.',
            'paymentMode' => false,
            'paymentReturnMode' => true,
            'status' => $transPayment['status'],
            'transPayment' => 'transPayment',
        ];

        $this->view('pay/return_pay', $data);
    }
}
