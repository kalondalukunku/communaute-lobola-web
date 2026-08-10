<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    if(Session::get('membre') || Session::get('enseignant')) include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

<section class="flex items-center justify-center px-4 py-20 animate-fade-in-up">
    <div class="w-full max-w-4xl rounded-3xl color-border shadow-md shadow-[#cfbb30]/10 glass-effect p-6 sm:p-8">
        
        <!-- Titre de la section -->
        <div class="mb-6">
            <h2 class="text-2xl font-serif text-primary tracking-tight">Visionnage de l'excursion</h2>
            <p class="text-slate-500 text-sm mt-1">Lecture optimisée et disponible en téléchargement</p>
        </div>

        <!-- Lecteur Vidéo -->
        <div class="relative overflow-hidden rounded-2xl bg-primary shadow-inner">
            <!-- <video class="w-full h-auto aspect-video" controls poster="https://placehold.co/1280x720/1e293b/ffffff?text=Video+Excursion+Lac+Ma+Vallée"> -->
            <video class="w-full h-auto aspect-video" controls>
                <source src="<?= ASSETS ?>ressources/videos/mavalle.mp4" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-[#cfbb30] pt-6">
            <div class="text-slate-600 text-sm">
                <span class="font-semibold block">Format disponible :</span> MP4 (Haute qualité)
            </div>
            
            <!-- Bouton de téléchargement -->
            <a href="<?= ASSETS ?>ressources/videos/mavalle2.mp4" 
               download 
               class="group relative inline-flex items-center justify-center text-xs px-6 py-3 rounded-xl bg-primary hover:bg-primary/90 font-bold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary/20 hover-lift">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Télécharger la vidéo (6.1 GB Version plus stable)</span>
            </a>
            <a href="<?= ASSETS ?>ressources/videos/mavalle.mp4" 
               download 
               class="group relative inline-flex items-center justify-center text-xs px-6 py-3 rounded-xl bg-primary hover:bg-primary/90 font-bold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary/20 hover-lift">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Télécharger la vidéo (2.1 GB)</span>
            </a>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>