<?php 
    $title = "Admin - Tableau de bord général";
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

                <div class="pl-2">
                    <h1 class="font-serif text-xl md:text-md font-bold text-primary">Tableau de bord</h1>
                    <p class="text-xs text-gray-400 mt-1 font-medium italic">Accueillez et gérez les âmes du sanctuaire</p>
                </div>
                
                
                <div class="flex items-center gap-3 lg:gap-6">
                    <div class="flex gap-2">
                        <button class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition relative">
                            <i class="far fa-bell"></i>
                            <span class="absolute top-3 right-3 w-2 h-2 bg-primary rounded-full border-2 border-white"></span>
                        </button>
                        <a href="add" class="hidden md:flex bg-primary text-paper font-bold text-[10px] tracking-widest px-6 py-3 rounded-2xl shadow-xl shadow-secondary/10 hover:scale-105 transition-transform items-center">
                            Nouvel administrateur
                        </a>
                        <a href="add" class="md:hidden w-11 h-11 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </header>
        
            <div class="p-10 space-y-10">
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
                                <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Enseignements</p>
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
                                <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Revenus Global</p>
                                <h3 class="text-white text-xl leading-none"><?= $totalPayment ?>$</h3>
                            </div>
                            <div class="w-12 h-12 bg-paper text-green-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="text-[10px] font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">+5.4%</span>
                        </div>
                    </div>

                    <div class="card-stat p-6 color-border rounded-[2.5rem]">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[10px] uppercase tracking-tighter text-gray-400 font-black mb-2">Taux d'engagement</p>
                                <h3 class="text-white text-xl leading-none"><?= $tauxEngagement ?>%</h3>
                            </div>
                            <div class="w-12 h-12 bg-paper text-primary rounded-2xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="text-[10px] font-bold text-primary bg-white px-2 py-1 rounded-lg">Excellent</span>
                        </div>
                    </div>
                </div>

                <!-- <div class="bg-paper rounded-[3rem] shadow-xl shadow-black/[0.02] color-border overflow-hidden">
                    <div class="px-10 py-8 color-border-b flex items-center justify-between">
                        <div class="flex items-center gap-8">
                            <button class="relative font-bold text-sm text-primary after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-full after:h-1 after:bg-primary after:rounded-full">Tous les membres</button>
                            <button class="font-bold text-sm text-gray-400 hover:text-primary transition">Actifs</button>
                            <button class="font-bold text-sm text-gray-400 hover:text-primary transition flex items-center gap-2">
                                En attente <span class="bg-amber-100 text-amber-600 text-[10px] px-2 py-0.5 rounded-full">42</span>
                            </button>
                        </div>
                        <div class="flex gap-3">
                            <button class="p-3 bg-gray-50 rounded-2xl text-gray-500 hover:bg-gray-100 transition"><i class="fas fa-filter"></i></button>
                            <button class="p-3 bg-gray-50 rounded-2xl text-gray-500 hover:bg-gray-100 transition"><i class="fas fa-download"></i></button>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-[#001411] color-border-b">
                                    <th class="pl-5 pr-3 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Identité</th>
                                    <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Type d'engagement</th>
                                    <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Statut actuel</th>
                                    <th class="px-3 py-3 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Dernière activité</th>
                                    <th class="pl-3 pr-5 py-6 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 text-right">Options</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
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
                                                                    <span class="text-sm font-bold text-white"><?= $membre->modalite_engagement ?></span>
                                                                    <span class="text-[10px] text-gray-400">Renouvellement : <?= Helper::formatDate($membre->date_expiration) ?></span>
                                                                    <?php if($membre->status === ARRAY_STATUS_MEMBER[0] || $membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                                                                    <span class="text-[10px] text-amber-500 font-bold">Paiement à confirmer</span>
                                                                    <?php endif; ?>
                                                            </div>
                                                            </td>
                                                            <td class="px-3 py-3">
                                                            <span class="color-border text-xs p-2 rounded-xl text-primary">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> <?= $membre->status === ARRAY_STATUS_MEMBER[0] || $membre->status === ARRAY_STATUS_MEMBER[1] ? 'A vérifier' : '' ?>  <?= $membre->status === ARRAY_STATUS_MEMBER[2] ? 'Actif' : '' ?>
                                                            </span>
                                                            </td>
                                                            <td class="px-3 py-3">
                                                            <p class="text-sm font-medium text-white">Aujourd'hui, 09:12</p>
                                                            <p class="text-[10px] text-primary font-bold">Enseignement #42</p>
                                                            </td>
                                                            <td class="pl-6 pr-10 py-5">
                                                            <div class="flex justify-end gap-2">
                                                                    <?php if($membre->status === ARRAY_STATUS_MEMBER[2]): ?>
                                                                    <a href="../admin/membre/<?= $membre->member_id ?>" class="bg-secondary text-primary text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-secondary/20 hover:scale-105 transition">Voir</a>
                                                                    <?php else: ?>
                                                                            <form action="" method="post">
                                                                                    <button type="submit" name="cllil_vwfl<?= $membre->member_id ?>" class="bg-secondary text-primary text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg shadow-secondary/20 hover:scale-105 transition">Approuver</button>
                                                                            </form>
                                                                    <?php endif; ?>
                                                            </div>
                                                            </td>
                                                    </tr>
                                            <?php endforeach; ?>
                                    <?php endif; ?>   
                            </tbody>
                        </table>
                    </div>

                    <div class="px-10 py-8 bg-[#001411] flex items-center justify-between">
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Page 1 sur 128</p>
                        <div class="flex gap-2">
                            <button class="w-10 h-10 rounded-xl color-border flex items-center justify-center text-gray-400 hover:bg-white transition"><i class="fas fa-chevron-left text-[10px]"></i></button>
                            <button class="w-10 h-10 rounded-xl bg-secondary text-primary font-bold text-xs shadow-lg shadow-secondary/10">1</button>
                            <button class="w-10 h-10 rounded-xl color-border flex items-center justify-center text-gray-400 hover:bg-white transition text-xs font-bold">2</button>
                            <button class="w-10 h-10 rounded-xl color-border flex items-center justify-center text-gray-400 hover:bg-white transition text-xs font-bold">3</button>
                            <button class="w-10 h-10 rounded-xl color-border flex items-center justify-center text-gray-400 hover:bg-white transition"><i class="fas fa-chevron-right text-[10px]"></i></button>
                        </div>
                    </div>
                </div> -->
            </div>
        </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>