<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <main class="flex-grow container mx-auto px-4 sm:px-6 py-8 sm:py-12 lg:py-16">
        <div class="fade-in max-w-7xl mx-auto">
        
            <!-- banniere album disponible sur spotify -->
            <section class="mb-10 transition-transform duration-300 hover:scale-[1.01]">
                <div class="shadow-xl rounded-xl overflow-hidden ring-1 ring-white/5">
                    <iframe data-testid="embed-iframe" src="https://open.spotify.com/embed/track/2OdeONjcYsbqEVdfnD6Y6p?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" class="w-full"></iframe>
                </div>
            </section>

            <?php if ($showRestriction): ?>
                <!-- Bloc Avertissement Privations - Rendu PLUS COMPACT -->
                <div class="mb-12 relative overflow-hidden rounded-xl border border-primary/20 bg-black/60 backdrop-blur-xl p-4 sm:p-5 shadow-2xl transition-all duration-300 hover:border-primary/40">
                    <!-- Ligne décorative gauche -->
                    <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-primary/50 via-primary to-primary/50"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-5">
                        
                        <!-- En-tête du bloc (Plus petit) -->
                        <div class="flex-shrink-0 text-center md:text-left md:pl-2">
                            <span class="text-primary text-[9px] font-black uppercase tracking-[0.2em] block mb-1 opacity-90">Sanctification</span>
                            <h2 class="font-serif text-xl sm:text-2xl text-white font-medium leading-none">Engagement Spirituel</h2>
                        </div>

                        <!-- Icônes d'abstinence (Réduites et compactées) -->
                        <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-8 flex-grow">
                            <!-- Abstinence Sexe -->
                            <div class="flex items-center gap-2.5 group cursor-default">
                                <div class="w-10 h-10 rounded-full border border-primary/20 flex items-center justify-center bg-primary/5 group-hover:bg-primary/10 transition-colors duration-300">
                                    <i class="fas fa-heart-broken text-primary text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <div class="text-[10px] uppercase tracking-wide leading-tight">
                                    <span class="text-gray-400 block">Abstinence</span>
                                    <span class="text-white font-bold">Pas de sexe</span>
                                </div>
                            </div>

                            <!-- Abstinence Viande -->
                            <div class="flex items-center gap-2.5 group cursor-default">
                                <div class="w-10 h-10 rounded-full border border-primary/20 flex items-center justify-center bg-primary/5 group-hover:bg-primary/10 transition-colors duration-300">
                                    <i class="fas fa-leaf text-primary text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <div class="text-[10px] uppercase tracking-wide leading-tight">
                                    <span class="text-gray-400 block">Régime</span>
                                    <span class="text-white font-bold">Pas de Viande</span>
                                </div>
                            </div>

                            <!-- Abstinence Alcool -->
                            <div class="flex items-center gap-2.5 group cursor-default">
                                <div class="w-10 h-10 rounded-full border border-primary/20 flex items-center justify-center bg-primary/5 group-hover:bg-primary/10 transition-colors duration-300">
                                     <i class="fa-solid fa-ban text-primary text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <div class="text-[10px] uppercase tracking-wide leading-tight">
                                    <span class="text-gray-400 block">Sobriété</span>
                                    <span class="text-white font-bold">Pas d'Alcool</span>
                                </div>
                            </div>
                        </div>

                        <!-- Citation (Plus discrète) -->
                        <div class="hidden lg:block flex-shrink-0">
                            <div class="px-4 py-2 rounded-lg bg-primary/5 border border-primary/10">
                                <span class="text-primary text-[10px] font-medium italic">"Purifie ton temple..."</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isOn): ?>
                <!-- Section Titre -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 border-b border-primary/15 pb-4 sm:pb-5">
                    <div class="max-w-2xl">
                        <h2 class="font-serif text-3xl sm:text-4xl text-white mb-2 flex items-center gap-3">
                            <span class="text-primary">Bibliothèque</span> Sacrée
                        </h2>
                        <p class="text-gray-400 text-sm italic font-light tracking-wide">"La sagesse ne s'apprend pas, elle se reconnaît." Explorez les enseignements du mois.</p>
                    </div>
                </div>

                <!-- Grille des Enseignements (Design Amélioré) -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">

                <?php if ($Series): ?>
                    <?php foreach ($Series as $item): ?>
                        <?php 
                            // Vérification si l'enseignement a moins de 24h
                            $isNew = false;
                            if (!empty($item->updated_at)) {
                                $createdAt = new DateTime($item->updated_at);
                                $now = new DateTime();
                                $diff = $now->diff($createdAt);
                                $hours = ($diff->days * 24) + $diff->h;
                                if ($hours < 24 && $diff->invert == 1) {
                                    $isNew = true;
                                }
                            }
                        ?>
                        <div class="audio-card bg-gradient-to-b from-[#001b1a] to-[#000808] border border-white/5 rounded-2xl group relative overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.5)] hover:shadow-primary/20 flex flex-col h-full">
                            
                            <!-- Effet de lueur interne au survol -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-primary/0 via-primary/0 to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                            <!-- Contenu de la carte -->
                            <div class="p-6 sm:p-7 flex flex-col flex-grow relative z-10">
                                <!-- Catégorie & Vues -->
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20">
                                            <i class="fas fa-headphones text-[10px] text-primary"></i>
                                        </div>
                                        <span class="text-gray-400 text-[9px] uppercase tracking-widest font-semibold truncate max-w-[120px]"><?= $item->nom !== null ? htmlspecialchars($item->nom) : '' ?></span>
                                    </div>
                                    <?php if ($isNew): ?>
                                        <div class="flex items-center gap-1.5 bg-primary/10 border border-primary/30 text-primary text-[8px] font-bold uppercase px-3 py-1.5 rounded-full backdrop-blur-md shadow-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                            Nouveau
                                        </div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-[10px] font-mono px-2.5 py-1 rounded-md border border-white/5">
                                            <i class="far fa-eye text-[9px] opacity-70"></i>
                                            <?= $VuesModel->countAll(['serie_id' => $item->serie_id, 'session_id' => $item->session_id]); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Titre & Description -->
                                <h3 class="text-lg sm:text-xl font-bold text-white leading-snug line-clamp-2 mb-3 group-hover:text-primary transition-colors duration-300 flex-grow">
                                    <?= Helper::textTruncate($item->nom, 35) ?>
                                </h3>

                                <p class="text-gray-400 text-xs sm:text-sm mb-6 flex items-center gap-2">
                                    <i class="fas fa-layer-group text-white/20 text-[10px]"></i>
                                    Série de <strong class="text-gray-200"><?= count($item->teachings) ?></strong> enseignement<?= count($item->teachings) > 1 ? 's' : '' ?>
                                </p>
                                
                                <!-- Séparateur discret -->
                                <div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-5"></div>

                                <!-- Footer de la carte : Date & Action -->
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-500 uppercase tracking-wider mb-0.5">Dernier ajout</span>
                                        <span class="text-[11px] text-gray-300 font-medium"><?= Helper::formatDate($item->updated_at) ?></span>
                                    </div>

                                    <?php if($item->category_id === $BolokeleId && Session::get('membre')['bolokele'] != 1): ?>
                                        <a href="/membre/engagement/<?= Session::get('membre')['member_id'] ?>" class="group/btn inline-flex items-center justify-center gap-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-primary/10 border border-primary/20 text-primary hover:bg-primary hover:text-black px-4 py-2 rounded-lg transition-all duration-300">
                                            S'engager <i class="fas fa-lock-open text-[10px] transition-transform duration-300 group-hover/btn:scale-110"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="../../enseignement/show/<?= $item->serie_id ?>?ssd=<?= $item->session_id ?>" class="group/btn inline-flex items-center justify-center gap-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-primary/10 border border-primary/20 text-primary hover:bg-primary hover:text-black px-4 py-2 rounded-lg transition-all duration-300 shadow-[0_0_15px_rgba(0,0,0,0)] hover:shadow-primary/30">
                                            Écouter <i class="fas fa-play text-[9px] transition-transform duration-300 group-hover/btn:scale-110"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php elseif($SeriesAlwaysOn): ?>
                        <div class="audio-card bg-gradient-to-b from-[#001b1a] to-[#000808] border border-white/5 rounded-2xl group relative overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.5)] hover:shadow-primary/20 flex flex-col h-full">
                            <?php 
                                $isNew = false;
                                if (!empty($SeriesAlwaysOn->updated_at)) {
                                    $createdAt = new DateTime($SeriesAlwaysOn->updated_at);
                                    $now = new DateTime();
                                    $diff = $now->diff($createdAt);
                                    $hours = ($diff->days * 24) + $diff->h;
                                    if ($hours < 24 && $diff->invert == 1) {
                                        $isNew = true;
                                    }
                                }
                            ?>
                            
                            <div class="absolute inset-0 bg-gradient-to-tr from-primary/0 via-primary/0 to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                            <div class="p-6 sm:p-7 flex flex-col flex-grow relative z-10">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20">
                                            <i class="fas fa-headphones text-[10px] text-primary"></i>
                                        </div>
                                        <span class="text-gray-400 text-[9px] uppercase tracking-widest font-semibold truncate max-w-[120px]"><?= $SeriesAlwaysOn->nom !== null ? htmlspecialchars($SeriesAlwaysOn->nom) : '' ?></span>
                                    </div>
                                    <?php if ($isNew): ?>
                                        <div class="flex items-center gap-1.5 bg-primary/10 border border-primary/30 text-primary text-[8px] font-bold uppercase px-3 py-1.5 rounded-full backdrop-blur-md shadow-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                            Nouveau
                                        </div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-[10px] font-mono px-2.5 py-1 rounded-md border border-white/5">
                                            <i class="far fa-eye text-[9px] opacity-70"></i>
                                            <?= $VuesModel->countAll(['serie_id' => $SeriesAlwaysOn->serie_id, 'session_id' => $SeriesAlwaysOn->session_id]); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <h3 class="text-lg sm:text-xl font-bold text-white leading-snug line-clamp-2 mb-3 group-hover:text-primary transition-colors duration-300 flex-grow">
                                    <?= Helper::textTruncate($SeriesAlwaysOn->nom, 35) ?>
                                </h3>

                                <p class="text-gray-400 text-xs sm:text-sm mb-6 flex items-center gap-2">
                                    <i class="fas fa-layer-group text-white/20 text-[10px]"></i>
                                    Série de <strong class="text-gray-200"><?= count($SeriesAlwaysOn->teachings) ?></strong> enseignement<?= count($SeriesAlwaysOn->teachings) > 1 ? 's' : '' ?>
                                </p>
                                
                                <div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-5"></div>

                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-500 uppercase tracking-wider mb-0.5">Dernier ajout</span>
                                        <span class="text-[11px] text-gray-300 font-medium"><?= Helper::formatDate($SeriesAlwaysOn->updated_at) ?></span>
                                    </div>
                                    <a href="../../enseignement/show/<?= $SeriesAlwaysOn->serie_id ?>?ssd=<?= $SeriesAlwaysOn->session_id ?>" class="group/btn inline-flex items-center justify-center gap-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-primary/10 border border-primary/20 text-primary hover:bg-primary hover:text-black px-4 py-2 rounded-lg transition-all duration-300 shadow-[0_0_15px_rgba(0,0,0,0)] hover:shadow-primary/30">
                                        Écouter <i class="fas fa-play text-[9px] transition-transform duration-300 group-hover/btn:scale-110"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php endif; ?>

                </div>
            <?php else: ?>  
                <div class="text-center py-20 sm:py-28 px-4 relative overflow-hidden rounded-2xl bg-[#111] border border-white/5">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 mx-auto bg-primary/5 border border-primary/10 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-book-open text-2xl text-primary/60"></i>
                        </div>
                        <h2 class="font-serif text-3xl sm:text-4xl text-white mb-3">Bibliothèque Sacrée</h2>
                        <p class="text-gray-400 text-sm italic mb-8">"La sagesse Mâat dans nos cœurs et dans notre âme."</p>
                        
                        <div class="inline-block px-6 py-3 rounded-xl bg-primary/10 border border-primary/20 backdrop-blur-sm max-w-lg mx-auto">
                            <span class="text-gray-300 text-[11px] sm:text-xs font-medium leading-relaxed block">La bibliothèque est en cours de préparation.<br> Revenez bientôt pour découvrir les enseignements.</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>