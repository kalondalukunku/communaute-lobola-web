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
            <h1 class="text-2xl font-bold text-gray-900 flex items-center mb-4 md:mb-0">
                <a href="#" class="text-gray-400 hover:text-[var(--color-secondary)] mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
                Ajouter un nouveau Personnel - Étape 1
            </h1>
        </header>
        
        <!-- Formulaire principal -->
        <div id="addPersonnelForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6" enctype="multipart/form-data">

            <!-- Colonne Gauche: Résumé et Téléchargement d'Avatar (1/3) -->
            <div class="lg:col-span-1">
                <div class="card-container p-6 flex flex-col items-center sticky top-[80px]">
                    
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Photo d'Identité</h2>

                    <!-- Zone d'Upload de l'Avatar -->
                    <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-dashed border-gray-300 flex items-center justify-center mb-4 group hover:border-[var(--color-primary)] transition duration-150 relative">
                        <img id="avatarPreview" class="h-full w-full object-cover opacity-50 transition duration-150 group-hover:opacity-70" 
                             src="https://placehold.co/300x300/e2e8f0/64748b?text=Photo" 
                             alt="Aperçu Photo"
                             onerror="this.onerror=null; this.src='https://placehold.co/300x300/e2e8f0/64748b?text=Photo'">
                        <input type="file" id="avatarUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        <div class="absolute text-sm font-medium text-gray-500 group-hover:text-[var(--color-primary)] transition duration-150">
                             <i class="fas fa-camera text-xl mb-1"></i><br>
                             Ajouter
                        </div>
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
                    </div>

                    <!-- --------------------------------- -->
                    <!-- 3. Contenu de l'Onglet 1 : Infos Personnelles -->
                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <form action="" method="post">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] pb-2">Informations d'identité et de contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                    <input type="text" id="firstName" name="firstName" value="<?=  Helper::getData($_POST, 'firstName') ?>" required class="form-input" placeholder="Ex: Jean">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Postnom & Prénom</label>
                                    <input type="text" id="lastName" name="lastName" value="<?=  Helper::getData($_POST, 'lastName') ?>" required class="form-input" placeholder="Ex: Kingunza Nkela">
                                </div>
                                
                                <div>
                                    <label for="dateOfBirth" class="block text-sm font-medium text-gray-700 mb-1">Date de Naissance</label>
                                    <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?=  Helper::getData($_POST, 'dateOfBirth') ?>" required class="form-input">
                                </div>
                                <div>
                                    <label for="sexe" class="block text-sm font-medium text-gray-700 mb-1">Sexe</label>
                                    <select id="sexe" name="sexe" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_SEXE as $sexe): ?>
                                            <option value="<?= $sexe ?>" <?= Helper::getSelectedValue('sexe', $sexe) ?> ><?= $sexe ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="maritalstatus" class="block text-sm font-medium text-gray-700 mb-1">État Civil</label>
                                    <select id="maritalstatus" name="maritalstatus" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_MARIAL_STATUS as $maritalstatus): ?>
                                            <option value="<?= $maritalstatus ?>" <?= Helper::getSelectedValue('maritalstatus', $maritalstatus) ?> ><?= $maritalstatus ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse Complète</label>
                                    <input type="text" id="address" name="address" value="<?=  Helper::getData($_POST, 'address') ?>" class="form-input" placeholder="Ex: 123, Avenue de la Liberté, Kinshasa">
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone Personnel</label>
                                    <input type="tel" id="phone" name="phone" value="<?=  Helper::getData($_POST, 'phone') ?>" class="form-input" placeholder="Ex: +33 6 12 34 56 78">
                                </div>
                                <div>
                                    <label for="personalEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Personnel</label>
                                    <input type="email" id="personalEmail" name="personalEmail" value="<?=  Helper::getData($_POST, 'personalEmail') ?>" class="form-input" placeholder="Ex: jean.dupont.perso@mail.com">
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mutu_add_psn_step_one" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Ajouter un personnel</span>
                                </button>
                            </div>
                        </form>
                            
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>