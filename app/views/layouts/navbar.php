<header class="bg-paper text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.html" class="flex items-center gap-3">
            <i class="fas fa-seedling text-primary text-2xl"></i>
            <h1 class="font-serif text-primary text-xl md:text-2xl tracking-wide text-medium"><?= SITE_NAME ?></h1>
        </a>
        
        <nav class="flex items-center gap-6">
            <a href="index.html" class="text-accent border-b-2 border-accent pb-1 hidden md:block">Enseignements</a>
            <div class="h-8 w-px bg-white/20 hidden md:block"></div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-light hidden sm:block">Bonjour, <span class="font-bold text-accent">Membre</span></span>
                <a href="connexion.html" class="text-sm border border-white/30 px-4 py-2 rounded hover:bg-white/10 transition uppercase tracking-wider font-semibold">
                    Déconnexion
                </a>
            </div>
        </nav>
    </div>
</header>