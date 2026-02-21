<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <main class="flex-grow container mx-auto px-4 py-12">
        <div class="fade-in">

            <?php if ($showRestriction): ?>
                <!-- Bloc Avertissement Privations -->
                <div class="mb-12 relative overflow-hidden rounded-2xl border border-primary/30 bg-black/40 backdrop-blur-md p-6 shadow-2xl">
                    <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                    
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="flex-shrink-0 text-center lg:text-left">
                            <span class="text-primary text-[10px] font-black uppercase tracking-[0.3em] block mb-2">Période de Sanctification</span>
                            <h2 class="font-serif text-2xl text-white">Engagement Spirituel</h2>
                        </div>

                        <div class="flex flex-wrap justify-center gap-6 lg:gap-12 flex-grow">
                            <!-- Abstinence Sexe -->
                            <div class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-full border border-primary/20 flex items-center justify-center bg-primary/5 group-hover:bg-primary/20 transition-colors">
                                    <i class="fas fa-heart-broken text-primary"></i>
                                </div>
                                <div class="text-[11px] uppercase tracking-wider">
                                    <span class="text-gray-500 block">Abstinence</span>
                                    <span class="text-white font-bold">Pas de sexe</span>
                                </div>
                            </div>

                            <!-- Abstinence Viande -->
                            <div class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-full border border-primary/20 flex items-center justify-center bg-primary/5 group-hover:bg-primary/20 transition-colors">
                                    <i class="fas fa-leaf text-primary"></i>
                                </div>
                                <div class="text-[11px] uppercase tracking-wider">
                                    <span class="text-gray-500 block">Régime</span>
                                    <span class="text-white font-bold">Pas de Viande</span>
                                </div>
                            </div>

                            <!-- Abstinence Alcool -->
                            <div class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-full border border-primary/20 flex items-center justify-center bg-primary/5 group-hover:bg-primary/20 transition-colors">
                                     <i class="fa-solid fa-ban text-primary"></i>
                                </div>
                                <div class="text-[11px] uppercase tracking-wider">
                                    <span class="text-gray-500 block">Sobriété</span>
                                    <span class="text-white font-bold">Pas d'Alcool</span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:text-right">
                            <div class="inline-block px-4 py-2 rounded-lg bg-primary/10 border border-primary/20">
                                <span class="text-primary text-[10px] font-bold italic">"Purifie ton temple pour recevoir la Lumière"</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Section Titre -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-primary/10 pb-6">
                <div class="max-w-2xl">
                    <h2 class="font-serif text-3xl text-primary mb-4">Bibliothèque Sacrée</h2>
                    <p class="text-gray-500 text-sm italic">"La sagesse ne s'apprend pas, elle se reconnaît." Explorez les enseignements audio du mois.</p>
                </div>
                <!-- <div class="bg-green-100 text-green-800 px-6 py-2 rounded-full text-sm font-bold flex items-center gap-2 mt-6 md:mt-0 shadow-sm border border-green-200">
                    <i class="fas fa-check-circle"></i> Engagement Actif • Décembre 2023
                </div> -->
            </div>

            <!-- Grille des Enseignements -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

            <?php foreach ($Series as $item): ?>
                <?php 
                    // Vérification si l'enseignement a moins de 24h
                    $isNew = false;
                    if (!empty($item->updated_at)) {
                        $createdAt = new DateTime($item->updated_at);
                        $now = new DateTime();
                        $diff = $now->diff($createdAt);
                        // Vérifie si la différence totale en heures est < 24
                        $hours = ($diff->days * 24) + $diff->h;
                        if ($hours < 24 && $diff->invert == 1) {
                            $isNew = true;
                        }
                    }
                ?>
                <div class="audio-card rounded-2xl color-border group relative overflow-hidden">
                    
                    <!-- Bandeau "Nouveau" (Affiché conditionnellement) -->
                    <?php if ($isNew): ?>
                        <div class="absolute top-0 right-0 z-10">
                            <div class="bg-primary text-black text-[10px] font-black uppercase px-3 py-1 rounded-bl-xl shadow-lg animate-pulse">
                                Nouveau
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Partie Inférieure : Contenu -->
                    <div class="p-6">
                        <div class="flex items-center gap-2">
                            <span class="badge-category text-primary text-[8px] rounded-full uppercase font-bold">Audio</span>
                            <span class="text-gray-500 text-[10px]">•</span>
                            <span class="text-gray-500 text-[7px] uppercase tracking-widest font-bold"><?= $item->nom !== null ? htmlspecialchars($item->nom) : '' ?></span>
                        </div>

                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-xl font-bold text-white mb-2 mt-4 line-clamp-1 group-hover:text-primary transition-colors">
                                <?= Helper::textTruncate($item->nom, 30) ?>
                            </h3>
                            <div class="text-center items-center bg-secondary text-white text-[10px] font-mono px-2 py-1 mt-4 rounded">
                                <?php $vues = $VuesModel->countAll(['serie_id' => $item->serie_id]); ?>
                                <?= $vues; ?> vue<?= $vues > 1 ? 's' : '' ?>
                            </div>
                        </div>

                        <p class="text-gray-400 text-sm line-clamp-2 mb-6 leading-relaxed">
                            Cette série contient actuellement <?= $item->enseignements_count ?> enseignements au total.
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
        </div>
    </main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>