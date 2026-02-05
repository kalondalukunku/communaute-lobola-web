<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="w-[80%] mx-auto py-12 px-6 grid grid-cols-1 gap-8">
        
        <!-- Colonne Latérale : Statut & Identité -->
        <div class="space-y-6">
            <div class="p-8 text-center relative overflow-hidden group">
                <!-- Décoration subtile -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/5 rounded-full blur-3xl group-hover:bg-accent/10 transition"></div>
                
                <div class="relative inline-block mb-6">
                    <div class="w-40 h-40 rounded-full border-2 border-accent/20 p-2">
                        <img src="../../<?= $Membre->path_profile ?>" alt="Avatar" class="w-full h-full rounded-full object-cover filter grayscale hover:grayscale-0 transition-all duration-700">
                    </div>
                    <span class="absolute top-2 right-2 w-6 h-6 bg-accent rounded-full border-4 border-surface flex items-center justify-center text-[8px] text-noble font-bold">A+</span>
                </div>

                <h2 class="text-2xl font-display italic text-white mb-2"><?= $Membre->nom_postnom ?></h2>
                <div class="flex items-center justify-center gap-2 mb-6">
                    <span class="w-2 h-2 bg-accent rounded-full"></span>
                    <p class="text-[9px] font-bold tracking-widest text-gray-500 uppercase"><?= $Membre->genre ?> - <?= $Membre->niveau_initiation ?></p>
                </div>

                <div class="space-y-3">
                    <div class="bg-white/5 rounded-2xl p-4 flex justify-between items-center">
                        <span class="text-[9px] font-bold text-gray-500 uppercase">Score Moyen</span>
                        <span class="text-sm font-bold text-accent">94%</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 flex justify-between items-center">
                        <span class="text-[9px] font-bold text-gray-500 uppercase">Assiduité</span>
                        <span class="text-sm font-bold text-white">Élite</span>
                    </div>
                </div>
            </div>

            <div class="glass-panel p-6">
                <h3 class="text-[10px] font-extrabold text-white uppercase tracking-ultra mb-6 opacity-50 text-center">Coordonnées Privées</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-calendar text-primary"></i>
                        <span class="text-gray-400"><?= Helper::formatDate($Membre->date_naissance) ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-flag text-primary"></i>
                        <span class="text-gray-400"><?= $Membre->nationalite ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-location-dot text-primary"></i>
                        <span class="text-gray-400"><?= $Membre->ville ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-location-dot text-primary"></i>
                        <span class="text-gray-400"><?= $Membre->adresse ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-phone text-primary"></i>
                        <span class="text-gray-400"><?= $Membre->phone_number ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-paper-plane text-primary"></i>
                        <span class="text-gray-400"><?= $Membre->email ?></span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <i class="fas fa-calendar-check text-primary"></i>
                        <span class="text-gray-400">Le <?= Helper::formatDate2($Membre->created_at) ?></span>
                    </div>
                </div>
            </div>

            <?php if ($Membre->niveau_initiation == ARRAY_TYPE_NIVEAU_INITIATION[2]):?>
                <div class="mt-4 justify-center flex">
                    <!-- <a href="../engagement/<?= $Membre->member_id ?>" class="btn-primaire w-full md:w-auto text-white px-16 py-5 rounded-full font-bold shadow-2xl hover:shadow-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-4">M'engager</a> -->
                </div>
            <?php endif; ?>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>