<?php 
    $title = $Series[0]->nom_serie ?? SITE_NAME;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

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
                        <span class="px-3 py-1 bg-primary/20 text-primary text-[10px] font-bold uppercase tracking-widest rounded-full border border-primary/30"><?= $Series[0]->nom_serie ?></span>
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
                        <span class="text-xs text-gray-500"><?= count($Series) ?> enseignement<?= count($Series) > 1 ? 's' : '' ?></span>
                    </div>
                    
                    <div class="playlist space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        <?php foreach($Series as $index => $ep): ?>
                            <div class="track-item group p-4 bg-white/[0.03] color-border rounded-2xl cursor-pointer hover:bg-white/[0.08] hover:border-white/10 transition-all duration-300 flex items-center gap-4 relative overflow-hidden"
                                data-index="<?= $index ?>"
                                data-es="<?= $ep->enseignement_id ?>"
                                data-sr="<?= $ep->serie_id ?>"
                                data-title="<?= htmlspecialchars($ep->title) ?>"
                                data-url="../../<?= htmlspecialchars($ep->audio_url) ?>"
                                data-desc="<?= htmlspecialchars($ep->description) ?>"
                                data-views="<?= number_format($ep->views ?? rand(500, 5000)) ?>">
                                
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
                                            <i class="fa-regular fa-clock"></i> <?= $ep->duration_minutes ?? '00:00' ?>
                                        </span>
                                        <span class="text-[10px] text-gray-500 flex items-center gap-1">
                                            <i class="fa-regular fa-eye"></i> <?= number_format($VuesModel->countAll(['enseignement_id' => $ep->enseignement_id])) ?>
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
            <?php if($Series[0]->nom_serie === "Mâat • Introduction & Préparation"): ?>
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

    <?php if($Series[0]->nom_serie === "Mâat • Introduction & Préparation"): ?>
        <div class="w-[80%] mx-auto mt-12 mb-20">

            <!-- SECTION PHOTOS / GALERIE (Droite - 5 colonnes) -->
            <div class="lg:col-span-5 bg-paper color-border rounded-[2rem] p-6 md:p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-primary/5 blur-[50px] rounded-full"></div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                            <i class="fa-solid fa-images text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold font-serif text-white">Galerie Photos</h3>
                            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Archives & Documents</p>
                        </div>
                    </div>

                    <!-- Grille de Photos -->
                    <div class="grid grid-cols-2 gap-3 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                        <!-- Photo Item 1 -->
                        <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                            <div class="aspect-square rounded-xl overflow-hidden mb-3">
                                <img src="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (1).jpg?w=400&h=400&fit=crop" 
                                    alt="Document 2" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="px-1 pb-1">
                                <p class="text-[11px] text-gray-400 truncate mb-2 italic">Nfumu Kimbangu</p>
                                <a href="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (1).jpg" 
                                download="Nfumu_Kimbangu.jpg" 
                                class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                            <div class="aspect-square rounded-xl overflow-hidden mb-3">
                                <img src="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (3).jpg?w=400&h=400&fit=crop" 
                                    alt="Document 2" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="px-1 pb-1">
                                <p class="text-[11px] text-gray-400 truncate mb-2 italic">Nkumu Elim'e Nzale</p>
                                <a href="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (3).jpg" 
                                download="Nkumu Elim'e Nzale.jpg" 
                                class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                            <div class="aspect-square rounded-xl overflow-hidden mb-3">
                                <img src="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (5).jpg?w=400&h=400&fit=crop" 
                                    alt="Document 2" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="px-1 pb-1">
                                <p class="text-[11px] text-gray-400 truncate mb-2 italic">Kalala Omotunde</p>
                                <a href="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (5).jpg" 
                                download="Kalala Omotunde.jpg" 
                                class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                            <div class="aspect-square rounded-xl overflow-hidden mb-3">
                                <img src="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (4).jpg?w=400&h=400&fit=crop" 
                                    alt="Document 2" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="px-1 pb-1">
                                <p class="text-[11px] text-gray-400 truncate mb-2 italic">Mbuta Sankara</p>
                                <a href="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (4).jpg" 
                                download="Mbuta Sankara.jpg" 
                                class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                            <div class="aspect-square rounded-xl overflow-hidden mb-3">
                                <img src="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (2).jpg?w=400&h=400&fit=crop" 
                                    alt="Document 2" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="px-1 pb-1">
                                <p class="text-[11px] text-gray-400 truncate mb-2 italic">Mbuta Lumumba</p>
                                <a href="<?= ASSETS ?>ressources/Photos_Ancetres/ancetre (2).jpg" 
                                download="Mbuta Lumumba.jpg" 
                                class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Note Footer Galerie -->
                    <div class="mt-auto pt-6">
                        <p class="text-xs text-gray-500 italic text-center">Cliquez sur une image pour l'agrandir</p>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

    <?php if($Series[0]->nom_serie === "Mâat • Module 1 : Religions & Spiritualités"): ?>
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
                            src="https://www.youtube.com/embed/" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Liste des autres vidéos (Miniatures) -->
                    <div class="flex gap-4 mt-6 overflow-x-auto pb-2 custom-scrollbar">
                        <!-- Exemple de miniature cliquable -->
                        <button onclick="document.getElementById('youtube-player').src='https://www.youtube.com/embed/RJcdgFIwm0k'" class="flex-shrink-0 w-32 aspect-video rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group">
                            <img src="https://img.youtube.com/vi/RJcdgFIwm0k/mqdefault.jpg" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </button>
                        <button onclick="document.getElementById('youtube-player').src='https://www.youtube.com/embed/vV0_S8ZXERA'" class="flex-shrink-0 w-32 aspect-video rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group">
                            <img src="https://img.youtube.com/vi/vV0_S8ZXERA/mqdefault.jpg" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </button>
                        <button onclick="document.getElementById('youtube-player').src='https://www.youtube.com/embed/dFF_WTNixMo'" class="flex-shrink-0 w-32 aspect-video rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group">
                            <img src="https://img.youtube.com/vi/dFF_WTNixMo/mqdefault.jpg" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </button>
                        <button onclick="document.getElementById('youtube-player').src='https://www.youtube.com/embed/5Cr4Ghsq9zM'" class="flex-shrink-0 w-32 aspect-video rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group">
                            <img src="https://img.youtube.com/vi/5Cr4Ghsq9zM/mqdefault.jpg" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </button>
                        <button onclick="document.getElementById('youtube-player').src='https://www.youtube.com/embed/sBXo4tdQsGI'" class="flex-shrink-0 w-32 aspect-video rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group">
                            <img src="https://img.youtube.com/vi/sBXo4tdQsGI/mqdefault.jpg" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </button>
                        <button onclick="document.getElementById('youtube-player').src='https://www.youtube.com/embed/WoNz_gy6Tmg'" class="flex-shrink-0 w-32 aspect-video rounded-lg overflow-hidden border-2 border-transparent hover:border-primary transition-all relative group">
                            <img src="https://img.youtube.com/vi/WoNz_gy6Tmg/mqdefault.jpg" class="w-full h-full object-cover opacity-60 group-hover:opacity-100">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </button>
                        <!-- Répéter pour d'autres vidéos -->
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

<script src="<?= ASSETS ?>js/modules/player.js?v=2"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>