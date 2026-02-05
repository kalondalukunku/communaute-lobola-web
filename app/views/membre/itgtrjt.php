<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <div class="container mx-auto py-[80px] px-4 md:px-0">
    
        <div class="status-container fade-in text-center p-8 md:p-10 border-t-4 border-gray-400">
            
            <!-- Icône de refus formel -->
            <div class="mb-6 flex justify-center">
                <div class="relative">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-gray-400 text-4xl"></i>
                    </div>
                </div>
            </div>

            <h1 class="font-serif text-2xl text-primary mb-6">Décision relative à votre intégration</h1>
            
            <div class="space-y-6 mb-10">
                <p class="text-gray-600 leading-relaxed text-sm">
                    Nous vous remercions de l'intérêt porté à notre communauté. Après examen attentif de votre demande et des documents fournis, nos modérateurs ont déterminé que nous ne sommes pas en mesure de valider votre intégration pour le moment.
                </p>
                
                <p class="text-gray-500 text-sm italic border-l-2 border-primary/20 pl-4 py-2 bg-gray-50/50">
                    Cette décision s'inscrit dans notre politique de préservation de l'intégrité et de la cohésion des membres actuels.
                </p>
            </div>

            <!-- Section Contestation -->
            <div class="bg-primary/5 p-6 rounded-xl mb-8 border border-primary/10">
                <h2 class="font-bold text-primary uppercase tracking-widest text-[10px] mb-3">Recours & Contestation</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-5">
                    Si vous estimez que cette décision résulte d'une erreur d'appréciation ou si vous souhaitez apporter des éléments complémentaires, vous avez la possibilité de déposer un recours auprès de notre service administratif.
                </p>
                
                <a href="mailto:<?= ADMIN_EMAIL ?>" class="inline-flex items-center justify-center bg-white border border-primary/20 text-primary py-3 px-6 rounded-lg text-sm font-bold hover:bg-primary hover:text-white transition-all w-full md:w-auto">
                    <i class="fas fa-envelope-open-text mr-2"></i> Contacter le support
                </a>
            </div>

            <div class="flex flex-col gap-4">
                <!-- <a href="/" class="text-gray-400 py-2 px-8 rounded-xl text-xs font-medium hover:text-primary transition underline decoration-dotted">
                    Retour à l'accueil
                </a> -->
            </div>

        </div>
    </div>
    
    <script src="<?= ASSETS ?>js/app.js"></script>