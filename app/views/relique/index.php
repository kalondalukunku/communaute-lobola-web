<?php 
    $title = $Series->nom ?? SITE_NAME;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

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
                            <p class="text-[11px] text-gray-400 mb-2 italic">Nfumu Kimbangu</p>
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
                            <p class="text-[11px] text-gray-400 mb-2 italic">Nkumu Elim'e Nzale</p>
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
                            <p class="text-[11px] text-gray-400 mb-2 italic">Kalala Omotunde</p>
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
                            <p class="text-[11px] text-gray-400 mb-2 italic">Mbuta Sankara</p>
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
                            <p class="text-[11px] text-gray-400 mb-2 italic">Mbuta Lumumba</p>
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
                        <h3 class="text-xl font-bold font-serif text-white">Photos Objet Cultuel</h3>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Les photos des objets cultuels</p>
                    </div>
                </div>

                <!-- Grille de Photos -->
                <div class="grid grid-cols-2 gap-3 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                    <!-- Photo Item 1 -->
                    <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                        <div class="aspect-square rounded-xl overflow-hidden mb-3">
                            <img src="<?= ASSETS ?>ressources/Photos_Reliques/relique2.jpeg?w=400&h=400&fit=crop" 
                                alt="Document 2" 
                                class="w-full h-full object-cover">
                        </div>
                        <div class="px-1 pb-1">
                            <p class="text-[11px] text-gray-400 mb-2 italic">Mpeve (Mpea, Mpèbè) et Mbidi (Accent, Ubani)</p>
                            <a href="<?= ASSETS ?>ressources/Photos_Reliques/relique2.jpeg" 
                            download="Mpeve_et_Mbidi.jpg" 
                            class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                <i class="fa-solid fa-download"></i>
                                Télécharger
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                        <div class="aspect-square rounded-xl overflow-hidden mb-3">
                            <img src="<?= ASSETS ?>ressources/Photos_Reliques/relique1.jpeg?w=400&h=400&fit=crop" 
                                alt="Document 2" 
                                class="w-full h-full object-cover">
                        </div>
                        <div class="px-1 pb-1">
                            <p class="text-[11px] text-gray-400 mb-2 italic">Mpeve (Mpea, Mpèbè)</p>
                            <a href="<?= ASSETS ?>ressources/Photos_Reliques/relique1.jpeg" 
                            download="Mpeve.jpg" 
                            class="flex items-center justify-center gap-2 w-full py-2 bg-primary hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition-colors">
                                <i class="fa-solid fa-download"></i>
                                Télécharger
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex flex-col bg-white/5 rounded-2xl p-2 border border-white/5">
                        <div class="aspect-square rounded-xl overflow-hidden mb-3">
                            <img src="<?= ASSETS ?>ressources/Photos_Reliques/relique3.jpeg?w=400&h=400&fit=crop" 
                                alt="Document 2" 
                                class="w-full h-full object-cover">
                        </div>
                        <div class="px-1 pb-1">
                            <p class="text-[11px] text-gray-400 mb-2 italic">Mpeve (Mpea, Mpèbè)</p>
                            <a href="<?= ASSETS ?>ressources/Photos_Reliques/relique3.jpeg" 
                            download="Mpeve.jpg" 
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

<script src="<?= ASSETS ?>js/modules/player.js?v=<?= APP_VERSION ?>"></script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>