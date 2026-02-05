<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <!-- <div class="w-full glass rounded-[2.5rem] p-8 shadow-2xl overflow-hidden relative">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-emerald-600/10 rounded-full blur-3xl"></div>

        <div class="text-center mb-10 relative z-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-paper mb-4 color-border">
                <i class="fa-solid fa-microphone-lines text-primary text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Ajouter un enseignant</h1>
            <p class="text-slate-400 text-sm mt-1">Appuyez sur le bouton pour enregistrer votre enseignement</p>
        </div>

        <div class="relative z-10 flex flex-col items-center justify-center p-8 bg-primary/50 border-2 border-dashed color-border rounded-3xl mb-8" id="recordZone">
            
            <button type="button" id="recordBtn" class="w-24 h-24 bg-primary rounded-full flex items-center justify-center transition-all duration-300 shadow-lg shadow-primary active:scale-95">
                <i id="micIcon" class="fa-solid fa-microphone text-3xl text-white"></i>
            </button>

            <div id="recordingStatus" class="mt-6 text-sm font-medium text-primary">
                Cliquez pour parler
            </div>

            <div id="audioPreviewContainer" class="hidden-element w-full mt-6 space-y-4">
                <div class="flex items-center gap-3 bg-primary p-3 rounded-xl border color-border">
                    <i class="fa-solid fa-waveform text-indigo-400"></i>
                    <audio id="audioPlayback" controls controlsList="nodownload" class="h-8 flex-1"></audio>
                    <button type="button" id="deleteAudio" class="p-2 hover:text-red-500">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
        </div>

        <form action="" method="POST" id="teacherForm" enctype="multipart/form-data" class="hidden-element space-y-4 relative z-10">
            <div class="p-5 color-border rounded-2xl">
                <label class="text-[10px] uppercase tracking-[0.1em] text-primary font-bold block mb-5">Infos de l'enseignement</label>
                
                <div class="space-y-3">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white" for="nom">Titre de l'enseignement</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="nom" name="titre" required 
                                class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                                placeholder="Ex: Shenuti Lobola"
                                value="<?= Helper::getData($_POST, 'titre') ?>"
                                style="color: var(--primary);">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="audio_data" id="audioDataInput" required>
                
                <p class="text-[11px] text-slate-500 mt-3 italic text-center">Vérifiez les informations avant l'envoi</p>
            </div>

            <div class="flex flex-col items-center pt-5">
                <button type="submit" name="cllil_add_enseignement" class="btn-sign2 w-full md:w-auto bg-primary text-white px-16 py-5 rounded-full font-bold shadow-2xl hover:shadow-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-4">
                    <i class="fas fa-paper-plane"></i>
                    Valider l'enseignement
                </button>
            </div>
        </form>
    </div> -->
    
    <div class="w-full glass-card rounded-[2rem] p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="text-center mb-8 relative z-10">
            <h1 class="text-2xl font-bold text-white mb-2">Nouvel Enseignement</h1>
            <p class="text-slate-400 text-sm">Gestion du fichier audio</p>
        </div>

        <form action="" method="post" class="space-y-6 relative z-10" enctype="multipart/form-data">
            
            <div id="dropZone" class="group relative color-border rounded-3xl p-8 transition-all flex flex-col items-center justify-center min-h-[220px]">
                <!-- L'input est au-dessus par défaut pour le clic initial -->
                <input type="file" name="audio_data" id="audioFile" 
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" 
                    accept="audio/*">
                
                <!-- Interface initiale -->
                <div id="uploadUI" class="flex flex-col items-center pointer-events-none transition-all duration-300">
                    <div class="w-16 h-16 color-border rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-microphone-lines text-2xl text-primary"></i>
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-slate-300">Cliquez pour choisir l'audio</p>
                        <p class="text-xs text-slate-500 mt-1">Format MP3, WAV ou M4A</p>
                    </div>
                </div>

                <!-- Interface après import (le z-index est géré en JS pour laisser passer les clics vers le lecteur) -->
                <div id="successUI" class="hidden w-full flex-col items-center animate-success z-40">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-full flex items-center justify-center mb-4 border border-emerald-500/50">
                        <i class="fa-solid fa-check text-xl text-emerald-400"></i>
                    </div>
                    
                    <div class="w-full p-5 rounded-2xl color-border shadow-inner">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 color-border rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-headphones text-primary"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p id="fileName" class="text-sm font-medium text-white truncate">Nom du fichier</p>
                                <p id="fileSize" class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">0.0 MB</p>
                            </div>
                            <button type="button" id="removeFile" class="p-2 text-slate-500 hover:text-red-400 transition-colors">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <!-- Le lecteur est maintenant accessible au clic -->
                        <audio id="audioPreview" controls controlsList="nodownload" class="w-full h-10 brightness-90 contrast-125"></audio>
                    </div>
                    <p class="mt-4 text-[11px] text-emerald-400/80 font-medium tracking-wide">FICHIER CHARGÉ AVEC SUCCÈS</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-white" for="titre">Titre de l'enseignement</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-white">
                        <i class="fa-solid fa-heading"></i>
                    </span>
                    <input type="text" id="titre" name="titre" required 
                        class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        placeholder="Ex: Shenuti Lobola"
                        value="<?= Helper::getData($_POST, 'titre') ?>"
                        style="color: var(--primary);">
                </div>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-medium text-white" for="biographie">Description</label>
                <div class="relative">
                    <textarea id="biographie" name="description" rows="4" required
                        class="w-full pl-10 pr-4 py-2.5  color-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                        placeholder="Écrivez une brève biographie de l'enseignant..."
                        style="color: var(--primary);"><?= Helper::getData($_POST, 'description') ?></textarea>
                </div>
            </div>

            <div class="flex flex-col items-center pt-8">
                <button type="submit" name="cllil_add_enseignement" class="btn-sign2 w-full md:w-auto bg-primary text-white px-16 py-5 rounded-full font-bold shadow-2xl hover:shadow-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-4">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer ma Candidature
                </button>
            </div>
        </form>
    </div>

</section>
    
<script src="<?= ASSETS ?>js/modules/recoder.js"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>