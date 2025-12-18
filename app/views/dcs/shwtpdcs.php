<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php';
    ?>
    
    <!-- Conteneur principal du contenu -->
    <div class="p-9 w-full mx-auto min-h-screen mt-2">
        
        <!-- En-tête de la page (Titre et Actions) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Détails du type de document</h1>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                        Configuration active
                    </span>
                    <span class="text-sm text-slate-400">•</span>
                    <span class="text-sm text-slate-500">Dernière modification le <?= Helper::formatDate2($typeDoc->updated_at) ?></span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="../edttpdcs/<?= $typeDoc->type_doc_id ?>" class="flex items-center space-x-2 bg-[var(--color-primary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150 transform hover:scale-[1.01]">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
            </div>
        </div>

        <!-- Grille de contenu -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne de Gauche (Informations Principales) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Carte : Informations Générales -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph ph-file-text text-blue-600"></i>
                            Informations Générales
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Libellé du document</label>
                                <p class="text-sm font-semibold text-slate-900"><?= $typeDoc->nom_type ?></p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Code de référence</label>
                                <p class="text-sm font-semibold text-slate-900"><?= $typeDoc->category ?></p>
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Description</label>
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    <?= $typeDoc->description ?> 
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Caractère obligatoire</label>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-<?= Helper::returnEstObligatoireStyle($typeDoc->est_obligatoire) ?>-500"></div>
                                    <p class="text-sm font-semibold text-slate-900"><?= $typeDoc->est_obligatoire ?></p>
                                </div>
                            </div>
                            <?php if($typeDoc->duree_validite_jours !== null): ?>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Validité</label>
                                    <p class="text-sm font-semibold text-slate-900"><?= $typeDoc->duree_validite_jours ?> jours</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Carte : Configuration Technique -->
                <!-- <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph ph-gear-six text-blue-600"></i>
                            Paramètres de soumission
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="flex items-start gap-4 p-4 rounded-lg bg-slate-50 border border-slate-100">
                                <i class="ph ph-images text-2xl text-slate-400"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Nombre de fichiers attendus</p>
                                    <p class="text-xs text-slate-500 mt-0.5">L'utilisateur doit soumettre 2 fichiers distincts (Recto et Verso).</p>
                                </div>
                                <span class="ml-auto font-bold text-blue-600">2</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-lg border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Formats autorisés</p>
                                    <div class="flex gap-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">PDF</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">JPG</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">PNG</span>
                                    </div>
                                </div>
                                <div class="p-4 rounded-lg border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Taille Max.</p>
                                    <p class="text-sm font-bold text-slate-900">5 MB par fichier</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- Colonne de Droite (Widgets et Actions rapides) -->
            <div class="space-y-6">
                
                <!-- Carte : Status & Stats -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4">État du déploiement</p>
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-3xl font-bold text-slate-900">88%</span>
                        <div class="px-2 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded uppercase">Optimal</div>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-slate-500">Taux d'acceptation</span>
                                <span class="text-slate-900">92%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-slate-500">Taux de rejet</span>
                                <span class="text-slate-900">8%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-red-400 h-1.5 rounded-full" style="width: 8%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste d'actions -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="divide-y divide-slate-100">
                        <!-- <button class="w-full flex items-center gap-3 px-5 py-4 text-left hover:bg-slate-50 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="ph ph-clock-counter-clockwise"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">Logs d'activité</span>
                                <span class="text-[10px] text-slate-400">Voir les modifications</span>
                            </div>
                            <i class="ph ph-caret-right ml-auto text-slate-300"></i>
                        </button>
                        
                        <button class="w-full flex items-center gap-3 px-5 py-4 text-left hover:bg-slate-50 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-all">
                                <i class="ph ph-copy"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">Dupliquer</span>
                                <span class="text-[10px] text-slate-400">Créer un type similaire</span>
                            </div>
                            <i class="ph ph-caret-right ml-auto text-slate-300"></i>
                        </button> -->
                        
                        <button class="w-full flex items-center gap-3 px-5 py-4 text-left hover:bg-red-50 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all">
                                <i class="fas fa-archive"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-red-600">Supprimer</span>
                                <span class="text-[10px] text-red-400">Action irréversible</span>
                            </div>
                            <i class="ph ph-caret-right ml-auto text-red-200"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>