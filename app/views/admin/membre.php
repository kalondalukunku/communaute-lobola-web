<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Top Bar -->
        <header class="h-20 bg-paper border-b border-gray-100 px-3 flex items-center justify-between">
            <!-- Bouton Hamburger -->
            <button id="openSidebar" class="lg:hidden w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-primary hover:bg-gray-100 transition shadow-sm">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="flex items-center gap-2">
                <a href="<?= RETOUR_EN_ARRIERE ?>" class="text-gray-400 hover:text-secondary"><i class="fas fa-arrow-left"></i></a>
                <span class="font-serif text-xl md:text-sm font-bold italic text-gray-400">Dossier / <span class="text-primary"><?= $Membre->nom_postnom ?></span></span>
            </div>
            <div class="flex gap-3">
                <?php if($Membre->status === ARRAY_STATUS_MEMBER[2] && $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && $Payment): ?>
                    <form action="" method="post" class="hidden md:block">
                        <button name="cllil_membre_suspended" class="px-4 py-2 rounded-xl border border-gray text-[10px] font-black tracking-widest text-gray-400 hover:bg-gray-90 transition">Suspendre</button>
                    </form>
                    <form action="" method="post" class="hidden md:block">
                        <button name="cllil_membre_desactive" class="px-4 py-2 rounded-xl border border-gray text-[10px] font-black tracking-widest text-orange-400 hover:bg-orange-90 transition">Désactiver</button>
                    </form>
                <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[3] || $Membre->status === ARRAY_STATUS_MEMBER[4] && $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && $Payment): ?>
                    <form action="" method="post">
                        <button name="cllil_membre_active" class="px-4 py-2 rounded-xl border border-green text-[10px] font-black tracking-widest text-green-500 hover:bg-green-90 transition">Activer</button>
                    </form>
                <?php endif; ?>
                <form action="" method="post">
                    <button name="cllil_membre_delete" class="px-4 py-2 rounded-xl border border-red text-[10px] font-black tracking-widest text-red-500 hover:bg-red-90 transition">Supprimer</button>
                </form>
                <!-- <button class="bg-secondary text-primary px-6 py-2 rounded-xl text-xs font-black tracking-widest shadow-lg">FINALISER L'ADMISSION</button> -->
            </div>
        </header>

        <div class="pt-10 px-4 max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Left Column: Info & Stats -->
            <div class="space-y-8">
                <div class="glass-card rounded-[2.5rem] p-8 text-center">
                    <div class="relative inline-block mb-4">
                        <div class="w-32 h-32 rounded-full border-4 border-primary/20 p-1">
                            <img src="../../<?= $Membre->path_profile ?>" alt="<?= $Membre->nom_postnom ?>" class="w-full h-full rounded-full object-cover shadow-xl">
                        </div>
                        <div class="absolute bottom-1 right-1 w-8 h-8 bg-amber-500 border-4 border-white rounded-full flex items-center justify-center text-white text-[10px]">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <h2 class="text-xl text-white font-bold"><?= $Membre->nom_postnom ?></h2>
                    <p class="text-xs text-gray-400 font-medium mb-6">Inscrite le <?= Helper::formatDate($Membre->created_at) ?></p>
                    
                    <div class="flex flex-col gap-2">
                        <span class="border border-amber rounded-xl p-2 text-[10px] text-amber-600 self-center">ATTENTE APPROBATION</span>
                    </div>
                </div>

                <div class="glass-card rounded-[2.5rem] p-8">
                    <h3 class="font-serif text-lg text-white font-bold mb-4">Contact</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->phone_number ?></span>
                        </div>
                        <?php if($Membre->email): ?>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-primary text-xs"></i>
                                <span class="text-xs font-bold text-gray-600"><?= $Membre->email ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->adresse ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Validation Steps -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section 1: Engagement Spirituel -->
                <div class="glass-card color-border rounded-[2.5rem] overflow-hidden bg-[#cfbb30]/20">
                    <div class="p-4 pb-2 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 bg-paper text-primary rounded-full flex items-center justify-center font-black text-xs">1</span>
                            <h3 class="font-serif text-white text-md md:text-sm font-bold">Engagement Spirituel</h3>
                        </div>
                        <div>
                            <?php if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1]): ?>
                            <span class="text-[10px] font-black text-amber-500 uppercase tracking-tighter">
                                <i class="fas fa-spinner fa-spin mr-2"></i>En cours
                            </span>
                            <?php elseif($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2]): ?>
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tighter">
                                <i class="fas fa-times-circle mr-2"></i>Rejeté
                            </span>
                            <?php elseif($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0]): ?>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-green-500 uppercase tracking-tighter">
                                        <i class="fas fa-check-circle mr-2"></i>Approuvé
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="text-center mb-5">
                        <form action="" method="post">
                            <button name="cllil_membre_download_engagement<?= $membreId ?>" class="bg-primary text-paper px-4 py-2 rounded-xl text-[10px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">Télécharger</button>
                        </form>
                    </div>
                    
                    <?php if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1]): ?>
                        <div class="p-8">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-4">Visualisation de l'engagement</p>

                            <div class="lg:col-span-2 card-container p-4 h-[700px] flex flex-col">
                                <div class="relative w-full rounded-lg overflow-hidden bg-slate-50 shadow-inner" 
                                    style="height: calc(100vh - 250px); min-height: 620px;">
                                    <iframe 
                                        src="<?= BASE_URL ?>/assets/uploads/document/<?= $name ?>#toolbar=0&navpanes=0&scrollbar=0" 
                                        class="absolute inset-0 w-full h-full"
                                        style="border: none;"
                                        title="Aperçu PDF">
                                    </iframe>
                                </div>

                                <!-- Pied de page optionnel / Info -->
                                <div class="mt-4 flex items-center justify-center gap-2 text-[8px] text-slate-500 uppercase tracking-widest">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Mode lecture uniquement
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-3">
                                <form action="" method="post">
                                    <button name="cllil_membre_eng_rejeted" class="px-6 py-3 rounded-xl border border-red text-[10px] font-black tracking-widest text-red-500 hover:bg-red-90 transition">Réjéter</button>
                                </form>
                                <form action="" method="post">
                                    <button name="cllil_membre_approuve" class="bg-primary text-paper px-8 py-3 rounded-xl text-[10px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">Approuver l'engagement</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($Membre->statut_engagement !== ARRAY_STATUS_ENGAGEMENT[2]) : ?>
                    <!-- Section 2: Paiement / Offrande -->
                    <div class="glass-card color-border rounded-[2.5rem] overflow-hidden">
                        <div class="p-4 color-border-b flex justify-between items-center bg-[#cfbb30]/20">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 bg-paper text-primary rounded-full flex items-center justify-center font-black text-xs">2</span>
                                <h3 class="font-serif text-white text-md md:text-sm font-bold">Vérification du Paiement</h3>
                            </div>
                            
                            <?php if($Payment): ?>
                            <span class="text-[10px] font-black text-green-500 uppercase tracking-tighter">
                                <i class="fas fa-check-circle mr-2"></i>Approuvé
                            </span>
                            <?php else: ?>
                            <span class="text-[10px] font-black text-amber-500 uppercase tracking-tighter">
                                <i class="fas fa-spinner fa-spin mr-2"></i>En cours
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if(!$Payment): ?>
                        <div class="p-8">
                            <div class="flex items-start justify-between mb-8">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1">Type d'engagement</p>
                                    <p class="text-md text-white font-bold"><?= $Membre->modalite_engagement ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1">Montant</p>
                                    <p class="text-md font-bold text-primary"><?= Utils::getMonthsByModalite($Membre->modalite_engagement, $Membre->montant) .' '. $Membre->devise   ?></p>
                                </div>
                            </div>

                            <!-- <div class="bg-secondary rounded-3xl p-6 text-white flex items-center justify-between shadow-2xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-xl text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Preuve de virement</p>
                                        <p class="text-xs font-bold">VIREMENT_JULIANNE_M.PDF</p>
                                    </div>
                                </div>
                                <button class="bg-white/10 hover:bg-white/20 p-3 rounded-xl transition">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div> -->

                            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                                <div class="flex-grow flex items-center gap-2 text-[10px] text-gray-400 italic">
                                    <i class="fas fa-info-circle"></i>
                                    Vérifiez que le libellé correspond au nom du membre
                                </div>
                                <form action="" method="post">
                                    <button name="cllil_membre_validate" class="bg-primary text-paper px-8 py-3 rounded-xl text-[10px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">CONFIRMER LE PAIEMENT</button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>