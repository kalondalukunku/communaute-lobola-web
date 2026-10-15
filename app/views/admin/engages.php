<?php
    $engagedSearchIndex = [];
    foreach ($AllEngages as $index => $membre) {
        $engagedSearchIndex[] = [
            'index' => $index,
            'search' => mb_strtolower(implode(' ', [
                $membre->nom_postnom ?? '',
                $membre->email ?? '',
                $membre->modalite_engagement ?? '',
                $membre->statut_engagement ?? '',
                $membre->phone_number ?? '',
                $membre->ville ?? '',
                $membre->niveau_initiation ?? '',
                $membre->genre ?? '',
                $membre->domaine_etude ?? '',
            ]), 'UTF-8'),
            'status' => $membre->admin_filter_status,
        ];
    }

    $title = "Admin - Gestion des membres engagés";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

        <main class="flex-grow flex flex-col min-w-0" x-data="adminTable(<?= htmlspecialchars(json_encode($engagedSearchIndex, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>, 10, <?= htmlspecialchars(json_encode($currentStatus), ENT_QUOTES, 'UTF-8') ?>)">
        <!-- Header Mobile Dédié -->
        <div class="lg:hidden p-4 bg-paper border-b border-slate-200 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <button @click="sidebarOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                <i class="fas fa-bars-staggered"></i>
            </button>
            <div class="flex items-center gap-2">
                <img class="w-7 h-7 rounded-lg" src="<?= ASSETS ?>images/logo.jpg" alt="">
                <span class="font-bold text-sm text-white tracking-tight"><?= SITE_NAME ?></span>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="flex gap-2">
                    <button class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition relative">
                        <i class="far fa-bell"></i>
                        <span class="absolute top-3 right-3 w-2 h-2 bg-primary rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Header -->
        <header class="min-h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 py-4 flex flex-col gap-3 justify-between sm:flex-row sm:items-center sticky top-0 z-40">
        
            <div>
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Membres engangés de la communauté</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Accépter les engagements et gérez les âmes de la communauté</p>
            </div>
            
            <div class="flex items-center gap-6">
                <label class="relative block w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-primary"></i>
                    <input type="search" x-model="search" @input="resetPage()" placeholder="Rechercher un membre..."
                        class="w-full rounded-2xl bg-paper py-3 pl-10 pr-6 text-sm color-border outline-none transition-all focus:ring-2 focus:ring-primary/20" style="color: var(--primary);">
                </label>
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
                            $psnStatus = [
                                'approuve' => 'Approuvé',
                                'non_approuve' => 'En attente',
                                'rejete' => 'Rejeté',
                            ];

                            foreach ($psnStatus as $key => $label) 
                            {
                                $escapedKey = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
                                $escapedLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                                echo "<button type=\"button\" @click=\"setStatus('{$escapedKey}')\" :class=\"activeStatus === '{$escapedKey}' ? 'color-border bg-primary/10 text-primary' : 'text-gray-400 hover:text-primary'\" class=\"relative flex-shrink-0 rounded-2xl color-border px-3 py-1.5 text-xs font-bold transition md:px-4 md:py-2\">
                                        {$escapedLabel}
                                        <span x-show=\"statusCounts['{$escapedKey}']\" x-text=\"statusCounts['{$escapedKey}'] || 0\" class=\"ml-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] font-bold text-[#001411]\"></span>
                                    </button>";
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
                                <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Date d'engagement</th>
                                <th class="pl-3 pr-5 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                                <!-- Membre Actif -->
                                <?php if(count($AllEngages) > 0): ?>
                                        <?php foreach ($AllEngages as $index => $membre): ?>
                                                <tr class="color-border-b row-animate" x-show="isRowVisible(<?= $index ?>)" style="display: none;">
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
                                                                <?php if($membre->status === ARRAY_STATUS_MEMBER[0] && $membre->doc_approuved == 0): ?>
                                                                <span class="text-[10px] text-amber-500 font-bold">Engagement à approuver</span>
                                                                <?php elseif( $membre->status === ARRAY_STATUS_MEMBER[0] && $membre->doc_approuved == 1): ?>
                                                                <span class="text-[10px] text-amber-500 font-bold">Paiement à confirmer</span>
                                                                <?php endif; ?>
                                                        </div>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <span class="color-border text-xs p-2 rounded-xl text-primary">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] ? 'Approuvé' : '' ?>
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1] && $membre->doc_approuved == 0 ? 'Engagement à confirmer' : '' ?>
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1] && $membre->doc_approuved == 1 && !$membre->payment_status ? 'Attente de paiement' : '' ?>
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1] && $membre->doc_approuved == 1 && $membre->payment_status ? 'Déjà payé' : '' ?>
                                                            <?= $membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2] ? 'Rejété' : '' ?>
                                                        </span>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                        <p class="text-xs font-medium text-white">Le <?= Helper::formatDate2($membre->date_engagement) ?></p>
                                                        <p class="text-[10px] text-primary font-bold">Enseignement #42</p>
                                                        </td>
                                                        <td class="pl-6 pr-10 py-5">
                                                        <div class="flex justify-end gap-2">
                                                            <form action="" method="post">
                                                                <button type="submit" name="cllil_vwfl<?= $membre->member_id ?>" class="bg-secondary text-primary text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-secondary/20 hover:scale-105 transition">Voir</button>
                                                            </form>
                                                        </div>
                                                        </td>
                                                </tr>
                                        <?php endforeach; ?>
                                        <tr x-show="filteredRows.length === 0" style="display: none;">
                                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400 italic">Aucun membre ne correspond à votre recherche.</td>
                                        </tr>
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
                <div class="px-7 py-4 bg-[#001411] flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-4">
                        <p class="text-xs text-gray-400">Affichage de <span x-text="filteredRows.length ? (page - 1) * perPage + 1 : 0" class="font-semibold text-white"></span> à <span x-text="Math.min(page * perPage, filteredRows.length)" class="font-semibold text-white"></span> sur <span x-text="filteredRows.length" class="font-semibold text-white"></span> membre(s)</p>
                        <label class="flex items-center gap-2 text-xs text-gray-400">Lignes
                            <select x-model.number="perPage" @change="resetPage()" class="rounded-lg border border-slate-700 bg-secondary px-2 py-1.5 text-white">
                                <option value="10">10</option><option value="25">25</option><option value="50">50</option>
                            </select>
                        </label>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="page > 1 && page--" :disabled="page === 1" class="rounded-lg border border-slate-700 bg-secondary px-3 py-2 text-xs text-white disabled:cursor-not-allowed disabled:opacity-40">Précédent</button>
                        <span class="px-2 text-xs text-gray-400">Page <span x-text="page" class="font-semibold text-white"></span> / <span x-text="totalPages" class="font-semibold text-white"></span></span>
                        <button @click="page < totalPages && page++" :disabled="page === totalPages" class="rounded-lg border border-slate-700 bg-secondary px-3 py-2 text-xs text-white disabled:cursor-not-allowed disabled:opacity-40">Suivant</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>