<?php 
    $title = "Admin - Bibliothèque d'enseignements";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <!-- Main Content -->
    <main class="flex-grow min-h-screen">
        
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

        <!-- Header de la gestion -->
         <header class="h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 flex justify-between items-center sticky top-0 z-40">
            
            <div class="hidden md:block">
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Bibliothèque d'enseignements</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Gestion des enseignements & visibilité</p>
            </div>
            
            <div class="flex items-center gap-6">
                <form action="" method="get">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-primary"></i>
                        <input type="text" name="q" placeholder="Rechercher un membre..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="pl-10 pr-6 py-3 bg-paper rounded-2xl text-sm color-border focus:ring-2 focus:ring-primary/20 outline-none w-64 transition-all" style="color: var(--primary);">                    
                    </div>
                </form>
            </div>
        </header>

        <div class="p-6 md:p-10 max-w-7xl mx-auto">
            <!-- Grille des enseignements -->
            <div id="cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if(!empty($allEnseignements)): ?>
                    <?php foreach ($allEnseignements as $item): ?>
                        <div id="cards" class="group rounded-[2.5rem] color-border p-2 shadow-xs hover:shadow-sm hover:shadow-[#cfbb30] transition-all duration-500 relative">
                            
                            <!-- Image & Status Badge -->
                            <div class="relative h-14 w-full rounded-[2rem] overflow-hidden mb-4">
                                <!-- <img src="<?= $item->cover ?? 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?q=80&w=800' ?>" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Cover" /> -->
                                
                                <!-- Badge Statut -->
                                <div class="absolute top-4 left-4">
                                    <span class="<?= $item->is_active == 1 ? 'bg-green-500' : 'bg-gray-400' ?> text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-full shadow-lg">
                                        <?= $item->is_active == 1 ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </div>

                                <!-- Toggle Switch (On/Off) -->
                                <div class="absolute top-4 right-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                                class="sr-only peer status-toggle" 
                                                data-es="<?= $item->enseignement_id ?>"
                                                onchange="submitToggle(this)"
                                                <?= $item->is_active == 1 ? 'checked' : '' ?>>
                                        <div class="w-12 h-6 bg-black/20 backdrop-blur-md rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500 shadow-inner"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-5 pb-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold text-primary px-2 py-0.5 bg-primary/5 rounded-md italic">
                                        <?= $item->nom_serie ?? 'Théologie' ?>
                                    </span>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <span class="text-[10px] text-gray-400 font-medium">
                                        <i class="far fa-calendar-alt mr-1"></i> <?= Helper::formatDate($item->created_at) ?>
                                    </span>
                                </div>

                                <h3 class="font-serif font-bold text-lg text-white mb-3 group-hover:text-primary transition-colors">
                                    <?= $item->title ?>
                                </h3>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                    <div class="flex -space-x-2">
                                        <!-- Mini avatars de qui a vu -->
                                        <div class="w-auto h-7 p-2 rounded-full bg-secondary color-border flex items-center justify-center text-[10px] font-bold text-gray-400">
                                            <?= $item->total_vues ?? 0 ?> Lectures
                                        </div>
                                        <!-- <span class="ml-3 text-[11px] font-bold text-gray-400 uppercase tracking-tighter self-center">Lectures</span> -->
                                    </div>

                                    <div class="flex gap-2">
                                        <button class="w-9 h-9 rounded-xl bg-secondary text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center">
                                            <i class="far fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-book-open text-3xl text-gray-200"></i>
                        </div>
                        <p class="text-gray-400 font-serif italic">Aucun enseignement pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

</section>

<script src="<?= ASSETS ?>js/modules/onoff.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>