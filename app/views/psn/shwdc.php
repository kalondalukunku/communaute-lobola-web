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
        
        <!-- Header de la Fiche Détaillée -->
        <header class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center mb-4 md:mb-0">
                <a href="<?= RETOUR_EN_ARRIERE ?>" class="text-gray-400 hover:text-[var(--color-secondary)] mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
                Fiche Détaillée: <span class="ml-2 text-[var(--color-secondary)]"><?= $Personnel->nom .' '. $Personnel->postnom ?></span>
            </h1>
            <div class="flex items-center space-x-3">
                <button class="flex items-center space-x-2 text-sm bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                    <i class="fas fa-download"></i>
                    <span>Télécharger tous ses documents</span>
                </button>
            </div>
        </header>
        
        <!-- Contenu de la Fiche (Structure en Grille Responsive) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Colonne Gauche: Résumé et Avatar (1/3) -->
            <div class="lg:col-span-1">
                <div class="card-container p-6 flex flex-col items-center">
                    
                    <!-- Avatar et Nom -->
                    <img class="h-32 w-32 rounded-full object-cover border-4 border-[var(--color-primary)] mb-4 shadow-lg" 
                         src="https://placehold.co/300x300/1565C0/FFFFFF?text=<?= Helper::getFirstLetter($Personnel->nom) . Helper::getFirstLetter($Personnel->postnom) ?>" 
                         alt="Photo du Personnel"
                         onerror="this.onerror=null; this.src='https://placehold.co/300x300/1565C0/FFFFFF?text=JD'">
                    
                    <h2 class="text-2xl font-bold text-gray-900"><?= $Personnel->nom .' '. $Personnel->postnom ?></h2>
                    <p class="text-md text-gray-600 mb-4"><?= $Personnel->nom_poste ?></p>
                    
                    <!-- Informations Clés -->
                    <div class="w-full space-y-3 pt-4 border-t border-gray-100">
                        <div class="flex justify-between text-sm text-gray-700">
                            <span class="font-medium"><i class="fas fa-id-badge mr-2 text-[var(--color-primary)]"></i> Matricule:</span>
                            <span><?= $Personnel->matricule ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span class="font-medium"><i class="fas fa-building mr-2 text-[var(--color-primary)]"></i> Service:</span>
                            <span><?= $Personnel->nom_service ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span class="font-medium"><i class="fas fa-clock mr-2 text-[var(--color-primary)]"></i> Ancienneté:</span>
                            <span><?= $anciennete ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span class="font-medium"><i class="fas fa-star mr-2 text-[var(--color-primary)]"></i> Statut:</span>
                            <span class="px-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= Helper::returnStatutPsnStyle($Personnel->statut_emploi) ?>-100 text-<?= Helper::returnStatutPsnStyle($Personnel->statut_emploi) ?>-800">
                                <?= $Personnel->statut_emploi ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne Droite: Onglets Détaillés (2/3) -->
            <div class="lg:col-span-2">
                <div class="card-container p-6">
                    
                    <!-- Barre d'Onglets -->
                    <div class="border-b border-gray-200 mb-6 flex space-x-6 overflow-x-auto">
                        <button id="tab-1" data-tab="personnel" class="tab-button py-2 px-1 text-sm font-medium transition duration-150 active-tab">
                            <i class="fas fa-folder-open text-[var(--color-primary)] mr-1"></i> Documents du personnel
                        </button>
                        <?php if($Personnel->statut_marital === ARRAY_MARIAL_STATUS[1]): ?>
                            <button id="tab-2" data-tab="professionnel" class="tab-button py-2 px-1 text-sm font-medium text-gray-500 hover:text-[var(--color-secondary)] transition duration-150">
                                <i class="fas fa-briefcase mr-2"></i> Documents du conjoint(e)
                            </button>
                            <?php if($Personnel->nbr_enfant > 0): ?>
                                <button id="tab-3" data-tab="documents" class="tab-button py-2 px-1 text-sm font-medium text-gray-500 hover:text-[var(--color-secondary)] transition duration-150">
                                    <i class="fas fa-folder-open mr-2"></i> Documents des enfants
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- --------------------------------- -->
                    <!-- 3. Contenu de l'Onglet 1 : Infos Personnelles -->
                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <div class="flex justify-between border-b pb-2">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)]">Actes, Diplômes, Certificats & Autres</h3>
                            
                                <button onclick="openModal()" class="flex items-center text-xs space-x-2 bg-green-600 text-white font-semibold py-2 px-4 rounded-xl hover:bg-green-700 transition duration-150">
                                    <i class="fas fa-upload"></i>
                                    <span>Ajouter un Document</span>
                                </button>
                        </div>

                        <ul class="divide-y divide-gray-200">
                            <?php if(count($allDcsPsn) > 0): ?>
                                <?php foreach($allDcsPsn as $d): ?>
                                    <!-- Document 1: Diplôme -->
                                    <li class="py-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-file-lines text-[var(--color-primary)] mr-3"></i>
                                            <div>
                                                <p class="font-medium text-gray-900"><?= $d->nom_fichier_original ?></p>
                                                <p class="text-xs text-gray-500"><?= ($d->nom_fichier_original === ARRAY_DOC_CLES[1]) ? Helper::returnTxtVwDc2($d->niveau_etude, $d->etablissement, $d->annee_obtention, $d->ville, $d->pays) : Helper::returnTxtVwDc1($d->statut_conformite, $d->date_expiration)   ?></p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <form method="post" class="align-center mt-2">
                                                <button type="submit" name="mosali_vwfl<?= $d->doc_id ?>">
                                                    <a href="" class="text-[var(--color-primary)] hover:text-blue-700" title="Voir Fiche">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </button>
                                            </form>
                                            <form method="post" class="align-center">
                                                <input type="hidden" name="nmfl" value="<?= $d->nom_fichier_original ?>">
                                                <button type="submit" name="mosali_dwfl<?= $d->doc_id ?>" class="text-gray-500 hover:text-gray-700 p-2 rounded-full" title="Télécharger">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <?php if($Personnel->statut_marital === ARRAY_MARIAL_STATUS[1]): ?>
                        <div id="professionnel" class="tab-content space-y-6">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] border-b pb-2">Acte de mariage</h3>
                            
                            <ul class="divide-y divide-gray-200">
                                <?php if(count($allMaris) > 0): ?>
                                    <?php foreach($allMaris as $m): ?>
                                        <!-- Document 1: Diplôme -->
                                        <li class="py-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-hand-holding-heart text-[var(--color-primary)] mr-3"></i>
                                                <div>
                                                    <p class="font-medium text-gray-900"><?= $m->nom_fichier_original ?></p>
                                                    <p class="text-xs text-gray-500">Marié(e) à <?= $m->nom_complet ?>, le <?= Helper::formatDate($m->date_mariage) ?></p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <form method="post" class="align-center mt-2">
                                                    <button type="submit" name="mosali_vwfl_cjt<?= $m->conjoint_id ?>">
                                                        <a href="" class="text-[var(--color-primary)] hover:text-blue-700" title="Voir Fiche">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </button>
                                                </form>
                                                <form method="post" class="align-center">
                                                    <input type="hidden" name="nmfl" value="<?= $m->nom_fichier_original ?>">
                                                    <button type="submit" name="mosali_dwfl_cjt<?= $m->conjoint_id ?>" class="text-gray-500 hover:text-gray-700 p-2 rounded-full" title="Télécharger">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <?php if($Personnel->nbr_enfant > 0): ?>
                            <div id="documents" class="tab-content space-y-6">
                            <h3 class="text-xl font-semibold text-[var(--color-secondary)] border-b pb-2">Actes de naissance</h3>
                            
                            <ul class="divide-y divide-gray-200">
                                <?php if(count($allEnfants) > 0): ?>
                                    <?php foreach($allEnfants as $e): ?>
                                        <!-- Document 1: Diplôme -->
                                        <li class="py-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-baby text-[var(--color-primary)] mr-3"></i>
                                                <div>
                                                    <p class="font-medium text-gray-900"><?= $e->nom_fichier_original .' - '. $e->nom_complet_enfant ?></p>
                                                    <p class="text-xs text-gray-500">Enfant n°<?= $e->ordre_naissance ?>, Né(e) le <?= Helper::formatDate($e->date_naissance) ?></p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <form method="post" class="align-center mt-2">
                                                    <button type="submit" name="mosali_vwfl_eft<?= $e->enfant_id ?>">
                                                        <a href="" class="text-[var(--color-primary)] hover:text-blue-700" title="Voir Fiche">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </button>
                                                </form>
                                                <form method="post" class="align-center">
                                                    <input type="hidden" name="nmfl" value="<?= $e->nom_fichier_original ?>">
                                                    <button type="submit" name="mosali_dwfl_eft<?= $e->enfant_id ?>" class="text-gray-500 hover:text-gray-700 p-2 rounded-full" title="Télécharger">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>

    
    <div id="modalOverlay" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
        
        <!-- Fenêtre Modale -->
        <div id="modalContent" 
             class="bg-white w-full max-w-lg rounded-2xl shadow-2xl transform scale-90 transition-transform duration-300 overflow-hidden">
            
            <!-- En-tête avec bouton Fermer (Croix) -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800">Ajouter un document du personnel <span class="text-[var(--color-primary)]"><?= $Personnel->nom .' '. $Personnel->postnom ?></span></h3>
                <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="" method="post">
                <!-- Contenu -->
                <div class="px-8 py-5">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Choisissez un type de document</label>
                        <select id="type" name="type" class="form-input">
                            <option value="">Sélectionner...</option>
                            <?php foreach($typesDbs as $type): ?>
                                <option value="<?= $type ?>" <?= Helper::getSelectedValue('type', $type) ?> ><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="p-6 bg-gray-50 flex flex-col sm:flex-row gap-3">
                    <button onclick="closeModal()" class="w-full items-center space-x-2 bg-white-600 text-sm font-semibold py-2 px-4 rounded-xl shadow-md border-1 hover:bg-white-800 transition duration-150 transform hover:scale-[1.01]">
                        <i class="fas fa-xmark"></i>
                        <span>Annuler</span>
                    </button>
                    <button name="mosali_psn_add_dcs_step_one" class="w-full items-center space-x-2 bg-[var(--color-secondary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-[var(--color-secondary)] transition duration-150 transform hover:scale-[1.01]">
                        <i class="fas fa-angles-right"></i>
                        <span>Suivant</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>