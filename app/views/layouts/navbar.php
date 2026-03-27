<header class="bg-paper/80 backdrop-blur-md text-white shadow-xl sticky top-0 z-[100] border-b border-white/5">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            
            <!-- Logo avec effet de profondeur -->
            <a href="/" class="flex items-center gap-3 group transition-transform hover:scale-105 active:scale-95">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-primary shadow-lg shadow-primary/30 -rotate-12 group-hover:rotate-0 transition-all duration-500">
                    <img class="w-9 h-9 rounded-2xl object-cover" src="<?= ASSETS ?>images/logo.jpg" alt="Logo">
                </div>
            </a>

            <!-- Navigation Desktop -->
            <nav class="flex items-center gap-8">
                <div class="flex items-center gap-6">
                    <a href="/" class="relative py-2 text-sm font-medium transition-colors hover:text-primary group <?= Helper::setActive('') ?>">
                        Enseignements
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a>
                    <!-- Ajoutez d'autres liens ici si nécessaire -->
                </div>

                <div class="h-6 w-px bg-white/10"></div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden lg:block">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">EmEm Htp</p>
                        <p class="text-sm font-semibold text-accent leading-none mt-1">
                            <?= Session::get('membre')['nom_postnom'] ?? '' ?>
                        </p>
                    </div>

                    <!-- Profil Dropdown ou simple lien -->
                    <!-- <a href="<?= Session::get('membre') ? "/membre/profile/". Session::get('membre')['member_id'] : "" ?><?= Session::get('enseignant') ? "/enseignant/profile/". Session::get('enseignant')['enseignant_id'] : "" ?>" 
                       class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-primary to-accent rounded-full opacity-0 group-hover:opacity-100 blur transition duration-500"></div>
                        <div class="relative w-11 h-11 rounded-full border-2 border-white/10 overflow-hidden bg-paper shadow-inner transition-transform group-hover:scale-105">
                            <img src="/<?= Session::get('membre')['path_profile'] ?? Session::get('enseignant')['path_profile'] ?>" 
                                 alt="Avatar" class="w-full h-full object-cover">
                        </div>
                    </a> -->

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="relative group focus:outline-none">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-primary to-accent rounded-full opacity-0 group-hover:opacity-100 blur transition duration-500"></div>
                            <div class="relative w-11 h-11 rounded-full border-2 border-white/10 overflow-hidden bg-paper shadow-inner transition-transform group-hover:scale-105">
                                <img src="/<?= Session::get('membre')['path_profile'] ?? Session::get('enseignant')['path_profile'] ?>" 
                                    alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        </button>

                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-65 bg-paper rounded-xl shadow-xl color-border py-2 z-50 origin-top-right"
                            style="display: none;"
                        >
                            <a href="<?= Session::get('membre') ? "/membre/profile/". Session::get('membre')['member_id'] : "" ?><?= Session::get('enseignant') ? "/enseignant/profile/". Session::get('enseignant')['enseignant_id'] : "" ?>" 
                            class="block px-4 py-2 text-sm text-white hover:bg-[#CFBB30] hover:text-white transition-colors">
                                Mon Profil
                            </a>
                            <a href="#" 
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-[#CFBB30] hover:text-white transition-colors" disabled>
                                Modifier mes informations (A venir)
                            </a>

                            <hr class="my-1 border-gray-100">

                            <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                Déconnexion
                            </a>
                        </div>
                    </div>

                    <!-- <a href="/logout" class="flex items-center justify-center w-10 h-10 rounded-xl border border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-lg shadow-red-500/5" title="Déconnexion">
                        <i class="fas fa-power-off text-sm"></i>
                    </a> -->
                </div>
            </nav>
        </div>
    </div>
</header>