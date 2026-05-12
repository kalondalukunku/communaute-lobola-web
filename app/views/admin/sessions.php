<?php 
    $title = "Admin - Liste de toutes les sessions d'initiation";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

        <main class="flex-grow flex flex-col min-w-0">
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
            <header class="h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 flex justify-between items-center sticky top-0 z-40">
                
                <div>
                    <h1 class="font-serif text-xl md:text-md font-bold text-primary">Sessions d'initiation</h1>
                    <p class="text-xs text-gray-400 mt-1 font-medium italic">Gérer les sessions d'initiation de la communauté</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <form action="" method="get">
                        <div class="relative hidden sm:block">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-primary"></i>
                            <input type="text" name="q" placeholder="Rechercher un membre..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="pl-10 pr-6 py-3 bg-paper rounded-2xl text-sm color-border focus:ring-2 focus:ring-primary/20 outline-none w-64 transition-all" style="color: var(--primary);">                    
                        </div>
                    </form>
                </div>
            </header>

            <!-- lien d'ajout un enseignant -->
            <section class="max-w-7xl mx-auto p-6 space-y-10">
                <div class="flex justify-end">
                    <div id="openModalBtn"
                    class="mt-auto inline-flex items-center justify-center w-full py-3 px-6 bg-primary text-paper text-sm font-bold rounded-xl group-hover:bg-primary group-hover:text-slate-900 transition-all duration-300 group-hover:shadow-primary/30">
                        <span>Ajouter une session</span>
                        <i class="fas fa-plus ml-2 transition-transform group-hover:translate-x-2"></i>
                    </div>
                </div>
            </section>


            <div class="p-10 space-y-10">
                <!-- liste des cards sessions --> 
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach($allSessions as $session): ?>
                        <div class="relative p-6 bg-paper rounded-2xl color-border shadow-sm">
                            <h2 class="text-lg font-bold text-primary mb-2"><?= htmlspecialchars($session->nom) ?>e Session</h2>
                            <p class="text-sm text-gray-500 mb-4">La date du début de cette session d'initiation est le <?= Helper::formatDate($session->date_debut) ?> et la date de fin est le <?= Helper::formatDate($session->date_fin) ?></p>
                            <a href="#" class="flex justify-end items-center gap-2 text-sm text-primary font-medium hover:underline">
                                Voir les détails
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <?= Helper::badgeSession($session->date_debut, $session->date_fin) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

    </section>

    
<div 
    id="modalOverlay" 
    class="hidden fixed inset-0 bg-transparent z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    
    <!-- Conteneur du Modal -->
    <div 
        id="modalContent"
        class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-md font-bold text-gray-800">Ajouter une nouvelle session</h3>
            <button id="closeIcon" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="" method="post">
            <!-- Corps du Modal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 p-6 pt-0">
                <div class="group">
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Quantième Session</label>
                    <input type="text" name="nom" value="<?= Helper::getData($_POST, 'nom') ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Quantième Session" required>
                </div>
                <div class="group">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Date de début</label>
                    <input type="date" name="date_debut" value="<?= Helper::getData($_POST, 'date_debut') ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="JJ/MM/AAAA..." required>
                </div>
                <div class="group">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Date de fin</label>
                    <input type="date" name="date_fin" value="<?= Helper::getData($_POST, 'date_fin') ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="JJ/MM/AAAA..." required>
                </div>
            </div>

            <!-- Footer / Boutons d'action -->
            <div class="flex flex-col sm:flex-row-reverse gap-3 p-6 bg-gray-50 rounded-b-xl">
                <button name="cllil_admin_add_session" class="bg-primary text-paper px-8 py-3 rounded-xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                    Ajouter
                </button>
                <button 
                    id="closeModalBtn"
                    class="w-full sm:w-auto px-6 py-2.5 bg-paper border border-gray-300 text-[12px] text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
    
<script src="<?= ASSETS ?>js/modules/modal.js?v=<?= APP_VERSION ?>"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>