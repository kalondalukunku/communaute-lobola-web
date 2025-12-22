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
            <a href="/" class="flex items-center space-x-2 p-3 rounded-xl <?= Helper::setActive('') ?> transition duration-150 whitespace-nowrap">
                <i class="fas fa-users w-5"></i>
                <span>Fiches Personnel</span>
            </a>
            <a href="/dcs" class="flex items-center space-x-2 p-3 rounded-xl <?= Helper::setActive('dcs') ?> transition duration-150 whitespace-nowrap">
                <i class="fas fa-folder-open w-5"></i>
                <span>Gestion des Documents</span>
            </a>
            <a href="/rpt" class="flex items-center space-x-2 p-3 rounded-xl <?= Helper::setActive('rpt') ?> transition duration-150 whitespace-nowrap">
                <i class="fas fa-file-invoice w-5"></i>
                <span>Rapports RH</span>
            </a>
            <!-- <a href="#" class="flex items-center space-x-2 p-3 rounded-xl <?= Helper::setActive('psn/conge') ?> transition duration-150 whitespace-nowrap">
                <i class="fas fa-calendar-alt w-5"></i>
                <span>Congés & Absences</span>
            </a> -->
            <!-- <a href="/stg" class="flex items-center space-x-2 p-3 rounded-xl <?= Helper::setActive('stg') ?> transition duration-150 whitespace-nowrap">
                <i class="fas fa-cog w-5"></i>
                <span>Paramètres</span>
            </a> -->
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
                    src="https://placehold.co/100x100/1565C0/FFFFFF?text=<?= Helper::getFirstLetter($_SESSION[SITE_NAME_SESSION_USER]['email']) ?>" 
                    alt="Photo de profil" 
                    onerror="this.onerror=null; this.src='https://placehold.co/100x100/1565C0/FFFFFF?text=MSL'"
                    id="menu-button" 
                    aria-expanded="false" 
                    aria-haspopup="true">
            
            <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10" 
                id="dropdown-menu"
                role="menu" 
                aria-orientation="vertical" 
                aria-labelledby="menu-button" 
                tabindex="-1">
                
                <div class="px-4 py-3" role="none">
                    <p class="text-sm text-gray-500" role="none">Connecté en tant que</p>
                    <p class="text-sm font-medium text-gray-900 truncate" role="none"><?= $_SESSION[SITE_NAME_SESSION_USER]['email'] ?></p>
                </div>

                <div class="py-1" role="none">
                    <!-- <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 transition-colors" role="menuitem" tabindex="-1">Paramètres du compte</a> -->
                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 transition-colors" role="menuitem" tabindex="-1">Support technique</a>
                </div>

                <div class="py-1" role="none">
                    <a href="/logout" type="submit" class="text-red-600 w-full text-left block px-4 py-2 text-sm hover:bg-red-50 transition-colors font-medium" role="menuitem" tabindex="-1">
                        Déconnexion
                    </a>
                </div>
            </div>

            <!-- Dropdown Menu -->
            <div 
                id="dropdownMenu"
                class="dropdown absolute right-0 mt-2 w-56 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden opacity-0 scale-95 z-50">
                
                <div class="py-1">
                    <!-- <a class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Mon Profil
                    </a> -->
                    <!-- <a class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Paramètres
                    </a> -->
                </div>

                <div class="py-1">
                    <a class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Support
                    </a>
                </div>

                <div class="py-1">
                    <a href="/logout" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Hamburger (pour les liens de navigation principaux si nécessaires) -->
        <button id="mobileMenuToggle" class="lg:hidden p-2 text-white hover:text-[var(--color-primary)] rounded-full transition duration-150">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>