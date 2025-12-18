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
                    <div class="w-full space-y-3 pt-4 border-t border-gray-100">
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut de l'employé</label>
                        <select id="status" name="status" class="form-input text-sm">
                            <option value="actif" selected>Actif</option>
                            <option value="conge">En Congé</option>
                            <option value="licencie">Licencié</option>
                        </select>
                    </div>
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
                                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                    <input type="text" id="firstName" name="firstName" value="<?= $data['firstName'] ?? '' ; ?>" required class="form-input" placeholder="Ex: Jean">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">PostNom & prénom</label>
                                    <input type="text" id="lastName" name="lastName" value="<?= $data['lastName'] ?? '' ; ?>" required class="form-input" placeholder="Ex: Kingunza Nkela">
                                </div>
                                
                                <div>
                                    <label for="dateOfBirth" class="block text-sm font-medium text-gray-700 mb-1">Date de Naissance</label>
                                    <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?= $data['dateOfBirth'] ?? '' ; ?>" required class="form-input">
                                </div>
                                <div>
                                    <label for="maritalStatus" class="block text-sm font-medium text-gray-700 mb-1">État Civil</label>
                                    <select id="maritalStatus" name="maritalStatus" class="form-input">
                                        <?php foreach(ARRAY_MARIAL_STATUS as $maritalstatus): ?>
                                            <option value="<?= $maritalstatus ?>" <?= $data['maritalStatus'] ?? '' ; ?> ><?= $maritalstatus ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse Complète</label>
                                    <input type="text" id="address" name="address" value="<?= $data['address'] ?? '' ; ?>" class="form-input" placeholder="Ex: 123, Avenue de la Liberté, Kinshasa">
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone Personnel</label>
                                    <input type="tel" id="phone" name="phone" value="<?= $data['phone'] ?? '' ; ?>" class="form-input" placeholder="Ex: +33 6 12 34 56 78">
                                </div>
                                <div>
                                    <label for="personalEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Personnel</label>
                                    <input type="email" id="personalEmail" name="personalEmail" value="<?= $data['personalEmail'] ?? '' ; ?>" class="form-input" placeholder="Ex: jean.dupont.perso@mail.com">
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
                                    <input type="text" id="employeeId" name="employeeId" value="<?= $data['employeeId'] ?? '' ; ?>" required class="form-input" placeholder="Ex: RH4578">
                                </div>
                                <div>
                                    <label for="hireDate" class="block text-sm font-medium text-gray-700 mb-1">Date d'Embauche</label>
                                    <input type="date" id="hireDate" name="hireDate" value="<?= $data['hireDate'] ?? '' ; ?>" required class="form-input">
                                </div>

                                <div>
                                    <label for="jobTitle" class="block text-sm font-medium text-gray-700 mb-1">Poste (Titre)</label>
                                    <input type="text" id="jobTitle" name="jobTitle" value="<?= $data['jobTitle'] ?? '' ; ?>" required class="form-input" placeholder="Ex: Responsable RH Principal">
                                </div>
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Service / Direction</label>
                                    <select id="department" name="department" value="<?= $data['department'] ?? '' ; ?>" required class="form-input">
                                        <?php foreach(ARRAY_DEPARTMENTS as $department): ?>
                                            <option value="<?= $department ?>" <?= $data['department'] ?? '' ; ?> ><?= $department ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="contractType" class="block text-sm font-medium text-gray-700 mb-1">Type de Contrat</label>
                                    <select id="contractType" name="contractType" value="<?= $data['contractType'] ?? '' ; ?>" required class="form-input">
                                        <?php foreach(ARRAY_CONTRACTS as $contrat): ?>
                                            <option value="<?= $contrat ?>" <?= $data['contrat'] ?? '' ; ?> ><?= $contrat ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grade / Échelon</label>
                                    <input type="text" id="grade" name="grade" value="<?= $data['grade'] ?? '' ; ?>" class="form-input" placeholder="Ex: A1 / Échelon 5">
                                </div>
                            </div>

                            <h4 class="text-lg font-semibold text-gray-700 mt-8 border-t pt-4">Informations de Contact Professionnel</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="workEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Professionnel</label>
                                    <input type="email" id="workEmail" name="workEmail" value="<?= $data['workEmail'] ?? '' ; ?>" class="form-input" placeholder="Ex: j.dupont@gouv.fr">
                                </div>
                                <div>
                                    <label for="manager" class="block text-sm font-medium text-gray-700 mb-1">Supérieur Hiérarchique (ID)</label>
                                    <input type="text" id="manager" name="manager" value="<?= $data['manager'] ?? '' ; ?>" class="form-input" placeholder="Ex: #RH1001 (Le chef de service)">
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mutu_add_psn_step_two" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
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
                        <form action="" method="post">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] border-b pb-2">Téléchargement des Pièces Justificatives (Optionnel)</h3>
                            
                            <p class="text-sm text-gray-600">Veuillez télécharger les documents essentiels. Vous pourrez ajouter d'autres documents plus tard sur la fiche détaillée.</p>

                            <div id="document-upload-list" class="space-y-4">
                                <!-- Bloc de téléchargement 1 -->
                                <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-passport mr-2 text-[var(--color-primary)]"></i> Pièce d'Identité
                                    </label>
                                    <input type="file" name="doc_identity" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer">
                                </div>
                                <!-- Bloc de téléchargement 2 -->
                                <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-graduation-cap mr-2 text-[var(--color-primary)]"></i> Diplôme Principal
                                    </label>
                                    <input type="file" name="doc_diploma" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer">
                                </div>
                                <!-- Bloc de téléchargement 3 -->
                                <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-file-contract mr-2 text-[var(--color-primary)]"></i> Contrat de Travail (Signé)
                                    </label>
                                    <input type="file" name="doc_contract" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer">
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Créer la Fiche Personnel</span>
                                </button>
                            </div>
                        </form>

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