<?php
// Site infos
define('SITE_NAME','Communauté Lobola');
define('FORMATEUR','Shenuti Lobola-lo-ilondo');
define('SITE_URL','https://communaute-lobola.ankhing.com');
// define('SITE_NAME_BY','Ankhing Mosali');
define('SITE_NAME_SESSION', str_replace(' ', '_', SITE_NAME));
define('SITE_NAME_SESSION_USER', strtolower(SITE_NAME_SESSION . '_user'));
define('SITE_EMAIL','Communauté Lobola');
define('SITE_PHONE','+243 841 112 307');
// define('SITE_PHONE_2','+243 80 123 4567');
// define('SITE_PHONE_3','+33 6 12 34 56 78');

// connexion MySql
define('DB_HOST', 'localhost');
define('DB_NAME', 'c_lobola');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SERIE_SUP', 'd3fded1cb2174f52891d0f144497f1b3');

// SMTP mail
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'user@exemple.com');
define('MAIL_PASS', 'pass');
define('MAIL_FROM', 'no-reply@exemple.com');
define('MAIL_FROM_NAME', SITE_NAME);

// repertoires
define('LIBS_PATH',  BASE_PATH .'libs/');
define('PDFJS_VIEW_PATH',  LIBS_PATH .'pdfJs/web/viewer.html');
define('FILE_VIEW_FOLDER_PATH', BASE_PATH . 'assets/uploads/document/');

//options de securité
define('CLEF_CHIFFRAGE_FILE', 'CLLIL2024SECUREKEYFILE');
// define('TEMPS_DESSAIE', 15);
define('ENABLE_SSL', true);

// infos du company

//taille max des telechargements
define('MAX_UPLOAD_SIZE', 256000);

//apiKey
define('API_KEY_CALL_APP', "api_lobola_access_rYJ1TBGfPkSkgpLwkIoVni1fBkN4RRIIEcQ2YSXADxuomlO");
define('KPAY_API_KEY2', "kpay_live_27e7219484cecadf718e38669bdfa5129ccf96d536de7e35");
define('KPAY_SECRET_KEY2', "a118dc73a23348f7b82749dc4b89b50fe0271754974e91543c9c9f6ffa91cb1c");

// others
define('ARRAY_ROLE_USER',['Admin','Enseignant','Membre']);
define('ARRAY_TYPE_ENGAGEMENT',['Menseul','Trimestriel','Semestriel','Annuel']);
define('ARRAY_TYPE_DEVISE',['USD','EUR']);
define('ARRAY_TYPE_SEXE',['Kami','Kamit']);
define('ARRAY_STATUS_MEMBER',['attente_engagement','attente_integration','active','suspended','integration_rejetee','inactive','engagement_rejetee']);
define('ARRAY_STATUS_ENGAGEMENT',['Approuvé','Non Approuvé','Rejété']);
define('ARRAY_DOC_HEADER_TYPE',['pdf' => 'application/pdf', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
define('ARRAY_PAYMENT_STATUS',['En attente','Payé','Echoué']);
define('ARRAY_TYPE_NIVEAU_INITIATION',['Pas encore initié', '15 jours', '30 jours','Neuvaine']);
define('ARRAY_CATEGORIE_LIVRE',['Initiation', 'Enseignements', 'Spiritualité','Histoire & Tradition']);
define('ARRAT_TAUX_CHANGE',['CDF' => 2200, 'EUR' => 0.87, 'USD' => 1.0]); // valeurs en CDF
define('ARRAY_STATUS_TOKEN',['utilisé', 'non utilisé', 'expiré']);
define('ARRAY_STATUS_ENSEIGNANT',['Actif','Inactif','En pause']);
define('ARRAY_ACTIONS_RAISONS',['Reject','Suspend','Desactiver','Reactiver','autres']);
define('ARRAY_ACTIONS_RAISONS_STATUS',['Traité','Non traité']);
define('ARRAY_ACTIONS_TOKEN',['invite','auth']);
define('ARRAY_KPAY_STATUS',['PENDING','COMPLETED','FAILED','CANCELLED']);
define('PRIX_LIVRE', 10);

define('RETOUR_EN_ARRIERE', 'javascript:history.back()');

define('PROVIDERS_COUNTRIES',[
        'BENIN' => ['dial_code' => '+229', 'providers' => ['MTN_MOMO_BEN' => 'MTN MOMO', 'MOOV_BEN' => 'Moov']],
        'CAMEROUN' => ['dial_code' => '+237', 'providers' => ['MTN_MOMO_CMR' => 'MTN MOMO', 'ORANGE_CMR' => 'Orange']],
        'CÔTE D’IVOIRE' => ['dial_code' => '+225', 'providers' => ['MTN_MOMO_CIV' => 'MTN MOMO', 'ORANGE_CIV' => 'Orange', 'WAVE_CIV' => 'Wave']],
        'RD CONGO' => ['dial_code' => '+243', 'providers' => ['VODACOM_MPESA_COD' => 'Vodacom', 'AIRTEL_COD' => 'Airtel', 'ORANGE_COD' => 'Orange']],
        'GABON' => ['dial_code' => '+241', 'providers' => ['AIRTEL_GAB' => 'Airtel', 'MOOV_GAB' => 'Moov']],
        'KENYA' => ['dial_code' => '+254', 'providers' => ['MPESA_KEN' => 'M-Pesa']],
        'CONGO' => ['dial_code' => '+242', 'providers' => ['AIRTEL_COG' => 'Airtel', 'MTN_MOMO_COG' => 'MTN MOMO']],
        'RWANDA' => ['dial_code' => '+250', 'providers' => ['AIRTEL_RWA' => 'Airtel', 'MTN_MOMO_RWA' => 'MTN MOMO']],
        'SÉNÉGAL' => ['dial_code' => '+221', 'providers' => ['FREE_SEN' => 'Free', 'ORANGE_SEN' => 'Orange', 'WAVE_SEN' => 'Wave']],
        'SIERRA LEONE' => ['dial_code' => '+232', 'providers' => ['ORANGE_SLE' => 'Orange']],
        'OUGANDA' => ['dial_code' => '+256', 'providers' => ['AIRTEL_OAPI_UGA' => 'Airtel', 'MTN_MOMO_UGA' => 'MTN MOMO']],
        'ZAMBIE' => ['dial_code' => '+260', 'providers' => ['AIRTEL_OAPI_ZMB' => 'Airtel', 'MTN_MOMO_ZMB' => 'MTN MOMO', 'ZAMTEL_ZMB' => 'Zamtel']],
    ]);