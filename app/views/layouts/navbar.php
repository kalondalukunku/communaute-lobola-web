<nav class="fixed top-0 inset-x-0 z-40 h-16 bg-[var(--color-secondary)] shadow-lg flex items-center justify-between px-4 sm:px-8">
        
    <!-- Logo et Titre Principal -->
    <div class="text-white text-xl font-bold flex items-center space-x-2 flex-shrink-0">
        <i class="fas fa-network-wired text-[var(--color-primary)]"></i>
        <span class="uppercase"><?= SITE_NAME ?></span>
    </div>

    <!-- Liens de Navigation (Centrés ou à Gauche) -->
    <div class="hidden lg:flex flex-1 justify-center space-x-1">
        <nav class="flex space-x-1 custom-scrollbar overflow-x-auto text-sm">
            <!-- ACCENT sur la gestion du personnel -->
            <a href="/" class="flex items-center space-x-2 p-3 rounded-xl text-white bg-blue-900/50 transition duration-150 font-medium whitespace-nowrap">
                <i class="fas fa-users w-5"></i>
                <span>Fiches Personnel</span>
            </a>
            <a href="/dcs" class="flex items-center space-x-2 p-3 rounded-xl <?= Helper::setActive('/dcs') ?> text-gray-300 hover:text-white hover:bg-blue-900/50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-folder-open w-5"></i>
                <span>Gestion des Documents</span>
            </a>
            <a href="/rpt" class="flex items-center space-x-2 p-3 rounded-xl text-gray-300 hover:text-white hover:bg-blue-900/50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-file-invoice w-5"></i>
                <span>Rapports RH</span>
            </a>
            <a href="#" class="flex items-center space-x-2 p-3 rounded-xl text-gray-300 hover:text-white hover:bg-blue-900/50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-calendar-alt w-5"></i>
                <span>Congés & Absences</span>
            </a>
            <a href="/stg" class="flex items-center space-x-2 p-3 rounded-xl text-gray-300 hover:text-white hover:bg-blue-900/50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-cog w-5"></i>
                <span>Paramètres</span>
            </a>
        </nav>
    </div>

    <!-- Profil et Actions (À Droite) -->
    <div class="flex items-center space-x-4 flex-shrink-0">
        <!-- Bouton de notification -->
        <button class="text-white hover:text-[var(--color-primary)] p-2 rounded-full transition duration-150" title="Notifications">
            <i class="fas fa-bell"></i>
        </button>
        
        <!-- Menu Profil/Déconnexion -->
        <div id="profileMenuButton" class="relative cursor-pointer group">
            <img class="h-10 w-10 rounded-full object-cover border-2 border-[var(--color-primary)] transition duration-150 group-hover:opacity-80" 
                    src="https://placehold.co/100x100/1565C0/FFFFFF?text=J.D" 
                    alt="Photo de profil" 
                    onerror="this.onerror=null; this.src='https://placehold.co/100x100/1565C0/FFFFFF?text=J.D'">
            
            <!-- Dropdown Menu -->
            <div id="profileDropdown" class="absolute right-0 mt-3 w-48 bg-white card-container shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 transform scale-95 group-hover:scale-100 origin-top-right">
                <div class="p-4 text-sm border-b border-gray-100">
                    <p class="font-semibold text-gray-900">Jean Dupont</p>
                    <p class="text-xs text-gray-500">Responsable RH</p>
                </div>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-circle mr-2"></i> Mon Profil
                </a>
                <a href="#" onclick="logout();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-xl">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>
            </div>
        </div>

        <!-- Mobile Hamburger (pour les liens de navigation principaux si nécessaires) -->
        <button id="mobileMenuToggle" class="lg:hidden p-2 text-white hover:text-[var(--color-primary)] rounded-full transition duration-150">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>