<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <!-- Main Content -->
    <main class="flex-grow min-h-screen">
        <!-- Header de la liste -->
         <header class="h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 flex justify-between items-center sticky top-0 z-40">
            <!-- Bouton Hamburger -->
            <button id="openSidebar" class="lg:hidden w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-primary hover:bg-gray-100 transition shadow-sm">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="">
                <h1 class="font-serif text-xl md:text-md font-bold text-primary"><?= $membre->nom_postnom ?></h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Suivi détaillé de ses activités</p>
            </div>
            
            <div class="flex items-center gap-6">                
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
        <!-- <header class="h-24 bg-paper border-b border-gray-100 px-8 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="font-serif text-2xl font-bold italic text-primary">Membres de la Communauté</h1>
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest italic">Gestion et suivi de progression en temps réel</p>
            </div>
        </header> -->

        <div class="p-8 max-w-7xl mx-auto space-y-8">
            <!-- Header: Profil & Stats Globales -->
            <div class="relative overflow-hidden rounded-[3rem] color-border shadow-2xl p-8 md:p-12">
                <!-- Décor de fond -->
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col md:flex-row items-center gap-10">
                    <!-- Avatar Large avec Progress Ring -->
                    <div class="relative group">
                        <svg class="w-40 h-40 transform -rotate-90">
                            <circle cx="80" cy="80" r="74" stroke="currentColor" stroke-width="6" fill="transparent" class="text-gray-100" />
                            <circle cx="80" cy="80" r="74" stroke="currentColor" stroke-width="8" fill="transparent" 
                                    stroke-dasharray="<?= 2 * pi() * 74 ?>" 
                                    stroke-dashoffset="<?= (2 * pi() * 74) * (1 - $suiviDetails['stats']['progress_bar'] / 100) ?>" 
                                    stroke-linecap="round"
                                    class="text-primary transition-all duration-1000 ease-out" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center p-4">
                            <?php if($membre->path_profile): ?>
                                <img src="../../<?= $membre->path_profile ?>" class="w-full h-full rounded-full object-cover shadow-inner" alt="Profile" />
                            <?php else: ?>
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-4xl italic shadow-lg">
                                    <?= substr($membre->nom_postnom, 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Badge Score flottant -->
                        <div class="absolute -bottom-2 right-6 bg-white px-4 py-1.5 rounded-full shadow-lg border border-gray-50 flex items-center gap-2">
                            <span class="text-xs font-black text-[#000f0e] uppercase tracking-widest">Score</span>
                            <span class="text-sm font-black text-primary"><?= $suiviDetails['stats']['progress_bar'] ?>%</span>
                        </div>
                    </div>

                    <!-- Infos & Quick Stats -->
                    <div class="flex-grow text-center md:text-left space-y-6">
                        <div>
                            <h1 class="text-3xl font-serif font-black text-primary italic mb-2 tracking-tight">
                                <?= $membre->nom_postnom ?>
                            </h1>
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 items-center">
                                <span class="flex items-center gap-2 text-gray-500 bg-gray-50 px-4 py-1.5 rounded-full text-[11px] font-medium">
                                    <i class="far fa-envelope text-primary"></i> <?= $membre->email ?>
                                </span>
                                <span class="flex items-center gap-2 text-gray-500 bg-gray-50 px-4 py-1.5 rounded-full text-[11px] font-medium">
                                    <i class="far fa-clock text-primary"></i> Dernière activité : <?= $suiviDetails['stats']['last_activity'] ? Helper::formatDate2($suiviDetails['stats']['last_activity']) : 'Jamais' ?>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-w-2xl">
                            <div class="bg-secondary rounded-[2rem] p-5 text-center border border-transparent hover:border-primary/20 transition-all">
                                <span class="block text-[10px] font-black text-gray-400 uppercase mb-1">Enseignements Lus</span>
                                <span class="text-3xl font-black text-primary"><?= $suiviDetails['stats']['read_count'] ?></span>
                            </div>
                            <div class="bg-secondary rounded-[2rem] p-5 text-center border border-transparent hover:border-secondary/20 transition-all">
                                <span class="block text-[10px] font-black text-gray-400 uppercase mb-1">Enseignements Non Lus</span>
                                <span class="text-3xl font-black text-white"><?= $suiviDetails['stats']['total_to_read'] - $suiviDetails['stats']['read_count'] ?></span>
                            </div>
                            <div class="hidden sm:block bg-primary text-white rounded-[2rem] p-5 text-center shadow-lg shadow-primary/20">
                                <span class="block text-[10px] font-black text-[#000f0e] uppercase mb-1">Total Actifs</span>
                                <span class="text-3xl text-[#000f0e] font-black"><?= $suiviDetails['stats']['total_to_read'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu : Onglets ou Colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- SECTION : ENSEIGNEMENTS LUS -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between px-4">
                        <h2 class="text-xl font-serif font-bold text-primary italic flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <i class="fas fa-check text-xs text-primary"></i>
                            </span>
                            Déjà Ecoutés
                        </h2>
                        <!-- <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-full uppercase">Historique</span> -->
                    </div>

                    <div class="space-y-4">
                        <?php if(!empty($suiviDetails['enseignements_lus'])): ?>
                            <?php foreach($suiviDetails['enseignements_lus'] as $item): ?>
                            <div class="group rounded-[1.5rem] p-5 color-border shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-bold text-white text-sm leading-tight mb-1"><?= $item['title'] ?></h4>
                                    <p class="text-[10px] text-gray-400 font-medium">
                                        Lu le <?= Helper::formatDate2($item['viewed_at']) ?>
                                        <span class="mx-1">•</span> 
                                        <?= Helper::formatDurationReadable($item['duration_minutes']) ?>
                                        <!-- <span class="mx-1">•</span> 
                                        <?= $item['serie'] ?> -->
                                    </p>
                                </div>
                                <a href="/enseignement/show/<?= $item['enseignement_id'] ?>" class="text-white group-hover:text-[#cfbb30] group-hover:translate transition-all" target="_blank">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-[2rem]">
                                <p class="text-gray-400 text-sm italic">Aucun enseignement lu pour le moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SECTION : ENSEIGNEMENTS NON LUS -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between px-4">
                        <h2 class="text-xl font-serif font-bold text-primary italic flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-xs text-primary"></i>
                            </span>
                            À Ecouter
                        </h2>
                        <!-- <span class="px-3 py-1 text-primary text-[10px] font-black rounded-full uppercase">En attente</span> -->
                    </div>

                    <div class="space-y-4">
                        <?php if(!empty($suiviDetails['enseignements_non_lus'])): ?>
                            <?php foreach($suiviDetails['enseignements_non_lus'] as $item): ?>
                            <div class="group rounded-[1.5rem] p-5 color-border shadow-sm hover:shadow-md transition-all flex items-center gap-4 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-secondary opacity-20"></div>
                                <div class="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center text-secondary">
                                    <!-- <i class="fas fa-lock text-sm"></i> -->
                                <i class="fa-solid fa-ear-listen text-sm text-primary"></i>
                                </div>
                                <div class="flex-grow text-left">
                                    <h4 class="font-bold text-white text-sm leading-tight mb-1"><?= $item['title'] ?></h4>
                                    <span class="px-2 py-0.5 bg-secondary/5 text-gray-400 text-[9px] font-bold rounded uppercase">Non consulté</span>
                                </div>
                                <a href="/enseignement/show/<?= $item['enseignement_id'] ?>" class="text-gray-200 group-hover:text-[#cfbb30] group-hover:translate-x-1 transition-all" target="_blank">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-10 bg-primary/5 border-2 border-primary/10 rounded-[2rem]">
                                <i class="fas fa-trophy text-primary text-3xl mb-3"></i>
                                <p class="text-primary font-bold italic">Félicitations ! Tous les enseignements ont été lus.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>
    
</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>