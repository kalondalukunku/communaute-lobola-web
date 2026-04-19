    <?php 
        include APP_PATH . 'views/layouts/header.php';
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <!-- banniere album disponible sur spotify -->
    <section class="px-12 mt-8">
        <iframe data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/track/2OdeONjcYsbqEVdfnD6Y6p?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
    </section>

    <!-- <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Musique</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="<?= ASSETS ?>images/logo.jpg" alt="Musique 1" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">Titre de la Musique 1</h3>
                        <p class="text-gray-600 mb-4">Description de la musique 1.</p>
                        <a href="#" class="text-primary font-semibold hover:underline">Écouter</a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <?php include APP_PATH . 'views/layouts/footer.php'; ?>