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
                Ajouter un nouveau document
            </h1>
        </header>
        
        <!-- Formulaire principal -->
        <div id="addPersonnelForm" class="grid grid-cols-1 lg:grid-cols-2 gap-4 px-9" enctype="multipart/form-data">

            <!-- Colonne Droite: Onglets de Saisie (2/3) -->
            <div class="lg:col-span-2">
                <div class="card-container p-6">

                    <!-- --------------------------------- -->
                    <div id="personnel" class="tab-content active space-y-6">
                        <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-[var(--color-primary)] transition duration-150 relative">    
                            <form action="" method="post" enctype="multipart/form-data">
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <i class="fas fa-passport mr-2 text-[var(--color-primary)]"></i> <?= $typeDoc->nom_type ?>
                                </label>
                                <input type="file" accept=".pdf" name="mosali_doc_psn" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[var(--color-primary)] hover:file:bg-orange-100 cursor-pointer" required>

                                <div class="mt-4 pt-6 border-t border-gray-200 flex justify-end text-xs">
                                    <button type="submit" name="mosali_add_doc" class="flex items-center space-x-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-[1.01]">
                                        <i class="fas fa-upload"></i>
                                        <span>Téléverser le document</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                            
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>