<?php 
    $title = "Gestion des membres engagés";
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

            <div>
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Membres engangés de la communauté</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Accépter les engagements et gérez les âmes de la communauté</p>
            </div>
            
            <div class="flex items-center gap-6">
                <form action="" method="get">
                    <div class="relative hidden xl:block">
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
                            <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Membres engagés</p>
                            <h3 class="text-white text-xl leading-none"><?= $membresEngages ?></h3>
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
                            <h3 class="text-xl leading-none text-amber-600"><?= $NbrAllEngagesAttente ?></h3>
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
                            <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Membres rejétés</p>
                            <h3 class="text-white text-xl leading-none"><?= $membresEngagesRejetes ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-paper text-red-600 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-users-slash text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-1 rounded-lg">Rejetés</span>
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
                                'att_engagement' => 'Attente Engagement',
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
                        <!-- <button class="relative font-bold text-xs text-primary after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-full after:h-1 after:bg-primary after:rounded-full">Tous les membres</button>
                        <button class="font-bold text-xs text-gray-400 hover:text-primary transition">Actifs</button>
                        <button class="font-bold text-xs text-gray-400 hover:text-primary transition flex items-center gap-2">
                            En attente <span class="bg-amber-100 text-amber-600 text-[10px] px-2 py-0.5 rounded-full">42</span>
                        </button> -->
                    </div>
                    <!-- <div class="flex gap-3">
                        <button class="p-2 bg-gray-50 rounded-2xl text-gray-500 hover:bg-gray-100 transition"><i class="text-[13px] fas fa-filter"></i></button>
                        <button class="p-2 bg-gray-50 rounded-2xl text-gray-500 hover:bg-gray-100 transition"><i class="text-[13px] fas fa-download"></i></button>
                    </div> -->
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#001411] color-border-b">
                                <th class="pl-5 pr-3 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Identité</th>
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Engagement</th>
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Statut engagement</th>
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Date d'intégration</th>
                                <th class="pl-3 pr-5 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                                <!-- Membre Actif -->
                                <?php if(count($AllEngages) > 0): ?>
                                        <?php foreach ($AllEngages as $membre): ?>
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
                                                                <span class="text-sm font-bold text-white"><?= $membre->modalite_engagement ?></span>
                                                                <span class="text-[10px] text-gray-400">Renouvellement : <?= Helper::formatDate($membre->date_expiration) ?></span>
                                                                <?php if($membre->status === ARRAY_STATUS_MEMBER[0]): ?>
                                                                <span class="text-[10px] text-amber-500 font-bold">Engagement à approuver</span>
                                                                <?php elseif( $membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                                                                <span class="text-[10px] text-amber-500 font-bold">Paiement à confirmer</span>
                                                                <?php endif; ?>
                                                        </div>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <span class="color-border text-xs p-2 rounded-xl text-primary">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 
                                                            <?= $membre->status === ARRAY_STATUS_MEMBER[0] ? 'A vérifier' : '' ?>  
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] ? 'Approuvé' : '' ?>
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1] ? 'Non approuvé' : '' ?>
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2] ? 'Rejété' : '' ?>
                                                        </span>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <p class="text-xs font-medium text-white">Le <?= Helper::formatDate2($membre->created_at) ?></p>
                                                        <p class="text-[10px] text-primary font-bold">Enseignement #42</p>
                                                        </td>
                                                        <td class="pl-6 pr-10 py-5">
                                                        <div class="flex justify-end gap-2">
                                                            <form action="" method="post">
                                                                <button type="submit" name="cllil_vwfl<?= $membre->member_id ?>" class="bg-secondary text-primary text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-secondary/20 hover:scale-105 transition">Voir</button>
                                                            </form>
                                                            
                                                            <form action="" method="post">
                                                                <input type="hidden" name="cllil_membre_id<?= $membre->member_id ?>" value="<?= $membre->member_id ?>">
                                                                <button type="submit" name="cllil_membre_delete<?= $membre->member_id ?>" class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-red-500/20 hover:scale-105 transition"><i class="fas fa-trash-can text-[11px]"></i></button>
                                                            </form>
                                                                <!-- <button class=""><i class="fas fa-ellipsis-h text-xs"></i></button> -->
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

                                <!-- Membre en Attente -->
                                <!-- <tr class="color-border-b row-animate">
                                        <td class="pl-10 pr-6 py-5">
                                        <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-amber-50 border-2 border-amber-200 flex items-center justify-center font-bold text-amber-600 text-sm">
                                                MA
                                                </div>
                                                <div>
                                                <p class="font-bold text-sm">Marie Angélo</p>
                                                <p class="text-[11px] text-gray-400 tracking-tight">marie.angelo@gmail.com</p>
                                                </div>
                                        </div>
                                        </td>
                                        <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                                <span class="text-sm font-bold text-secondary">Abonnement Mensuel</span>
                                                <span class="text-[10px] text-amber-500 font-bold">Paiement à confirmer</span>
                                        </div>
                                        </td>
                                        <td class="px-6 py-5">
                                        <span class="status-badge bg-amber-50 text-amber-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> À vérifier
                                        </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-400 italic">
                                        Inscription le 02/01
                                        </td>
                                        <td class="pl-6 pr-10 py-5">
                                        <div class="flex justify-end gap-3">
                                                <button class="bg-secondary text-primary text-[10px] font-black px-5 py-2.5 rounded-xl shadow-lg shadow-secondary/20 hover:scale-105 transition">APPROUVER</button>
                                                <button class="btn-action text-red-400"><i class="fas fa-times text-xs"></i></button>
                                        </div>
                                        </td>
                                </tr> -->
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