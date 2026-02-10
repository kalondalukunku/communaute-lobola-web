<?php 
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <main class="max-w-6xl mx-auto py-20 px-8">
    
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            <!-- Colonne Gauche: Présentation Mystique -->
            <div class="lg:col-span-5 flex flex-col items-center text-center lg:text-left lg:items-start">
                <div class="relative mb-10">
                    <!-- Cadre Aura -->
                    <div class="absolute inset-0 border border-[#D4AF37] rounded-full scale-110 opacity-20 animate-pulse"></div>
                    <div class="w-56 h-72 rounded-full overflow-hidden spirit-border relative z-10">
                        <img src="../../<?= $Enseignant->path_profile ?>" 
                            alt="Guide Spirituel" 
                            class="w-full h-full object-cover grayscale-[30%] transition-all duration-700">
                    </div>
                    <!-- Symbole Discret -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-[#0f1115] px-4 py-1 spirit-border rounded-full text-[#D4AF37] text-xs tracking-[0.3em] z-20">
                        GUIDE
                    </div>
                </div>

                <h1 class="font-serif text-5xl md:text-6xl text-white italic mb-4">
                    <?= $Enseignant->nom_complet ?>
                </h1>
                
                <p class="text-[#D4AF37] tracking-[0.2em] font-medium text-sm mb-8 uppercase">
                    Accompagnateur d'Éveil & Sagesse
                </p>

                <div class="space-y-6 text-gray-400 font-light leading-relaxed max-w-md">
                    <p>
                        "La véritable connaissance n'est pas ce que l'on apprend, mais ce que l'on redécouvre en soi-même."
                    </p>
                    <div class="h-px w-20 bg-[#D4AF37]/30 mx-auto lg:mx-0"></div>
                    <p class="text-sm italic">
                        Présent pour vous guider sur le chemin de la pleine conscience et de l'harmonie intérieure.
                    </p>
                </div>
                
                <div class="mt-12 flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="../add/<?= $Enseignant->enseignant_id ?>"
                    class="bg-transparent border border-[#D4AF37] text-[#D4AF37] px-8 py-3 rounded-full hover:bg-[#D4AF37] hover:text-black transition-all duration-500 text-sm tracking-widest font-medium">
                        TRANSMETTRE UNE SAGESSE
                        <i class="fa-solid fa-brain"></i>
                    </a>
                </div>
                
                <div class="mt-12 flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="../enseignements/<?= $Enseignant->enseignant_id ?>"
                    class="btn-sign2 bg-primary border border-[#D4AF37] px-8 py-3 rounded-full hover:bg-[#D4AF37] hover:text-black transition-all duration-500 text-sm tracking-widest font-medium">
                        MES ENSEIGNEMENTS
                        <i class="fa-solid fa-book-open-reader"></i>
                    </a>
                </div>
            </div>

            <!-- Colonne Droite: Détails & Vibrations -->
            <div class="lg:col-span-7 space-y-12">
                
                <!-- Section Biographie -->
                <section class="spirit-border bg-white/5 p-10 rounded-2xl backdrop-blur-sm">
                    <h3 class="font-serif text-2xl text-white mb-6">Cheminement</h3>
                    <div class="text-gray-300 font-light space-y-4 leading-relaxed">
                        <?= $Enseignant->biographie ?? "Une vie dédiée à l'exploration des profondeurs de l'âme et au partage des vérités universelles. À travers ses enseignements, il invite chacun à cultiver la paix intérieure et la présence." ?>
                    </div>
                </section>

                <!-- Grille de Rayonnement (Stats) -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="p-6 spirit-border rounded-xl text-center">
                        <span class="block text-2xl font-serif text-[#D4AF37]"><?= date('Y', strtotime($Enseignant->created_at)) ?></span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">Éveil Initial</span>
                    </div>
                    <div class="p-6 spirit-border rounded-xl text-center">
                        <span class="block text-2xl font-serif text-[#D4AF37]"><?= count($allEnseignements) ?></span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">Leçons Partagées</span>
                    </div>
                    <div class="p-6 spirit-border rounded-xl text-center col-span-2 md:col-span-1">
                        <span class="block text-2xl font-serif text-[#D4AF37]">Infinie</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">Bienveillance</span>
                    </div>
                </div>

                <!-- Contact & Liens -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-8 pt-8 border-t border-white/5">
                    <div class="flex items-center gap-6">
                        <div class="text-center md:text-left">
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Adresse Email</p>
                            <a href="mailto:<?= $Enseignant->email ?>" class="text-[#D4AF37] hover:text-white transition-colors underline underline-offset-8 decoration-1">
                                <?= $Enseignant->email ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <!-- Icônes de méditation ou réseaux -->
                        <!-- <div class="w-10 h-10 rounded-full spirit-border flex items-center justify-center cursor-pointer text-[#D4AF37] hover:bg-[#D4AF37]/10">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                        </div> -->
                        <!-- <div class="w-10 h-10 rounded-full spirit-border flex items-center justify-center cursor-pointer text-[#D4AF37] hover:bg-[#D4AF37]/10">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
                        </div> -->
                    </div>
                </div>

            </div>
        </div>
    </main>

</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>