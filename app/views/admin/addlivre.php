<?php 
    $title = "Admin - Ajouter un livre";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>
    <main class="min-h-screen bg-transparent py-12 px-4 sm:px-6">

        <!-- <div class="max-w-4xl mx-auto space-y-8"> -->
            

            <!-- FORMULAIRE -->
            <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- ZONE DE TÉLÉCHARGEMENT (GAUCHE - COUVERTURE & FICHIER) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Image de couverture -->
                    <div class="backdrop-blur-xl rounded-[2rem] p-6 bg-white/[0.02] color-border shadow-xl flex flex-col items-center">
                        <span class="block text-xs font-black text-white uppercase tracking-wider opacity-60 mb-4 self-start">Couverture du livre</span>
                        
                        <label class="relative w-full aspect-[3/4] rounded-2xl border-2 border-dashed flex flex-col items-center justify-center cursor-pointer overflow-hidden transition-all duration-300"
                            :class="dragOverCover ? 'border-[#cfbb30] bg-[#cfbb30]/5' : 'color-border hover:border-[#cfbb30]/50 bg-white/[0.01]'"
                            @dragover.prevent="dragOverCover = true"
                            @dragleave.prevent="dragOverCover = false"
                            @drop.prevent="dragOverCover = false; $refs.coverInput.files = $event.dataTransfer.files; handleCoverChange($event)">
                            
                            <!-- Input réel caché -->
                            <input type="file" name="cover_file" x-ref="coverInput" class="hidden" accept="image/*" @change="handleCoverChange">
                            
                            <!-- Si aucune image n'est sélectionnée -->
                            <template x-if="!coverPreview">
                                <div class="text-center p-4">
                                    <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                    <span class="block text-xs font-bold text-white mb-1">Sélectionner une image</span>
                                    <span class="block text-[10px] text-gray-500">PNG, JPG ou WEBP</span>
                                </div>
                            </template>

                            <!-- Aperçu de l'image -->
                            <template x-if="coverPreview">
                                <div class="absolute inset-0 w-full h-full group">
                                    <img :src="coverPreview" alt="Aperçu de la couverture" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="text-xs font-bold text-[#cfbb30] uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-sync"></i> Remplacer
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </label>
                    </div>

                    <!-- Fichier numérique du livre (PDF/EPUB) -->
                    <div class="backdrop-blur-xl rounded-[2rem] p-6 bg-white/[0.02] color-border shadow-xl">
                        <span class="block text-xs font-black text-white uppercase tracking-wider opacity-60 mb-4">Document Numérique</span>
                        
                        <label class="relative w-full py-6 rounded-2xl border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all duration-300"
                            :class="dragOverFile ? 'border-[#cfbb30] bg-[#cfbb30]/5' : 'color-border hover:border-[#cfbb30]/50 bg-white/[0.01]'"
                            @dragover.prevent="dragOverFile = true"
                            @dragleave.prevent="dragOverFile = false"
                            @drop.prevent="dragOverFile = false; $refs.bookInput.files = $event.dataTransfer.files; handleFileChange($event)">
                            
                            <!-- Input réel caché -->
                            <input type="file" name="book_file" x-ref="bookInput" class="hidden" accept=".pdf,.epub" @change="handleFileChange">
                            
                            <div class="text-center px-4">
                                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-file-pdf text-red-400"></i>
                                </div>
                                <span class="block text-xs font-bold text-white mb-1" x-text="bookFileName ? 'Document chargé' : 'Importer le livre'"></span>
                                <span class="block text-[10px] text-gray-500 line-clamp-1 max-w-[180px] mx-auto" x-text="bookFileName ? bookFileName : 'Format PDF ou EPUB'"></span>
                            </div>
                        </label>
                    </div>

                </div>

                <!-- INFORMATIONS DE L'OUVRAGE (DROITE - FORMULAIRE PRINCIPAL) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="backdrop-blur-xl w-full rounded-[2.5rem] p-8 bg-white/[0.02] color-border shadow-xl space-y-6">
                        
                        <!-- Titre de l'ouvrage -->
                        <div class="space-y-2">
                            <label for="titre" class="block text-xs font-black text-white uppercase tracking-wider opacity-60">Titre de l'ouvrage *</label>
                            <input 
                                type="text" 
                                id="titre" 
                                name="titre" 
                                required 
                                placeholder="Ex: Le chemin de l'éveil spirituel"
                                value="<?=  Helper::getData($_POST, 'titre') ?>"
                                class="block w-full px-5 py-3.5 bg-white/5 border color-border rounded-2xl text-sm text-primary placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#cfbb30]/50 focus:border-[#cfbb30]/50 transition-all duration-300"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Auteur -->
                            <div class="space-y-2">
                                <label for="auteur" class="block text-xs font-black text-white uppercase tracking-wider opacity-60">Auteur / Source *</label>
                                <input 
                                    type="text" 
                                    id="auteur" 
                                    name="auteur" 
                                    required 
                                    placeholder="Ex: Mfumu Kimbangu"
                                    value="<?=  Helper::getData($_POST, 'auteur') ?>"
                                    class="block w-full px-5 py-3.5 bg-white/5 border color-border rounded-2xl text-sm text-primary placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#cfbb30]/50 focus:border-[#cfbb30]/50 transition-all duration-300"
                                />
                            </div>

                            <!-- Catégorie -->
                            <div class="space-y-2">
                                <label for="categorie" class="block text-xs font-black text-white uppercase tracking-wider opacity-60">Catégorie / Enseignement *</label>
                                <select 
                                    id="categorie" 
                                    name="categorie" 
                                    required 
                                    class="block w-full px-5 py-3.5 bg-white/5 border color-border rounded-2xl text-sm text-primary focus:outline-none focus:ring-2 focus:ring-[#cfbb30]/50 focus:border-[#cfbb30]/50 transition-all duration-300 [&>option]:bg-[#121214] [&>option]:text-white">
                                    <?php foreach(ARRAY_CATEGORIE_LIVRE as $categorie): ?>
                                        <option value="<?= $categorie ?>" <?= Helper::getSelectedValue('categorie', $categorie) ?>><?= $categorie ?></option>
                                    <?php endforeach; ?>
                                    <option value="Initiation">Initiation</option>
                                    <option value="Enseignements">Enseignements</option>
                                    <option value="Spiritualité">Spiritualité</option>
                                    <option value="Histoire & Tradition">Histoire & Tradition</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description / Résumé -->
                        <div class="space-y-2">
                            <label for="description" class="block text-xs font-black text-white uppercase tracking-wider opacity-60">Résumé ou Description</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="6" 
                                placeholder="Rédigez un court résumé ou indiquez des détails utiles sur l'ouvrage..."
                                class="block w-full px-5 py-4 bg-white/5 border color-border rounded-2xl text-sm text-primary placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#cfbb30]/50 focus:border-[#cfbb30]/50 transition-all duration-300 resize-none"
                                required
                            ><?= Helper::getData($_POST, 'description') ?></textarea>
                        </div>

                        <!-- BOUTONS D'ACTION -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-white/5">
                            <a href="admin_livres.php" 
                            class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-white transition-colors duration-300">
                                Annuler
                            </a>
                            
                            <button type="submit" name="cllil_admin_add_livre"
                                    class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-black transition-all duration-300 bg-[#cfbb30] rounded-full hover:shadow-[0_0_30px_rgba(207,187,48,0.4)] disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="relative z-10 flex items-center gap-2 uppercase text-xs tracking-wider">
                                    <i class="fas fa-check text-[11px]"></i> 
                                    Enregistrer l'ouvrage
                                </span>
                            </button>
                        </div>

                    </div>
                </div>

            </form>

        <!-- </div> -->
    </main>

</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>