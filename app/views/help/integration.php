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

        <h1 class="mt-6 font-serif text-3xl sm:text-4xl text-secondary">Comment faire son intégration</h1>
        <p class="mt-4 max-w-3xl text-sm leading-7 text-gray-600">
            Ce tutoriel vous accompagne pas à pas pour finaliser votre intégration dans la plateforme.
        </p>

        <div class="mt-8 space-y-6">
            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 1 : Préparer vos informations</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Rassemblez votre nom, votre adresse e-mail, votre numéro de téléphone et les documents demandés.
                </p>
                <div class="mt-5 grid gap-4 md:grid-cols-1">
                    <img src="<?= ASSETS ?>images/help/integration-1.svg" alt="Illustration intégration" class="h-40 w-full rounded-2xl border border-gray-200 object-cover shadow-sm">
                </div>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 2 : Remplir le formulaire</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Saisissez vos données avec précision et vérifiez chaque champ avant d’envoyer votre demande.
                </p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 3 : Valider et attendre la confirmation</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Une fois le formulaire envoyé, vous recevrez une confirmation et les prochaines instructions par e-mail ou sur votre espace.
                </p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <img src="<?= ASSETS ?>images/help/integration-2.svg" alt="Illustration validation" class="h-40 w-full rounded-2xl border border-gray-200 object-cover shadow-sm">
                    <img src="<?= ASSETS ?>images/help/integration-3.svg" alt="Illustration confirmation" class="h-40 w-full rounded-2xl border border-gray-200 object-cover shadow-sm">
                </div>
            </article>
        </div>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
