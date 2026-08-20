<?php 
    $title = $Series->nom ?? SITE_NAME;
    $cosmogonyVideos = [
        ['video_id' => 'RJcdgFIwm0k', 'title' => 'Cosmogonie Dogon du Mali'],
        ['video_id' => 'vV0_S8ZXERA', 'title' => 'Cosmogonie Fang, Ekang'],
        ['video_id' => 'dFF_WTNixMo', 'title' => 'Cosmogonie Egyptienne d\'Héliopolis'],
        ['video_id' => '5Cr4Ghsq9zM', 'title' => 'Cosmogonie Japonaise (Izanagi & Izanami)'],
        ['video_id' => 'sBXo4tdQsGI', 'title' => 'Cosmogonie Arabe'],
        ['video_id' => 'WoNz_gy6Tmg', 'title' => 'Cosmogonie Chrétienne'],
    ];
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <?php if ($isOn): ?>
        <div id="audio-app" class="w-[80%] mx-auto bg-paper color-border text-white rounded-[2rem] shadow-2xl shadow-red/20 mt-8 overflow-hidden relative">
        
            <!-- Effet de halo décoratif en arrière-plan -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 blur-[100px] rounded-full"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/5 blur-[100px] rounded-full"></div>

            <div class="relative z-10 p-6 pb-0 md:p-10">
                <!-- Zone du Lecteur Principal -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    
                    <!-- Gauche: Visual et Info -->
                    <div class="lg:col-span-7 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-3 py-1 bg-primary/20 text-primary text-[10px] font-bold uppercase tracking-widest rounded-full border border-primary/30"><?= $Series->nom ?></span>
                            <div class="flex items-center gap-2 text-gray-500 text-xs font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <span id="view-count"><?= number_format($nbrSerieViews) ?> vue<?= $nbrSerieViews > 1 ? 's' : '' ?></span>
                            </div>
                        </div>

                        <h2 id="current-title" class="text-4xl md:text-5xl font-serif font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-gray-500 mb-4 leading-tight">
                            Sélectionnez un épisode
                        </h2>
                        
                        <p id="current-desc" class="text-gray-400 text-lg leading-relaxed mb-8 line-clamp-3">
                            Cliquez sur un titre dans la liste pour commencer l'expérience.
                        </p>

                        <audio id="main-player" class="hidden"></audio>
                        
                        <!-- Contrôles et Barre -->
                        <div class="bg-white/5 p-6 rounded-3xl color-border backdrop-blur-md">
                            <div class="flex items-center justify-between gap-6 mb-6">
                                <div class="flex items-center gap-4">
                                    <button id="prev-btn" class="hover:text-primary transition-colors">
                                        <i class="fa-solid fa-backward-step text-xl"></i>
                                    </button>
                                    <button id="play-btn" class="w-13 h-13 bg-primary text-black rounded-full flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-lg shadow-primary/20"></button>
                                    <button id="next-btn" class="hover:text-primary transition-colors">
                                        <i class="fa-solid fa-forward-step text-xl"></i>
                                    </button>
                                </div>
                                
                                <div class="hidden md:flex items-center gap-3 bg-black/20 p-2 px-4 rounded-full color-border group">
                                    <button id="mute-btn" class="focus:outline-none">
                                        <i id="volume-icon" class="fa-solid fa-volume-high text-gray-500 text-xs transition-colors group-hover:text-primary"></i>
                                    </button>
                                    <input 
                                        type="range" 
                                        id="volume-slider" 
                                        min="0" 
                                        max="1" 
                                        step="0.01" 
                                        value="1" 
                                        class="w-20 h-1 bg-white/10 rounded-full appearance-none cursor-pointer accent-primary"
                                    />
                                </div>
                                <!-- <div class="hidden md:flex items-center gap-3 bg-black/20 p-2 px-4 rounded-full color-border">
                                    <i class="fa-solid fa-volume-high text-gray-500 text-xs"></i>
                                    <div class="w-20 h-1 bg-white/10 rounded-full">
                                        <div class="w-2/3 h-full bg-primary rounded-full"></div>
                                    </div>
                                </div> -->
                            </div>

                            <div class="space-y-2">
                                <input type="range" id="seek-bar" value="0" class="w-full accent-primary h-1 bg-white/10 rounded-full appearance-none cursor-pointer">
                                <div class="flex justify-between text-[10px] font-mono text-gray-500 uppercase tracking-tighter">
                                    <span id="time-now">00:00</span>
                                    <span id="time-total">00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Droite: Playlist -->
                    <div class="lg:col-span-5">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold font-serif">Enseignements de la Série</h3>
                            <span class="text-xs text-gray-500"><?= count($Series->teachings) ?> enseignement<?= count($Series->teachings) > 1 ? 's' : '' ?></span>
                        </div>
                        
                        <div class="playlist space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                            <?php foreach($Series->teachings as $index => $ep): ?>
                                <div class="track-item group p-4 bg-white/[0.03] color-border rounded-2xl cursor-pointer hover:bg-white/[0.08] hover:border-white/10 transition-all duration-300 flex items-center gap-4 relative overflow-hidden"
                                    data-index="<?= $index ?>"
                                    data-es="<?= $ep->enseignement_id ?>"
                                    data-sr="<?= $ep->serie_id ?>"
                                    data-ssd="<?= $sessionId ?>"
                                    data-title="<?= htmlspecialchars($ep->title) ?>"
                                    data-url="../../<?= htmlspecialchars($ep->audio_url) ?>"
                                    data-desc="<?= htmlspecialchars($ep->description) ?>"
                                    data-views="<?= number_format($ep->total_views ?? rand(500, 5000)) ?>">
                                    
                                    <!-- Indicateur de lecture (Spectre animé) -->
                                    <div class="mt-3 playing-bars hidden group-[.active]:flex items-end gap-[2px] h-4 absolute right-4 top-4">
                                        <div class="bar w-[3px] bg-primary animate-music-bar-1"></div>
                                        <div class="bar w-[3px] bg-primary animate-music-bar-2"></div>
                                        <div class="bar w-[3px] bg-primary animate-music-bar-3"></div>
                                    </div>

                                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-black/40 color-border flex items-center justify-center text-sm font-mono text-gray-500 group-hover:text-primary transition-colors">
                                        <?= sprintf("%02d", $index + 1) ?>
                                    </div>

                                    <div class="flex-grow min-w-0">
                                        <h4 class="text-sm font-semibold truncate group-hover:text-primary transition-colors"><?= htmlspecialchars($ep->title) ?></h4>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[10px] text-gray-500 flex items-center gap-1">
                                                <i class="fa-regular fa-clock"></i> <?= $ep->duration ?? '00:00' ?>
                                            </span>
                                            <span class="text-[10px] text-gray-500 flex items-center gap-1">
                                                <i class="fa-regular fa-eye"></i> <?= number_format($VuesModel->countAll(['enseignement_id' => $ep->enseignement_id, 'session_id' => $sessionId])) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-circle-play text-primary text-xl"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 p-6 md:p-10 border-t border-white/5 flex flex-wrap items-center justify-between gap-4">
                <?php if($Series->nom === "Mâat • Introduction & Préparation"): ?>
                    <div class="flex items-center gap-4">
                        <!-- Bouton Téléchargement -->
                        <a href="<?= ASSETS ?>ressources/Photos_Ancetres.rar" 
                        id="download-link" 
                        class="flex items-center gap-2 px-5 py-2.5 bg-white/5 hover:bg-white/10 color-border rounded-xl transition-all group" download>
                            <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-cloud-arrow-down"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] uppercase tracking-widest text-gray-500 font-bold">Ressources</span>
                                <span class="text-sm font-semibold text-white">Télécharger les fichiers</span>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Section Poser une Question -->
                <div class="flex items-center gap-4">
                    <p class="hidden md:block text-right">
                        <span class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold">Besoin d'éclaircissement ?</span>
                        <span class="text-xs text-gray-400 font-medium italic">Une question sur l'enseignement ?</span>
                    </p>
                    <a href="<?= $whatsappUrl ?>" 
                    target="_blank"
                    class="flex items-center gap-3 px-6 py-3 bg-[#25D366]/10 hover:bg-[#25D366]/20 border border-[#25D366]/20 rounded-full transition-all group">
                        <i class="fa-brands fa-whatsapp text-[#25D366] text-xl animate-pulse"></i>
                        <span class="text-sm font-bold text-white group-hover:text-[#25D366] transition-colors">Poser une question</span>
                    </a>
                </div>
            </div>
        </div>

        <?php if($Series->nom === "Mâat • Module 1 : Religions & Spiritualités"): ?>
            <div class="w-[80%] mx-auto mt-12 mb-20">

                <!-- SECTION VIDÉOS YOUTUBE (Gaucher - 7 colonnes) -->
                <div class="lg:col-span-7 bg-paper color-border rounded-[2rem] p-6 md:p-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-12 -left-12 w-32 h-32 bg-red-500/5 blur-[50px] rounded-full"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-red-600/20 flex items-center justify-center text-red-500">
                                <i class="fa-brands fa-youtube text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold font-serif text-white">Vidéos Complémentaires</h3>
                                <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Immersion Visuelle</p>
                            </div>
                        </div>

                        <!-- Lecteur Principal Vidéo -->
                        <div class="aspect-video w-full rounded-2xl overflow-hidden color-border bg-black/40">
                            <iframe 
                                id="youtube-player"
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/RJcdgFIwm0k" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen>
                            </iframe>
                        </div>

                        <div class="mt-4">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Cosmogonie active</p>
                            <h4 id="youtube-video-title" class="mt-2 text-lg font-semibold text-white">Cosmogonie de la naissance du monde</h4>
                        </div>

                        <!-- Liste des autres vidéos (Miniatures) -->
                        <div class="flex gap-4 mt-6 overflow-x-auto pb-2 custom-scrollbar">
                            <?php foreach ($cosmogonyVideos as $video): ?>
                                <button type="button"
                                    data-video-id="<?= htmlspecialchars($video['video_id']) ?>"
                                    data-video-title="<?= htmlspecialchars($video['title']) ?>"
                                    class="video-thumb flex-shrink-0 w-32 rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group bg-black/30 focus:outline-none"
                                >
                                    <div class="aspect-video relative">
                                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['video_id']) ?>/mqdefault.jpg" alt="<?= htmlspecialchars($video['title']) ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    <div class="px-2 py-2 bg-white/5">
                                        <span class="block text-[10px] text-gray-200 font-medium leading-snug line-clamp-2"><?= htmlspecialchars($video['title']) ?></span>
                                    </div>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="text-center py-20">
            <h2 class="font-serif text-3xl text-primary mb-4">Bibliothèque Sacrée</h2>
            <p class="text-gray-500 text-sm italic mb-8">"La sagesse Mâat dans nos cœurs et dans notre âme."</p>
            <div class="inline-block px-6 py-3 rounded-lg bg-primary/10 border border-primary/20">
                <span class="text-primary text-[10px] font-bold italic">"La bibliothèque est en cours de préparation, revenez bientôt pour découvrir les enseignements de la prochaine session."</span>
            </div>
        </div>
    <?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const player = document.getElementById('youtube-player');
        const videoTitle = document.getElementById('youtube-video-title');
        const thumbs = document.querySelectorAll('.video-thumb');

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', function () {
                const videoId = this.dataset.videoId;
                const title = this.dataset.videoTitle;

                if (player && videoId) {
                    player.src = 'https://www.youtube.com/embed/' + videoId;
                }

                if (videoTitle && title) {
                    videoTitle.textContent = title;
                }

                thumbs.forEach((item) => item.classList.remove('border-primary'));
                this.classList.add('border-primary');
            });
        });
    });
</script>
<script src="<?= ASSETS ?>js/modules/player.js?v=<?= APP_VERSION ?>"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>