<?php
    require_once APP_PATH . 'views/layouts/header.php';
    require_once APP_PATH . 'views/layouts/navbar.php';
?>

    <div class="container mx-auto py-12 px-4 md:px-0 flex justify-center">
    
        <!-- Conteneur Principal -->
        <div class="document-container fade-in p-6 md:p-12 rounded-3xl bg-white shadow-2xl relative overflow-hidden border border-gray-100" style="width: 95%; max-width: 950px;">
            
            <!-- En-tête Institutionnel -->
            <div class="text-center mb-12">
                <div class="inline-block p-3 rounded-full bg-primary/5 mb-4">
                    <i class="fas fa-universal-access text-primary text-3xl"></i>
                </div>
                <h1 class="font-serif text-3xl md:text-5xl text-center text-primary mb-4 tracking-tight">Modifier mes informations</h1>
                <p class="text-gray-500 font-sans uppercase tracking-[0.3em] text-[10px] md:text-xs">Modification de mes informations au Sanctuaire Lobola Lo-Ilondo</p>
                <div class="w-32 h-px bg-gradient-to-r from-transparent via-primary to-transparent mx-auto mt-6"></div>
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
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Autre Nom</label>
                            <input type="text" name="autre_nom" value="<?= Helper::getData($_POST, 'autre_nom', $Membre->autre_nom) ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: Shenuti">
                        </div>

                        <div class="group relative">
                            <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Genre</label>
                            <select name="genre" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none" required>
                                <option value="" disabled selected>Sélectionnez votre genre...</option>
                                <?php foreach(ARRAY_TYPE_SEXE as $genre): ?>
                                    <option value="<?= $genre ?>" <?= Helper::getSelectedValue('genre', $genre, $Membre->genre) ?> ><?= $genre ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Domaine d'étude</label>
                            <input type="text" name="domaine_etude" value="<?= Helper::getData($_POST, 'domaine_etude', $Membre->domaine_etude) ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: Science informatique" required>
                        </div>
                    </div>
                </section>

                <!-- Section 2 : Parcours Spirituel -->
                <!-- <section>
                    <div class="flex items-center gap-4 mb-8">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-lg">2</span>
                        <h3 class="text-sm uppercase tracking-widest font-bold text-gray-800">Cheminement Spirituel</h3>
                        <div class="flex-grow h-px bg-gray-100"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 mb-8">
                        <div class="group relative">
                            <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Niveau d'initiation</label>
                            <select name="niveau_initiation" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none" required>
                                <option value="" disabled selected>Sélectionnez votre niveau d'initiation</option>
                                <?php foreach(ARRAY_TYPE_NIVEAU_INITIATION as $niveau): ?>
                                    <option value="<?= $niveau ?>" <?= Helper::getSelectedValue('niveau_initiation', $niveau, $Membre->niveau_initiation) ?> ><?= $niveau ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                    </div>
                </section> -->

                <!-- Section 3 : Engagements Locaux -->
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-lg">2</span>
                        <h3 class="text-sm uppercase tracking-widest font-bold text-gray-800">Localisation & Contact</h3>
                        <div class="flex-grow h-px bg-gray-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div class="group relative">
                            <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Votre nationalité</label>
                            <select id="select-nationalite" name="nationalite" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none" required>
                                <option value="" disabled selected>Sélectionnez votre nationalité</option>
                                <?php foreach($allPays as $pays): ?>
                                    <option value="<?= $pays->nationalite ?>" <?= Helper::getSelectedValue('nationalite', $pays->nationalite, $Membre->nationalite) ?> ><?= $pays->nationalite ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                        <div class="group relative">
                            <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Votre pays de résidence</label>
                            <select id="select-pays" name="pays" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none" required>
                                <option value="" disabled selected>Sélectionnez votre pays de résidence</option>
                                <?php foreach($allPays as $pays): ?>
                                    <option value="<?= $pays->pays ?>" <?= Helper::getSelectedValue('pays', $pays->pays, $Membre->pays) ?> ><?= $pays->pays ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                        <div class="group relative">
                            <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Votre ville</label>
                            <select id="select-ville" name="ville" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none" required>
                                <option value="" disabled selected>Sélectionnez votre ville</option>
                                <?php foreach($villes as $ville): ?>
                                    <option value="<?= $ville->ville ?>" <?= Helper::getSelectedValue('ville', $ville->ville, $Membre->ville) ?> ><?= $ville->ville ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                        
                        <div class="group">
                            <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Lieu de résidence (Adresse)</label>
                            <input type="text" name="adresse" value="<?= Helper::getData($_POST, 'adresse', $Membre->adresse) ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ville, Commune, Quartier..." required>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">WhatsApp / Appel</label>
                            <input type="tel" name="phone" value="<?= Helper::getData($_POST, 'phone', $Membre->phone_number) ?>" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="+243 8*******" required>
                        </div>
                    </div>
                </section>

                <!-- Pièces Jointes -->
                <div class="p-8 border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50/30 flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-1 text-center md:text-left">
                        <h4 class="font-bold text-gray-800 text-sm mb-1">Identité Visuelle</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">Veuillez uploader une nouvelle photo nette pour votre future carte de membre.</p>
                    </div>
                    <div class="w-full md:w-auto">
                        <div class="relative group">
                            <input type="file" name="photo_file" id="photo-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                            <div id="photo-preview-container" class="w-32 h-32 md:w-40 md:h-40 bg-white border border-gray-200 rounded-2xl shadow-sm group-hover:border-primary transition-all flex flex-col items-center justify-center overflow-hidden">
                                <img id="image-display" src="../../<?= $Membre->path_profile ?>" alt="Aperçu" class="relative w-full h-full object-cover">
                                <div id="preview-placeholder" class="absolute text-center p-4">
                                    <i class="fas fa-camera text-primary text-2xl mb-2"></i>
                                    <p class="text-[9px] uppercase font-bold text-primary">Modifier ma photo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bouton de Soumission -->
                <div class="flex flex-col items-center pt-8">
                    <button type="submit" name="c_lobola_membre_edit" class="btn-sign w-full md:w-auto bg-primary text-white px-16 py-5 rounded-full font-bold shadow-2xl hover:shadow-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-4">
                        <i class="fas fa-edit"></i>
                        Modifier mes informations
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

<?php require_once APP_PATH . 'views/layouts/footer.php';
