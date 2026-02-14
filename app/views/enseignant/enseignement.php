<?php 
    $title = $title ?? SITE_NAME;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="flex-grow container mx-auto px-4 py-12 max-w-6xl">
        <!-- En-tête de section -->
        <div class="mb-12 border-l-4 border-yellow-400 pl-6">
            <h2 class="font-serif text-4xl text-white mb-2">Bibliothèque d'Enseignements de <span class="text-primary"><?= $Enseignant->nom_complet ?></span></h2>
            <p class="text-gray-400 max-w-2xl">Explorez nos séries audio dédiées à la paix intérieure, la méditation et la croissance spirituelle.</p>
        </div>

        <!-- Filtres (Optionnel) -->
        <!-- <div class="flex gap-4 mb-10 overflow-x-auto pb-2">
            <button class="px-5 py-2 rounded-full text-sm font-bold bg-primary text-dark">Tous</button>
            <button class="px-5 py-2 rounded-full text-sm font-bold border border-gray-700 text-gray-400 hover:text-white transition">Méditation</button>
            <button class="px-5 py-2 rounded-full text-sm font-bold border border-gray-700 text-gray-400 hover:text-white transition">Philosophie</button>
        </div> -->

        <!-- Grille des enseignements -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php foreach ($Enseignements as $item): ?>
                <div class="audio-card rounded-2xl color-border group">
                    <!-- Partie Supérieure : Visuel -->
                    <!-- <div class="card-visual h-20 flex items-center justify-center relative"> -->
                        <!-- <i class="fa-solid fa-people-arrows text-5xl text-primary group-hover:text-primary transition-colors duration-500"></i> -->
                        
                        <!-- Overlay au survol -->
                        <!-- <div class="play-overlay absolute inset-0 flex items-center justify-center">
                            <a href="enseignement/show/<?= $item->enseignant_id ?>" class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-dark shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <i class="fas fa-play text-xl ml-1"></i>
                            </a>
                        </div> -->

                        <!-- Durée / Badge -->
                    <!-- </div> -->

                    <!-- Partie Inférieure : Contenu -->
                    <div class="p-6">
                        <div class="flex items-center gap-2">
                            <span class="badge-category text-primary text-[8px] rounded-full uppercase font-bold">Audio</span>
                            <span class="text-gray-500 text-[10px]">•</span>
                            <span class="text-gray-500 text-[8px] uppercase tracking-widest font-bold"><?= $item->type !== null ? "Série : $item->type" : '' ?></span>
                        </div>

                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-xl font-bold text-white mb-2 mt-4 line-clamp-1 group-hover:text-primary transition-colors">
                                <?= Helper::textTruncate($item->title, 30) ?>
                            </h3>
                            <div class="text-center items-center bg-secondary text-white text-[10px] font-mono px-2 py-1 mt-4 rounded">
                                <?= $item->duration_minutes ?>
                            </div>
                        </div>

                        <p class="text-gray-400 text-sm line-clamp-2 mb-6 leading-relaxed">
                            <?= Helper::textTruncate($item->description, 91) ?>
                        </p>
                        <div class="color-border-b"></div>

                        <div class="flex items-center justify-between pt-4">
                            <div class="flex items-center gap-2 text-gray-500">
                                <i class="far fa-calendar-alt text-xs"></i>
                                <span class="text-[11px] font-bold uppercase tracking-tighter"><?= Helper::formatDate($item->created_at) ?></span>
                            </div>
                            
                            <a href="../../enseignement/show/<?= $item->serie_id ?>" class="text-xs font-bold uppercase tracking-widest text-primary hover:underline">
                                Écouter <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>
    
<script src="<?= ASSETS ?>js/modules/player.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>