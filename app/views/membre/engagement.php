<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'templates/alertView.php'; 
?>

<div class="container mx-auto py-12 mx-4 md:px-0">
        
    <div class="document-container fade-in p-4 md:p-8 rounded-2xl" style="width: 90% !important;">
        
        <div class="seal">
            SCEAU DU<br>SANCTUAIRE
        </div>

        <h1 class="font-serif text-2xl md:text-4xl text-center text-primary mb-8">Acte d'Engagement Éthique</h1>
        
        <div class="prose prose-slate max-w-none font-serif text-sm leading-relaxed  space-y-6 mb-12">
            <p>
                Je déclare solliciter mon adhésion à la <strong>Communauté Spirituelle et Mystique Lobola Lo-Ilondo</strong> pour l’année <strong><?= date('Y') ?></strong>, conformément aux <strong>Statuts et au Règlement Intérieur de l’Ordre</strong>.
            </p>
            
            <p>En tant que membre de la communauté, je m’engage à :</p>
            
            <ul class="list-none space-y-4">
                <li>
                    <i class="fas fa-feather-alt text-primary mx-1 mt-1"></i>
                    Respecter la <strong class="mx-1">Maât</strong> et les principes sacrés de la spiritualité Kamit.
                </li>
                <li>
                    <i class="fas fa-feather-alt text-primary mx-1 mt-1"></i>
                    Respecter le <strong class="mx-1">Règlement Intérieur</strong>, dont j’ai reçu et pris connaissance d’un exemplaire.
                </li>
                <li>
                    <i class="fas fa-feather-alt text-primary mx-1 mt-1"></i>
                    Accomplir les <strong class="mx-1">initiations à la Maât</strong>, selon les sessions proposées sur la plateforme dédiée.
                </li>
                <li>
                    <i class="fas fa-feather-alt text-primary mx-1 mt-1"></i>
                    Contribuer au <strong class="mx-1">développement économique et spirituel</strong> de la communauté par le versement d’une <strong class="mx-1">cotisation minimale de 10 € ou 10 $ par mois</strong> pendant toute l’année <?= date('Y') ?>.
                </li>
                <li>
                    <i class="fas fa-feather-alt text-primary mx-1 mt-1"></i>
                    Participer activement à la vie de la communauté dans l’esprit <strong class="mx-1">Ubuntu</strong> et la solidarité kamit
                </li>
            </ul>

            <p class="italic text-primary mt-8">
                "Que cette promesse ne soit pas un fardeau, mais un bouclier protégeant la pureté de votre propre chemin."
            </p>
        </div>

        <!-- Formulaire de soumission -->
        <form method="post" class="space-y-8 border-t border-gray-100 pt-10" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Nom & Postnom</label>
                    <input type="text" name="nom_postnom" class="w-full border-b-2 border-gray-200 focus:border-primary outline-none py-2 text-sm" placeholder="Votre nom complet..." required>
                </div>
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Sexe</label>
                    <select name="sexe" class="w-full border-b-2 border-gray-200 focus:border-[#CFBB30] outline-none py-2 text-sm bg-transparent cursor-pointer font-sans appearance-none">
                        <option value="" disabled selected>Choisissez une option...</option>
                        <?php foreach(ARRAY_TYPE_SEXE as $sexe): ?>
                            <option value="<?= $sexe ?>" <?= Helper::getSelectedValue('sexe', $sexe) ?> ><?= $sexe ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="relative">
                        <i class="fas fa-chevron-down absolute right-0 -top-7 text-xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Date de naissance</label>
                    <input type="date" name="date_naissance" class="w-full border-b-2 border-gray-200 focus:border-primary outline-none py-2 text-sm" placeholder="Votre date de naissance..." required>
                </div>
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Numéro de téléphone</label>
                    <input type="number" name="phone_number" class="w-full border-b-2 border-gray-200 focus:border-primary outline-none py-2 text-sm" placeholder="Votre numéro de téléphone..." required>
                </div>
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Modalité d'engagement</label>
                    <select name="modalite_engagement" class="w-full border-b-2 border-gray-200 focus:border-[#CFBB30] outline-none py-2 text-sm bg-transparent cursor-pointer font-sans appearance-none">
                        <option value="" disabled selected>Choisissez une option...</option>
                        <?php foreach(ARRAY_TYPE_ENGAGEMENT as $modalite): ?>
                            <option value="<?= $modalite ?>" <?= Helper::getSelectedValue('modalite_engagement', $modalite) ?> ><?= $modalite ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="relative">
                        <i class="fas fa-chevron-down absolute right-0 -top-7 text-xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Montant</label>
                    <input type="number" name="montant" class="w-full border-b-2 border-gray-200 focus:border-primary outline-none py-2 text-sm" placeholder="Minimun: 10" required>
                </div>
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Devise</label>
                    <select name="devise" class="w-full border-b-2 border-gray-200 focus:border-[#CFBB30] outline-none py-2 text-sm bg-transparent cursor-pointer font-sans appearance-none">
                        <option value="" disabled selected>Choisissez une option...</option>
                        <?php foreach(ARRAY_TYPE_DEVISE as $devise): ?>
                            <option value="<?= $devise ?>" <?= Helper::getSelectedValue('devise', $devise) ?> ><?= $devise ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="relative">
                        <i class="fas fa-chevron-down absolute right-0 -top-7 text-xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs tracking-widest font-bold text-paper mb-2">Adresse</label>
                    <input type="text" name="adresse" class="w-full border-b-2 border-gray-200 focus:border-primary outline-none py-2 text-sm" placeholder="Votre adresse..." required>
                </div>
            </div>

            <!-- Champ d'upload du document -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <div class="space-y-4">
                    <label class="block text-xs tracking-widest font-bold text-[#CFBB30] mb-3">Uploader votre photo</label>
                    
                    <div id="2" class="file-upload-wrapper relative border-2 border-dashed border-gray-300 rounded-2xl p-8 transition-all duration-300 bg-white/5 group">
                        <input type="file" name="photo_file" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".jpg,.png" required>
                        
                        <!-- État initial : Vide -->
                        <div id="upload-prompt" class="flex flex-col items-center gap-2 transition-opacity duration-300">
                            <i class="fas fa-cloud-upload-alt text-[#CFBB30] text-4xl group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm font-medium text-[#CFBB30]">Cliquez pour parcourir ou glissez votre fichier ici</span>
                            <span class="text-xs text-gray-400">JPG, PNG (Max 5Mo)</span>
                        </div>

                        <!-- État : Fichier sélectionné (Tampon) -->
                        <div id="upload-success" class="hidden flex flex-col items-center gap-2 animate-bounce-short">
                            <div class="w-16 h-16 bg-[#16302B] rounded-full flex items-center justify-center border-2 border-[#CFBB30] shadow-lg">
                                <i class="fas fa-check text-[#CFBB30] text-2xl"></i>
                            </div>
                            <span id="file-name" class="text-sm font-bold text-white font-serif">Document prêt</span>
                            <button type="button" onclick="resetUpload(event)" class="text-xs text-red-500 underline hover:text-red-700 z-20">Modifier le fichier</button>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <label class="block text-xs tracking-widest font-bold text-[#CFBB30] mb-3">Document d'engagement signé</label>
                    
                    <div id="drop-zone2" class="file-upload-wrapper relative border-2 border-dashed border-gray-300 rounded-2xl p-8 transition-all duration-300 bg-white/5 group">
                        <input type="file" name="engagement_file" id="file-input2" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.docx" required>
                        
                        <!-- État initial : Vide -->
                        <div id="upload-prompt2" class="flex flex-col items-center gap-2 transition-opacity duration-300">
                            <i class="fas fa-cloud-upload-alt text-[#CFBB30] text-4xl group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm font-medium text-[#CFBB30]">Cliquez pour parcourir ou glissez votre fichier ici</span>
                            <span class="text-xs text-gray-400">PDF, DOCX (Max 10Mo)</span>
                        </div>

                        <!-- État : Fichier sélectionné (Tampon) -->
                        <div id="upload-success2" class="hidden flex flex-col items-center gap-2 animate-bounce-short">
                            <div class="w-16 h-16 bg-[#16302B] rounded-full flex items-center justify-center border-2 border-[#CFBB30] shadow-lg">
                                <i class="fas fa-check text-[#CFBB30] text-2xl"></i>
                            </div>
                            <span id="file-name2" class="text-sm font-bold text-white font-serif">Document prêt</span>
                            <button type="button" onclick="resetUpload(event)" class="text-xs text-red-500 underline hover:text-red-700 z-20">Modifier le fichier</button>
                        </div>
                    </div>
                </div>
                <!-- <div>
                        <label class="block text-xs uppercase tracking-widest font-bold text-paper mb-3">Document d'engagement signé</label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="file" class="file-upload-input" accept=".pdf,.docx" required>
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-cloud-upload-alt text-primary text-3xl"></i>
                                <span class="text-sm font-medium text-primary">Cliquez pour parcourir ou glissez votre fichier ici</span>
                                <span class="text-xs text-gray-400">PDF ou Docx acceptés</span>
                            </div>
                        </div>
                </div> -->
            </div>

            <div class="bg-gray-50 p-6 rounded-xl border border-dashed border-gray-200">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" class="mt-1 mr-4 h-5 w-5 text-primary" required>
                    <span class="text-sm text-gray-600 italic">
                        Je confirme avoir lu l'intégralité de l'engagement et je donne ma parole d'honneur pour le respecter. Je comprends que tout manquement à ces règles entraînera mon retrait définitif du cercle.
                    </span>
                </label>
            </div>

            <div class="flex flex-col items-center">
                <button type="submit" name="c_lobola_engagement" class="btn-sign mt-8">
                    <i class="fas fa-pen-nib"></i>
                    Sceller mon Engagement
                </button>
                <p class="text-[10px] text-gray-400 mt-5 uppercase tracking-tighter">Accès immédiat à la bibliothèque après validation</p>
            </div>
        </form>
    </div>

    <!-- <div class="text-center mt-8">
        <a href="connexion.html" class="text-sm text-gray-400 hover:text-primary transition underline">Retour à la connexion</a>
    </div> -->
</div>

<script src="<?= ASSETS ?>js/modules/main.js"></script>
    