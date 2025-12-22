<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="flex-1 p-4 sm:p-8">
        
        <!-- Header de la Page d'Ajout -->
        <header class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center mb-4 md:mb-0">
                <a href="<?= RETOUR_EN_ARRIERE ?>" class="text-gray-400 hover:text-[var(--color-secondary)] mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
                Modifier un nouveau Personnel
            </h1>
        </header>
        
        <!-- Formulaire principal -->
        <div id="addPersonnelForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6" enctype="multipart/form-data">

            <!-- Colonne Gauche: Résumé et Téléchargement d'Avatar (1/3) -->
            <div class="lg:col-span-1">
                <div class="card-container p-6 flex flex-col items-center sticky top-[80px]">
                    
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Photo d'Identité</h2>

                    <!-- Zone d'Upload de l'Avatar -->
                     <div class="h-32 w-32 flex items-center justify-center mb-4 group hover:border-[var(--color-primary)] transition duration-150 relative">
                        <img class="h-full w-full rounded-full object-cover border-4 border-[var(--color-primary)] mb-4 shadow-lg" 
                            src="https://placehold.co/300x300/1565C0/FFFFFF?text=<?= Helper::getFirstLetter($Personnel->nom) . Helper::getFirstLetter($Personnel->postnom) ?>" 
                            alt="Photo du Personnel"
                            onerror="this.onerror=null; this.src='https://placehold.co/300x300/1565C0/FFFFFF?text=JD'">
                        
                        <!-- <div class="absolute text-sm font-medium text-gray-500 group-hover:text-[var(--color-primary)] transition duration-150">
                             <i class="fas fa-camera text-xl mb-1"></i><br>
                             Ajouter
                        </div> -->
                    </div>
                    
                    <p class="text-sm text-gray-500 text-center mb-6">Taille max : 5MB (JPG, PNG)</p>

                    <!-- Statut (Par défaut à Actif) -->
                    <!-- <div class="w-full space-y-3 pt-4 border-t border-gray-100">
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut de l'employé</label>
                        <select id="status" name="status" class="form-input text-sm">
                            <option value="actif" selected>Actif</option>
                            <option value="conge">En Congé</option>
                            <option value="licencie">Licencié</option>
                        </select>
                    </div> -->
                </div>
            </div>

            <!-- Colonne Droite: Onglets de Saisie (2/3) -->
            <div class="lg:col-span-2">
                <div class="card-container p-6">
                    
                    <!-- Barre d'Onglets -->
                    <div class="border-b border-gray-200 mb-6 flex space-x-6 overflow-x-auto">
                        <button type="button" data-tab="personnel" class="tab-button py-2 px-1 text-sm font-medium transition duration-150 active-tab">
                            <i class="fas fa-user-circle mr-2"></i> Infos Personnelles
                        </button>
                        <button type="button" data-tab="professionnel" class="tab-button py-2 px-1 text-sm font-medium text-gray-500 hover:text-[var(--color-secondary)] transition duration-150">
                            <i class="fas fa-briefcase mr-2"></i> Infos Professionnelles
                        </button>
                        <button type="button" data-tab="documents" class="tab-button py-2 px-1 text-sm font-medium text-gray-500 hover:text-[var(--color-secondary)] transition duration-150">
                            <i class="fas fa-file-upload mr-2"></i> Documents Clés
                        </button>
                    </div>

                    <!-- --------------------------------- -->
                    <!-- 3. Contenu de l'Onglet 1 : Infos Personnelles -->
                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <form action="" method="post">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] border-b pb-2">Informations d'identité et de contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="dateOfBirth" class="block text-sm font-medium text-gray-700 mb-1">Date de Naissance</label>
                                    <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?=  Helper::getData($_POST, 'dateOfBirth', $Personnel->date_naissance) ?>" required class="form-input">
                                </div>
                                <div>
                                    <label for="sexe" class="block text-sm font-medium text-gray-700 mb-1">Sexe</label>
                                    <select id="sexe" name="sexe" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_SEXE as $sexe): ?>
                                            <option value="<?= $sexe ?>" <?= Helper::getSelectedValue('sexe', $sexe, $Personnel->sexe) ?> ><?= $sexe ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="maritalstatus" class="block text-sm font-medium text-gray-700 mb-1">État Civil</label>
                                    <select id="maritalstatus" name="maritalstatus" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_MARIAL_STATUS as $maritalstatus): ?>
                                            <option value="<?= $maritalstatus ?>" <?= Helper::getSelectedValue('maritalstatus', $maritalstatus, $Personnel->statut_marital) ?> ><?= $maritalstatus ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse Complète</label>
                                    <input type="text" id="address" name="address" value="<?=  Helper::getData($_POST, 'address', $Personnel->adresse) ?>" class="form-input" placeholder="Ex: 123, Avenue de la Liberté, Kinshasa">
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone Personnel</label>
                                    <input type="tel" id="phone" name="phone" value="<?=  Helper::getData($_POST, 'phone', $Personnel->telephone) ?>" class="form-input" placeholder="Ex: +33 6 12 34 56 78">
                                </div>
                                <div>
                                    <label for="personalEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Personnel</label>
                                    <input type="email" id="personalEmail" name="personalEmail" value="<?=  Helper::getData($_POST, 'personalEmail', $Personnel->email) ?>" class="form-input" placeholder="Ex: jean.dupont.perso@mail.com">
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mutu_edt_psn_step_one" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Mettre à jour les informations d'identité</span>
                                </button>
                            </div>
                        </form>
                            
                    </div>

                    <!-- --------------------------------- -->
                    <!-- 4. Contenu de l'Onglet 2 : Infos Professionnelles -->
                    <!-- --------------------------------- -->
                    <div id="professionnel" class="tab-content space-y-6">
                        <form action="" method="post">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] border-b pb-2">Détails du Contrat et du Poste</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="employeeId" class="block text-sm font-medium text-gray-700 mb-1">Matricule Employé</label>
                                    <input type="text" id="employeeId" name="employeeId" value="<?=  Helper::getData($_POST, 'employeeId', $Personnel->matricule) ?>" required class="form-input" placeholder="Ex: RH4578">
                                </div>
                                <div>
                                    <label for="hireDate" class="block text-sm font-medium text-gray-700 mb-1">Date d'engagement sous statut</label>
                                    <input type="date" id="hireDate" name="hireDate" value="<?=  Helper::getData($_POST, 'hireDate', $Personnel->date_engagement) ?>" required class="form-input">
                                </div>

                                <div>
                                    <label for="jobTitle" class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
                                    <input type="text" id="jobTitle" name="jobTitle" value="<?=  Helper::getData($_POST, 'jobTitle', $Personnel->nom_poste) ?>" required class="form-input" placeholder="Ex: Responsable RH Principal">
                                </div>
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Service / Direction</label>
                                    <select id="department" name="department" required class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach($departmentsDb as $department): ?>
                                            <option value="<?= $department->nom_service ?>" <?= Helper::getSelectedValue('department', $department->nom_service, $Personnel->nom_service) ?> ><?= $department->nom_service ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                                    <input type="text" id="grade" name="grade" value="<?=  Helper::getData($_POST, 'grade', $Personnel->grade) ?>" class="form-input" placeholder="Ex: A1">
                                </div>
                                <div>
                                    <label for="salaire_base" class="block text-sm font-medium text-gray-700 mb-1">Salaire de base</label>
                                    <input type="number" id="salaire_base" name="salaire_base" value="<?=  Helper::getData($_POST, 'salaire_base', $Personnel->salaire_base) ?>" class="form-input" placeholder="Ex: 500.000">
                                </div>
                                <div>
                                    <label for="primeType" class="block text-sm font-medium text-gray-700 mb-1">Type de Prime</label>
                                    <select id="primeType" name="primeType" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_PRIMES as $primeType): ?>
                                            <option value="<?= $primeType ?>" <?= Helper::getSelectedValue('primeType', $primeType, $Personnel->type_prime) ?>  ><?= $primeType ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if($Personnel->statut_marital === ARRAY_MARIAL_STATUS[1]): ?>
                                    <div>
                                        <label for="nbr_child" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'enfants</label>
                                        <input type="number" id="nbr_child" name="nbr_child" value="<?=  Helper::getData($_POST, 'nbr_child', $Personnel->nbr_enfant) ?>" class="form-input" placeholder="Ex: 3">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h4 class="text-lg font-semibold text-gray-700 mt-8 border-t pt-4">Informations de Contact Professionnel</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="workEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Professionnel</label>
                                    <input type="email" id="workEmail" name="workEmail" value="<?=  Helper::getData($_POST, 'workEmail', $Personnel->email_pro) ?>" class="form-input" placeholder="Ex: j.dupont@gouv.fr">
                                </div>
                                <!-- <div>
                                    <label for="manager" class="block text-sm font-medium text-gray-700 mb-1">Supérieur Hiérarchique (Matricule)</label>
                                    <input type="text" id="manager" name="manager" value="<?=  Helper::getData($_POST, 'manager') ?>" class="form-input" placeholder="Ex: #RH1001">
                                </div> -->
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mutu_edt_psn_step_two" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Mettre à jour les infos Professionnelles</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- --------------------------------- -->
                    <!-- 5. Contenu de l'Onglet 3 : Documents Clés -->
                    <!-- --------------------------------- -->
                    <div id="documents" class="tab-content space-y-6">
                        <h3 class="text-xl font-semibold text-[var(--color-secondary)] border-b pb-2">Téléchargement des Pièces Justificatives (Optionnel)</h3>
                            
                        <p class="text-sm text-gray-600">Veuillez télécharger les documents essentiels. Vous pourrez ajouter d'autres documents plus tard sur la fiche détaillée.</p>

                        <div id="document-upload-list" class="space-y-4">
                            <!-- Bloc de téléchargement 1 -->
                            <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                
                                <?php if(!$firstDocumentsPsn): ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-passport mr-2 text-[var(--color-primary)]"></i> Pièce d'Identité
                                        </label>
                                        <input type="file" accept=".pdf" name="mosali_doc_identity" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer" required>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-7">
                                            <div>
                                                <label for="type_document" class="block text-sm font-medium text-gray-700 mb-1">Type document</label>
                                                <select id="type_document" name="type_document" required class="form-input">
                                                    <option value="">Sélectionner...</option>
                                                    <?php foreach(ARRAY_IDENTITY_PIECES as $type_document): ?>
                                                        <option value="<?= $type_document ?>" <?= Helper::getSelectedValue('type_document', $type_document) ?> ><?= $type_document ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label for="numero_document" class="block text-sm font-medium text-gray-700 mb-1">N° document</label>
                                                <input type="text" id="numero_document" name="numero_document" value="<?=  Helper::getData($_POST, 'numero_document') ?>" required class="form-input" placeholder="Ex: 123456">
                                            </div>
                                            <div>
                                                <label for="date_delivrance" class="block text-sm font-medium text-gray-700 mb-1">Date de délivrance</label>
                                                <input type="date" id="date_delivrance" name="date_delivrance" value="<?=  Helper::getData($_POST, 'date_delivrance') ?>" required class="form-input" placeholder="Ex: 05/02/2022">
                                            </div>
                                            <div>
                                                <label for="date_expiration" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                                <input type="date" id="date_expiration" name="date_expiration" value="<?=  Helper::getData($_POST, 'date_expiration') ?>" required class="form-input" placeholder="Ex: 05/02/2027">
                                            </div>
                                            
                                        </div>

                                        <div class="mt-4 pt-6 border-t border-gray-200 flex justify-end text-xs">
                                            <button type="submit" name="mosali_add_psn_step_three_doc_1" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Compléter & téléverser le document</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-passport mr-2 text-[var(--color-primary)]"></i> Pièce d'Identité
                                    </label>
                                    <p class="ms-4 py-2 px-1 text-md font-medium transition duration-150"><?= strtolower(str_replace([' ',"'"],'-',$firstDocumentsPsn->nom_fichier_original)) ?>.pdf</p>
                                <?php endif; ?>
                            </div>
                            <!-- Bloc de téléchargement 2 -->
                            <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                
                                <?php if(!$secondDocumentsPsn): ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-graduation-cap mr-2 text-[var(--color-primary)]"></i> Diplôme Principal
                                        </label>
                                    
                                        <input type="file" accept=".pdf" name="mosali_doc_diploma" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer" required>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-7">
                                            <div>
                                                <label for="niveau_etude" class="block text-sm font-medium text-gray-700 mb-1">Niveau d'étude</label>
                                                <select id="niveau_etude" name="niveau_etude" required class="form-input">
                                                    <option value="">Sélectionner...</option>
                                                    <?php foreach(ARRAY_STUDY_LEVEL as $niveau_etude): ?>
                                                        <option value="<?= $niveau_etude ?>" <?= Helper::getSelectedValue('niveau_etude', $niveau_etude) ?> ><?= $niveau_etude ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="intitule_diplome" class="block text-sm font-medium text-gray-700 mb-1">Option / Faculté / Domaine</label>
                                                <input type="text" id="intitule_diplome" name="intitule_diplome" value="<?=  Helper::getData($_POST, 'intitule_diplome') ?>" required class="form-input" placeholder="Ex: Gestion des Ressources Humaines">
                                            </div> 
                                            <div>
                                                <label for="etablissement" class="block text-sm font-medium text-gray-700 mb-1">Etablissement</label>
                                                <input type="text" id="etablissement" name="etablissement" value="<?=  Helper::getData($_POST, 'etablissement') ?>" required class="form-input" placeholder="Ex: Université de Paris">
                                            </div> 
                                            <div>
                                                <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                                <input type="text" id="ville" name="ville" value="<?=  Helper::getData($_POST, 'ville') ?>" required class="form-input" placeholder="Ex: Kinshasa">
                                            </div> 
                                            <div>
                                                <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                                <input type="text" id="pays" name="pays" value="<?=  Helper::getData($_POST, 'pays') ?>" required class="form-input" placeholder="Ex: RDC">
                                            </div> 
                                            <div>
                                                <label for="annee_obtention" class="block text-sm font-medium text-gray-700 mb-1">Année obtention</label>
                                                <input type="number" id="annee_obtention" name="annee_obtention" value="<?=  Helper::getData($_POST, 'annee_obtention') ?>" required class="form-input" placeholder="Ex: 2010">
                                            </div>                                                
                                        </div>
                                    
                                        <div class="mt-4 pt-6 border-t border-gray-200 flex justify-end text-xs">
                                            <button type="submit" name="mosali_add_psn_step_three_doc_2" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Compléter & téléverser le document</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-graduation-cap mr-2 text-[var(--color-primary)]"></i> Diplôme Principal
                                    </label>
                                    <p class="ms-4 py-2 px-1 text-md font-medium transition duration-150">
                                        <?= strtolower(str_replace([' ',"'"],'-',$secondDocumentsPsn->nom_fichier_original)) ?>.pdf
                                    </p>
                                <?php endif; ?>
                            </div>
                            <!-- Bloc de téléchargement 3 -->
                            <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                
                                <form action="" method="post" enctype="multipart/form-data">
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Contrat de Travail (Signé)
                                    </label>
                                    
                                    <?php if(!$thirdDocumentsPsn): ?>
                                        <input type="file" accept=".pdf" name="mosali_doc_contract" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer" required>
                                
                                        <div class="mt-4 pt-6 border-t border-gray-200 flex justify-end text-xs">
                                            <button type="submit" name="mosali_add_psn_step_three_doc_3" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Téléverser le document</span>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <p class="ms-4 py-2 px-1 text-md font-medium transition duration-150">
                                            <?= strtolower(str_replace([' ',"'"],'-',$thirdDocumentsPsn->nom_fichier_original)) ?>.pdf
                                        </p>
                                    <?php endif; ?>
                                </form>
                            </div>
                            <?php if($Personnel->statut_marital === ARRAY_MARIAL_STATUS[1]): ?>
                                <!-- Bloc de téléchargement - -->
                                <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                    
                                    <?php if(!$mariageDocumentsPsn): ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Acte de mariage
                                            </label>
                                            
                                            <input type="file" accept=".pdf" name="mosali_doc_conjoint" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer" required>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-7">
                                                <div>
                                                    <label for="nom_complet" class="block text-sm font-medium text-gray-700 mb-1">Nom complet du conjoint</label>
                                                    <input type="text" id="nom_complet" name="nom_complet" value="<?=  Helper::getData($_POST, 'nom_complet') ?>" required class="form-input" placeholder="Ex: Kalinde Divin(e)">
                                                </div>
                                                <div>
                                                    <label for="profession" class="block text-sm font-medium text-gray-700 mb-1">Profession</label>
                                                    <input type="text" id="profession" name="profession" value="<?=  Helper::getData($_POST, 'profession') ?>" required class="form-input" placeholder="Ex: IT de la Ceni">
                                                </div>
                                                
                                                <div>
                                                    <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                                    <input type="date" id="date_naissance" name="date_naissance" value="<?=  Helper::getData($_POST, 'date_naissance') ?>" required class="form-input">
                                                </div>
                                                
                                                <div>
                                                    <label for="date_mariage" class="block text-sm font-medium text-gray-700 mb-1">Date de mariage</label>
                                                    <input type="date" id="date_mariage" name="date_mariage" value="<?=  Helper::getData($_POST, 'date_mariage') ?>" required class="form-input">
                                                </div>
                                            </div>

                                            <div class="mt-4 pt-6 border-t border-gray-200 flex justify-end text-xs">
                                                <button type="submit" name="mosali_add_psn_step_three_conjoint" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>Remplissier & téléverser le document</span>
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Acte de mariage
                                        </label>
                                        <p class="ms-4 py-2 px-1 text-md font-medium transition duration-150">
                                            <?= strtolower(str_replace([' ',"'"],'-',$mariageDocumentsPsn->nom_fichier_original)) ?>.pdf
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if($Personnel->nbr_enfant > 0): ?>
                                    <?php for($i = 1; $i <= $Personnel->nbr_enfant; $i++): ?>
                                        <?php $enfantFor = "enfantDocumentPsn" . $i ?>
                                        <!-- Bloc de téléchargement - -->
                                        <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">                          
                                            <?php if(!$$enfantFor): ?>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Acte de naissance - Enfant <?= $i ?>
                                                    </label>
                                                    
                                                    <input type="file" accept=".pdf" name="mosali_doc_enfant" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer" required>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-7">
                                                        <div>
                                                            <label for="nom_complet" class="block text-sm font-medium text-gray-700 mb-1">Nom complet de l'enfant</label>
                                                            <input type="text" id="nom_complet" name="nom_complet" value="<?=  Helper::getData($_POST, 'nom_complet') ?>" required class="form-input" placeholder="Ex: Kalinde Divin(e)">
                                                        </div>
                                                        <div>
                                                            <label for="sexe" class="block text-sm font-medium text-gray-700 mb-1">Sexe</label>
                                                            <select id="sexe" name="sexe" required class="form-input">
                                                                <option value="">Sélectionner...</option>
                                                                <?php foreach(ARRAY_SEXE as $sexe): ?>
                                                                    <option value="<?= $sexe ?>" <?= Helper::getSelectedValue('sexe', $sexe) ?> ><?= $sexe ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="scolarise" class="block text-sm font-medium text-gray-700 mb-1">Scolarisé?</label>
                                                            <select id="scolarise" name="scolarise" required class="form-input">
                                                                <option value="">Sélectionner...</option>
                                                                <?php foreach(ARRAY_SCOLARISE as $scolarise): ?>
                                                                    <option value="<?= $scolarise ?>" <?= Helper::getSelectedValue('scolarise', $scolarise) ?> ><?= $scolarise ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <div>
                                                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                                            <input type="date" id="date_naissance" name="date_naissance" value="<?=  Helper::getData($_POST, 'date_naissance') ?>" required class="form-input">
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="mt-4 pt-6 border-t border-gray-200 flex justify-end text-xs">
                                                        <button type="submit" name="mosali_add_psn_step_three_enfant<?= $i ?>" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                                            <i class="fas fa-check-circle"></i>
                                                            <span>Remplissier & téléverser le document</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                    <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Acte de naissance - Enfant <?= $i ?>
                                                </label>
                                                <p class="ms-4 py-2 px-1 text-md font-medium transition duration-150">
                                                    <?= strtolower(str_replace([' ',"'"],'-',$$enfantFor->nom_fichier_original . "-enfant-". $i)) ?>.pdf
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>                                           
                                <?php endif; ?>
                            <?php endif ?>
                        </div>

                    </div>

                    <!-- Bouton de Soumission (fixe en bas) -->
                    <!-- <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                            <i class="fas fa-check-circle"></i>
                            <span>Créer la Fiche Personnel</span>
                        </button>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>