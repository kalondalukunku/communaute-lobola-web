<?php 
    $title = "Admin - Paramètres Système | " . SITE_NAME;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="flex-1">
        <!-- Top Header -->
        <header class="h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 flex justify-between items-center sticky top-0 z-40">
            <!-- Bouton Hamburger -->
            <button id="openSidebar" class="lg:hidden w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-primary hover:bg-gray-100 transition shadow-sm">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="pl-2">
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Paramètres du Système</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Gérez les paramètres du système.</p>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="flex gap-2">
                    <button class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition relative">
                        <i class="far fa-bell"></i>
                        <span class="absolute top-3 right-3 w-2 h-2 bg-primary rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </div>
        </header>

        <!-- parametres main -->
        <section class="p-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="font-serif text-xl font-bold text-primary mb-4">Paramètres du Système</h2>
                <p class="text-sm text-gray-500 mb-6">Les paramètres du système peuvent être configurés ici. Veuillez contacter le développeur pour toute modification avancée.</p>
                <form action="" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom du Site</label>
                            <input type="text" name="site_name" value="<?= SITE_NAME ?>" disabled
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition bg-gray-100 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL du Site</label>
                            <input type="text" name="site_url" value="<?= SITE_URL ?>" disabled
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition bg-gray-100 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse Email du Site</label>
                        <input type="email" name="site_email" value="<?= SITE_EMAIL ?>" disabled
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition bg-gray-100 cursor-not-allowed">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" disabled
                            class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary/90 transition cursor-not-allowed">
                            Enregistrer les Modifications
                        </button>
                    </div>
                </form>
            </div>
        </section>
        
    </main>

</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>