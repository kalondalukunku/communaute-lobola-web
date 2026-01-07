<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

<main class="flex-grow container mx-auto px-4 py-8 md:py-12 max-w-5xl">
        <div class="fade-in">
            
            <!-- Breadcrumbs & Info -->
            <div class="mb-8">
                <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-primary font-bold mb-2">
                    <span class="bg-secondary text-dark px-2 py-0.5 rounded">Cours Audio</span>
                    <span class="text-gray-400">•</span>
                    <span>Série : Paix & Méditation</span>
                </div>
                <h2 class="font-serif text-4xl md:text-5xl text-primary mb-4">L'Essence de la Paix Intérieure</h2>
                <p class="text-gray-500 italic">"Regardez en vous-même, non pas avec jugement, mais avec curiosité."</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Colonne Gauche : Lecteur et Description -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Lecteur Audio -->
                    <div class="audio-player-container">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <div class="relative w-32 h-32 flex-shrink-0">
                                <div class="absolute inset-0 bg-primary rounded-2xl animate-pulse"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-headphones text-4xl text-dark"></i>
                                </div>
                            </div>
                            
                            <div class="flex-grow w-full">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">En cours de lecture</p>
                                        <h4 class="font-bold text-lg text-primary">Enseignement 01 - Essence</h4>
                                    </div>
                                    <span class="text-sm font-bold text-primary">35% écouté</span>
                                </div>
                                
                                <div class="progress-bar mb-4">
                                    <div class="progress-filled"></div>
                                </div>
                                
                                <div class="flex justify-between items-center text-xs text-gray-400 font-mono mb-6">
                                    <span>15:42</span>
                                    <span>45:00</span>
                                </div>
                                
                                <div class="flex items-center justify-center gap-8">
                                    <button class="text-gray-400 hover:text-secondary"><i class="fas fa-undo-alt text-xl"></i></button>
                                    <button class="control-btn"><i class="fas fa-play text-xl ml-1"></i></button>
                                    <button class="text-gray-400 hover:text-secondary"><i class="fas fa-redo-alt text-xl"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description de l'enseignement -->
                    <div class="bg-paper p-8 rounded-2xl color-border shadow-sm">
                        <h5 class="font-serif text-2xl text-primary mb-4">À propos de cet enseignement</h5>
                        <div class="prose text-gray-200 leading-relaxed space-y-4">
                            <p>
                                Dans cette session, nous explorons la distinction fondamentale entre la "paix de circonstance" (qui dépend des événements extérieurs) et la "paix d'essence" (qui réside en nous en tout temps).
                            </p>
                            <p>
                                Points abordés :
                            </p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Identifier le bruit mental incessant.</li>
                                <li>La technique du témoin silencieux.</li>
                                <li>Désamorcer les réactions émotionnelles automatiques.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section Questions / Réponses -->
                    <div class="space-y-6">
                        <h5 class="font-serif text-2xl text-secondary">Espace d'Échange</h5>
                        <div class="bg-paper p-6 rounded-2xl color-border shadow-sm flex gap-4">
                            <div class="w-10 h-10 bg-paper rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-primary text-sm"></i>
                            </div>
                            <div class="flex-grow">
                                <textarea class="color-border" placeholder="Une question ou un partage sur cet enseignement ?"></textarea>
                                <div class="mt-4 flex justify-end">
                                    <button class="btn-action">Envoyer ma question</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne Droite : Notes Personnelles & Ressources -->
                <div class="space-y-8">
                    
                    <!-- Notes Personnelles -->
                    <div class="bg-secondary text-white p-8 rounded-2xl shadow-xl">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fas fa-pen-fancy text-primary"></i>
                            <h5 class="font-serif text-2xl">Vos Notes</h5>
                        </div>
                        <p class="text-paper text-sm mb-4 italic">Ces notes sont privées et ne sont visibles que par vous.</p>
                        <textarea class="bg-paper placeholder:text-white/20 color-border mb-4" placeholder="Notez ici vos ressentis, vos prises de conscience..." style="textarea::focus { color: var(--paper) !important; border: 1px solid rgba(207, 187, 48, 0.2) !important; }" rows="8">

                        </textarea>
                        <button class="btn-action w-full">
                            Enregistrer mes notes
                        </button>
                    </div>

                    <!-- Ressources Supplémentaires -->
                    <div class="bg-paper p-8 rounded-2xl color-border shadow-sm">
                        <h5 class="font-serif text-xl text-primary mb-6">Ressources</h5>
                        <div class="space-y-4">
                            <a href="#" class="flex items-center justify-between p-3 rounded-xl hover:bg-paper transition border border-transparent hover:border-primary/20">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file-pdf text-red-400"></i>
                                    <span class="text-sm text-gray-200 font-medium">Guide de pratique (PDF)</span>
                                </div>
                                <i class="fas fa-download text-xs text-gray-300"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 rounded-xl hover:bg-paper transition border border-transparent hover:border-primary/20">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-book-open text-blue-400"></i>
                                    <span class="text-sm text-gray-200 font-medium">Textes de référence</span>
                                </div>
                                <i class="fas fa-external-link-alt text-xs text-gray-300"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>