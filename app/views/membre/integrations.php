<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'templates/alertView.php'; 
?>

<div class="container mx-auto py-12 px-4 md:px-0 flex justify-center">

<!-- Conteneur Principal -->
<div class="document-container fade-in p-6 md:p-12 rounded-3xl bg-white shadow-2xl relative overflow-hidden border border-gray-100" style="width: 95%; max-width: 950px;">
    
    <!-- En-tête Institutionnel -->
    <div class="text-center mb-12">
        <div class="inline-block p-3 rounded-full bg-amber-500/10 mb-4">
            <i class="fas fa-hourglass-half text-amber-600 text-3xl animate-pulse"></i>
        </div>
        <h1 class="font-serif text-3xl md:text-5xl text-center text-primary mb-4 tracking-tight">Portail d'Intégration</h1>
        <p class="text-gray-500 font-sans uppercase tracking-[0.3em] text-[10px] md:text-xs">Statut des Candidatures à l Communauté Lobola Lo-Ilondo</p>
        <div class="w-32 h-px bg-gradient-to-r from-transparent via-primary to-transparent mx-auto mt-6"></div>
    </div>

    <!-- Section Message de Clôture -->
    <div class="bg-amber-50/50 p-8 md:p-12 rounded-3xl mb-8 border border-amber-100 text-center">
        <h2 class="font-serif text-2xl text-primary mb-6">Avis de suspension temporaire</h2>
        
        <div class="space-y-6 max-w-2xl mx-auto">
            <p class="text-gray-600 leading-relaxed text-base md:text-lg font-serif italic">
                "Chaque chose a son temps sous le soleil, et il y a un temps pour chaque intention sous le ciel."
            </p>
            
            <div class="py-4">
                <p class="text-gray-700 text-sm md:text-base leading-relaxed">
                    Le Conseil des Initiés informe les chercheurs de vérité que la phase actuelle d'intégration a atteint son terme. Afin de garantir un accompagnement de qualité aux nouveaux membres, nous avons suspendu la réception de nouvelles candidatures.
                </p>
            </div>

            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white rounded-full border border-amber-200 shadow-sm">
                <span class="w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-700">Réouverture à une date ultérieure</span>
            </div>
        </div>
    </div>

    <!-- Section d'Orientation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
            <h3 class="text-xs uppercase tracking-widest font-bold text-primary mb-3">Que faire en attendant ?</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                Nous vous invitons à poursuivre votre écoute des enseignements disponibles sur notre plateforme et à méditer sur les principes de la sagesse ancestrale. Le savoir est une graine qui germe dans le silence.
            </p>
        </div>
        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
            <h3 class="text-xs uppercase tracking-widest font-bold text-primary mb-3">Rester informé</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                Les annonces concernant la prochaine session d'intégration seront publiées sur nos canaux officiels. Assurez-vous d'être abonné à notre newsletter pour ne manquer aucune mise à jour.
            </p>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="flex flex-col items-center pt-8 border-t border-gray-50">
               
        <div class="mt-12 flex items-center gap-3 text-gray-300">
            <div class="w-10 h-px bg-gray-200"></div>
            <span class="text-[9px] uppercase tracking-[0.4em]">Patience - Étude - Persévérance</span>
            <div class="w-10 h-px bg-gray-200"></div>
        </div>
    </div>

</div>


</div>

<script src="<?= ASSETS ?>js/modules/main2.js"></script>
    