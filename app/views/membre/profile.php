<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="max-w-6xl mx-auto py-8 sm:py-12 px-4 sm:px-6">
        
        <!-- En-tête / Navigation du Dashboard -->
        <div class="mb-10 relative">
            <div class="flex flex-wrap items-center gap-3 border-b border-primary/20 pb-5">
                <button onclick="switchTab('page-identite')" id="btn-identite" class="tab-btn active-tab flex items-center gap-2 px-3 py-2 rounded-full text-[8px] font-bold uppercase tracking-wider transition-all duration-300 bg-primary text-black shadow-[0_0_20px_rgba(var(--primary-rgb),0.3)] hover:-translate-y-0.5">
                    <i class="fas fa-user-circle text-sm"></i> Profil
                </button>
                <button onclick="switchTab('page-parcours')" id="btn-parcours" class="tab-btn flex items-center gap-2 px-3 py-2 rounded-full text-[8px] font-bold uppercase tracking-wider transition-all duration-300 text-gray-400 bg-white/5 hover:bg-white/10 border border-transparent hover:border-primary/30 hover:text-white">
                    <i class="fas fa-route text-sm"></i> Engagements
                </button>
                <button onclick="switchTab('page-communaute')" id="btn-communaute" class="tab-btn flex items-center gap-2 px-3 py-2 rounded-full text-[8px] font-bold uppercase tracking-wider transition-all duration-300 text-gray-400 bg-white/5 hover:bg-white/10 border border-transparent hover:border-primary/30 hover:text-white">
                    <i class="fas fa-users text-sm"></i> Parrainage
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PAGE 1 : IDENTITÉ & INFORMATIONS DE BASE   -->
        <!-- ========================================== -->
        <div id="page-identite" class="tab-content block transition-opacity duration-500 opacity-100">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Avatar & Progression globale -->
                <div class="lg:col-span-12 xl:col-span-12 space-y-6">
                    <div class="relative from-white/[0.08] to-transparent backdrop-blur-2xl rounded-[2.5rem] p-8 color-border overflow-hidden group shadow-2xl border border-white/5">
                        <!-- Décoration d'ambiance -->
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 rounded-full blur-[80px] group-hover:bg-primary/20 transition-all duration-1000"></div>
                        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-accent/5 rounded-full blur-[80px]"></div>
                        
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="relative mb-8">
                                <div class="w-44 h-44 rounded-full border-4 border-[#cfbb30] shadow-2xl overflow-hidden relative z-10 bg-surface">
                                    <img src="../../<?= $Membre->path_profile ?>" alt="Avatar" 
                                        class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700 ease-out">
                                </div>
                                <div class="absolute bottom-2 right-2 w-10 h-10 bg-primary rounded-full border-4 border-[#121212] flex items-center justify-center shadow-lg z-20">
                                    <span class="text-[11px] text-black font-black">A+</span>
                                </div>
                            </div>

                            <h2 class="text-2xl font-serif font-medium text-white mb-2 tracking-tight"><?= $Membre->nom_postnom ?></h2>
                            
                            <div class="flex items-center gap-3 px-4 py-1.5 bg-white/5 rounded-full color-border mb-8 border border-white/10">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                                </span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase italic">
                                    <?= $Membre->genre ?> • <?= $Membre->niveau_initiation ?>
                                </p>
                            </div>

                            <?php if ($inSession): ?>
                                <!-- Cartes de Stats Fluides -->
                                <div class="w-full space-y-3 max-w-2xl mx-auto">
                                    <!-- Score de Progression -->
                                    <div class="group/stat bg-white/[0.03] hover:bg-white/[0.06] rounded-2xl p-5 border border-white/5 transition-all duration-300">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest"><strong class="text-primary"><?= $lastSession->nom ?>e Session</strong> Progression Spirituelle</span>
                                            <span class="text-[12px] font-black text-primary"><?= $evaluationSpirituel; ?>%</span>
                                        </div>
                                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-primary from-primary/60 to-primary shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)] transition-all duration-1000 ease-out" 
                                                style="width: <?= $evaluationSpirituel; ?>%"></div>
                                        </div>
                                    </div>

                                    <!-- Grade d'Assiduité -->
                                    <div class="flex items-center justify-between bg-white/[0.03] hover:bg-white/[0.06] rounded-2xl p-5 border border-white/5 transition-all duration-300">
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

                <!-- Warning Profil Incomplet -->
                <?php if ($Membre->pays === null || $Membre->ville === null || $Membre->ville === ''): ?>
                    <div class="lg:col-span-12 xl:col-span-12 space-y-6">
                        <div class="backdrop-blur-xl rounded-[2.5rem] p-10 border border-[#e7000b66] bg-red-900/5 shadow-xl relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-8">
                                    <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#e7000b] pl-4">Complétez votre profil</h3>
                                    <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-red-500/20 to-transparent"></div>
                                </div>
                                <p class="text-gray-400 text-sm mb-0">Veuillez compléter vos informations personnelles pour avoir un profil complet et accéder à toutes les fonctionnalités.</p>
                                <div class="mt-8 flex justify-end">
                                    <a href="../profile_edit/<?= $Membre->member_id ?>" 
                                    class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-red-600 rounded-full hover:shadow-[0_0_30px_rgba(231,0,11,0.4)] hover:-translate-y-1">
                                        <span class="relative z-10 flex items-center gap-2 uppercase text-[11px]">
                                            Mettre à jour mon profil
                                            <i class="fas fa-edit text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Données Personnelles -->
                <div class="lg:col-span-12 xl:col-span-12 space-y-6">
                    <div class="backdrop-blur-xl rounded-[2.5rem] p-10 bg-white/[0.02] border border-white/5 shadow-xl relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-10">
                                <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#cfbb30] pl-4">Données de l'Initié</h3>
                                <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-white/10 to-transparent"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-calendar-alt text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Date de naissance</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= Helper::formatDate($Membre->date_naissance) ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-globe-africa text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Origine & Nationalité</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->nationalite ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-flag text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Pays</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->pays ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-city text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Ville</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->ville ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-map-marker-alt text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Résidence Actuelle</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->adresse ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-graduation-cap text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Domaine d'études</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->domaine_etude ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-phone-alt text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Ligne Directe</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->phone_number ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-envelope text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Adresse Mail</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= $Membre->email ?></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/10 group-hover/item:border-primary/50 group-hover/item:bg-primary/10 transition-all">
                                        <i class="fas fa-shield-alt text-primary group-hover/item:scale-110 transition-transform"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Membre depuis le</p>
                                        <p class="text-sm text-gray-200 font-medium"><?= Helper::formatDate2($Membre->created_at) ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-9 flex justify-center">
                                <a href="../profile_edit/<?= $Membre->member_id ?>" 
                                class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold transition-all duration-300 bg-primary rounded-full hover:shadow-[0_0_15px_rgba(207,187,48,0.4)] hover:-translate-y-1">
                                    <span class="relative z-10 flex items-center gap-2 uppercase text-[11px]">
                                        Mettre à jour mon profil
                                        <i class="fas fa-edit text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PAGE 2 : PARCOURS, ACTIONS & RESSOURCES    -->
        <!-- ========================================== -->
        <div id="page-parcours" class="tab-content hidden opacity-0 transition-opacity duration-500">
            <div class="grid grid-cols-1 gap-8 items-start">
                
                <!-- CHEMINEMENT -->
                <div class="backdrop-blur-xl rounded-[2.5rem] p-10 bg-white/[0.02] border border-white/5 shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#cfbb30] pl-4">Cheminement & Évolution</h3>
                            <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-gray-400 text-sm mb-8">Vos parcours et évolutions initiatiques au sein de la Communaute Lobola</p>

                        <!-- liste des sessions et le taux % de lecture de chaque session -->
                        <div x-data="{
                                page: 1,
                                perPage: 3,
                                totalItems: 0,
                                get totalPages() {
                                    return Math.ceil(this.totalItems / this.perPage);
                                },
                                updateView() {
                                    let start = (this.page - 1) * this.perPage;
                                    let end = this.page * this.perPage;
                                    
                                    // Gère l'affichage dynamique des sessions
                                    if (this.$refs.sessionContainer) {
                                        Array.from(this.$refs.sessionContainer.children).forEach((el, index) => {
                                            el.style.display = (index >= start && index < end) ? '' : 'none';
                                        });
                                    }
                                }
                            }"
                            x-init="
                                $nextTick(() => {
                                    if ($refs.sessionContainer) {
                                        totalItems = $refs.sessionContainer.children.length;
                                        updateView();
                                    }
                                });
                                $watch('page', () => updateView());
                            "
                            class="w-full">

                            <!-- Conteneur des sessions -->
                            <div x-ref="sessionContainer" class="space-y-4">
                                <?php if (!empty($scoreSessions)): ?>
                                    <?php foreach ($scoreSessions as $item): ?>
                                        <div class="p-5 rounded-2xl border border-white/5 bg-white/5 hover:bg-white/10 transition-all duration-300">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                                                <div>
                                                    <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-[#cfbb30]"></span>
                                                        <?= htmlspecialchars($item['session_title']) ?>e Session 
                                                    </h4>
                                                    <p class="text-[10px] text-gray-400 mt-2">
                                                        Période : du <?= Helper::formatDate($item['date_debut']) ?> au <?= Helper::formatDate($item['date_fin']) ?>
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs font-semibold text-gray-300">
                                                        <?= $item['stats']['read_count'] ?> / <?= $item['stats']['total_to_read'] ?> vus
                                                    </span>
                                                    <span class="text-xs font-bold text-[#cfbb30]">
                                                        <?= $item['stats']['progress_bar'] ?>%
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Barre de progression -->
                                            <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-gradient-to-r from-[#cfbb30]/40 to-[#cfbb30] h-full rounded-full transition-all duration-500" 
                                                    style="width: <?= $item['stats']['progress_bar'] ?>%">
                                                </div>
                                            </div>

                                            <?php if (!empty($item['stats']['last_activity'])): ?>
                                                <p class="text-[9px] text-gray-500 mt-3 italic">
                                                    Dernière lecture : <?= Helper::formatDate2($item['stats']['last_activity']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-6 text-gray-500 text-sm">
                                        <i class="fas fa-history text-lg mb-2 block text-white/20"></i>
                                        Aucune session expirée à afficher.
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Contrôles de pagination (s'affichent si > 3 éléments) -->
                            <div x-show="totalPages > 1" style="display: none;" class="flex items-center justify-between mt-6 border-t border-white/5 pt-4">
                                <button 
                                    @click="page > 1 ? page-- : null" 
                                    :disabled="page === 1"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-white/5 bg-white/5 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                                    Précédent
                                </button>
                                
                                <span class="text-xs text-gray-400">
                                    Page <span x-text="page" class="text-white font-bold"></span> sur <span x-text="totalPages" class="text-white font-bold"></span>
                                </span>
                                
                                <button 
                                    @click="page < totalPages ? page++ : null" 
                                    :disabled="page === totalPages"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-white/5 bg-white/5 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                                    Suivant
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Engagement -->
                <div class="backdrop-blur-xl rounded-[2.5rem] p-10 bg-white/[0.02] border border-white/5 shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#cfbb30] pl-4">Engagements</h3>
                            <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-gray-400 text-sm mb-8">Gérez votre statut, vos differants paiements et imprimez vos documents officiels liés à votre parcours initiatique au sein de La Communauté Lobola.</p>

                        <div class="flex flex-wrap gap-4 items-center">
                            <?php if ($Membre->niveau_initiation === ARRAY_TYPE_NIVEAU_INITIATION[3]):?>
                                <?php if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1] 
                                        || $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2]
                                        || $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && !$paiement
                                        || $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && $paiement && $paiement->payment_status === ARRAY_PAYMENT_STATUS[0]): ?>
                                        <a href="../attente/<?= $Membre->member_id ?>" 
                                        class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-purple-600/80 border border-purple-500/50 rounded-full hover:shadow-[0_0_30px_rgba(147,51,234,0.4)] hover:-translate-y-1">
                                            <span class="relative z-10 flex items-center gap-2 uppercase text-xs">
                                                <i class="fas fa-clock text-[10px]"></i> Etat de mon engagement
                                            </span>
                                        </a>
                                <?php elseif(!$Membre->statut_engagement): ?>
                                        <a href="../engagement/<?= $Membre->member_id ?>" 
                                        class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-purple-600/80 border border-purple-500/50 rounded-full hover:shadow-[0_0_30px_rgba(147,51,234,0.4)] hover:-translate-y-1">
                                            <span class="relative z-10 flex items-center gap-2 uppercase text-xs">
                                                <i class="fas fa-handshake text-[10px]"></i> M'engager aux enseignements
                                            </span>
                                        </a>
                                <?php elseif($paiement->payment_status === ARRAY_PAYMENT_STATUS[1]): ?>
                                        <a href="../../pay/afrik_pay" 
                                        class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-green-600/80 border border-green-500/50 rounded-full hover:shadow-[0_0_30px_rgba(22,163,74,0.4)] hover:-translate-y-1">
                                            <span class="relative z-10 flex items-center gap-2 uppercase text-xs">
                                                <i class="fas fa-credit-card text-[10px]"></i> Payer mon prochain versement
                                            </span>
                                        </a>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Impression Fiche -->
                            <form action="" method="POST" class="inline-block">
                                <button name="cllil_membre_expt_fiche" type="submit" class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-black transition-all duration-300 bg-primary rounded-full hover:shadow-[0_0_30px_rgba(var(--primary-rgb),0.4)] hover:-translate-y-1">
                                    <span class="relative z-10 flex items-center gap-2 uppercase text-xs">
                                        <i class="fas fa-print text-[11px]"></i> Imprimer ma fiche
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ressources Téléchargeables -->
                <?php if($Membre->niveau_initiation === ARRAY_TYPE_NIVEAU_INITIATION[3]): ?>
                    <div class="backdrop-blur-xl rounded-[2.5rem] p-10 bg-white/[0.02] border border-white/5 shadow-xl relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#cfbb30] pl-4">Ressources & Documents</h3>
                                <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-white/10 to-transparent"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a href="../../assets/ressources/engagement/FORMULAIRE%20ENGAGEMENT%20A%20LA%20COMMUNAUTE%20LOBOLA.docx" 
                                    class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 hover:border-primary/30 transition-all duration-300 group" download target="_blank" rel="noopener noreferrer">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center">
                                            <i class="fas fa-file-word text-blue-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-200 mb-0.5">Formulaire d'Engagement</span>
                                            <span class="text-[10px] text-gray-500 uppercase tracking-wider">Document DOCX</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-primary opacity-0 group-hover:opacity-100 -translate-y-2 group-hover:translate-y-0 transition-all duration-300"></i>
                                </a>
                                <!-- Placeholder pour d'autres documents -->
                                <!--
                                <a href="#" class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 hover:border-primary/30 transition-all duration-300 group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-red-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-200 mb-0.5">Calendrier des Rituels</span>
                                            <span class="text-[10px] text-gray-500 uppercase tracking-wider">Document PDF</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-primary opacity-0 group-hover:opacity-100 -translate-y-2 group-hover:translate-y-0 transition-all duration-300"></i>
                                </a>
                                -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PAGE 3 : RÉSEAU, INVITATIONS & COMMUNAUTÉ  -->
        <!-- ========================================== -->
        <div id="page-communaute" class="tab-content hidden opacity-0 transition-opacity duration-500">
            <div class="grid grid-cols-1 gap-8 items-start">
                
                <div class="backdrop-blur-xl rounded-[2.5rem] p-10 bg-white/[0.02] border border-white/5 shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] opacity-80 border-l-4 border-[#cfbb30] pl-4">Parrainage</h3>
                            <div class="h-[1px] flex-grow mx-6 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <!-- Bloc Génération de Lien -->
                            <div class="bg-paper rounded-3xl p-6 border border-white/5">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20">
                                        <i class="fas fa-link text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">Invitation</p>
                                        <h4 class="text-lg font-semibold text-white">Partager un lien</h4>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-400 mb-6">Générez un lien unique pour permettre à une personne de remplir sa demande d’intégration.</p>
                                
                                <?php if (empty($inviteLink)): ?>
                                    <form method="post">
                                        <input type="hidden" name="cllil_membre_generate_invite" value="1">
                                        <button type="submit" class="w-full rounded-xl bg-primary px-5 py-3.5 text-sm font-semibold text-black transition hover:-translate-y-0.5 hover:shadow-[0_5px_15px_rgba(var(--primary-rgb),0.3)]">
                                            <i class="fas fa-magic mr-2"></i> Générer mon lien
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="rounded-2xl color-border p-4">
                                        <p class="text-[9px] font-bold uppercase tracking-[0.3em] text-primary/80 mb-2">Lien actif</p>
                                        <div class="flex flex-col gap-3">
                                            <input id="invite-link" type="text" value="<?= htmlspecialchars($inviteLink) ?>" readonly class="w-full rounded-xl border border-white/10 bg-black/50 px-4 py-3 text-sm text-primary outline-none focus:border-primary/50 transition-colors">
                                            <button type="button" onclick="copyInviteLink(event)" class="w-full rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 cursor-pointer text-sm font-semibold text-primary transition hover:bg-primary hover:text-black">
                                                <i class="fas fa-copy mr-2"></i> Copier le lien
                                            </button>
                                        </div>
                                        <p class="mt-3 text-[11px] text-gray-500 text-center"><i class="fas fa-info-circle"></i> Valable pendant 7 jours.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Bloc Liste des invités -->
                            <div>
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500 mb-1">Historique</p>
                                        <h4 class="text-lg font-semibold text-white">Membres invités</h4>
                                    </div>
                                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-bold text-primary"><?= count($invitedMembers ?? []) ?> Personnes</span>
                                </div>

                                <?php if (!empty($invitedMembers)): ?>
                                    <div x-data="{
                                        page: 1,
                                        perPage: 3,
                                        totalItems: 0,
                                        get totalPages() {
                                            return Math.ceil(this.totalItems / this.perPage);
                                        },
                                        updateView() {
                                            let start = (this.page - 1) * this.perPage;
                                            let end = this.page * this.perPage;
                                            
                                            // Parcourt les éléments générés par PHP et gère leur affichage
                                            Array.from(this.$refs.container.children).forEach((el, index) => {
                                                el.style.display = (index >= start && index < end) ? '' : 'none';
                                            });
                                        }
                                        }"
                                        x-init="
                                            $nextTick(() => {
                                                totalItems = $refs.container.children.length;
                                                updateView();
                                            });
                                            $watch('page', () => updateView());
                                        "
                                        class="w-full">

                                        <!-- Votre code original (j'ai juste ajouté x-ref='container' sur la div parente) -->
                                        <div x-ref="container" class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                            <?php foreach ($invitedMembers as $invitedMember): ?>
                                                <div class="flex items-center justify-between rounded-2xl border border-white/5 bg-white/5 px-4 py-3 hover:bg-white/10 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 text-sm uppercase font-bold text-primary">
                                                            <?= htmlspecialchars(substr($invitedMember->nom_postnom ?? 'M', 0, 1)) ?>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-white"><?= htmlspecialchars($invitedMember->nom_postnom ?? '') ?></p>
                                                            <p class="text-[10px] text-gray-400 tracking-wider">Rejoint le <?= Helper::formatDate2($invitedMember->created_at ?? '') ?></p>
                                                        </div>
                                                    </div>
                                                    <span class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1.5 text-[8px] font-bold uppercase tracking-[0.2em] text-primary">
                                                        <?php if($invitedMember->status === ARRAY_STATUS_MEMBER[2]): ?>
                                                            ACTIVE
                                                        <?php elseif($invitedMember->status === ARRAY_STATUS_MEMBER[3]): ?>
                                                            SUSPENDUE
                                                        <?php elseif($invitedMember->status === ARRAY_STATUS_MEMBER[5]): ?>
                                                            INACTIVE
                                                        <?php elseif($invitedMember->status === ARRAY_STATUS_MEMBER[1]): ?>
                                                            Attente Intégration
                                                        <?php elseif($invitedMember->status === ARRAY_STATUS_MEMBER[4]): ?>
                                                            Intégration Rejetée
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- Contrôles de pagination (s'affichent uniquement s'il y a plus de 3 éléments) -->
                                        <div x-show="totalPages > 1" style="display: none;" class="flex items-center justify-between mt-4 border-t border-white/10 pt-4">
                                            <button 
                                                @click="page > 1 ? page-- : null" 
                                                :disabled="page === 1"
                                                class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-white/5 bg-white/5 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                                                Précédent
                                            </button>
                                            
                                            <span class="text-xs text-gray-400">
                                                Page <span x-text="page" class="text-white font-bold"></span> sur <span x-text="totalPages" class="text-white font-bold"></span>
                                            </span>
                                            
                                            <button 
                                                @click="page < totalPages ? page++ : null" 
                                                :disabled="page === totalPages"
                                                class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-white/5 bg-white/5 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                                                Suivant
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-10 rounded-3xl border border-dashed border-white/10 bg-white/[0.02]">
                                        <div class="w-12 h-12 mx-auto rounded-full bg-white/5 flex items-center justify-center mb-3">
                                            <i class="fas fa-user-plus text-gray-600 text-lg"></i>
                                        </div>
                                        <p class="text-sm text-gray-400 px-4">Aucun membre n’a encore rejoint la communauté grâce à votre lien.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</section>
    
<script>
function copyInviteLink(event) {
    const input = document.getElementById('invite-link');
    if (!input) return;
    input.select();
    input.setSelectionRange(0, input.value.length);
    navigator.clipboard.writeText(input.value).then(() => {
        const button = event?.currentTarget;
        if (button) {
            const originalText = button.innerText;
            button.innerText = 'Copié !';
            setTimeout(() => button.innerText = originalText, 1500);
        }
    });
}
// Logique de navigation entre les onglets
    function switchTab(tabId) {
        // 1. Cacher tous les contenus
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('opacity-100');
            content.classList.add('opacity-0');
        });

        // 2. Réinitialiser le style de tous les boutons
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('bg-primary', 'text-black', 'active-tab', 'shadow-[0_0_20px_rgba(var(--primary-rgb),0.3)]', 'hover:-translate-y-0.5');
            btn.classList.add('text-gray-400', 'bg-white/5', 'hover:bg-white/10', 'border-transparent', 'hover:border-primary/30', 'hover:text-white');
        });

        // 3. Afficher le contenu ciblé
        const activeContent = document.getElementById(tabId);
        if(activeContent) {
            activeContent.classList.remove('hidden');
            // Léger délai pour déclencher l'animation CSS d'opacité
            setTimeout(() => {
                activeContent.classList.remove('opacity-0');
                activeContent.classList.add('opacity-100');
            }, 50);
        }

        // 4. Mettre en surbrillance le bouton cliqué
        // Extraction du nom du bouton basé sur l'ID de l'onglet (ex: page-identite -> btn-identite)
        const btnId = 'btn-' + tabId.replace('page-', '');
        const activeBtn = document.getElementById(btnId);
        
        if(activeBtn) {
            activeBtn.classList.remove('text-gray-400', 'bg-white/5', 'hover:bg-white/10', 'border-transparent', 'hover:border-primary/30', 'hover:text-white');
            activeBtn.classList.add('bg-primary', 'text-black', 'active-tab', 'shadow-[0_0_20px_rgba(var(--primary-rgb),0.3)]', 'hover:-translate-y-0.5');
        }
    }

    // Fonction existante pour copier le lien d'invitation (ajoutée pour garantir que le JS fonctionne si absent)
    function copyInviteLink(event) {
        const input = document.getElementById('invite-link');
        if(input) {
            input.select();
            input.setSelectionRange(0, 99999); // Pour mobiles
            document.execCommand("copy");
            
            // Feedback visuel du bouton
            const btn = event.currentTarget;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check mr-2"></i> Lien Copié !';
            btn.classList.add('bg-green-500/20', 'text-green-500', 'border-green-500/50');
            btn.classList.remove('bg-primary/10', 'text-primary', 'border-primary/30');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-500/20', 'text-green-500', 'border-green-500/50');
                btn.classList.add('bg-primary/10', 'text-primary', 'border-primary/30');
            }, 2000);
        }
    }
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>