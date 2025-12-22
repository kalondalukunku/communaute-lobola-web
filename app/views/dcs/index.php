<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
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
                <i class="fas fa-folder-open text-[var(--color-primary)] mr-3"></i>
                Gestion des Documents (<?= $totalrecords ?>)
            </h1>
            <div class="flex items-center space-x-3">
                <!-- Bouton d'Action Primaire (Orange) -->
                <!-- <a href="addtpdcs" class="flex items-center space-x-2 bg-[var(--color-primary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                    <i class="fas fa-folder-plus"></i>
                    <span>Gestion des Documents</span>
                </a> -->
            </div>
        </header>
        
        <!-- Bar d'Outils et Filtres -->
        <div class="card-container p-4 mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-center">
            
            <!-- Recherche Globale -->
            <div class="w-full md:w-1/3 relative">
                <form action="" method="get">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="<?= $_GET['q'] ?? '' ?>" placeholder="Rechercher par nom du document ou du personnel"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--color-primary)] transition duration-150">
                    <!-- <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i> -->
                </form>
            </div>
        </div>

        <?php if(count($alldocs) > 0): ?>
            <!-- Tableau des Personnels (Vue Principale) -->
            <div class="card-container overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employé Associé
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Type
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                            <?php foreach($alldocs as $doc): ?>
                                <tr class="typedoc-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center">
                                                <i class="fas fa-file-contract"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= str_replace([' ',"'"], '_', strtolower($doc->nom_fichier_original .'_'. date('Y', strtotime($doc->date_telechargement)))) ?>.pdf</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= $doc->personnel_nom ." ". $doc->personnel_postnom ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="inline-flex text-sm leading-5 font-semibold rounded-full">
                                            <?= $doc->nom_fichier_original ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                        <form method="post">
                                            <button type="submit" name="mosali_vwfl<?= $doc->doc_id ?>">
                                                <a href="" class="text-[var(--color-primary)] hover:text-blue-700" title="Voir Fiche">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </button>
                                            
                                        </form>
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
                📋 Aucun document trouvé !
            </h1>
        <?php endif; ?>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>