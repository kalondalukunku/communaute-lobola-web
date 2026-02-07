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
                <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[3] || $Membre->status === ARRAY_STATUS_MEMBER[5] && $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && $Payment): ?>
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
                        <?php if($Membre->status === ARRAY_STATUS_MEMBER[2]): ?>
                            <span class="border border-green rounded-xl p-2 text-[10px] text-green-600 self-center">ACTIVE</span>
                        <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[3]): ?>
                            <span class="border border-red rounded-xl p-2 text-[10px] text-red-600 self-center">SUSPENDUE</span>
                        <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[5]): ?>
                            <span class="border border-gray rounded-xl p-2 text-[10px] text-gray-600 self-center">INACTIVE</span>
                        <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                            <span class="border border-amber rounded-xl p-2 text-[10px] text-amber-600 self-center">ATTENTE INTÉGRATION</span>
                        <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[4]): ?>
                            <span class="border border-red rounded-xl p-2 text-[10px] text-red-600 self-center">INTÉGRATION REJETÉE</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="glass-card rounded-[2.5rem] p-8">
                    <h3 class="font-serif text-lg text-white font-bold mb-4">Initiation</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-seedling text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->niveau_initiation ?></span>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-[2.5rem] p-8">
                    <h3 class="font-serif text-lg text-white font-bold mb-4">Infos</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-earth-africa text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->nationalite ?></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->ville ?>, <?= $Membre->adresse ?></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->phone_number ?></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->email ?></span>
                        </div>

                    </div>
                </div>

                <div class="glass-card rounded-[2.5rem] p-8">
                    <h3 class="font-serif text-lg text-white font-bold mb-4">Etudes</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-graduation-cap text-primary text-xs"></i>
                            <span class="text-xs font-bold text-gray-600"><?= $Membre->domaine_etude ?></span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Validation Steps -->
            <div class="lg:col-span-2 space-y-8">
                <?php if ($Membre->engagement_id) : ?>
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
                <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                    <!-- Card pour validation integration -->
                    <div class="glass-card color-border rounded-[2.5rem] overflow-hidden">
                        <div class="p-4 flex justify-between items-center bg-[#cfbb30]/20">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 bg-paper text-primary rounded-full flex items-center justify-center font-black text-xs">1</span>
                                <h3 class="font-serif text-white text-md md:text-sm font-bold">Validation d'intégration</h3>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-amber-500 uppercase tracking-tighter">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>En cours
                                </span>
                            </div>
                        </div>
                        <div class="p-8">
                            <p class="text-[10px] tracking-widest text-gray-400 font-black mb-4">Veuillez avoir un entretien avec ce membre pour valider ou réjéter son intégration  à la plateforme et accès aux enseignants selon son niveau.</p>
                            <div class="p-4 rounded-xl border mb-6" style="border: 1px solid rgba(255, 255, 255, 0.1);">
                                <h4 class="text-sm font-bold text-gray-400 mb-3">Informations du membre</h4>
                                <ul class="text-xs text-gray-500 space-y-1">
                                    <li>Nom complet :<span class="font-bold text-gray-400"> <?= $Membre->nom_postnom ?></span></li>
                                    <li>Date de naissance :<span class="font-bold text-gray-400"> <?= Helper::formatDate($Membre->date_naissance) ?></span></li>
                                    <li>Nationalité :<span class="font-bold text-gray-400"> <?= $Membre->nationalite ?></span></li>
                                    <li>Ville :<span class="font-bold text-gray-400"> <?= $Membre->ville ?></span></li>
                                    <li>Adresse :<span class="font-bold text-gray-400"> <?= $Membre->adresse ?></span></li>
                                    <li>Niveau d'initiation :<span class="font-bold text-gray-400"> <?= $Membre->niveau_initiation ?></span></li>
                                    <li>Téléphone :<span class="font-bold text-gray-400"> <?= $Membre->phone_number ?></span></li>
                                    <li>Email :<span class="font-bold text-gray-400"> <?= $Membre->email ?></span></li>
                                    <li>Date d'inscription :<span class="font-bold text-gray-400"> <?= Helper::formatDate2($Membre->created_at) ?></span></li>
                                    <li>Motivation :<span class="font-bold text-gray-400"> <?= $Membre->motivation ?></span></li>
                                </ul>
                                <div class="mt-6 flex justify-center items-center gap-4">
                                    <a href="https://wa.me/<?= str_replace('+', '', $Membre->phone_number) ?>" class="text-[10px] font-black p-3 rounded-xl text-green-500 border border-green-500 hover:text-green-70 transition" target="_blank">
                                        <i class="fa-brands fa-whatsapp text-[14px] mr-1"></i>
                                        <span>Appeler sur WhatsApp</span>
                                    </a>
                                    <a href="tel:<?= str_replace('+', '', $Membre->phone_number) ?>" class="text-[10px] font-black p-3 rounded-xl text-gray-400 border border-gray-400 hover:text-gray-70 transition" target="_blank">
                                        <i class="fa-solid fa-phone text-[13px] mr-1"></i>
                                        <span>Appeler sur téléphone</span>
                                    </a>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end gap-3">
                                <button 
                                    id="openModalBtn"
                                    class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-black py-3 px-8 rounded-xl shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                                    Rejeté
                                </button>
                                <!-- <form action="" method="post">
                                    <button name="cllil_membre_integration_rejeted" class="px-6 py-3 rounded-xl border border-red text-[10px] font-black tracking-widest text-red-500 hover:bg-red-90 transition">Réjéter</button>
                                </form> -->
                                <form action="" method="post">
                                    <button name="cllil_membre_integration_approuve" class="bg-primary text-paper px-8 py-3 rounded-xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">Approuver l'intégration</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

</section>

<div 
    id="modalOverlay" 
    class="hidden fixed inset-0 bg-transparent z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    
    <!-- Conteneur du Modal -->
    <div 
        id="modalContent"
        class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-md font-bold text-gray-800">Confirmation réjet du membre : <?= $Membre->nom_postnom ?></h3>
            <button id="closeIcon" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="" method="post">
            <!-- Corps du Modal -->
            <div class="p-6 pt-0">
                <div class="group">
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Pourquoi souhaitez-vous Réjéter ce membre ?</label>
                    <textarea name="motif" rows="2" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent resize-none font-serif" placeholder="Partagez vos raisons de réjet." required><?= Helper::getData($_POST, 'motif') ?></textarea>
                </div>
            </div>

            <!-- Footer / Boutons d'action -->
            <div class="flex flex-col sm:flex-row-reverse gap-3 p-6 bg-gray-50 rounded-b-xl">
                <button name="cllil_membre_integration_rejeted" class="bg-primary text-paper px-8 py-3 rounded-xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                    Réjeter le membre
                </button>
                <button 
                    id="closeModalBtn"
                    class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-[12px] text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
    
<script src="<?= ASSETS ?>js/modules/modal.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>