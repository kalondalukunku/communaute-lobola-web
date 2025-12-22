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
                📋 Fiches du Personnel (<?= $totalrecords ?>)
            </h1>
            <div class="flex items-center space-x-3">
                <!-- Bouton d'Action Primaire (Orange) -->
                <a href="/psn/add" class="flex items-center space-x-2 bg-[var(--color-primary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                    <i class="fas fa-user-plus"></i>
                    <span>Ajouter un Personnel</span>
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
                        'actif' => 'Actif',
                        'conge' => 'En Congé',
                        'retraite' => 'Retraité',
                        'inactif' => 'Inactif',
                    ];

                    $current_status = $_GET['stt'] ?? 'actif';

                    foreach ($psnStatus as $key => $label) 
                    {
                        $is_active = ($current_status === $key);

                        $active_class = $is_active
                            ? 'bg-[var(--color-primary)] text-white shadow-lg filter-btn' // Active
                            : 'text-gray-600 hover:bg-gray-100 filter-btn'; // Inactive

                        $new_params = $_GET;

                        $new_params['stt'] = $key;

                        if(isset($new_params['page'])) unset($new_params['page']);
                        // if(isset($new_params['q'])) unset($new_params['q']);

                        $url_params = http_build_query($new_params);

                        echo "<a href=\"?{$url_params}\" class=\"px-3 py-1.5 md:px-4 md:py-2 text-xs font-medium rounded-lg {$active_class} flex-shrink-0 transition duration-150 ease-in-out\">{$label}</a>";
                    }
                    ?>
            </div>
        </div>

        <?php if(count($allPersonnels) > 0): ?>
            <!-- Tableau des Personnels (Vue Principale) -->
            <div class="card-container overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Personnel
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Fonction / Service
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Date d'Entrée
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                            <?php foreach($allPersonnels as $personnel): ?>
                                <tr class="personnel-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-[var(--color-primary)] mb-4 shadow-lg" 
                                                src="https://placehold.co/300x300/1565C0/FFFFFF?text=<?= Helper::getFirstLetter($personnel->nom) . Helper::getFirstLetter($personnel->postnom) ?>" 
                                                alt="Photo du Personnel"
                                                onerror="this.onerror=null; this.src='https://placehold.co/300x300/1565C0/FFFFFF?text=JD'">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= $personnel->nom .' '. $personnel->postnom  ?></div>
                                                <div class="text-sm text-gray-500"><?= $personnel->email ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <div class="text-sm text-gray-900"><?= $personnel->poste_actuel ?></div>
                                        <div class="text-sm text-gray-500"><?= $personnel->nom_service ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                        <?= Helper::formatDate($personnel->date_engagement) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= Helper::returnStatutPsnStyle($personnel->statut_emploi) ?>-100 text-<?= Helper::returnStatutPsnStyle($personnel->statut_emploi) ?>-800">
                                            <?= $personnel->statut_emploi ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                        <a href="psn/shw/<?= $personnel->personnel_id ?>" class="text-[var(--color-primary)] hover:text-blue-700" title="Voir Fiche">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="psn/edt/<?= $personnel->personnel_id ?>" class="text-gray-500 hover:text-[var(--color-secondary)]" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- <button class="text-red-500 hover:text-red-700" title="Archiver">
                                            <i class="fas fa-archive"></i>
                                        </button> -->
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
                📋 Aucun fiche du personnel trouvé !
            </h1>
        <?php endif; ?>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>