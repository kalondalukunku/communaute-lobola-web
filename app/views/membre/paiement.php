<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

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

                <h1 class="font-serif text-2xl text-primary font-bold mb-3">Effectuer le paiement</h1>
                <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                    Pour finaliser votre demande d'engagement à la communauté Lobola, veuillez effectuer le paiement de votre engagement. Une fois le paiement reçu, nos modérateurs procéderont à la vérification finale de votre engagement pour activer votre accès aux enseignements de <strong>BOLOKELE</strong>.
                </p>

                <!-- Résumé de la transaction -->
                <div class="text-left mb-8">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                        <h2 class="text-[10px] text-gray-400 font-extrabold uppercase tracking-[0.2em] mb-4 text-center">Numéro de transaction mobile money (RDC)</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                <span class="text-xs text-gray-500">Mpesa :</span>
                                <span class="text-xs md:text-sm font-bold text-primary">
                                    +243 819 889 889 (BASELE)
                                </span>
                            </div>
                            <!-- <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                <span class="text-xs text-gray-500">Orange Money :</span>
                                <span class="text-sm font-bold text-primary">
                                    +243 842 345 678
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                <span class="text-xs text-gray-500">Airtel Money :</span>
                                <span class="text-sm font-bold text-primary">
                                    +243 992 345 678
                                </span>
                            </div> -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                <span class="text-xs text-gray-500">Western Union :</span>
                                <span class="text-xs md:text-sm font-bold text-primary">
                                    +33 6 44 16 74 99 <br>
                                    MABE DEFFO <br>
                                    gdossou@hotmail.fr <br>
                                    France
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
                                <span class="text-xs text-gray-500">Montant versé</span>
                                <span class="text-sm font-bold text-primary">
                                    <?= Utils::getMonthsNumber($membre->modalite_engagement) * $membre->montant .' '. $membre->devise ?>
                                </span>
                            </div>

                            <!-- <div class="flex justify-between items-center py-2">
                                <span class="text-xs text-gray-500">ID Transaction</span>
                                <span class="font-mono text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                    TXN-<?= strtoupper(substr(md5(time()), 0, 8)) ?>
                                </span>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- Note d'information -->
                <div class="bg-primary/5 p-4 rounded-2xl mb-8 border border-primary/10">
                    <p class="text-[11px] text-primary leading-relaxed flex items-start text-left">
                        <i class="fas fa-info-circle mt-0.5 mr-3 opacity-70"></i>
                        <span>
                            Une fois la transaction effectuée sur l'un des canaux de paiement, la validation prend généralement <strong>3 à 12 heures</strong>. Vous recevrez une notification par email dès que votre espace membre sera déverrouillé.
                        </span>
                    </p>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col gap-3">
                    <button onclick="window.location.reload()" class="w-full bg-primary text-white py-4 px-8 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                        <i class="fas fa-sync-alt mr-2"></i> Actualiser mon statut
                    </button>
                    <a href="../profile/<?= $membre->member_id ?>" class="text-[11px] text-gray-600 font-bold hover:text-primary transition-colors uppercase tracking-widest mt-3">
                        Retour à mon profil
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
    
    <script src="<?= ASSETS ?>js/app.js"></script>