<?php
include APP_PATH . 'views/layouts/header.php';
?>
</head>

<?php include APP_PATH . 'views/layouts/navbar.php'; ?>

<main class="flex-grow container mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="rounded-3xl border border-primary/10 bg-white/80 p-6 sm:p-8 shadow-sm backdrop-blur-sm">
        <a href="/help" class="inline-flex items-center text-sm font-semibold text-primary">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Retour à l’aide
        </a>

        <h1 class="mt-6 font-serif text-3xl sm:text-4xl text-secondary">Comment faire un réabonnement</h1>
        <p class="mt-4 max-w-3xl text-sm leading-7 text-gray-600">
            Ce guide vous aide à renouveler votre abonnement sans difficulté.
        </p>

        <div class="mt-8 space-y-6">
            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 1 : Se connecter à son espace</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Connectez-vous avec votre compte pour accéder à la section abonnement.
                </p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 2 : Choisir la formule</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Sélectionnez la formule souhaitée puis vérifiez les détails du renouvellement.
                </p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 3 : Finaliser le paiement</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Validez votre paiement pour obtenir immédiatement l’accès à nouveau.
                </p>
            </article>
        </div>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
