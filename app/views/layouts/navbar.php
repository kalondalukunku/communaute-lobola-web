<header class="bg-paper text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="flex items-center gap-3">
            <i class="fas fa-seedling text-primary text-2xl"></i>
            <h1 class="font-serif text-primary text-xl md:text-2xl tracking-wide text-medium"><?= SITE_NAME ?></h1>
        </a>
        
        <nav class="flex items-center gap-6">
            <a href="/" class="text-accent border-b-2 border-accent pb-1 <?= Helper::setActive('') ?> hidden md:block">Enseignements</a>
            <div class="h-8 w-px bg-white/20 hidden md:block"></div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-light hidden sm:block">EmEm Htp <span class="font-bold text-accent ml-1"><?= Session::get('membre')['nom_postnom'] ?? '' ?></span></span>
                <a href="<?= Session::get('membre') ? "/membre/profile/". Session::get('membre')['member_id'] : "" ?><?= Session::get('enseignant') ? "/enseignant/profile/". Session::get('enseignant')['enseignant_id'] : "" ?>" class="w-10 h-10 rounded-full overflow-hidden border-2 border-accent flex items-center justify-center bg-white/10 hover:border-primary transition-all">
                    <img src="/<?= Session::get('membre')['path_profile'] ?? Session::get('enseignant')['path_profile'] ?>" alt="Avatar de <?= Session::get('membre')['nom_postnom'] ?>" class="w-full h-full object-cover">
                </a>
                <a href="/logout" class="text-xs border border-red-500 text-red-500 px-4 py-2 rounded-2xl hover:bg-red-500/10 transition tracking-wider font-semibold">Déconnexion</a>
            </div>
        </nav>
    </div>
</header>