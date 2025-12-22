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
                📋 Fiches du Personnel (<?= $totalrecords ?> Actifs)
            </h1>
            <div class="flex items-center space-x-3">
                <!-- Bouton d'Action Primaire (Orange) -->
                <a href="addtpdcs" class="flex items-center space-x-2 bg-[var(--color-primary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                    <i class="fas fa-folder-plus"></i>
                    <span>Ajouter un type des documents</span>
                </a>
            </div>
        </header>
        
        <!-- Bar d'Outils et Filtres -->
        <div class="card-container p-4 mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-center">
            
            <!-- Recherche Globale -->
            <div class="w-full md:w-1/3 relative">
                <form action="" method="get">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="<?= $_GET['q'] ?? '' ?>" placeholder="Rechercher par Nom, ID, ou Fonction..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--color-primary)] transition duration-150">
                </form>
            </div>

            <!-- Filtre Statut -->
            <div class="inline-flex flex-wrap gap-1 end-0 rounded-xl bg-white p-1 shadow-md border border-gray-200">
                <?php
                    $full_uri = $_SERVER['REQUEST_URI'] ?? '/';
                    $path_only = parse_url($full_uri, PHP_URL_PATH);
                    $clean_path = rtrim($path_only, '/');
                    
                    $psnStatus = [
                        'all' => 'Tous',
                        'oui' => 'Oui',
                        'non' => 'Non',
                    ];

                    $current_status = $_GET['isrqrd'] ?? null;

                    foreach ($psnStatus as $key => $label) 
                    {
                        $is_active = ($current_status === $key);

                        $active_class = $is_active
                            ? 'bg-[var(--color-primary)] text-white shadow-lg filter-btn' // Active
                            : 'text-gray-600 hover:bg-gray-100 filter-btn'; // Inactive

                        $new_params = $_GET;

                        $new_params['isrqrd'] = $key;

                        if(isset($new_params['page'])) unset($new_params['page']);

                        $url_params = http_build_query($new_params);

                        echo "<a href=\"?{$url_params}\" class=\"px-3 py-1.5 md:px-4 md:py-2 text-xs font-medium rounded-lg {$active_class} flex-shrink-0 transition duration-150 ease-in-out\">{$label}</a>";
                    }
                    ?>
            </div>
        </div>

        <?php if(count($alltypesdocs) > 0): ?>
            <!-- Tableau des Personnels (Vue Principale) -->
            <div class="card-container overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Libellé
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Validité
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Obligation
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                            <?php foreach($alltypesdocs as $typedoc): ?>
                                <tr class="typedoc-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center">
                                                <i class="fas fa-file-contract"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= $typedoc->nom_type ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <div class="text-sm text-gray-900"><?= $typedoc->duree_validite_jours ?? "--" ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= Helper::returnStatutPsnStyle($typedoc->statut_emploi) ?>-100 text-<?= Helper::returnStatutPsnStyle($typedoc->statut_emploi) ?>-800">
                                            <?= $typedoc->est_obligatoire ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                        <a href="shwtpdcs/<?= $typedoc->type_doc_id ?>" class="text-[var(--color-secondary)] hover:text-blue-700" title="Voir Fiche">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edttpdcs/<?= $typedoc->type_doc_id ?>" class="text-gray-500 hover:text-[var(--color-primary)]" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="text-red-500 hover:text-red-700" title="Archiver">
                                            <i class="fas fa-archive"></i>
                                        </button>
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
                📋 Aucun type des documents trouvé !
            </h1>
        <?php endif; ?>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>