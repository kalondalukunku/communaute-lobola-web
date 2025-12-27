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
        
        <!-- Header du Contenu -->
        <header class="mb-2 flex flex-col md:flex-row justify-between items-start md:items-center">
            <h1 class="text-xl font-bold text-gray-900 mb-4 md:mb-0">
                <i class="fas fa-user-lock mr-3 text-[var(--color-primary)]"></i> 
                Gestion de présence journalière
            </h1>
        </header>
        
        <!-- Bar d'Outils et Filtres -->
        <div class="card-container p-4 mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-center">
            
            <!-- Recherche Globale -->
            <div class="w-full md:w-1/3 relative">
                <form action="" method="get">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="<?= $_GET['q'] ?? '' ?>" placeholder="Rechercher par Nom & Matricule"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--color-primary)] transition duration-150">
                </form>
            </div>
        </div>

        <?php if(count($allPresences) > 0): ?>
            <!-- Tableau des Personnels (Vue Principale) -->
            <div class="card-container overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employé
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Matricule 
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                            <?php foreach($allPresences as $psn): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">
                                        <i class="fas fa-user-circle mr-2 text-xl text-gray-400"></i> <?= Utils::formatNamePsn($psn) ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-gray-500 hidden sm:table-cell"><?= $psn->matricule ?></td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap justify-center items-center gap-2">
                                            
                                            <!-- Bouton Retard -->
                                            <form action="" method="post">
                                                <button type="submit" name="mosali_prsc_retard_<?= $psn->personnel_id ?>" class="text-xs flex items-center space-x-2 bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-xl hover:bg-amber-600 transition duration-150">
                                                    <i class="fas fa-clock"></i>
                                                    <span>Retard</span>
                                                </button>
                                            </form>

                                            <!-- Bouton Absent -->
                                            <form action="" method="post">
                                                <button type="submit" name="mosali_prsc_absent_<?= $psn->personnel_id ?>" class="text-xs flex items-center space-x-2 bg-red-600 text-white font-semibold py-2 px-4 rounded-xl hover:bg-red-700 transition duration-150">
                                                    <i class="fas fa-user-times"></i>
                                                    <span>Absent</span>
                                                </button>
                                            </form>

                                            <!-- Bouton Congé -->
                                            <form action="" method="post">
                                                <button type="submit" name="mosali_prsc_conge_<?= $psn->personnel_id ?>" class="text-xs flex items-center space-x-2 bg-[var(--color-secondary)] text-white font-semibold py-2 px-4 rounded-xl hover:bg-blue-900 transition duration-150">
                                                    <i class="fas fa-plane"></i>
                                                    <span>Congé</span>
                                                </button>
                                            </form>

                                            <!-- Bouton Maladie -->
                                            <form action="" method="post">
                                                <button type="submit" name="mosali_prsc_maladie_<?= $psn->personnel_id ?>" class="text-xs flex items-center space-x-2 bg-purple-600 text-white font-semibold py-2 px-4 rounded-xl hover:bg-purple-700 transition duration-150">
                                                    <i class="fas fa-briefcase-medical"></i>
                                                    <span>Maladie</span>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between p-4 bg-white rounded-xl shadow-lg border border-gray-100">
            <p class='text-sm text-gray-600'>Page <?= $currentPage ?> sur <?= $totalPages ?> (<?= $totalrecords ?> enregistrements)</p>

            <div class="flex space-x-2">
                <?php Helper::generatePaginationFull($currentPage, $totalPages); ?>
            </div>
        </div>

        <?php else: ?>
            <h1 class="text-md text-center font-bold text-gray-900 pt-8 mb-4 md:mb-0">
                📋 Aucun utilisateur trouvé !
            </h1>
        <?php endif; ?>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>