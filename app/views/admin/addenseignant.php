<?php 
    $title = "Admin - Ajouter un enseignant";
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
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Ajouter un enseignant</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Créez un nouvel enseignant dans le système.</p>
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

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto rounded-2xl shadow-sm color-border overflow-hidden">
            <div class="p-6 color-border-b">
                <h3 class="font-semibold text-white text-sm flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Informations de l'Enseignant
                </h3>
            </div>

            <form method="post" id="addAdminForm" class="p-8" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="nom">Nom Complet</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="nom" name="nom_complet" required 
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="Ex: Shenuti Lobola"
                                value="<?=  Helper::getData($_POST, 'nom_complet') ?>"
                                style="color: var(--primary);">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="email">Adresse Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" required
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="admin@communautelobola.com"
                                value="<?=  Helper::getData($_POST, 'email') ?>"
                                style="color: var(--primary);">
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="telephone">Numéro de Téléphone</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" id="telephone" name="phone_number" required
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="+243 81 234 5678"
                                value="<?=  Helper::getData($_POST, 'phone_number') ?>"
                                style="color: var(--primary);">
                        </div>
                    </div>

                    <!-- biographie -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-medium text-white" for="biographie">Biographie</label>
                        <div class="relative">
                            <textarea id="biographie" name="biographie" rows="4" required
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="Écrivez une brève biographie de l'enseignant..."
                                style="color: var(--primary);"><?= Helper::getData($_POST, 'biographie') ?></textarea>
                        </div>
                    </div>

                    <!-- photo -->
                    <div class="space-y-2 md:col-span-2">
                        <div class="p-8 color-border rounded-3xl flex flex-col md:flex-row items-center gap-8">
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="font-bold text-primary text-sm mb-1">Identité Visuelle</h4>
                                <p class="text-xs text-gray-400 leading-relaxed">Veuillez uploader une photo nette pour votre future carte de membre.</p>
                            </div>
                            <div class="w-full md:w-auto">
                                <div class="relative group">
                                    <input type="file" name="photo_file" id="photo-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" required>
                                    <div id="photo-preview-container" class="w-32 h-32 md:w-40 md:h-40 bg-primary border border-gray-200 rounded-2xl shadow-sm group-hover:border-primary transition-all flex flex-col items-center justify-center overflow-hidden">
                                        <div id="preview-placeholder" class="text-center p-4">
                                            <i class="fas fa-camera text-paper text-2xl mb-2"></i>
                                            <p class="text-[9px] uppercase font-bold text-paper">Ajouter une photo</p>
                                        </div>
                                        <img id="image-display" src="" alt="Aperçu" class="hidden w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col md:flex-row gap-4 items-center justify-end">
                    <button name="cllil_admin_add_enseignant" class="bg-primary text-paper px-8 py-3 rounded-xl text-xs font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                        <i class="fas fa-save"></i> Ajouter l'enseignant
                    </button>
                </div>
            </form>
        </div>
    </main>

</section>

<script src="<?= ASSETS ?>js/modules/main2.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>