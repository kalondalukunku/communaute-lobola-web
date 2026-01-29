<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'templates/alertView.php'; 
?>

<div class="container mx-auto py-12 px-4 md:px-0 flex justify-center">
    
    <!-- Conteneur Principal -->
    <div class="document-container fade-in p-6 md:p-12 rounded-3xl bg-white shadow-2xl relative overflow-hidden border border-gray-100" style="width: 95%; max-width: 950px;">
        
        <!-- En-tête Institutionnel -->
        <div class="text-center mb-12">
            <div class="inline-block p-3 rounded-full bg-primary/5 mb-4">
                <i class="fas fa-universal-access text-primary text-3xl"></i>
            </div>
            <h1 class="font-serif text-3xl md:text-5xl text-center text-primary mb-4 tracking-tight">Formulaire d'Intégration</h1>
            <p class="text-gray-500 font-sans uppercase tracking-[0.3em] text-[10px] md:text-xs">Portail de Candidature au Sanctuaire Lobola Lo-Ilondo</p>
            <div class="w-32 h-px bg-gradient-to-r from-transparent via-primary to-transparent mx-auto mt-6"></div>
        </div>

        <!-- Section Introduction -->
        <div class="bg-gray-50 p-6 md:p-8 rounded-2xl mb-12 border-l-4 border-primary/30">
            <h2 class="font-serif text-xl text-primary mb-3">Salutations, chercheur de vérité</h2>
            <p class="text-gray-600 leading-relaxed text-sm md:text-base font-serif italic">
                L'intégration au cercle est un pas sacré vers la connaissance de soi et des ancêtres. Veuillez remplir ce formulaire avec sincérité. Vos réponses seront examinées avec sagesse par le conseil des initiés.
            </p>
        </div>

        <form method="post" class="space-y-12" enctype="multipart/form-data">
            
            <!-- Section 1 : État Civil -->
            <section>
                <div class="flex items-center gap-4 mb-8">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-lg">1</span>
                    <h3 class="text-sm uppercase tracking-widest font-bold text-gray-800">Identité & Origines</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Nom complet (Nom du KAMA)</label>
                        <input type="text" name="nom_postnom" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: Mukendi Kitenge" required>
                    </div>

                    <div class="group relative">
                        <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Sexe</label>
                        <select name="sexe" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none">
                            <option value="" disabled selected>Sélectionnez votre sexe...</option>
                            <?php foreach(ARRAY_TYPE_SEXE as $sexe): ?>
                                <option value="<?= $sexe ?>" <?= Helper::getSelectedValue('sexe', $sexe) ?> ><?= $sexe ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Date de naissance</label>
                        <input type="date" name="date_naissance" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="JJ/MM/AAAA..." required>
                    </div>
                </div>
            </section>

            <!-- Section 2 : Parcours Spirituel -->
            <section>
                <div class="flex items-center gap-4 mb-8">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-lg">2</span>
                    <h3 class="text-sm uppercase tracking-widest font-bold text-gray-800">Cheminement Spirituel</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 mb-8">
                    <div class="group relative">
                        <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Niveau d'initiation</label>
                        <select name="niveau_initiation" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none">
                            <option value="" disabled selected>Sélectionnez votre niveau d'initiation</option>
                            <?php foreach(ARRAY_TYPE_NIVEAU_INITIATION as $niveau): ?>
                                <option value="<?= $niveau ?>" <?= Helper::getSelectedValue('niveau_initiation', $niveau) ?> ><?= $niveau ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Pourquoi souhaitez-vous rejoindre la communauté ?</label>
                        <textarea name="motivation" rows="3" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent resize-none font-serif" placeholder="Partagez vos motivations profondes..." required></textarea>
                    </div>
                </div>
            </section>

            <!-- Section 3 : Engagements Locaux -->
            <section>
                <div class="flex items-center gap-4 mb-8">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-lg">3</span>
                    <h3 class="text-sm uppercase tracking-widest font-bold text-gray-800">Localisation & Contact</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Nationalité</label>
                        <input type="text" name="nationalite" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Votre pays d'origine..." required>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Ville de résidence</label>
                        <input type="text" name="ville" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Kinshasa" required>
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Lieu de résidence (Adresse)</label>
                        <input type="text" name="adresse" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ville, Commune, Quartier..." required>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Adresse Email</label>
                        <input type="email" name="email" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="exemple@gmail.com" required>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">WhatsApp / Appel</label>
                        <input type="tel" name="phone" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="+243 8*******" required>
                    </div>
                </div>
            </section>

            <!-- Pièces Jointes -->
            <div class="p-8 border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50/30 flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 text-center md:text-left">
                    <h4 class="font-bold text-gray-800 text-sm mb-1">Identité Visuelle</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">Veuillez uploader une photo nette pour votre future carte de membre.</p>
                </div>
                <div class="w-full md:w-auto">
                    <div class="relative group">
                        <input type="file" name="photo_file" id="photo-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" required>
                        <div id="photo-preview-container" class="w-32 h-32 md:w-40 md:h-40 bg-white border border-gray-200 rounded-2xl shadow-sm group-hover:border-primary transition-all flex flex-col items-center justify-center overflow-hidden">
                            <div id="preview-placeholder" class="text-center p-4">
                                <i class="fas fa-camera text-primary text-2xl mb-2"></i>
                                <p class="text-[9px] uppercase font-bold text-gray-400">Ajouter une photo</p>
                            </div>
                            <img id="image-display" src="" alt="Aperçu" class="hidden w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton de Soumission -->
            <div class="flex flex-col items-center pt-8">
                <button type="submit" name="c_lobola_integration" class="btn-sign w-full md:w-auto bg-primary text-white px-16 py-5 rounded-full font-bold shadow-2xl hover:shadow-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-4">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer ma Candidature
                </button>
                <div class="mt-8 flex items-center gap-3 text-gray-400">
                    <div class="w-10 h-px bg-gray-200"></div>
                    <span class="text-[9px] uppercase tracking-[0.4em]">Sagesse - Respect - Unité</span>
                    <div class="w-10 h-px bg-gray-200"></div>
                </div>
            </div>

        </form>
    </div>
</div>

<script src="<?= ASSETS ?>js/modules/main2.js"></script>
    