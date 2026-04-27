<?php 
    $title = "Admin - Détails du membre - " . $Membre->nom_postnom;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <!-- Main Content -->
    <main class="flex-grow bg-paper min-h-screen">
    <!-- Header Mobile Dédié -->
    <div class="lg:hidden p-4 bg-paper border-b border-slate-200 flex items-center justify-between sticky top-0 z-30 shadow-sm">
        <button @click="sidebarOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500">
            <i class="fas fa-bars-staggered"></i>
        </button>
        <div class="flex items-center gap-2">
            <img class="w-7 h-7 rounded-lg" src="<?= ASSETS ?>images/logo.jpg" alt="">
            <span class="font-bold text-sm text-white tracking-tight"><?= SITE_NAME ?></span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
            <?= Helper::getFirstTwoInitials(Session::get('admin')['nom']) ?>
        </div>
    </div>
    
    <!-- Barre de Navigation Supérieure Style "Glass" -->
    <header class="h-20 bg-paper backdrop-blur-md border-b border-gray-100 px-6 flex items-center justify-between sticky top-0 z-20">
        <div class="flex items-center gap-4">
            <a href="<?= RETOUR_EN_ARRIERE ?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-secondary text-gray-400 hover:text-primary hover:bg-paper hover:shadow-sm transition-all">
                <i class="fas fa-arrow-left text-primary"></i>
            </a>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Dossier Membre</span>
                <span class="font-bold text-white"><?= $Membre->nom_postnom ?></span>
            </div>
        </div>
        <div class="flex gap-3">
            <?php if($Membre->status === ARRAY_STATUS_MEMBER[2] && $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0]): ?>
                <form action="" method="post" class="hidden md:block">
                    <button name="cllil_membre_suspended" class="px-5 py-2.5 rounded-xl border border-gray-200 text-[10px] font-black tracking-widest text-gray-500 hover:bg-gray-50 transition-all uppercase">Suspendre</button>
                </form>
            <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[3] || $Membre->status === ARRAY_STATUS_MEMBER[5] && $Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && $Payment): ?>
                <form action="" method="post">
                    <button name="cllil_membre_active" class="px-5 py-2.5 rounded-xl border border-green-500 text-[10px] font-black tracking-widest text-green-600 hover:bg-green-50 transition-all uppercase">Activer</button>
                </form>
            <?php elseif($Membre->statut_engagement !== ARRAY_STATUS_ENGAGEMENT[0]): ?>
                    <button id="openModalBtn2" class="px-5 py-2.5 rounded-xl border border-red-500 text-[10px] font-black tracking-widest text-red-600 hover:bg-red-50 transition-all uppercase">Supprimer</button>
            <?php endif; ?>
        </div>
    </header>

    <div class="pt-8 px-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Colonne Gauche : Profil (4/12) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Carte Profil Principale -->
                <div class="bg-paper rounded-[2rem] shadow-sm color-border overflow-hidden">
                    <div class="h-24 bg-secondary from-slate-800 to-slate-900"></div>
                    <div class="px-6 pb-8 -mt-12 text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-32 h-32 rounded-3xl border-4 border-white overflow-hidden shadow-lg transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                                <img src="../../<?= $Membre->path_profile ?>" alt="<?= $Membre->nom_postnom ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-amber-500 border-4 border-white rounded-2xl flex items-center justify-center text-white shadow-md">
                                <i class="fas fa-clock text-xs"></i>
                            </div>
                        </div>
                        <h2 class="text-xl text-white font-bold leading-tight"><?= $Membre->nom_postnom ?></h2>
                        <p class="text-[11px] text-slate-200 font-medium mb-6 uppercase tracking-wider">Inscrit le <?= Helper::formatDate($Membre->created_at) ?></p>
                        
                        <div class="inline-flex">
                            <?php if($Membre->status === ARRAY_STATUS_MEMBER[2]): ?>
                                <span class="bg-green-50 text-green-600 px-4 py-1.5 rounded-full text-[10px] font-bold tracking-tighter border border-green-100">ACTIVE</span>
                            <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[3]): ?>
                                <span class="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-[10px] font-bold tracking-tighter border border-red-100">SUSPENDUE</span>
                            <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[5]): ?>
                                <span class="bg-gray-50 text-gray-600 px-4 py-1.5 rounded-full text-[10px] font-bold tracking-tighter border border-gray-100">INACTIVE</span>
                            <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                                <span class="bg-amber-50 text-amber-600 px-4 py-1.5 rounded-full text-[10px] font-bold tracking-tighter border border-amber-100 uppercase">Attente Intégration</span>
                            <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[4]): ?>
                                <span class="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-[10px] font-bold tracking-tighter border border-red-100 uppercase">Intégration Rejetée</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Infos Rapides -->
                    <div class="border-t border-slate-50 grid grid-cols-2 divide-x divide-slate-50 text-center">
                        <div class="p-4">
                            <p class="text-[10px] text-slate-200 uppercase font-bold mb-1">Niveau</p>
                            <span class="text-xs font-bold text-slate-200"><?= $Membre->niveau_initiation ?></span>
                        </div>
                        <div class="p-4">
                            <p class="text-[10px] text-slate-200 uppercase font-bold mb-1">Genre</p>
                            <span class="text-xs font-bold text-slate-200"><?= $Membre->genre ?></span>
                        </div>
                    </div>
                </div>

                <!-- Carte Contact & Bio -->
                <div class="bg-paper rounded-[2rem] shadow-sm color-border p-8">
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-primary rounded-full"></span> Coordonnées
                    </h3>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-primary text-xs shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <p class="text-[10px] text-slate-200 uppercase font-bold">Localisation</p>
                                <p class="text-xs font-bold text-slate-500"><?= $Membre->ville ?>, <?= $Membre->adresse ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-primary text-xs shrink-0"><i class="fas fa-phone"></i></div>
                            <div>
                                <p class="text-[10px] text-slate-200 uppercase font-bold">Téléphone</p>
                                <p class="text-xs font-bold text-slate-500"><?= $Membre->phone_number ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-primary text-xs shrink-0"><i class="fas fa-envelope"></i></div>
                            <div>
                                <p class="text-[10px] text-slate-200 uppercase font-bold">Email</p>
                                <p class="text-xs font-bold text-slate-500"><?= $Membre->email ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-primary text-xs shrink-0"><i class="fa-solid fa-graduation-cap"></i></div>
                            <div>
                                <p class="text-[10px] text-slate-200 uppercase font-bold">Domaine d'études</p>
                                <p class="text-xs font-bold text-slate-500"><?= $Membre->domaine_etude ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne Droite : Processus (8/12) -->
            <div class="lg:col-span-8 space-y-8">
                <?php if ($Membre->engagement_id) : ?>
                    <!-- Section Engagement Spirituel -->
                    <div class="bg-paper rounded-[2.5rem] shadow-sm color-border overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-paper">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 bg-paper shadow-sm color-border text-primary rounded-xl flex items-center justify-center font-black text-sm">01</span>
                                <h3 class="text-white text-base font-bold">Engagement Spirituel</h3>
                            </div>
                            <div>
                                <?php if($Membre->doc_approuved == 0): ?>
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span> En cours
                                </span>
                                <?php elseif($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[2]): ?>
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-times-circle"></i> Rejeté
                                </span>
                                <?php elseif($Membre->doc_approuved == 1): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Approuvé
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="flex flex-col items-center justify-center py-6 bg-secondary rounded-3xl border border-dashed border-[#cfbb3040] mb-8">
                                <i class="fas fa-file-pdf text-4xl text-red-400 mb-4"></i>
                                <p class="text-xs font-bold text-slate-500 mb-4 uppercase tracking-widest">Document d'engagement</p>
                                <form action="" method="post">
                                    <button name="cllil_membre_download_engagement<?= $membreId ?>" class="bg-primary text-white px-6 py-2.5 rounded-xl text-[10px] font-black tracking-widest shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all uppercase">Télécharger le document</button>
                                </form>
                            </div>
                            
                            <?php if($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1] && $Membre->doc_approuved == 0): ?>
                                <div class="space-y-6">
                                    <div class="p-2 bg-secondary rounded-[2rem] overflow-hidden shadow-2xl">
                                        <div class="relative w-full rounded-[1.5rem] overflow-hidden" style="height: 500px;">
                                            <iframe src="<?= BASE_URL ?>/assets/uploads/document/<?= $name ?>#toolbar=0&navpanes=0&scrollbar=0" class="absolute inset-0 w-full h-full" style="border: none;" title="Aperçu PDF"></iframe>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <form action="" method="post">
                                            <button name="cllil_membre_eng_rejeted" class="px-8 py-3.5 rounded-2xl border-2 border-red-100 text-[10px] font-black tracking-widest text-red-500 hover:bg-red-50 transition-all uppercase">Réjéter</button>
                                        </form>
                                        <form action="" method="post">
                                            <button name="cllil_membre_approuve" class="bg-primary text-white px-10 py-3.5 rounded-2xl text-[10px] font-black tracking-widest shadow-xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all uppercase">Approuver l'engagement</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($Payment) : ?>
                        <!-- Section Paiement -->
                        <div class="bg-paper rounded-[2.5rem] shadow-sm color-border overflow-hidden">
                            <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-paper">
                                <div class="flex items-center gap-4">
                                    <span class="w-10 h-10 bg-paper shadow-sm color-border text-primary rounded-xl flex items-center justify-center font-black text-sm">02</span>
                                    <h3 class="text-white text-base font-bold">Vérification du Paiement</h3>
                                </div>
                                <?php if($Payment->payment_status === ARRAY_PAYMENT_STATUS[1]): ?>
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2"><i class="fas fa-check-circle"></i> Payé</span>
                                <?php elseif($Payment->payment_status === ARRAY_PAYMENT_STATUS[2]): ?>
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2"><i class="fas fa-times-circle"></i> Refusé</span>
                                <?php else: ?>
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i> En attente</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($Payment->payment_status === ARRAY_PAYMENT_STATUS[0]): ?>
                                <div class="p-8">
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="p-5 rounded-2xl bg-secondary">
                                            <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold mb-1">Type d'engagement</p>
                                            <p class="text-md text-white font-black"><?= $Membre->modalite_engagement ?></p>
                                        </div>
                                        <div class="p-5 rounded-2xl bg-secondary border border-primary/10">
                                            <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold mb-1">Montant à valider</p>
                                            <p class="text-md font-black text-white"><?= Utils::getMonthsByModalite($Membre->modalite_engagement, $Membre->montant) .' '. $Membre->devise ?></p>
                                        </div>
                                    </div>

                                    <div class="relative group">
                                        <div class="absolute -inset-1 bg-secondary from-primary to-amber-500 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                                        <div class="relative bg-paper rounded-[1.8rem] color-border overflow-hidden shadow-sm">
                                            <div class="p-4 bg-secondary flex items-center justify-between border-b border-slate-100">
                                                <div class="flex items-center gap-3">
                                                    <i class="fas fa-image text-slate-200"></i>
                                                    <span class="text-[10px] font-black uppercase text-white">Preuve_de_paiement.jpg</span>
                                                </div>
                                            </div>
                                            <img src="../../<?= $Membre->preuve_paiement ?>" alt="Preuve" class="w-full h-auto grayscale-[20%] hover:grayscale-0 transition-all duration-500">
                                        </div>
                                    </div>

                                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                                        <div class="flex items-center gap-3 p-3 px-4 rounded-xl bg-amber-50 border border-amber-100">
                                            <i class="fas fa-info-circle text-amber-500 text-sm"></i>
                                            <p class="text-[10px] text-amber-700 font-medium italic">Assurez-vous que le nom sur le reçu correspond bien au membre.</p>
                                        </div>
                                        <form action="" method="post">
                                            <button name="cllil_membre_validate" class="bg-primary text-white px-10 py-4 rounded-2xl text-[10px] font-black tracking-widest shadow-xl shadow-primary/30 hover:scale-[1.05] active:scale-95 transition-all uppercase">Confirmer le paiement</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[1]): ?>
                    <!-- Section Validation Intégration -->
                    <div class="bg-paper rounded-[2.5rem] shadow-sm color-border overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-paper">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 bg-paper shadow-sm color-border text-primary rounded-xl flex items-center justify-center font-black text-sm">01</span>
                                <h3 class="text-white text-base font-bold uppercase tracking-tight">Entretien & Intégration</h3>
                            </div>
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i> Action Requise</span>
                        </div>
                        <div class="p-8">
                            <div class="p-8 rounded-[2rem] bg-secondary text-white shadow-xl mb-8 relative overflow-hidden">
                                <div class="relative z-10">
                                    <h4 class="text-lg font-bold mb-2">Prêt pour l'entretien ?</h4>
                                    <p class="text-slate-200 text-xs leading-relaxed mb-6">Contactez le membre via les canaux ci-dessous pour valider son éligibilité et son accès à la plateforme.</p>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="https://wa.me/<?= str_replace('+', '', $Membre->phone_number) ?>" class="flex items-center gap-3 bg-green-500 hover:bg-green-600 px-6 py-3 rounded-xl transition-all group" target="_blank">
                                            <i class="fa-brands fa-whatsapp text-lg"></i>
                                            <span class="text-[10px] font-black uppercase tracking-widest">WhatsApp</span>
                                        </a>
                                        <a href="tel:<?= str_replace('+', '', $Membre->phone_number) ?>" class="flex items-center gap-3 bg-paper/10 hover:bg-paper/20 px-6 py-3 rounded-xl transition-all border border-white/10" target="_blank">
                                            <i class="fa-solid fa-phone text-sm text-primary"></i>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Appel Direct</span>
                                        </a>
                                    </div>
                                </div>
                                <i class="fa-solid fa-comments absolute -bottom-4 -right-4 text-9xl text-white/5 rotate-12"></i>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 color-border rounded-3xl mb-8 bg-paper">
                                <div class="space-y-3">
                                    <div><p class="text-[10px] text-slate-200 uppercase font-bold">Motivation</p><p class="text-xs text-slate-500 font-medium italic">"<?= $Membre->motivation ?>"</p></div>
                                    <div><p class="text-[10px] text-slate-200 uppercase font-bold">Date Naissance</p><p class="text-xs text-slate-500 font-bold"><?= Helper::formatDate($Membre->date_naissance) ?></p></div>
                                </div>
                                <div class="space-y-3">
                                    <div><p class="text-[10px] text-slate-200 uppercase font-bold">Inscrit le</p><p class="text-xs text-slate-500 font-bold"><?= Helper::formatDate2($Membre->created_at) ?></p></div>
                                    <div><p class="text-[10px] text-slate-200 uppercase font-bold">Nationalité</p><p class="text-xs text-slate-500 font-bold"><?= $Membre->nationalite ?></p></div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3">
                                <button id="openModalBtn" class="px-8 py-4 rounded-2xl border-2 border-red-50 text-[11px] font-black tracking-widest text-red-500 hover:bg-red-50 hover:border-red-100 transition-all uppercase">Rejeter le profil</button>
                                <form action="" method="post">
                                    <button name="cllil_membre_integration_approuve" class="bg-primary text-white px-10 py-4 rounded-2xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all uppercase">Approuver l'intégration</button>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[4]): ?>
                    <!-- Section Intégration Rejetée -->
                    <div class="bg-paper border border-red-200 rounded-[2.5rem] p-8 text-center">
                        <div class="w-20 h-20 bg-red-100 text-red-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6 shadow-sm">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <h3 class="text-red-900 text-xl font-bold mb-2">Intégration Rejetée</h3>
                        <p class="text-red-600/70 text-sm max-w-md mx-auto mb-8 font-medium">Le profil n'a pas été retenu suite à l'analyse de son dossier ou de son entretien.</p>
                        
                        <div class="bg-paper p-6 rounded-2xl border border-red-200 text-left max-w-lg mx-auto">
                            <p class="text-[10px] text-red-400 uppercase font-black mb-3 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i> Motif enregistré
                            </p>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed italic">"<?= $MembreMotif->raison ?>"</p>
                        </div>
                    </div>

                <?php elseif($Membre->status === ARRAY_STATUS_MEMBER[5]): ?>
                    <!-- Section Intégration Validée -->
                    <div class="bg-paper rounded-[2.5rem] shadow-sm color-border p-8">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <div class="w-15 h-15 bg-green-50 text-green-500 rounded-[2.5rem] flex items-center justify-center text-5xl shrink-0 shadow-inner">
                                <i class="fas fa-check-double text-[28px]"></i>
                            </div>
                            <div class="text-center md:text-left flex-grow">
                                <h3 class="text-white text-md font-black mb-2 uppercase tracking-tight">Compte Approuvé</h3>
                                <p class="text-slate-500 text-sm mb-6 leading-relaxed">Félicitations, l'intégration est finalisée. Le membre peut désormais définir son mot de passe pour accéder à des enseignements et ressources.</p>
                                
                                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                    <form action="" method="post">
                                        <button name="cllil_membre_renvoyer_email" class="bg-primary text-white px-8 py-3.5 rounded-2xl text-[10px] font-black tracking-widest shadow-sm shadow-primary/20 hover:-translate-y-0.5 transition-all uppercase">Renvoyer l'accès email</button>
                                    </form>
                                    <a href="https://wa.me/<?= str_replace('+', '', $Membre->phone_number) ?>" target="_blank" class="bg-green-500 text-white px-8 py-3.5 rounded-2xl text-[10px] font-black tracking-widest hover:bg-green-600 transition-all uppercase shadow-xs shadow-green-200">Aviser sur WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
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
        class="bg-paper rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        
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
                    class="w-full sm:w-auto px-6 py-2.5 bg-paper border border-gray-300 text-[12px] text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<div 
    id="modalOverlay2" 
    class="hidden fixed inset-0 bg-transparent z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    
    <!-- Conteneur du Modal -->
    <div 
        id="modalContent"
        class="bg-paper rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-md font-bold text-gray-800">Vous voulez vraiment supprimer ce membre ? : <?= $Membre->nom_postnom ?></h3>
            <button id="closeIcon2" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="" method="post">

            <!-- Footer / Boutons d'action -->
            <div class="flex flex-col sm:flex-row-reverse gap-3 p-6 bg-gray-50 rounded-b-xl">
                <button name="cllil_membre_integration_rejeted" class="bg-red-500 text-paper px-8 py-3 rounded-xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                    Supprimer le membre
                </button>
                <button 
                    id="closeModalBtn2"
                    class="w-full sm:w-auto px-6 py-2.5 bg-paper border border-gray-300 text-[12px] text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script src="<?= ASSETS ?>js/modules/modal.js?v=<?= APP_VERSION ?>"></script>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>