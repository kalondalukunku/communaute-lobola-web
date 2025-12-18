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
                Ajouter un nouveau Personnel - Étape 2
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
                        
                    </div>
                    
                    <p class="text-sm text-gray-500 text-center mb-6">Taille max : 5MB (JPG, PNG)</p>

                    <!-- Statut (Par défaut à Actif) -->
                    <!-- <div class="w-full flex space-y-2 pt-4 border-t border-gray-100">
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut de l'employé</label>
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-xs text-green-400 font-medium">Système opérationnel</span>
                    </div> -->
                </div>
            </div>

            <!-- Colonne Droite: Onglets de Saisie (2/3) -->
            <div class="lg:col-span-2">
                <div class="card-container p-6">
                    
                    <!-- Barre d'Onglets -->
                    <div class="border-b border-gray-200 mb-6 flex space-x-6 overflow-x-auto">
                        <button type="button" data-tab="personnel" class="tab-button py-2 px-1 text-sm font-medium transition duration-150 active-tab">
                            <i class="fas fa-user-circle mr-2"></i> infos Professionnelles
                        </button>
                    </div>

                    <!-- --------------------------------- -->
                    <!-- 4. Contenu de l'Onglet 2 : Infos Professionnelles -->
                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <form action="" method="post">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] pb-3">Détails du Contrat et du Poste</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="employeeId" class="block text-sm font-medium text-gray-700 mb-1">Matricule Employé</label>
                                    <input type="text" id="employeeId" name="employeeId" value="<?=  Helper::getData($_POST, 'employeeId') ?>" required class="form-input" placeholder="Ex: RH4578">
                                </div>
                                <div>
                                    <label for="hireDate" class="block text-sm font-medium text-gray-700 mb-1">Date d'engagement sous statut</label>
                                    <input type="date" id="hireDate" name="hireDate" value="<?=  Helper::getData($_POST, 'hireDate') ?>" required class="form-input">
                                </div>

                                <div>
                                    <label for="jobTitle" class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
                                    <input type="text" id="jobTitle" name="jobTitle" value="<?=  Helper::getData($_POST, 'jobTitle') ?>" required class="form-input" placeholder="Ex: Responsable RH Principal">
                                </div>
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Service / Direction</label>
                                    <select id="department" name="department" required class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_DEPARTMENTS as $department): ?>
                                            <option value="<?= $department ?>" <?= Helper::getSelectedValue('department', $department) ?> ><?= $department ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                                    <input type="text" id="grade" name="grade" value="<?=  Helper::getData($_POST, 'grade') ?>" class="form-input" placeholder="Ex: A1">
                                </div>
                                <div>
                                    <label for="salaire_base" class="block text-sm font-medium text-gray-700 mb-1">Salaire de base</label>
                                    <input type="number" id="salaire_base" name="salaire_base" value="<?=  Helper::getData($_POST, 'salaire_base') ?>" class="form-input" placeholder="Ex: 500.000">
                                </div>
                                <div>
                                    <label for="primeType" class="block text-sm font-medium text-gray-700 mb-1">Type de Prime</label>
                                    <select id="primeType" name="primeType" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_PRIMES as $primeType): ?>
                                            <option value="<?= $primeType ?>" <?= Helper::getSelectedValue('primeType', $primeType) ?>  ><?= $primeType ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if($Personnel->statut_marital === ARRAY_MARIAL_STATUS[1]): ?>
                                    <div>
                                        <label for="nbr_child" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'enfants</label>
                                        <input type="number" id="nbr_child" name="nbr_child" value="<?=  Helper::getData($_POST, 'nbr_child') ?>" class="form-input" placeholder="Ex: 3">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h4 class="text-lg font-semibold text-gray-700 mt-8 border-t pt-4">Informations de Contact Professionnel</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="workEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Professionnel</label>
                                    <input type="email" id="workEmail" name="workEmail" value="<?=  Helper::getData($_POST, 'workEmail') ?>" class="form-input" placeholder="Ex: j.dupont@gouv.fr">
                                </div>
                                <div>
                                    <label for="manager" class="block text-sm font-medium text-gray-700 mb-1">Supérieur Hiérarchique (Matricule)</label>
                                    <input type="text" id="manager" name="manager" value="<?=  Helper::getData($_POST, 'manager') ?>" class="form-input" placeholder="Ex: #RH1001">
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mutu_add_psn_step_two" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Ajouter les infos profesionnels du personnel</span>
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