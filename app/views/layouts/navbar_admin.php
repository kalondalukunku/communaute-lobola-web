<section class="min-h-screen flex flex-col lg:flex-row bg-paper" 
         x-data="{ sidebarOpen: false, openMenu: 'membres' }">

    <!-- Sidebar Moderne avec effet de verre -->
    <aside id="sidebar" 
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:sticky top-0 left-0 w-72 bg-paper text-slate-300 flex flex-col h-screen z-50 transition-all duration-500 ease-in-out shadow-2xl lg:shadow-none border-r border-white/5">
        
        <!-- En-tête du Sidebar -->
        <div class="p-6 mb-2">
            <div class="flex items-center justify-between bg-paper/5 p-4 rounded-2xl border border-white/10 shadow-inner">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white shadow-[0_0_15px_rgba(var(--primary-rgb),0.4)]">
                            <img class="w-8 h-8 rounded-lg object-cover" src="<?= ASSETS ?>images/logo.jpg" alt="Logo">
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-[#1e293b] rounded-full"></div>
                    </div>
                    <div class="leading-tight">
                        <span class="text-white font-bold text-sm block tracking-tight"><?= SITE_NAME ?></span>
                        <span class="text-[9px] uppercase tracking-tighter text-primary font-black opacity-80">Panel Admin</span>
                    </div>
                </div>
                <!-- Fermer (Mobile) -->
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
        </div>

        <!-- Navigation Scrollable -->
        <nav class="flex-grow px-4 space-y-6 overflow-y-auto custom-scrollbar">
            
            <!-- Section : Principal -->
            <div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] px-3 mb-3 block">Navigation</span>
                <div class="space-y-1">
                    <a href="/admin/dashboard" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-paper/5 <?= Helper::setActiveAdmin('admin/dashboard', true) ? 'bg-primary/10 text-primary border border-primary/20 shadow-lg shadow-primary/5' : 'hover:text-white' ?>">
                        <i class="fas fa-chart-line w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold">Tableau de bord</span>
                    </a>
                    <a href="/admin/admins" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-paper/5 <?= Helper::setActiveAdmin('admin/admins', true) ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:text-white' ?>">
                        <i class="fas fa-shield-alt w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold">Administrateurs</span>
                    </a>
                </div>
            </div>

            <!-- Section : Gestion (Dropdowns) -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] px-3 mb-3 block">Ressources</span>
                
                <!-- Dropdown Membres -->
                <div class="relative overflow-hidden rounded-2xl bg-paper/[0.02] border border-white/5">
                    <button @click="openMenu = (openMenu === 'membres' ? '' : 'membres')" 
                            class="w-full flex items-center gap-3 px-4 py-3 transition-colors hover:text-white"
                            :class="openMenu === 'membres' ? 'text-white bg-paper/5' : ''">
                        <i class="fas fa-user-group w-5 text-center text-primary/70"></i>
                        <span class="text-xs font-semibold flex-grow text-left">Membres</span>
                        <i class="fas fa-chevron-right text-[9px] transition-transform duration-300" 
                           :class="openMenu === 'membres' ? 'rotate-90' : ''"></i>
                    </button>
                    
                    <div x-show="openMenu === 'membres'" x-collapse x-cloak>
                        <div class="pb-3 pl-12 pr-4 space-y-2">
                            <a href="/admin/membres" class="block text-[11px] py-1 transition-colors hover:text-primary <?= Helper::setActiveAdmin('admin/membres', true) ? 'text-primary font-bold' : 'text-slate-400' ?>">Liste complète</a>
                            <a href="/admin/inities" class="block text-[11px] py-1 transition-colors hover:text-primary <?= Helper::setActiveAdmin('admin/inities', true) ? 'text-primary font-bold' : 'text-slate-400' ?>">Type d'initié</a>
                            <a href="/admin/membres_suivi" class="block text-[11px] py-1 transition-colors hover:text-primary <?= Helper::setActiveAdmin('admin/membres_suivi', true) ? 'text-primary font-bold' : 'text-slate-400' ?>">Suivi d'activité</a>
                            <a href="/admin/engages" class="block text-[11px] py-1 transition-colors hover:text-primary <?= Helper::setActiveAdmin('admin/engages', true) ? 'text-primary font-bold' : 'text-slate-400' ?>">Membres Engagés</a>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Pédagogie -->
                <div class="relative overflow-hidden rounded-2xl bg-paper/[0.02] border border-white/5">
                    <button @click="openMenu = (openMenu === 'pedagogie' ? '' : 'pedagogie')" 
                            class="w-full flex items-center gap-3 px-4 py-3 transition-colors hover:text-white"
                            :class="openMenu === 'pedagogie' ? 'text-white bg-paper/5' : ''">
                        <i class="fas fa-graduation-cap w-5 text-center text-primary/70"></i>
                        <span class="text-xs font-semibold flex-grow text-left">Apprentissage</span>
                        <i class="fas fa-chevron-right text-[9px] transition-transform duration-300" 
                           :class="openMenu === 'pedagogie' ? 'rotate-90' : ''"></i>
                    </button>
                    
                    <div x-show="openMenu === 'pedagogie'" x-collapse x-cloak>
                        <div class="pb-3 pl-12 pr-4 space-y-2">
                            <a href="/admin/enseignants" class="block text-[11px] py-1 transition-colors hover:text-primary <?= Helper::setActiveAdmin('admin/enseignants', true) ? 'text-primary font-bold' : 'text-slate-400' ?>">Enseignants</a>
                            <a href="/admin/enseignements" class="block text-[11px] py-1 transition-colors hover:text-primary <?= Helper::setActiveAdmin('admin/enseignements', true) ? 'text-primary font-bold' : 'text-slate-400' ?>">Enseignements</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Sécurité -->
            <div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] px-3 mb-3 block">Sécurité</span>
                <a href="/admin/edtpswd" 
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-paper/5 <?= Helper::setActiveAdmin('admin/edtpswd', true) ? 'bg-primary/10 text-primary border border-primary/20' : 'hover:text-white' ?>">
                    <i class="fas fa-key w-5 text-center group-hover:rotate-12 transition-transform"></i>
                    <span class="text-xs font-semibold">Accès & Mot de passe</span>
                </a>
            </div>
        </nav>

        <!-- Profil Utilisateur (Bas) -->
        <div class="p-4 border-t border-white/5 bg-black/10">
            <div class="flex items-center gap-3 p-2 bg-paper/5 rounded-2xl border border-white/5">
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                        <?= Helper::getFirstTwoInitials(Session::get('admin')['nom']) ?>
                    </div>
                </div>
                <div class="min-w-0 flex-grow">
                    <p class="text-[11px] font-bold text-white truncate"><?= Session::get('admin')['nom'] ?></p>
                    <p class="text-[9px] text-slate-500 truncate lowercase"><?= Session::get('admin')['email'] ?></p>
                </div>
                <a href="/admin/logout" class="p-2 text-slate-500 hover:text-red-400 transition-colors">
                    <i class="fas fa-power-off text-sm"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Overlay Mobile avec flou profond -->
    <div @click="sidebarOpen = false" 
         x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#000f0e96] backdrop-blur-md z-40 lg:hidden" x-cloak>
    </div>