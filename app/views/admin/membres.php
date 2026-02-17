<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

        <main class="flex-grow flex flex-col min-w-0">
        
        <!-- Header -->
        <header class="h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 flex justify-between items-center sticky top-0 z-40">
            <!-- Bouton Hamburger -->
            <button id="openSidebar" class="lg:hidden w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-primary hover:bg-gray-100 transition shadow-sm">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="hidden xl:block">
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Membres de la communauté</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Accueillez et gérez les âmes du sanctuaire</p>
            </div>
            
            <div class="flex items-center gap-6">
                <form action="" method="get">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-primary"></i>
                        <input type="text" name="q" placeholder="Rechercher un membre..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="pl-10 pr-6 py-3 bg-paper rounded-2xl text-sm color-border focus:ring-2 focus:ring-primary/20 outline-none w-64 transition-all" style="color: var(--primary);">                    
                    </div>
                </form>
                
                <div class="flex gap-2">
                    <button class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition relative">
                        <i class="far fa-bell"></i>
                        <span class="absolute top-3 right-3 w-2 h-2 bg-primary rounded-full border-2 border-white"></span>
                    </button>
                    <!-- <button class="bg-primary text-paper font-bold text-xs tracking-widest px-6 py-3 rounded-2xl shadow-xl shadow-secondary/10 hover:scale-105 transition-transform active:scale-95">
                        Nouveau membre
                    </button> -->
                </div>
            </div>
        </header>

        <div class="p-10 space-y-10">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card-stat p-6 color-border rounded-[2.5rem]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Membres actifs</p>
                            <h3 class="text-white text-xl leading-none"><?= $NbrAllMembres ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">+12%</span>
                        <span class="text-[10px] text-gray-400">vs mois dernier</span>
                    </div>
                </div>

                <div class="card-stat p-6 color-border rounded-[2.5rem]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">En attente</p>
                            <h3 class="text-xl leading-none text-amber-600"><?= $NbrAllMembresAttente ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-paper text-amber-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-lg">Action requise</span>
                    </div>
                </div>

                <div class="card-stat p-6 color-border rounded-[2.5rem]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Membres initiés</p>
                            <h3 class="text-white text-xl leading-none"><?= $NbrAllMembresInities ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-paper text-primary rounded-2xl flex items-center justify-center">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-primary bg-white px-2 py-1 rounded-lg">Excellent</span>
                    </div>
                </div>

                <div class="card-stat p-6 color-border rounded-[2.5rem]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Revenus de ce mois</p>
                            <h3 class="text-white text-xl leading-none"><?= $totalPaymentMonth ?>$</h3>
                        </div>
                        <div class="w-12 h-12 bg-paper text-green-600 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">+5.4%</span>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-paper rounded-[3rem] shadow-xl shadow-black/[0.02] color-border overflow-hidden">
                <div class="px-10 py-8 color-border-b flex items-center justify-between">
                    <div class="flex text-[10px] items-center gap-8">
                        <?php
                            $full_uri = $_SERVER['REQUEST_URI'] ?? '/';
                            $path_only = parse_url($full_uri, PHP_URL_PATH);
                            $clean_path = rtrim($path_only, '/');
                            
                            $psnStatus = [
                                'active' => 'Actif',
                                'att_validation' => 'Attente Intégration',
                                'att_rejete' => 'Intégration Réjété',
                                'suspended' => 'Suspendu',
                                'inactive' => 'Inactif',
                            ];

                            $current_status = $_GET['stt'] ?? 'active';

                            foreach ($psnStatus as $key => $label) 
                            {
                                $is_active = ($current_status === $key);

                                $active_class = $is_active
                                    ? "relative font-bold text-xs text-primary after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-full after:h-1 after:bg-primary after:rounded-full" // Active
                                    : 'font-bold text-xs text-gray-400 hover:text-primary transition'; // Inactive

                                $new_params = $_GET;

                                $new_params['stt'] = $key;

                                if(isset($new_params['page'])) unset($new_params['page']);
                                // if(isset($new_params['q'])) unset($new_params['q']);

                                $url_params = http_build_query($new_params);

                                echo "<a href=\"?{$url_params}\" class=\"px-3 py-1.5 md:px-4 md:py-2 text-xs font-medium color-border rounded-2xl {$active_class} flex-shrink-0 transition duration-150 ease-in-out\">{$label}</a>";
                            }
                            ?>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#001411] color-border-b">
                                <th class="pl-5 pr-3 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Identité</th>
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Niveau initiation</th>
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Statut actuel</th>
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Date d'intégration</th>
                                <th class="pl-3 pr-5 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                                <!-- Membre Actif -->
                                <?php if(count($allMembres) > 0): ?>
                                        <?php foreach ($allMembres as $membre): ?>
                                                <tr class="color-border-b row-animate">
                                                        <td class="pl-5 pr-3 py-5">
                                                        <div class="flex items-center gap-4">
                                                                <div class="w-12 h-12 rounded-2xl text-white border-2 color-border-b flex items-center justify-center font-bold text-secondary text-sm">
                                                                        <img class="w-full" src="../<?= $membre->path_profile ?>">
                                                                </div>
                                                                <div>
                                                                <p class="font-bold text-white text-sm"><?= $membre->nom_postnom ?></p>
                                                                <p class="text-[11px] text-gray-400 tracking-tight"><?= $membre->phone_number ?></p>
                                                                </div>
                                                        </div>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <div class="flex flex-col">
                                                                <span class="text-xs font-bold text-white"><?= $membre->niveau_initiation ?></span>
                                                                <!-- <span class="text-[10px] text-gray-400">Renouvellement : </span> -->
                                                                <?php if($membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                                                                <span class="text-[10px] text-amber-500 font-bold">Intégration à approuver</span>
                                                                <?php elseif( $membre->status === ARRAY_STATUS_MEMBER[2]): ?>
                                                                <span class="text-[10px] text-green-500 font-bold">Intégré</span>
                                                                <?php endif; ?>
                                                        </div>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <span class="color-border text-xs p-2 rounded-xl text-primary">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 
                                                            <?= $membre->status === ARRAY_STATUS_MEMBER[0] || $membre->status === ARRAY_STATUS_MEMBER[1] ? 'A vérifier' : '' ?>  
                                                            <?= $membre->status === ARRAY_STATUS_MEMBER[2] ? 'Actif' : '' ?>
                                                            <?= $membre->status === ARRAY_STATUS_MEMBER[3] ? 'Suspendu' : '' ?>
                                                            <?= $membre->status === ARRAY_STATUS_MEMBER[4] ? 'Réjété' : '' ?>
                                                            <?= $membre->status === ARRAY_STATUS_MEMBER[5] ? 'Inactif' : '' ?>
                                                        </span>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <p class="text-xs font-medium text-white">Le <?= Helper::formatDate2($membre->created_at) ?></p>
                                                        <p class="text-[10px] text-primary font-bold">Enseignement #42</p>
                                                        </td>
                                                        <td class="pl-6 pr-10 py-5">
                                                        <div class="flex justify-end gap-2">
                                                            <a class="bg-secondary text-primary text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-secondary/20 hover:scale-105 transition" href="membre/<?= $membre->member_id ?>">Voir</a>

                                                            <form action="" method="post">
                                                                <input type="hidden" name="cllil_membre_id<?= $membre->member_id ?>" value="<?= $membre->member_id ?>">
                                                                <!-- <button type="submit" name="cllil_membre_delete<?= $membre->member_id ?>" class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-red-500/20 hover:scale-105 transition"><i class="fas fa-trash-can text-[11px]"></i></button> -->
                                                            </form>
                                                        </div>
                                                        </td>
                                                </tr>
                                        <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-5 text-center text-sm text-gray-400 italic">
                                                Aucun membre trouvé pour le statut sélectionné.
                                        </td>
                                    </tr>
                                <?php endif; ?>                               
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="px-7 py-4 bg-[#001411] flex items-center justify-between">
                    <p class="text-[10px] text-gray-400 font-bold tracking-widest">Page <?= $currentPage; ?> sur <?= $totalPages; ?></p>
                    
                    <div class="flex gap-2">
                        <?php Helper::generatePaginationFull($currentPage, $totalPages); ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>