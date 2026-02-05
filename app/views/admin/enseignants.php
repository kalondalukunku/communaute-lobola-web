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

            <div>
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Enseignants de la communauté</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Ajouter et gérez les transmeteurs du savoir des ancêtres</p>
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

        <!-- lien d'ajout un enseignant -->
        <section class="max-w-7xl mx-auto p-6 space-y-10">
            <div class="flex justify-end">
                <a href="addenseignant" 
                class="mt-auto inline-flex items-center justify-center w-full py-3 px-6 bg-primary text-paper text-sm font-bold rounded-xl group-hover:bg-primary group-hover:text-slate-900 transition-all duration-300 group-hover:shadow-primary/30">
                    <span>Ajouter un enseignant</span>
                    <i class="fas fa-plus ml-2 transition-transform group-hover:translate-x-2"></i>
                </a>
            </div>
        </section>


        <div class="p-10 space-y-10">
            <!-- liste des cards enseignants --> 
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach($allEnseignants as $enseignant): ?>
                    <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 color-border hover:-translate-y-2 flex flex-col">
                        
                        <!-- Décoration de fond (Cercle discret) -->
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>

                        <div class="p-3 flex flex-col items-center text-center relative z-10 flex-grow">
                            <!-- Conteneur Photo avec anneau de design -->
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-primary/20 rounded-full blur-md group-hover:blur-xl transition-all duration-500"></div>
                                <div class="relative w-28 h-28 rounded-full p-1 bg-gradient-to-tr from-primary to-primary/30">
                                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-[#cfbb30]">
                                        <img src="<?= '../' . htmlspecialchars($enseignant->path_profile) ?>" 
                                            alt="Photo de <?= htmlspecialchars($enseignant->nom) ?>" 
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Informations -->
                            <h2 class="text-lg font-extrabold text-primary mb-1 group-hover:text-primary transition-colors">
                                <?= htmlspecialchars($enseignant->nom_complet) ?>
                            </h2>
                            
                            <div class="w-10 h-1 bg-primary/30 rounded-full mb-4 group-hover:w-20 transition-all duration-500"></div>

                            <p class="text-xs text-slate-500 line-clamp-3 mb-6 italic leading-relaxed">
                                "<?= htmlspecialchars($enseignant->biographie) ?>"
                            </p>

                            <!-- Bouton d'action -->
                            <a href="vwenseignant/<?= htmlspecialchars($enseignant->enseignant_id) ?>" 
                            class="mt-auto inline-flex items-center justify-center w-full py-2 px-4 bg-primary text-paper text-sm font-bold rounded-xl group-hover:bg-primary group-hover:text-slate-900 transition-all duration-300 group-hover:shadow-primary/30">
                                <span>Voir le profil</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>