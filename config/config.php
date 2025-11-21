<?php
// Site infos
define('SITE_NAME','Ankhing Bukus');
define('SITE_NAME_SESSION', str_replace(' ', '_', SITE_NAME));
define('SITE_NAME_SESSION_USER', strtolower(SITE_NAME_SESSION . '_user'));
define('SITE_EMAIL','CSAK');
define('SITE_PHONE','CSAK');

// connexion MySql
define('DB_HOST', 'localhost');
define('DB_NAME', 'kuria');
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
define('FILE_VIEW_FOLDER_PATH', BASE_PATH . 'assets/uploads/courier/');
define('UPLOAD_RAPPORT_FOLDER_PATH', STORAGE_GENERATED . 'rapports/');
define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL',UPLOAD_RAPPORT_FOLDER_PATH .'global/');
define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SUIVI',UPLOAD_RAPPORT_FOLDER_PATH .'global_suivi/');
define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_ENTRANT',UPLOAD_RAPPORT_FOLDER_PATH .'global_entrant/');
define('UPLOAD_RAPPORT_FOLDER_PATH_GLOBAL_SORTANT',UPLOAD_RAPPORT_FOLDER_PATH .'global_sortant/');
define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_EN_ATTENTE',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_en_attente/');
define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_CLASSE',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_classe/');
define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_REDIRECTIONS',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_redirections/');
define('UPLOAD_RAPPORT_FOLDER_PATH_DOC_ACTIVITE_USER',UPLOAD_RAPPORT_FOLDER_PATH .'global_doc_activite_user/');

//options de securité
define('CLEF_CHIFFRAGE_PDF', 'ANKHINGBUKUS');
define('TEMPS_DESSAIE', 15);
define('ENABLE_SSL', true);

// infos du company
define('COMPANY_NAME',"Agrisa");
define('COMPANY_NAME_', str_replace(" ",'_', strtoupper(COMPANY_NAME)));

//taille max des telechargements
define('MAX_UPLOAD_SIZE', 256000);

// others
define('ARRAY_STATUS', ['en cours','non traité','traité','classé sans suite','classé']);
define('ARRAY_CATEGORIES', ['entrant','sortant']);
define('ARRAY_TYPE',['interne','externe']);
define('ARRAY_PRIORITY',['normal','urgent']);
define('ARRAY_TRANSMISSION',['normal','ordre de service','feuille de route']);
define('ARRAY_ROLE_USER',['admin','superviseur','couriste']);
define('ARRAY_TYPE_RAPPORT',['global','global suivi','global entrant','global sortant']);
define('NBR_LIMITE_USER_COURISTE', 3);
define('RETOUR_EN_ARRIERE',$_SERVER['HTTP_REFERER'] ?? 'javascript:history.back()');
define('STYLE_TDS',"style='cursor: pointer; background-color:#0DCAF02b !important; border-radius: 5px'");