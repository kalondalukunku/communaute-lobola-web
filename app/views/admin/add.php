<?php 
    $title = "Admin - Ajouter un administrateur";
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
                <h1 class="font-serif text-xl md:text-md font-bold text-primary">Ajouter un administrateur</h1>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Créez un nouvel accès administratif au système.</p>
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
                    <i class="fas fa-id-card"></i> Informations du Compte
                </h3>
            </div>

            <form method="post" id="addAdminForm" class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="nom">Nom Complet</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="nom" name="nom" required 
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="Ex: Shenuti Lobola"
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
                                style="color: var(--primary);">
                        </div>
                    </div>

                    <!-- Rôle -->
                    <!-- <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="role">Rôle / Privilèges</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-user-tag"></i>
                            </span>
                            <select id="role" name="role" required
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition appearance-none">
                                <option value="superadmin">Super Administrateur</option>
                                <option value="editor" selected>Éditeur / Gestionnaire</option>
                                <option value="viewer">Consultant (Lecture seule)</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-white">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="tel">Téléphone</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" id="tel" name="tel"
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="+243 ...">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="password">Mot de passe</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-10 pr-10 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePass('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white hover:text-primary transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="confirm_password">Confirmer le mot de passe</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-check-double"></i>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="••••••••">
                        </div>
                    </div> -->
                </div>

                <!-- Permissions Section -->
                <!-- <div class="mt-8 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                    <h4 class="text-sm font-semibold text-indigo-900 mb-4 uppercase tracking-wider">Autorisations rapides</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="text-sm text-white group-hover:text-white transition">Peut voir les rapports financiers</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="text-sm text-white group-hover:text-white transition">Peut gérer les paiements</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary">
                            <span class="text-sm text-white group-hover:text-white transition">Peut supprimer des données</span>
                        </label>
                    </div>
                </div> -->

                <div class="mt-10 flex flex-col md:flex-row gap-4 items-center justify-end">
                    <button name="cllil_admin_add" class="bg-primary text-paper px-8 py-3 rounded-xl text-xs font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                        <i class="fas fa-save"></i> Enregistrer l'administrateur
                    </button>
                </div>
            </form>
        </div>
    </main>

</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>