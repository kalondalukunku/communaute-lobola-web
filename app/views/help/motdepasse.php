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

        <h1 class="mt-6 font-serif text-3xl sm:text-4xl text-secondary">Mot de passe oublié</h1>
        <p class="mt-4 max-w-3xl text-sm leading-7 text-gray-600">
            Récupérez facilement l’accès à votre compte si vous avez oublié votre mot de passe.
        </p>

        <div class="mt-8 space-y-6">
            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 1 : Cliquer sur “Mot de passe oublié”</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Sur la page de connexion, utilisez le lien de récupération.
                </p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 2 : Vérifier votre boîte mail</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Consultez l’e-mail de réinitialisation et suivez le lien fourni.
                </p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-semibold text-secondary">Étape 3 : Créer un nouveau mot de passe</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">
                    Choisissez un mot de passe unique et confirmez le changement.
                </p>
            </article>
        </div>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
