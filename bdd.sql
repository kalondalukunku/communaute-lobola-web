-- -----------------------------------------------------------------------------
-- Schéma de Base de Données pour le Système d'Information RH (SIRH)
-- Basé sur les fonctionnalités : Fiches Personnel, Gestion des Rôles, Paramètres RH.
-- -----------------------------------------------------------------------------

--
-- I. TABLES DE SÉCURITÉ ET D'ACCÈS (Gestion des Rôles et Utilisateurs)
--

-- Table pour définir les Rôles disponibles dans l'application (Administrateur RH, Manager, Employé)
CREATE TABLE ROLES (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    nom_role VARCHAR(50) UNIQUE NOT NULL COMMENT 'Ex: Administrateur RH, Manager, Employe',
    description_role VARCHAR(255)
);

-- Table pour définir les Permissions spécifiques associées à chaque Rôle
-- Permet de contrôler l'accès aux modules (Lecture/Ecriture/Interdit)
CREATE TABLE PERMISSIONS (
    permission_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    module VARCHAR(50) NOT NULL COMMENT 'Ex: Fiches Personnel, Parametres RH, Rapports',
    niveau_acces ENUM('Lecture', 'Ecriture', 'Complet', 'Interdit') NOT NULL,
    
    FOREIGN KEY (role_id) REFERENCES ROLES(role_id) ON DELETE CASCADE,
    UNIQUE KEY uk_role_module (role_id, module) -- Un rôle ne peut avoir qu'une permission par module
);

-- Table des Utilisateurs/Comptes de connexion
CREATE TABLE UTILISATEURS (
    user_id CHAR(36) PRIMARY KEY COMMENT 'UUID pour identification unique du compte',
    matricule_personnel VARCHAR(20) UNIQUE COMMENT 'Clé étrangère vers la table PERSONNEL',
    role_id INT NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe_hash CHAR(60) NOT NULL COMMENT 'Stockage du hash du mot de passe',
    statut_compte ENUM('Actif', 'Inactif') NOT NULL DEFAULT 'Actif' COMMENT 'Permet d arreter l acces sans supprimer le compte',
    
    FOREIGN KEY (role_id) REFERENCES ROLES(role_id) ON DELETE RESTRICT
    -- La FK vers PERSONNEL est ajoutée plus tard pour éviter la dépendance circulaire si PERSONNEL doit référencer UTILISATEURS
);


--
-- II. TABLES DE GESTION DU PERSONNEL (Fiches Personnel)
--

-- Table des informations de base du personnel (La Fiche)
CREATE TABLE PERSONNEL (
    matricule VARCHAR(20) PRIMARY KEY COMMENT 'Matricule unique et identifiant principal de l employe',
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    adresse TEXT,
    telephone VARCHAR(20),
    email_pro VARCHAR(100) UNIQUE NOT NULL,
    poste_actuel VARCHAR(100),
    service_id INT, -- Pourrait lier à une future table 'SERVICES'
    grade_echelon VARCHAR(50) COMMENT 'Ex: A1 / Echelon 5',
    type_contrat VARCHAR(50) COMMENT 'Ex: Fonctionnaire Titulaire, Contractuel',
    date_entree DATE NOT NULL,
    statut_emploi ENUM('Actif', 'En Conge', 'Termine') NOT NULL DEFAULT 'Actif'
    
    -- Ajoutez ici la FK vers UTILISATEURS si nécessaire, ou utilisez le MATRICULE dans UTILISATEURS
);

-- Mise à jour de la clé étrangère dans UTILISATEURS vers PERSONNEL
ALTER TABLE UTILISATEURS 
ADD CONSTRAINT fk_user_personnel
FOREIGN KEY (matricule_personnel) REFERENCES PERSONNEL(matricule) ON DELETE CASCADE;

-- Table pour l'historique de carrière interne
CREATE TABLE POSTES_OCCUPES (
    poste_occupe_id INT PRIMARY KEY AUTO_INCREMENT,
    matricule VARCHAR(20) NOT NULL,
    nom_poste VARCHAR(100) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE COMMENT 'NULL si poste actuel',
    service VARCHAR(100),
    
    FOREIGN KEY (matricule) REFERENCES PERSONNEL(matricule) ON DELETE CASCADE
);


