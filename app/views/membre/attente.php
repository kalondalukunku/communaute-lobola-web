<?php 
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <div class="container mx-auto py-[80px] px-4 md:px-0">
        
        <div class="status-container fade-in text-center p-8 md:p-6">
            
            <!-- Icône de chargement stylisée -->
            <div class="mb-2 flex justify-center">
                <div class="relative">
                    <div class="w-9 h-9 border-2 border-primary border-t-primary rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-primary text-md"></i>
                    </div>
                </div>
            </div>

            <h1 class="font-serif text-2xl text-primary mb-4">Engagement en cours de vérification</h1>
            <p class="text-gray-500 mb-8 text-sm italic">
                Votre document a bien été reçu. Nos modérateurs valident votre accès manuellement pour préserver l'intégrité de la communauté.
            </p>

            <!-- Section Paiement -->
            <div class="text-left mb-5">
                <h2 class="font-bold text-primary uppercase tracking-widest text-xs mb-4 text-center">Finalisation de l'adhésion</h2>
                
                <div class="payment-card space-y-4">
                    <div class="flex justify-between items-center border-b border-primary/10 pb-3">
                        <span class="text-sm text-gray-500">Montant de la contribution :</span>
                        <span class="text-md font-bold text-primary"><?= Utils::getMonthsNumber($membre->modalite_engagement) * $membre->montant .' '. $membre->devise   ?> </span>
                    </div>
                    
                    <div class="space-y-2">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-tighter">Numéro de transfert (Mpsea) :</span>
                        <div class="flex justify-between items-center bg-white p-2 rounded-lg border border-gray-100">
                            <span class="font-mono text-sm text-primary font-bold tracking-wider"> <?= SITE_PHONE ?></span>
                            <span class="copy-btn" onclick="copyToClipboard('<?= SITE_PHONE ?>')">
                                <i class="far fa-copy"></i> Copier
                            </span>
                        </div>
                    </div>

                    <!-- <div class="space-y-2">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-tighter">Référence à inclure :</span>
                        <div class="flex justify-between items-center bg-white p-2 rounded-lg border border-gray-100">
                            <span class="font-mono text-sm text-primary">ENG-2024-8842</span>
                            <span class="copy-btn" onclick="copyToClipboard('ENG-2024-8842')">
                                <i class="far fa-copy"></i> Copier
                            </span>
                        </div>
                    </div> -->
                </div>
            </div>

            <div class="bg-primary/10 p-4 rounded-xl mb-8">
                <p class="text-xs text-primary leading-relaxed">
                    <i class="fas fa-info-circle mr-2"></i>
                    Une fois le transfert effectué, la validation prend généralement entre <strong>1 et 4 heures</strong> durant les heures d'ouverture. Vous recevrez un email dès que votre accès sera ouvert.
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <button onclick="window.location.reload()" class="bg-primary text-white py-3 px-8 rounded-xl font-bold hover:opacity-90 transition">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser mon statut
                </button>
            </div>

        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">Besoin d'aide ? Contactez le support : <span class="font-bold"><?= ADMIN_EMAIL ?></span></p>
        </div>
    </div>

    <div class="fixed bottom-6 left-6 right-6 md:left-auto md:right-12 md:w-96 audio-player-mini p-4 rounded-2xl shadow-2xl text-white flex items-center gap-4 border border-primary/30">
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
    </div>
    
    <script src="<?= ASSETS ?>js/app.js"></script>