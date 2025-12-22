<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="flex-1 p-4 sm:p-8">
        
        <!-- Header de la Page d'Ajout -->
        <header class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center mb-4 md:mb-0">
                <a href="<?= RETOUR_EN_ARRIERE ?>" class="text-gray-400 hover:text-[var(--color-secondary)] mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
                Ajouter un nouvel utilisateur
            </h1>
        </header>
        
        <!-- Formulaire principal -->
        <div id="addPersonnelForm" class="grid grid-cols-1 lg:grid-cols-2 gap-4 px-9" enctype="multipart/form-data">

            <!-- Colonne Droite: Onglets de Saisie (2/3) -->
            <div class="lg:col-span-2">
                <div class="card-container p-6">

                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <form action="" method="post">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" name="email" value="<?=  Helper::getData($_POST, 'email') ?>" required class="form-input" placeholder="Ex: nefertiti@domaine">
                                </div>
                                <div>
                                    <label for="matricule" class="block text-sm font-medium text-gray-700 mb-1">Matricule personnel</label>
                                    <input type="text" id="matricule" name="matricule" value="<?=  Helper::getData($_POST, 'matricule') ?>" required class="form-input" placeholder="Ex: RH001">
                                </div>
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                                    <select id="role" name="role" class="form-input">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach($rolesDbs as $role): ?>
                                            <option value="<?= $role ?>" <?= Helper::getSelectedValue('role', $role) ?> ><?= $role ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                                <button type="submit" name="mosali_add_us" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Ajouter un utilisateur</span>
                                </button>
                            </div>
                        </form>
                            
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>