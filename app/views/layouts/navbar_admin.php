<section class="min-h-screen flex flex-col lg:flex-row">
    <!-- Sidebar (Desktop: Fixed / Mobile: Floating Overlay) -->
    <aside id="sidebar" class="w-72 bg-paper text-white hidden lg:flex flex-col sticky top-0 pb-5 h-screen z-50 transition-all duration-300">
        <div class="p-8 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-secondary shadow-lg transform -rotate-6">
                    <img class="w-9 rounded-xl" src="<?= ASSETS ?>images/logo.jpg" alt="" srcset="">
                </div>
                <div>
                    <span class="font-serif text-lg block leading-none"><?= SITE_NAME ?></span>
                    <span class="text-[8px] uppercase tracking-[0.2em] text-primary/70 font-bold">Administration</span>
                </div>
            </div>
            <!-- Fermer (Mobile uniquement) -->
            <button id="closeSidebar" class="lg:hidden text-white/50 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <nav class="flex-grow px-6 space-y-1">
            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold px-4 mb-4">Menu Principal</p>
            <a href="/admin/dashboard" class="flex items-center gap-4 p-4 rounded-2xl <?= Helper::setActiveAdmin('admin/dashboard', true) ?>">
                <i class="fas fa-th-large w-5 group-hover:text-primary transition"></i> 
                <span class="text-sm font-medium">Tableau de bord</span>
            </a>
            <a href="/admin/membres" class="flex items-center gap-4 p-4 rounded-2xl <?= Helper::setActiveAdmin('admin/membres', true) ?>">
                <i class="fas fa-user-friends w-5"></i> 
                <span class="text-sm">Gestion Membres</span>
            </a>
            <a href="/admin/enseignants" class="flex items-center gap-4 p-4 rounded-2xl <?= Helper::setActiveAdmin('admin/enseignants', true) ?>">
                <i class="fas fa-book-open w-5 group-hover:text-primary transition"></i> 
                <span class="text-sm font-medium">Enseignants</span>
            </a>
            
            <div class="pt-8">
                <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold px-4 mb-4">Système</p>
                <a href="#" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 transition text-gray-400 group">
                    <i class="fas fa-cog w-5 group-hover:text-primary transition"></i> 
                    <span class="text-sm font-medium">Paramètres</span>
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs border border-primary/30">
                    <?= Helper::getFirstTwoInitials(Session::get('admin')['nom']) ?>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold truncate"><?= Session::get('admin')['nom'] ?></p>
                    <p class="text-[10px] text-gray-500 truncate"><?= Session::get('admin')['email'] ?></p>
                </div>
                <a href="/admin/logout" class="ml-auto text-gray-500 hover:text-red-400"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </aside>

    <!-- Overlay de fond (Flou) -->
    <div id="overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0"></div>