--
-- III. TABLES DE GESTION DES DOCUMENTS ET CONFORMITÉ (Paramètres RH & Rapports RH)
--

-- Table de paramétrage des types de documents requis
CREATE TABLE TYPES_DOCUMENT (
    type_doc_id INT PRIMARY KEY AUTO_INCREMENT,
    nom_type VARCHAR(100) UNIQUE NOT NULL COMMENT 'Ex: Certificat Medical, Permis de Conduire, Diplome Master',
    description TEXT,
    est_obligatoire BOOLEAN NOT NULL DEFAULT FALSE,
    duree_validite_jours INT COMMENT 'Duree en jours. NULL si jamais expire',
    delai_alerte_jours INT COMMENT 'Nombre de jours avant expiration pour declencher l alerte'
);

-- Table pour l'enregistrement des documents téléchargés par le personnel
CREATE TABLE DOCUMENTS_PERSONNEL (
    doc_id CHAR(36) PRIMARY KEY COMMENT 'UUID pour le document',
    matricule VARCHAR(20) NOT NULL,
    type_doc_id INT NOT NULL,
    date_telechargement DATE NOT NULL,
    date_expiration DATE COMMENT 'Calculee a partir du type_doc_id et de la date de telechargement',
    nom_fichier_original VARCHAR(255),
    chemin_fichier_stockage VARCHAR(512) NOT NULL COMMENT 'Chemin securise vers le fichier sur le serveur (ou Firestore)',
    statut_conformite ENUM('Conforme', 'Expire', 'Bientot_Expire', 'Manquant') NOT NULL DEFAULT 'Conforme',
    
    FOREIGN KEY (matricule) REFERENCES PERSONNEL(matricule) ON DELETE CASCADE,
    FOREIGN KEY (type_doc_id) REFERENCES TYPES_DOCUMENT(type_doc_id) ON DELETE RESTRICT
);

-- NOUVELLE TABLE: Historique des versions de documents
CREATE TABLE HISTORIQUE_DOCUMENTS (
    historique_id INT PRIMARY KEY AUTO_INCREMENT,
    doc_id_actuel CHAR(36) NOT NULL COMMENT 'Lien vers le document actuel (clé principale de DOCUMENTS_PERSONNEL)',
    version_id INT NOT NULL COMMENT 'Numero de version du document (1, 2, 3...)',
    date_action DATETIME NOT NULL COMMENT 'Date et heure de l action (upload, remplacement, suppression logique)',
    utilisateur_id CHAR(36) COMMENT 'L utilisateur ayant effectue l action',
    type_action ENUM('Upload', 'Remplacement', 'Suppression_Logique', 'Rejet') NOT NULL,
    ancien_chemin_fichier VARCHAR(512) NOT NULL COMMENT 'Chemin du fichier tel qu il etait avant l action',
    
    FOREIGN KEY (doc_id_actuel) REFERENCES DOCUMENTS_PERSONNEL(doc_id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES UTILISATEURS(user_id) ON DELETE SET NULL,
    UNIQUE KEY uk_doc_version (doc_id_actuel, version_id) -- Assure que chaque version est unique pour un document donne
);

-- Table pour le suivi des alertes de conformité (Rapports RH)
CREATE TABLE ALERTE_SUIVI (
    alerte_id INT PRIMARY KEY AUTO_INCREMENT,
    doc_id CHAR(36) COMMENT 'Lier au document concerne (peut etre NULL si alerte Manquant)',
    matricule VARCHAR(20) NOT NULL,
    type_alerte ENUM('Expiration', 'Manquant') NOT NULL,
    date_declenchement DATETIME NOT NULL,
    est_traitee BOOLEAN NOT NULL DEFAULT FALSE,
    
    FOREIGN KEY (doc_id) REFERENCES DOCUMENTS_PERSONNEL(doc_id) ON DELETE SET NULL,
    FOREIGN KEY (matricule) REFERENCES PERSONNEL(matricule) ON DELETE CASCADE
);