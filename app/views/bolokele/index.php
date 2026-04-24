<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <?php if(isset($paiedMembre->payment_status) && $paiedMembre->payment_status  === ARRAY_PAYMENT_STATUS[1]): ?>
        <main class="flex-grow container mx-auto px-4 py-12">
            <div class="fade-in">
                <!-- Section Titre -->
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-primary/10 pb-6">
                    <div class="max-w-3xl">
                        <h2 class="font-serif text-3xl text-primary mb-4">Bibliothèque Sacrée de l'enseignement avancé : <span class="text-purple-400">Bolokele</span></h2>
                        <p class="text-gray-500 text-sm italic">Découvrez les enseignements hautement spirituels enseignés par les maîtres LOBOLA LO ILONDO et reservés uniquement aux membres engagés.</p>
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
                                    
                                    <a href="../../bolokele/show/<?= $item->serie_id ?>" class="text-xs font-bold uppercase tracking-widest text-primary hover:underline">
                                        Écouter <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </main>
    <?php else: ?>
        <div class="text-center py-40">
            <h2 class="font-serif text-4xl text-primary mb-4">Accès Restreint</h2>
            <p class="text-gray-500 text-sm italic mb-8">"Pour accéder à ce contenu, veuillez faire une demande d'engagement."</p>
            <a href="../../membre/engagement/<?= Session::get('membre')['member_id'] ?>" class="inline-block px-6 py-3 rounded-lg bg-primary hover:bg-primary/90 text-black font-bold transition-colors">
                S'engager pour accéder aux enseignements
            </a>
        </div>
    <?php endif; ?>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>