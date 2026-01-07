<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

<main class="flex-grow container mx-auto px-4 py-8 md:py-12">
    <div class="fade-in">
        
        <!-- Header Section -->
        <div class="mb-1 border-b border-primary/20 pb-8">
            <h2 class="font-serif text-2xl text-primary mb-4">Cercle des Engagés</h2>
            <p class="text-gray-500 text-sm italic max-w-2xl">
                "Nous marchons seuls, mais nous marchons ensemble." Retrouvez ici les âmes qui partagent ce voyage de conscience.
            </p>
        </div>

        <!-- Filtres et Recherche -->
        <div class="flex flex-col md:flex-row gap-4 mb-10 items-center">
            <div class="relative flex-grow max-w-md w-full">
                <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-primary"></i>
                <input type="text" class="search-bar color-border pl-12" placeholder="Chercher un membre...">
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-secondary text-primary color-border text-sm font-bold">Tous</button>
                <button class="px-4 py-2 rounded-lg bg-primary border border-paper-200 text-paper text-sm hover:border-primary transition">Actifs</button>
                <button class="px-4 py-2 rounded-lg bg-primary border border-paper-200 text-paper text-sm hover:border-primary transition">Anciens</button>
            </div>
        </div>

        <!-- Grille des Membres -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <!-- Membre 1 -->
            <div class="member-card flex flex-col items-center text-center color-border shadow-sm rounded-2xl p-6">
                <div class="avatar-placeholder mb-4">JS</div>
                <h3 class="font-serif text-xl font-bold text-white mb-1">Jean-Samuel M.</h3>
                <span class="badge-status text-white mb-4">Engagé depuis 2022</span>
                <p class="text-xs text-gray-500 leading-relaxed mb-6">"En quête de silence et de vérité à travers la méditation quotidienne."</p>
                <div class="flex gap-3 mt-auto">
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-envelope"></i></button>
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-phone"></i></button>
                </div>
            </div>

            <!-- Membre 2 -->
            <div class="member-card flex flex-col items-center text-center color-border shadow-sm rounded-2xl p-6">
                <div class="avatar-placeholder mb-4" style="background: var(--primary); color: var(--secondary);">EL</div>
                <h3 class="font-serif text-xl font-bold text-white mb-1">Elena L.</h3>
                <span class="badge-status text-white mb-4">Engagée depuis 2023</span>
                <p class="text-xs text-gray-500 leading-relaxed mb-6">"Le pardon a transformé ma vie. Ici pour approfondir ma pratique."</p>
                <div class="flex gap-3 mt-auto">
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-envelope"></i></button>
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-phone"></i></button>
                </div>
            </div>

            <!-- Membre 3 -->
            <div class="member-card flex flex-col items-center text-center color-border shadow-sm rounded-2xl p-6">
                <div class="avatar-placeholder mb-4">MK</div>
                <h3 class="font-serif text-xl font-bold text-white mb-1">Marc K.</h3>
                <span class="badge-status text-white mb-4">Engagé depuis 2021</span>
                <p class="text-xs text-gray-500 leading-relaxed mb-6">"Ancien étudiant de la voie du Zen, explorant maintenant ces enseignements."</p>
                <div class="flex gap-3 mt-auto">
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-envelope"></i></button>
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-phone"></i></button>
                </div>
            </div>

            <!-- Membre 4 -->
            <div class="member-card flex flex-col items-center text-center color-border shadow-sm rounded-2xl p-6">
                <div class="avatar-placeholder mb-4" style="background: var(--primary); color: var(--secondary);">SD</div>
                <h3 class="font-serif text-xl font-bold text-white mb-1">Sophie D.</h3>
                <span class="badge-status mb-4">Engagée depuis 2024</span>
                <p class="text-xs text-gray-500 leading-relaxed mb-6">"Nouvelle sur le chemin, pleine de gratitude pour cet espace sacré."</p>
                <div class="flex gap-3 mt-auto">
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-envelope"></i></button>
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-phone"></i></button>
                </div>
            </div>

            <!-- Membre 5 -->
            <div class="member-card flex flex-col items-center text-center color-border shadow-sm rounded-2xl p-6">
                <div class="avatar-placeholder mb-4">AM</div>
                <h3 class="font-serif text-xl font-bold text-white mb-1">Arnaud M.</h3>
                <span class="badge-status text-white mb-4">Engagé depuis 2023</span>
                <p class="text-xs text-gray-500 leading-relaxed mb-6">"Chercheur de paix dans le tumulte urbain."</p>
                <div class="flex gap-3 mt-auto">
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-envelope"></i></button>
                    <button class="text-primary hover:text-primary-dark transition"><i class="fas fa-phone"></i></button>
                </div>
            </div>

        </div>
    </div>
</main>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>