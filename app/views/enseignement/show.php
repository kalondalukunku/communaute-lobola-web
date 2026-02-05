<?php 
    $title = $title ?? SITE_NAME;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="flex-grow container mx-auto px-4 py-8 md:py-12 max-w-5xl">
        <!-- Balise audio cachée pour la source -->
        <audio id="audioSource" src="../../<?= $Enseignement->audio_url ?>" hidden></audio>

        <div class="fade-in">
            <!-- Breadcrumbs & Info -->
            <div class="mb-8">
                <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-primary font-bold mb-2">
                    <span class="bg-secondary text-dark px-2 py-0.5 rounded" style="background-color: #f3f4f6; color: #1a202c;">Cours Audio</span>
                    <span class="text-gray-400">•</span>
                    <span class="text-white">Série : Enseignement avancé</span>
                </div>
                <h2 class="font-serif text-4xl md:text-5xl mb-4" style="color: #d4af37;"><?= $Enseignement->title ?></h2>
                <p class="text-gray-500 italic">"Regardez en vous-même, non pas avec jugement, mais avec curiosité."</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Colonne Gauche : Lecteur et Description -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Lecteur Audio -->
                    <div class="audio-player-container">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <!-- Pochette / Icône -->
                            <div class="relative w-20 h-20 flex-shrink-0">
                                <div id="audioVisualizer" class="absolute inset-0 rounded-2xl" style="background-color: #d4af37;"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fa-solid fa-people-arrows text-4xl"></i>
                                    <!-- <i class="fas fa-headphones text-4xl"></i> -->
                                </div>
                            </div>
                            
                            <!-- Commandes et Barre -->
                            <div class="flex-grow w-full">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-2">En cours de lecture</p>
                                        <h4 class="font-bold text-lg" style="color: #d4af37;"><?= $Enseignement->title ?></h4>
                                    </div>
                                    <span id="playbackPercent" class="text-sm font-bold" style="color: #d4af37;">0% écouté</span>
                                </div>
                                
                                <!-- Barre de progression -->
                                <div class="progress-bar mb-2" id="progressBarContainer">
                                    <div class="progress-filled" id="progressBarFill"></div>
                                </div>
                                
                                <div class="flex justify-between items-center text-xs text-gray-400 font-mono mb-1">
                                    <span id="timeCurrent">00:00</span>
                                    <span id="timeDuration">00:00</span>
                                </div>
                                
                                <div class="flex items-center justify-center gap-8">
                                    <button id="btnRewind" class="text-gray-400 hover:text-white transition"><i class="fas fa-undo-alt text-xl"></i></button>
                                    <button id="btnPlayPause" class="control-btn">
                                        <i id="playPauseIcon" class="fas fa-play text-xl ml-1"></i>
                                    </button>
                                    <button id="btnForward" class="text-gray-400 hover:text-white transition" disabled><i class="fas fa-redo-alt text-xl"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description de l'enseignement -->
                    <div class="bg-paper p-8 rounded-2xl shadow-sm color-border">
                        <h5 class="font-serif text-2xl mb-4" style="color: #d4af37;">À propos de cet enseignement</h5>
                        <div class="prose text-gray-200 leading-relaxed space-y-4">
                            <p><?= $Enseignement->description ?></p>
                            <!-- <p>Points abordés :</p>
                            <ul class="list-disc pl-5 space-y-2 text-gray-300">
                                <li>Identifier le bruit mental incessant.</li>
                                <li>La technique du témoin silencieux.</li>
                                <li>Désamorcer les réactions émotionnelles automatiques.</li>
                            </ul> -->
                        </div>
                    </div>

                    <!-- Section Questions -->
                    <div class="space-y-6">
                        <h5 class="font-serif text-2xl" style="color: #f3f4f6;">Espace d'Échange</h5>
                        <div class="p-6 rounded-2xl shadow-sm flex gap-4 color-border">
                            <div class="w-10 h-10 rounded-full color-border flex items-center justify-center flex-shrink-0" style="background-color: #1a202c;">
                                <i class="fas fa-user text-sm" style="color: #d4af37;"></i>
                            </div>
                            <div class="flex-grow">
                                <textarea class="w-full bg-transparent color-border focus:border-primary outline-none text-primary py-2 resize-none" placeholder="Une question ou un partage sur cet enseignement ?" rows="2"></textarea>
                                <div class="mt-4 flex justify-end">
                                    <button class="px-6 py-2 rounded-full font-bold text-sm transition" style="background-color: #d4af37; color: #1a202c;">Envoyer ma question</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne Droite -->
                <div class="space-y-8">
                    <div class="p-8 rounded-2xl shadow-sm color-border">
                        <h5 class="font-serif text-xl mb-6" style="color: #d4af37;">Ressources (A venir)</h5>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-xl border border-transparent hover:border-gray-600 transition cursor-not-allowed opacity-50">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file-pdf text-red-400"></i>
                                    <span class="text-sm text-gray-200 font-medium">Guide de pratique (PDF)</span>
                                </div>
                                <i class="fas fa-lock text-xs text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
<script src="<?= ASSETS ?>js/modules/player.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>