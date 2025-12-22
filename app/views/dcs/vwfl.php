<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="w-[80%] mx-auto mt-[3.5rem] p-4 md:p-6 bg-white rounded-xl shadow-sm border border-[var(--color-primary)]">
    <!-- Header de la page -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <!-- Icône PDF avec fond léger -->
            <div class="p-2.5 bg-red-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--color-primary)]" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                    <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-slate-800 tracking-tight">
                Visualisation du document PDF
            </h1>
        </div>

        <!-- Bouton Retour -->
        <a href="<?= RETOUR_EN_ARRIERE ?>" 
           class="inline-flex items-center px-4 py-2 bg-[var(--color-primary)] hover:bg-[var(--color-secondary)] text-white text-sm font-medium rounded-lg transition-colors duration-200 border border-[var(--color-primary)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>

    <!-- Conteneur de l'Iframe -->
    <div class="relative w-full rounded-lg overflow-hidden bg-slate-50 shadow-inner" 
         style="height: calc(100vh - 250px); min-height: 500px;">
        <iframe 
            src="<?= $urlFile ?>#toolbar=0&navpanes=0&scrollbar=0" 
            class="absolute inset-0 w-full h-full"
            style="border: none;"
            title="Aperçu PDF">
        </iframe>
    </div>
    
    <!-- Pied de page optionnel / Info -->
    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-500 uppercase tracking-widest">
        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
        Mode lecture uniquement
    </div>
</div> 
    
</section>

<?php 
    include APP_PATH . 'views/layouts/footer.php'; 
    // sleep(4);
    // unlink($pathFilePdf);
?>