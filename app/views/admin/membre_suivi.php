<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <!-- Main Content -->
    <main class="flex-grow min-h-screen">
    <!-- Header de la liste -->
        <header class="h-24 bg-paper border-b border-gray-100 px-8 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="font-serif text-2xl font-bold italic text-primary">Membres de la Communauté</h1>
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest italic">Gestion et suivi de progression en temps réel</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                    <input type="text" placeholder="Rechercher un membre..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-secondary/20 w-64 transition-all">
                </div>
            </div>
        </header>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Grille de cartes de membres -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php foreach ($membreSuivi as $member): ?>
                <!-- Member Card -->
                <div class="group rounded-[2rem] color-border p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <!-- Background Decor -->
                    <div class="absolute -right-11 -top-11 w-24 h-24 bg-primary rounded-full group-hover:bg-secondary transition-colors"></div>
                    
                    <div class="relative flex items-center gap-4 mb-6">
                        <!-- Avatar avec badge de progression circulaire -->
                        <div class="relative">
                            <svg class="w-16 h-16 transform -rotate-90">
                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="2" fill="transparent" class="text-gray-100" />
                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="2" fill="transparent" 
                                        stroke-dasharray="<?= 2 * pi() * 28 ?>" 
                                        stroke-dashoffset="<?= (2 * pi() * 28) * (1 - $member['stats']['progress_bar'] / 100) ?>" 
                                        class="text-primary" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center p-1.5">
                                <?php if($member['path_profile']): ?>
                                    <img src="../<?= $member['path_profile'] ?>" class="w-full h-full rounded-full object-cover shadow-sm" alt="Profile" />
                                <?php else: ?>
                                    <div class="w-full h-full rounded-full bg-primary flex items-center justify-center text-primary font-bold text-lg italic">
                                        <?= substr($member['nom_postnom'], 0, 1) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <h3 class="font-serif font-bold text-lg text-primary leading-tight group-hover:text-secondary transition-colors italic">
                                <?= $member['nom_postnom'] ?>
                            </h3>
                            <p class="text-[11px] text-gray-400 font-medium truncate max-w-[150px]"><?= $member['email'] ?></p>
                        </div>
                    </div>

                    <!-- Stats Summary -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="color-border rounded-2xl p-3">
                            <span class="block text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1">Lectures</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-primary"><?= $member['stats']['read_count'] ?></span>
                                <span class="text-[12px] text-gray-400 italic">/ <?= $member['stats']['total_to_read'] ?></span>
                            </div>
                        </div>
                        <div class="color-border rounded-2xl p-3">
                            <span class="block text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1">Score</span>
                            <span class="text-lg font-black text-primary"><?= $member['stats']['progress_bar'] ?>%</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-gray-300 uppercase italic">Activité</span>
                            <span class="text-[10px] font-bold text-gray-500">
                                <?= $member['stats']['last_activity'] ? Helper::formatDate($member['stats']['last_activity']) : 'Jamais' ?>
                            </span>
                        </div>
                        
                        <a href="/membre/profile/<?= $member['member_id'] ?>" 
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center hover:bg-secondary transition-all shadow-md shadow-primary/20">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </main>

</section>

    
<script src="<?= ASSETS ?>js/modules/modal.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>