<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'templates/alertView.php'; 
?>

<div class="container mx-auto py-12 px-4 md:px-0 flex justify-center">
    
    <!-- Conteneur Principal avec effet de papier parcheminé léger -->
    <div class="document-container fade-in p-6 md:p-12 rounded-3xl bg-white shadow-2xl relative overflow-hidden border border-gray-100" style="width: 95%; max-width: 900px;">
        
        <!-- Ornement de fond discret -->
        <div class="absolute top-0 right-0 w-32 h-32 opacity-5 pointer-events-none">
            <i class="fas fa-scroll text-9xl"></i>
        </div>

        <!-- Sceau du Sanctuaire repositionné pour l'équilibre -->
        <div class="seal flex items-center justify-center text-center font-bold text-[10px] border-2 border-primary text-primary rounded-full w-24 h-24 mx-auto mb-8 transform -rotate-12 hover:rotate-0 transition-transform duration-500 cursor-help">
            SCEAU DU<br>SANCTUAIRE
        </div>

        <h1 class="font-serif text-3xl md:text-5xl text-center text-primary mb-4 tracking-tight">Acte d'Engagement Éthique</h1>
        <div class="w-24 h-1 bg-primary mx-auto mb-12 rounded-full opacity-50"></div>
        
        <div class="prose prose-slate max-w-none font-serif text-base leading-relaxed space-y-8 mb-16 text-gray-800">
            <p class="text-lg leading-relaxed">
                Je déclare solliciter mon adhésion à la <strong class="text-primary">Communauté Spirituelle et Mystique Lobola Lo-Ilondo</strong> pour l’année <strong><?= date('Y') ?></strong>, conformément aux <strong>Statuts et au Règlement Intérieur de l’Ordre</strong>.
            </p>
            
            <div class="bg-gray-50/50 p-6 rounded-2xl border-l-4 border-primary italic">
                <p class="font-sans font-medium text-gray-700 mb-4">En tant que membre de la communauté, je m’engage à :</p>
                
                <ul class="list-none space-y-5 ml-2">
                    <li class="flex items-start">
                        <i class="fas fa-feather-alt text-primary mt-1.5 mr-3 flex-shrink-0"></i>
                        <span>Respecter la <strong class="text-primary">Maât</strong> et les principes sacrés de la spiritualité Kamit.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-feather-alt text-primary mt-1.5 mr-3 flex-shrink-0"></i>
                        <span>Respecter le <strong class="text-primary">Règlement Intérieur</strong>, dont j’ai reçu et pris connaissance d’un exemplaire.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-feather-alt text-primary mt-1.5 mr-3 flex-shrink-0"></i>
                        <span>Accomplir les <strong class="text-primary">initiations à la Maât</strong>, selon les sessions proposées sur la plateforme dédiée.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-feather-alt text-primary mt-1.5 mr-3 flex-shrink-0"></i>
                        <span>Contribuer au <strong class="text-primary">développement économique et spirituel</strong> par le versement d’une cotisation minimale de <strong>10 € ou 10 $</strong> par mois pendant toute l’année <?= date('Y') ?>.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-feather-alt text-primary mt-1.5 mr-3 flex-shrink-0"></i>
                        <span>Participer activement à la vie de la communauté dans l’esprit <strong class="text-primary font-bold">Ubuntu</strong> et la solidarité kamit.</span>
                    </li>
                </ul>
            </div>

            <p class="text-center font-serif italic text-primary text-xl py-6 border-y border-gray-100">
                "Que cette promesse ne soit pas un fardeau, mais un bouclier protégeant la pureté de votre propre chemin."
            </p>
        </div>

        <!-- Formulaire de soumission -->
        <form method="post" class="space-y-10 pt-4" enctype="multipart/form-data">
            
            <!-- Informations Personnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                <!-- <div class="group">
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Nom & Postnom</label>
                    <input type="text" name="nom_postnom" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Saisissez votre nom complet..." required>
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
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Date de naissance</label>
                    <input type="date" name="date_naissance" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" required>
                </div>

                <div class="group">
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Numéro de téléphone</label>
                    <input type="tel" name="phone_number" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="+243..." required>
                </div> -->

                <div class="group relative">
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Modalité d'engagement</label>
                    <select name="modalite_engagement" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none">
                        <option value="" disabled selected>Type d'engagement...</option>
                        <?php foreach(ARRAY_TYPE_ENGAGEMENT as $modalite): ?>
                            <option value="<?= $modalite ?>" <?= Helper::getSelectedValue('modalite_engagement', $modalite) ?> ><?= $modalite ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>

                <div class="group">
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Montant de la contribution</label>
                    <input type="number" name="montant" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Min: 10" required>
                </div>

                <div class="group relative">
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Devise</label>
                    <select name="devise" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent cursor-pointer font-sans appearance-none">
                        <option value="" disabled selected>Sélectionnez la devise...</option>
                        <?php foreach(ARRAY_TYPE_DEVISE as $devise): ?>
                            <option value="<?= $devise ?>" <?= Helper::getSelectedValue('devise', $devise) ?> ><?= $devise ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-0 bottom-3 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
                
            </div>

            <!-- Section Upload (Améliorée visuellement) -->
            <div class="grid grid-cols-1 gap-8 mt-12">
                
                <!-- Document Signé -->
                <div class="space-y-4">
                    <label class="block text-xs tracking-widest font-bold text-primary mb-3 uppercase">Document Scanné & Signé</label>
                    <div class="file-upload-wrapper relative border-2 border-dashed border-gray-200 rounded-2xl p-8 transition-all hover:border-primary hover:bg-primary/5 group bg-gray-50/50">
                        <input type="file" name="engagement_file" id="file-input2" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.docx" required>
                        <div id="upload-prompt2" class="flex flex-col items-center text-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-signature text-primary"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Joindre l'acte signé</span>
                            <span class="text-[10px] text-gray-400">PDF, DOCX (Max 10Mo)</span>
                        </div>
                        <div id="upload-success2" class="hidden flex flex-col items-center animate-pulse">
                            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                            <span class="text-sm font-bold text-primary mt-2">Document prêt</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation -->
            <div class="bg-primary/5 p-8 rounded-2xl border border-primary/20 mt-12">
                <label class="flex items-start cursor-pointer group">
                    <div class="relative flex items-center h-5 mr-4">
                        <input type="checkbox" class="h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer" required>
                    </div>
                    <span class="text-sm text-gray-700 italic leading-relaxed group-hover:text-black transition-colors">
                        Je confirme avoir lu l'intégralité de l'engagement et je donne ma <strong>parole d'honneur</strong> pour le respecter. Je comprends que tout manquement à ces règles entraînera mon retrait définitif du cercle.
                    </span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex flex-col items-center pt-6">
                <button type="submit" name="c_lobola_engagement" class="btn-sign w-full md:w-auto bg-primary text-white px-12 py-5 rounded-full font-bold shadow-xl hover:shadow-primary/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-pen-nib"></i>
                    Sceller mon Engagement pour <?= date('Y') ?>
                </button>
                <div class="mt-6 flex flex-col items-center gap-2">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em]">Authentification Mystique & Administrative</p>
                    <div class="flex gap-4 opacity-30">
                        <i class="fas fa-shield-alt text-xs"></i>
                        <i class="fas fa-book-dead text-xs"></i>
                        <i class="fas fa-key text-xs"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= ASSETS ?>js/modules/main.js"></script>
    