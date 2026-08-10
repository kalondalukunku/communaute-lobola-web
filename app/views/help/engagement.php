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

        <h1 class="mt-6 font-serif text-3xl sm:text-4xl text-secondary">Comment s’engager correctement</h1>
        <p class="mt-4 max-w-3xl text-sm leading-7 text-gray-600">
            Ce guide vous explique les étapes nécessaires pour valider votre engagement.
        </p>

        <div class="mt-8 space-y-6">
            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 1 : Lire les conditions</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Prenez connaissance des obligations et des avantages liés à votre engagement.
                </p>
                <div class="mt-5 grid gap-4 md:grid-cols-1">
                    <img src="<?= ASSETS ?>images/help/engagement-1.svg" alt="Illustration engagement" class="h-40 w-full rounded-2xl border border-gray-200 object-cover shadow-sm">
                </div>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 2 : Soumettre votre demande</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Remplissez le formulaire d’engagement à partir de votre espace membre.
                </p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 3 : Suivre l’avancement</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Consultez vos notifications et validez les informations complémentaires si nécessaire.
                </p>
            </article>
        </div>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
