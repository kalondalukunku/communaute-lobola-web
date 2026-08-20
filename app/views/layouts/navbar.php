<header x-data="{ mobileOpen: false, open: false }" class="<?= Helper::getUrlPart()[0] === 'bolokele' ? 'bg-[#130121]' : 'bg-paper/80 backdrop-blur-md' ?> text-white shadow-xl sticky top-0 z-[100] border-b border-white/5">
    <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-3 group transition-transform hover:scale-105 active:scale-95">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-2xl bg-primary shadow-lg shadow-primary/30 -rotate-12 group-hover:rotate-0 transition-all duration-500">
                    <img class="w-8 h-8 rounded-2xl object-cover" src="<?= ASSETS ?>images/logo.jpg" alt="Logo">
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <nav class="hidden md:flex items-center gap-5 lg:gap-6">
                    <a href="/" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('') ?>">
                        Mâat
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a>
                    <?php if(isset(Session::get('membre')['engagement_id']) && Session::get('membre')['bolokele'] === '1' || Session::get('membre')['bolokele'] === '2' || isset(Session::get('enseignant')['enseignant_id'])): ?>
                        <a href="/bolokele" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-purple-400 group <?= Helper::setActive('bolokele') ?>">
                            BOLOKELE
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-purple-400 transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                        </a>
                    <?php endif; ?>
                    <a href="/prieres" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('prieres') ?>">
                        Prières
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a>
                    <a href="/relique" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('relique') ?>">
                        Reliques
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a>
                    <a href="/livres" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('livres') ?>">
                        Livres
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a>
                    <a href="/musique" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('musique') ?>">
                        Musiques
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a>
                    <!-- <a href="/blog" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('blog') ?>">
                        Blog
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a> -->
                    <!-- <a href="/help" class="relative py-2 text-[11px] sm:text-xs font-medium transition-colors hover:text-primary group <?= Helper::setActive('help') ?>">
                        Aide
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full <?= Helper::setActive('') ? 'w-full' : '' ?>"></span>
                    </a> -->
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    <div class="text-right hidden lg:block">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">EmEm Htp</p>
                        <p class="text-sm font-semibold text-accent leading-none mt-1">
                            <?= Session::get('membre')['nom_postnom'] ?? '' ?>
                        </p>
                    </div>

                    <div class="relative flex" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="relative group focus:outline-none">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-primary to-accent rounded-full opacity-0 group-hover:opacity-100 blur transition duration-500"></div>
                            <div class="relative w-9 h-9 rounded-full border-2 border-white/10 overflow-hidden bg-paper shadow-inner transition-transform group-hover:scale-105">
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
                            class="absolute right-0 mt-3 w-64 bg-paper rounded-xl shadow-xl color-border py-2 z-50 origin-top-right"
                            style="display: none;"
                        >
                            <a href="<?= Session::get('membre') ? "/membre/profile/". Session::get('membre')['member_id'] : "" ?><?= Session::get('enseignant') ? "/enseignant/profile/". Session::get('enseignant')['enseignant_id'] : "" ?>" 
                            class="block px-4 py-2 text-sm text-white hover:bg-[#CFBB30] hover:text-white transition-colors">
                                Mon Profil
                            </a>
                            <a href="<?= Session::get('membre') ? "/membre/profile_edit/". Session::get('membre')['member_id'] : "" ?>" 
                            class="block px-4 py-2 text-sm text-white hover:bg-[#CFBB30] hover:text-white transition-colors">
                                Modifier mes informations
                            </a>

                            <hr class="my-1 border-gray-100">

                            <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>

                <button type="button" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-white shadow-sm transition hover:bg-white/15" @click="mobileOpen = !mobileOpen" aria-label="Ouvrir le menu">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mt-3 rounded-2xl border border-white/10 bg-paper p-3 shadow-2xl backdrop-blur-xl z-50 md:hidden"
            style="display: none;"
        >
            <div class="flex flex-col gap-1.5">
                <a href="/" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Mâat
                </a>
                <?php if(isset(Session::get('membre')['engagement_id']) && Session::get('membre')['bolokele'] === '1' || Session::get('membre')['bolokele'] === '2' || isset(Session::get('enseignant')['enseignant_id'])): ?>
                    <a href="/bolokele" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-purple-400">
                        BOLOKELE
                    </a>
                <?php endif; ?>
                <a href="/prieres" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Prières
                </a>
                <a href="/relique" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Reliques
                </a>
                <a href="/livres" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Livres
                </a>
                <a href="/musique" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Musiques
                </a>
                <!-- <a href="/blog" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Blog
                </a> -->
                <!-- <a href="/help" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 hover:text-primary">
                    Aide
                </a> -->
                <div class="my-1 h-px bg-white/10"></div>
                <a href="<?= Session::get('membre') ? "/membre/profile/". Session::get('membre')['member_id'] : "" ?><?= Session::get('enseignant') ? "/enseignant/profile/". Session::get('enseignant')['enseignant_id'] : "" ?>" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10">
                    Mon Profil
                </a>
                <a href="<?= Session::get('membre') ? "/membre/profile_edit/". Session::get('membre')['member_id'] : "" ?>" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10">
                    Modifier mes informations
                </a>
                <a href="/logout" @click="mobileOpen = false" class="rounded-xl px-3 py-2 text-sm font-medium text-red-400 transition hover:bg-red-500/10">
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
</header>