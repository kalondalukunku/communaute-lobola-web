<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="flex-1 p-4 sm:p-9">
        
        <div class="w-full w-full bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- En-tête avec bannière courte -->
            <div class="h-24 bg-gradient-to-r from-[var(--color-secondary)] to-[#053d69]"></div>
            
            <!-- Contenu du Profil -->
            <div class="px-6 pb-8">
                <div class="relative flex justify-center">
                    <!-- Avatar -->
                    <div class="-mt-12 w-24 h-24 bg-white rounded-4xl shadow-md flex items-center justify-center border-2 border-[var(--color-secondary)]">
                        <span class="text-3xl font-bold text-[var(--color-secondary)]">
                            <i class="fas fa-user-circle text-5xl text-[var(--color-secondary)]"></i>
                        </span> 
                    </div>
                </div>

                <div class="text-center mt-4">
                    <h1 class="text-xl font-bold text-gray-900"><?= $user->nom ? $user->nom .' '. $user->postnom : $user->email ?></h1>
                    <p class="text-sm text-gray-500 font-medium"><?= $user->nom_role ?></p>
                    
                    <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full bg-<?= Helper::returnStatutUsStyle($user->statut_compte) ?>-50 text-<?= Helper::returnStatutUsStyle($user->statut_compte) ?>-700 text-xs font-bold border border-<?= Helper::returnStatutUsStyle($user->statut_compte) ?>-100">
                        <span class="w-2 h-2 bg-<?= Helper::returnStatutUsStyle($user->statut_compte) ?>-500 rounded-full mr-2"></span>
                        <?= $user->statut_compte ?>
                    </div>
                </div>

                <!-- Liste d'informations -->
                <div class="mt-8 space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <div class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-700"><?= $user->email ?></p>
                        </div>
                    </div>

                    <?php if($user->matricule): ?>
                        <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                            <div class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0a1 1 0 011-1h2a1 1 0 011 1v1m-4 0h4"></path></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Matricule</p>
                                <p class="text-sm font-semibold text-gray-700"><?= $user->matricule ?>2</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($user->nom_role !== ARRAY_ROLE_USER[0]): ?>
                    <!-- Boutons d'action -->
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <button onclick="openModal()" class="items-center space-x-2 bg-[var(--color-secondary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-[#053d69] transition duration-150 transform hover:scale-[1.01]">
                            <i class="fas fa-edit"></i>
                            <span>Modifier le rôle</span>
                        </button>
                        <?php if($user->statut_compte === ARRAY_USER[1]): ?>
                            <form action="" method="post">
                                <button name="mosali_on_us" class="w-full items-center space-x-2 bg-green-600 text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-green-800 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-street-view"></i>
                                    <span>Activer</span>
                                </button>
                            </form>
                        <?php elseif($user->statut_compte === ARRAY_USER[0]): ?>
                            <form action="" method="post">
                                <button name="mosali_off_us" class="w-full items-center space-x-2 bg-[var(--color-primary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-user-slash"></i>
                                    <span>Désactiver</span>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="modalOverlay" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
        
        <!-- Fenêtre Modale -->
        <div id="modalContent" 
             class="bg-white w-full max-w-lg rounded-2xl shadow-2xl transform scale-90 transition-transform duration-300 overflow-hidden">
            
            <!-- En-tête avec bouton Fermer (Croix) -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800">Modifier le rôle de l'utilisateur</h3>
                <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="" method="post">
                <!-- Contenu -->
                <div class="px-8 py-5">
                    <!-- <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6 mx-auto">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-center text-lg font-semibold text-gray-900 mb-2">Attention !</h4>
                    <p class="text-center text-gray-500 leading-relaxed">
                        Cette fenêtre ne peut pas être fermée en cliquant à l'extérieur. Vous devez impérativement choisir une option ci-dessous ou utiliser la croix de fermeture.
                    </p> -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <select id="role" name="role" class="form-input">
                            <option value="">Sélectionner...</option>
                            <?php foreach($rolesDbs as $role): ?>
                                <option value="<?= $role ?>" <?= Helper::getSelectedValue('role', $role, $user->nom_role) ?> ><?= $role ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="p-6 bg-gray-50 flex flex-col sm:flex-row gap-3">
                    <button onclick="closeModal()" class="w-full items-center space-x-2 bg-red-600 text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-red-800 transition duration-150 transform hover:scale-[1.01]">
                        <i class="fas fa-xmark"></i>
                        <span>Annuler</span>
                    </button>
                    <button name="mosali_edt_us" class="w-full items-center space-x-2 bg-[var(--color-secondary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-[var(--color-secondary)] transition duration-150 transform hover:scale-[1.01]">
                        <i class="fas fa-edit"></i>
                        <span>Modifier</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>