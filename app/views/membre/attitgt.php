<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <div class="container mx-auto py-[80px] px-4 md:px-0 max-w-2xl">
        
        <div class="document-container fade-in p-6 md:p-12 rounded-3xl bg-white shadow-2xl relative overflow-hidden border border-gray-100" style="width: 95%; max-width: 950px;">
            
            <!-- Icône de Sablier / Attente -->
            <div class="mb-8 flex justify-center">
                <div class="relative">
                    <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-amber-500 text-4xl pulse-soft"></i>
                    </div>
                    <!-- Petit badge de notification -->
                    <span class="absolute top-0 right-0 block h-4 w-4 rounded-full ring-2 ring-white bg-amber-400"></span>
                </div>
            </div>

            <h1 class="font-serif text-3xl md:text-5xl text-center text-primary mb-4 tracking-tight">Demande en cours d'examen</h1>
            
            <div class="space-y-6 mb-10">
                <p class="text-gray-600 text-center leading-relaxed">
                    Votre demande d'adhésion a bien été enregistrée et envoyée chez nos administrateurs. Pour garantir l'excellence et la bienveillance au sein de notre communauté, chaque profil est étudié manuellement par nos administrateurs.
                </p>
                
                <div class="flex items-center justify-center space-x-2 text-amber-600 font-medium bg-amber-50/50 py-3 rounded-lg border border-amber-100">
                    <i class="fas fa-clock text-sm"></i>
                    <span class="text-sm">Délai moyen de réponse : 24h à 72h</span>
                </div>
            </div>

            <!-- Section d'information -->
            <div class="bg-gray-50 p-6 rounded-xl mb-8 border border-gray-100 text-left">
                <h2 class="font-bold text-gray-400 uppercase tracking-[0.2em] text-[10px] mb-4 flex items-center">
                    <span class="w-8 h-px bg-gray-300 mr-2"></span> Prochaines étapes
                </h2>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-[10px] mt-0.5 mr-3">1</div>
                        <p class="text-xs text-gray-500">Vous réceverez un appel téléphonique de nos administrateurs pour un entretien pour valider votre intégration.</p>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-[10px] mt-0.5 mr-3">2</div>
                        <p class="text-xs text-gray-500">Après validation, vous recevrez un email qui vous indiquera comment mettre un mot de passe et vous connecter à la plateforme.</p>
                    </li>
                    <!-- <li class="flex items-start">
                        <div class="flex-shrink-0 w-5 h-5 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-[10px] mt-0.5 mr-3">3</div>
                        <p class="text-xs text-gray-500">Notification par email dès que votre accès est activé.</p>
                    </li> -->
                </ul>
            </div>
            
            <div class="flex flex-col gap-4">
                <button onclick="window.location.reload()" class="bg-primary text-white py-3 px-8 rounded-xl font-bold hover:opacity-90 transition">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser mon statut
                </button>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">Besoin d'aide ? Contactez le support : <span class="font-bold"><?= ADMIN_EMAIL ?></span></p>
            </div>

        </div>
    </div>
    
    <script src="<?= ASSETS ?>js/app.js"></script>