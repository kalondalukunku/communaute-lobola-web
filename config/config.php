<?php
// Site infos
define('SITE_NAME','Mosali');
// define('SITE_NAME_BY','Ankhing Mosali');
define('SITE_NAME_SESSION', str_replace(' ', '_', SITE_NAME));
define('SITE_NAME_SESSION_USER', strtolower(SITE_NAME_SESSION . '_user'));
define('SITE_EMAIL','CSAK');
define('SITE_PHONE','CSAK');
define('SITE_DEVISE','CDF');

// connexion MySql
define('DB_HOST', 'localhost');
define('DB_NAME', 'mutu');
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
define('CLEF_CHIFFRAGE_PDF', 'ANKHINGMOSALI');
define('TEMPS_DESSAIE', 15);
define('ENABLE_SSL', true);

// infos du company
define('COMPANY_NAME',"Agrisa");
define('COMPANY_NAME_', str_replace(" ",'_', strtoupper(COMPANY_NAME)));

//taille max des telechargements
define('MAX_UPLOAD_SIZE', 256000);

// others
define('ARRAY_ROLE_USER',['Administrateur RH','Manager','Employé']);
define('ARRAY_MARIAL_STATUS', ['Célibataire','Marié(e)','Divorcé(e)','Veuf(ve)']);
define('ARRAY_DEPARTMENTS', ['Ressources humaine','Finances','Informatique','Sécretariat général','Communication','Marketing','Comptabilité']);
define('ARRAY_PRIMES', ['Prime administratif','Prime de fonction','Prime professionnel']);
define('ARRAY_PERSONNEL_STATUT_EMPLOI', ['Inactif','Actif','En Conge','Retraité']);
define('ARRAY_NAME_FILE_DOC', ['mosali_doc_identity','mosali_doc_diploma','mosali_doc_contract']);
define('ARRAY_SCOLARISE', ['Oui','Non']);
define('ARRAY_ISREQUIRED', ['Oui','Non']);
define('ARRAY_SEXE', ['M','F']);
define('ARRAY_STUDY_LEVEL', ['Primaire','Secondaire','Universitaire Licence','Universitaire Master','Universitaire Doctorat','Formation Professionnelle','Certification']);
define('ARRAY_IDENTITY_PIECES',["Carte électorale",'Passport',"Permis de conduire"]);
define('ARRAY_DOC_CATEGORY',["Documents d'État Civil et d'Identité",'Documents de Formation et de Compétences',"Documents Professionnels et Sociaux","Certificats Spécifiques et Attestations","Documents de Propriété et Financiers"]);
define('ARRAY_TYPE_ACTION_HISTO_DOC',['Upload','Remplacement','Suppression_Logique','Rejet']);
// define('ARRAY_TRANSMISSION',['normal','ordre de service','feuille de route']);
// define('ARRAY_HISTORIQUE_USER',['ajout', 'transmission sg', 'transmission direction', 'lecture','retour transmission','telechargement','traitement', 'commentaire','mise à jour','changement destinataire','classement','classement sans suite','restauration']);
// define('ARRAY_DOC_STATE', ['ajoute','transmis_sg', 'transmis_dr', 'recu_sg','recu_bureau_dr','valide','rejete','archive']);
// define('ARRAY_TYPE_RAPPORT',['global','global suivi','global entrant','global sortant']);
define('RETOUR_EN_ARRIERE',$_SERVER['HTTP_REFERER'] ?? 'javascript:history.back()');
