<?php
// Site infos
define('SITE_NAME','Communauté Lobola');
define('FORMATEUR','Shenuti Lobola-lo-ilondo');
define('SITE_URL','https://communautelobola.ankhing.com');
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
// define('UPLOAD_RAPPORT_FOLDER_PATH', STORAGE_GENERATED . 'rapports/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL',UPLOAD_RAPPORT_FOLDER_PATH .'global/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SUIVI',UPLOAD_RAPPORT_FOLDER_PATH .'global_suivi/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_ENTRANT',UPLOAD_RAPPORT_FOLDER_PATH .'global_entrant/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SORTANT',UPLOAD_RAPPORT_FOLDER_PATH .'global_sortant/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_EN_ATTENTE',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_en_attente/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_CLASSE',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_classe/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_REDIRECTIONS',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_redirections/');
// define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_ACTIVITE_USER',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_activite_user/');

//options de securité
define('CLEF_CHIFFRAGE_FILE', 'CLLIL2024SECUREKEYFILE');
// define('TEMPS_DESSAIE', 15);
define('ENABLE_SSL', true);

// infos du company

//taille max des telechargements
define('MAX_UPLOAD_SIZE', 256000);

// others
define('ARRAY_ROLE_USER',['Admin','Enseignant','Membre']);
define('ARRAY_TYPE_ENGAGEMENT',['Menseul','Trimestriel','Semestriel','Annuel']);
define('ARRAY_TYPE_DEVISE',['CDF','USD','EUR']);
define('ARRAY_TYPE_SEXE',['M','F']);
define('ARRAY_STATUS_MEMBER',['pending_engagement','pending_validation','active','suspended','inactive']);
define('ARRAY_STATUS_ENGAGEMENT',['Approuvé','Non Approuvé']);
define('ARRAY_DOC_HEADER_TYPE',['pdf' => 'application/pdf', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
define('ARRAY_PAYMENT_STATUS',['En attente','Payé','Echoué']);
define('ARRAT_TAUX_CHANGE',['CDF' => 2200, 'EUR' => 0.87]); // valeurs en CDF

define('RETOUR_EN_ARRIERE', 'javascript:history.back()');
