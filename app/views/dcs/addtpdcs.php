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
                <a href="<?= RETOUR_EN_ARRIERE ?>" class="text-gray-400 hover:text-[var(--color-secondary)] mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
                Ajouter un nouveau type de document
            </h1>
        </header>
        
        <!-- Formulaire principal -->
        <div id="addPersonnelForm" class="grid grid-cols-1 lg:grid-cols-2 gap-4 px-9" enctype="multipart/form-data">

            <!-- Colonne Droite: Onglets de Saisie (2/3) -->
            <div class="lg:col-span-2">
                <div class="card-container p-6">

                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <form action="" method="post">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="nom_type" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                    <input type="text" id="nom_type" name="nom_type" value="<?=  Helper::getData($_POST, 'nom_type') ?>" required class="form-input" placeholder="Ex: Contrat de travail">
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                                    <select id="category" name="category" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_DOC_CATEGORY as $category): ?>
                                            <option value="<?= $category ?>" <?= Helper::getSelectedValue('category', $category) ?> ><?= $category ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="est_obligatoire" class="block text-sm font-medium text-gray-700 mb-1">Est obligatoire?</label>
                                    <select id="est_obligatoire" name="est_obligatoire" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach(ARRAY_ISREQUIRED as $est_obligatoire): ?>
                                            <option value="<?= $est_obligatoire ?>" <?= Helper::getSelectedValue('est_obligatoire', $est_obligatoire) ?> ><?= $est_obligatoire ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="duree_validite_jours" class="block text-sm font-medium text-gray-700 mb-1">Durée de validité (en jours)</label>
                                    <input type="number" id="duree_validite_jours" name="duree_validite_jours" value="<?=  Helper::getData($_POST, 'duree_validite_jours') ?>" class="form-input" placeholder="Ex: 734">
                                </div>
                                
                                <div>
                                    <label for="delai_alerte_jours" class="block text-sm font-medium text-gray-700 mb-1">Délai d'alerte (en jours)</label>
                                    <input type="number" id="delai_alerte_jours" name="delai_alerte_jours" value="<?=  Helper::getData($_POST, 'delai_alerte_jours') ?>" class="form-input" placeholder="Ex: 28">
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <input type="text" id="description" name="description" value="<?=  Helper::getData($_POST, 'description') ?>" class="form-input" placeholder="Ex: Accord légal définissant les termes de l'emploi.">
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mutu_add_tp_dcs" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Ajouter un type de document</span>
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