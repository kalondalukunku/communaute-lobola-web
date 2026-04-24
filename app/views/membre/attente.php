<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <?php if($paiement && $paiement->payment_status === ARRAY_PAYMENT_STATUS[1]): ?>
        <div class="container mx-auto py-[60px] px-4 md:px-0">
            <div class="max-w-md mx-auto">
                <div class="status-container fade-in bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 border border-gray-100 text-center p-10 md:p-8">
                    
                    <!-- Icône de validation en attente -->
                    <div class="mb-6 flex justify-center">
                        <div class="relative">
                            <!-- Cercle rotatif discret -->
                            <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-primary text-md"></i>
                            </div>
                        </div>
                    </div>

                    <h1 class="font-serif text-2xl text-primary font-bold mb-3">Paiement reçu !</h1>
                    <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                        Votre contribution a bien été enregistrée. Nos modérateurs procèdent actuellement à la <strong>vérification finale</strong> de votre engagement pour activer votre accès.
                    </p>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col gap-3">
                        <a href="/bolokele" class="w-full bg-primary text-white py-4 px-8 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                            <i class="fas fa-graduation-cap mr-2"></i> Accéder aux enseignements BOLOKELE
                        </a>
                    </div>
                </div>

                <div class="mt-10 text-center">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.1em]">
                        Besoin d'aide ? <a href="mailto:<?= ADMIN_EMAIL ?>" class="text-primary font-bold underline decoration-primary/30">Contactez le support</a>
                    </p>
                </div>
            </div>
        </div>
    <?php elseif($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[0] && !$paiement): ?>
        <div class="container mx-auto py-[60px] px-4 md:px-0">
            <div class="max-w-md mx-auto">
                <div class="status-container fade-in bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 border border-gray-100 text-center p-10 md:p-8">
                    
                    <!-- Icône de validation en attente -->
                    <div class="mb-6 flex justify-center">
                        <div class="relative">
                            <!-- Cercle rotatif discret -->
                            <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-primary text-md"></i>
                            </div>
                        </div>
                    </div>

                    <h1 class="font-serif text-2xl text-primary font-bold mb-3">Paiement non effectué</h1>
                    <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                        Nous n'avons pas encore reçu votre contribution. Veuillez effectuer le paiement via l'un des canaux suivants pour que nous puissions activer votre accès.
                    </p>
                    
                    <!-- Note d'information -->
                    <div class="bg-primary/5 p-4 rounded-2xl mb-8 border border-primary/10">
                        <p class="text-[11px] text-primary leading-relaxed flex items-start text-left">
                            <i class="fas fa-info-circle mt-0.5 mr-3 opacity-70"></i>
                            <span>
                                Si vous avez déjà effectué le paiement, veuillez patienter quelques minutes et actualiser cette page. Si le problème persiste, n'hésitez pas à contacter notre support.
                            </span>
                        </p>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex flex-col gap-3">
                        <a href="../paiement/<?= $Membre->member_id ?>" class="w-full bg-primary text-white py-4 px-8 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                            <i class="fas fa-credit-card mr-2"></i> Effectuer mon paiement
                        </a>
                        <a href="../profile/<?= $Membre->member_id ?>" class="text-[11px] text-gray-600 font-bold hover:text-primary transition-colors uppercase tracking-widest mt-3">
                            Retour à mon profil
                        </a>  
                    </div>
                </div>
            </div>
        </div>
    <?php elseif($Membre->statut_engagement === ARRAY_STATUS_ENGAGEMENT[1]): ?>
        <div class="container mx-auto py-[60px] px-4 md:px-0">
            <div class="max-w-md mx-auto">
                <div class="status-container fade-in bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 border border-gray-100 text-center p-10 md:p-8">
                    
                    <!-- Icône de validation en attente -->
                    <div class="mb-6 flex justify-center">
                        <div class="relative">
                            <!-- Cercle rotatif discret -->
                            <div class="w-16 h-16 border-4 border-primary/10 border-t-primary rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-primary text-md"></i>
                            </div>
                        </div>
                    </div>

                    <h1 class="font-serif text-2xl text-primary font-bold mb-3">Validation en cours</h1>
                    <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                        Votre engagement est actuellement en cours de validation par nos modérateurs. Nous vérifions les informations fournies et la conformité de votre paiement pour activer votre accès aux enseignements avancés.
                    </p>
                    <!-- Note d'information -->
                    <div class="bg-primary/5 p-4 rounded-2xl mb-8 border border-primary/10">
                        <p class="text-[11px] text-primary leading-relaxed flex items-start text-left">
                            <i class="fas fa-info-circle mt-0.5 mr-3 opacity-70"></i>
                            <span>
                                La validation prend généralement jusqu'à <strong>48 heures</strong>. Vous recevrez une notification par email dès que votre espace membre sera déverrouillé.
                            </span>
                        </p>
                    </div>
                    <!-- Résumé de la transaction -->
                    <div class="text-left mb-8">
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                            <h2 class="text-[10px] text-gray-400 font-extrabold uppercase tracking-[0.2em] mb-4 text-center">Détails de la validation</h2>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                <span class="text-xs text-gray-500">Statut actuel</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider">
                                    <i class="fas fa-clock mr-1"></i> En attente
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- boutons d'action -->
                    <div class="flex flex-col gap-3">
                        <button onclick="window.location.reload()" class="w-full bg-primary text-white py-4 px-8 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                            <i class="fas fa-sync-alt mr-2"></i> Actualiser mon statut
                        </button>
                    </div>
                </div>
                
                <div class="mt-10 text-center">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.1em]">
                        Besoin d'aide ? <a href="mailto:<?= ADMIN_EMAIL ?>" class="text-primary font-bold underline decoration-primary/30">Contactez le support</a>
                    </p>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- <div class="fixed bottom-6 left-6 right-6 md:left-auto md:right-12 md:w-96 audio-player-mini p-4 rounded-2xl shadow-2xl text-white flex items-center gap-4 border border-primary/30">
        <div class="w-12 h-12 bg-primary rounded-lg flex-shrink-0 flex items-center justify-center text-[#000f0e]">
            <i class="fas fa-music"></i>
        </div>
        <div class="flex-grow min-w-0">
            <h4 class="text-xs font-bold truncate">L'Essence de la Paix Intérieure</h4>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[10px] text-white/50">12:45 / 45:00</span>
                <div class="flex-grow h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-primary" style="width: 30%"></div>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="hover:text-primary"><i class="fas fa-backward-step"></i></button>
            <button class="w-8 h-8 bg-white text-[#000f0e] rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fas fa-pause"></i></button>
            <button class="hover:text-primary"><i class="fas fa-forward-step"></i></button>
        </div>
    </div> -->
    
    <script src="<?= ASSETS ?>js/app.js"></script>