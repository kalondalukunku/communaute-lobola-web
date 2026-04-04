<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="max-w-6xl mx-auto py-12 px-6">
        <!-- Conteneur Principal avec Grid adaptatif -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Colonne Latérale : Identité (4 colonnes sur 12) -->
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                <div class="relative from-white/[0.08] to-transparent backdrop-blur-2xl rounded-[2.5rem] p-8 color-border overflow-hidden group shadow-2xl">
                    <!-- Décoration d'ambiance -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 rounded-full blur-[80px] group-hover:bg-primary/20 transition-all duration-1000"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-accent/5 rounded-full blur-[80px]"></div>
                    
                    <!-- Section Avatar avec bague de progression -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="relative mb-8">
                            <!-- Anneau de progression spirituelle visuel -->
                            <!-- <svg class="absolute -inset-4 w-[calc(100%+32px)] h-[calc(100%+32px)] -rotate-90">
                                <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2" fill="transparent" class="text-white/5" />
                                <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2" fill="transparent" 
                                        class="text-primary" stroke-dasharray="300" stroke-dashoffset="<?= 300 - (300 * $evaluationSpirituel / 100); ?>" 
                                        stroke-linecap="round" />
                            </svg> -->
                            <div class="w-44 h-44 rounded-full border-4 border-[#cfbb30] shadow-2xl overflow-hidden relative z-10 bg-surface">
                                <img src="../../<?= $Membre->path_profile ?>" alt="Avatar" 
                                    class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700 ease-out">
                            </div>
                            <div class="absolute bottom-2 right-2 w-10 h-10 bg-primary rounded-full border-4 border-[#121212] flex items-center justify-center shadow-lg z-20">
                                <span class="text-[11px] text-paper font-black">A+</span>
                            </div>
                        </div>

                        <h2 class="text-2xl font-display font-medium text-white mb-2 tracking-tight"><?= $Membre->nom_postnom ?></h2>
                        
                        <div class="flex items-center gap-3 px-4 py-1.5 bg-white/5 rounded-full color-border mb-8">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                            <p class="text-[10px] font-bold text-gray-400 uppercase italic">
                                <?= $Membre->genre ?> • <?= $Membre->niveau_initiation ?>
                            </p>
                        </div>

                        <?php if ($isOn): ?>
                            <!-- Cartes de Stats Fluides -->
                            <div class="w-full space-y-3">
                                <!-- Score de Progression -->
                                <div class="group/stat bg-white/[0.03] hover:bg-white/[0.06] rounded-2xl p-5 color-border transition-all duration-300">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Progression Spirituelle</span>
                                        <span class="text-[12px] font-black text-primary"><?= $evaluationSpirituel; ?>%</span>
                                    </div>
                                    <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary from-primary/60 to-primary shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)] transition-all duration-1000 ease-out" 
                                            style="width: <?= $evaluationSpirituel; ?>%"></div>
                                    </div>
                                </div>

                                <!-- Grade d'Assiduité -->
                                <div class="flex items-center justify-between bg-white/[0.03] hover:bg-white/[0.06] rounded-2xl p-5 color-border transition-all duration-300">
                                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Assiduité</span>
                                    <div class="text-right">
                                        <span class="block text-sm font-bold text-white leading-none mb-1"><?= Helper::getAssiduityGrade($evaluationSpirituel); ?></span>
                                        <span class="text-[9px] text-primary font-medium italic">Niveau Actuel</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Colonne Informations (8 colonnes sur 12) -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                <div class="backdrop-blur-xl rounded-[2.5rem] p-10 color-border shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#cfbb30] pl-4">Données de l'Initié</h3>
                            <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                            <!-- Item Info -->
                            <div class="flex items-start gap-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center color-border group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                    <i class="fas fa-calendar-alt text-primary group-hover/item:text-primary transition-colors"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Date de naissance</p>
                                    <p class="text-sm text-gray-200 font-medium"><?= Helper::formatDate($Membre->date_naissance) ?></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center color-border group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                    <i class="fas fa-globe-africa text-primary group-hover/item:text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Origine & Nationalité</p>
                                    <p class="text-sm text-gray-200 font-medium"><?= $Membre->nationalite ?></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center color-border group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                    <i class="fas fa-map-marker-alt text-primary group-hover/item:text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Résidence Actuelle</p>
                                    <p class="text-sm text-gray-200 font-medium"><?= $Membre->ville ?>, <?= $Membre->adresse ?></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center color-border group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                    <i class="fas fa-phone-alt text-primary group-hover/item:text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Ligne Directe</p>
                                    <p class="text-sm text-gray-200 font-medium"><?= $Membre->phone_number ?></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center color-border group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                    <i class="fas fa-envelope text-primary group-hover/item:text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Adresse Mail</p>
                                    <p class="text-sm text-gray-200 font-medium"><?= $Membre->email ?></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center color-border group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                    <i class="fas fa-shield-alt text-primary group-hover/item:text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Membre depuis le</p>
                                    <p class="text-sm text-gray-200 font-medium"><?= Helper::formatDate2($Membre->created_at) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($Membre->niveau_initiation === ARRAY_TYPE_NIVEAU_INITIATION[3]):?>
                            <?php if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1]): ?>
                                <!-- <div class="mt-12 flex justify-end">
                                    <a href="../attente/<?= $Membre->member_id ?>" 
                                    class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-paper transition-all duration-300 bg-primary rounded-full hover:shadow-[0_0_30px_rgba(var(--primary-rgb),0.4)] hover:-translate-y-1">
                                        <span class="relative z-10 flex items-center gap-2 uppercase text-xs">
                                            Voir mon Attente
                                            <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </a>
                                </div> -->
                            <?php else: ?>
                                <!-- <div class="mt-12 flex justify-end">
                                    <a href="../engagement/<?= $Membre->member_id ?>" 
                                    class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-paper transition-all duration-300 bg-primary rounded-full hover:shadow-[0_0_30px_rgba(var(--primary-rgb),0.4)] hover:-translate-y-1">
                                        <span class="relative z-10 flex items-center gap-2 uppercase text-xs">
                                            Confirmer mon Engagement
                                            <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </a>
                                </div> -->
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>