<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <main class="flex-grow container mx-auto px-4 py-12">
        <div class="fade-in">
            <!-- Section Titre -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-primary/10 pb-6">
                <div class="max-w-2xl">
                    <h2 class="font-serif text-5xl text-primary mb-4">Bibliothèque Sacrée</h2>
                    <p class="text-gray-500 text-lg italic">"La sagesse ne s'apprend pas, elle se reconnaît." Explorez les enseignements audio du mois.</p>
                </div>
                <div class="bg-green-100 text-green-800 px-6 py-2 rounded-full text-sm font-bold flex items-center gap-2 mt-6 md:mt-0 shadow-sm border border-green-200">
                    <i class="fas fa-check-circle"></i> Engagement Actif • Décembre 2023
                </div>
            </div>

            <!-- Grille des Enseignements -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                
                <!-- Carte Enseignement 1 -->
                <div class="bg-paper rounded-2xl overflow-hidden card-hover transition-all duration-300 border border-gray-100 flex flex-col">
                    <div class="h-48 bg-primary/5 flex items-center justify-center relative group">
                        <i class="fas fa-om text-5xl text-primary group-hover:text-accent group-hover:scale-110 transition duration-500"></i>
                        <div class="absolute top-4 right-4 bg-primary text-paper px-3 py-1 rounded-full text-xs font-bold tracking-widest ">Méditation</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="font-serif text-2xl font-bold text-gray-200 mb-3 leading-snug">L'Essence de la Paix Intérieure</h3>
                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-4 uppercase tracking-tighter">
                            <span><i class="far fa-calendar-alt mr-1 text-accent"></i> 12 Oct 2023</span>
                            <span><i class="far fa-clock mr-1 text-accent"></i> 45 min</span>
                        </div>
                        <p class="text-gray-200 text-sm leading-relaxed mb-8 flex-grow">Une exploration profonde des mécanismes de l'ego et comment trouver le silence au milieu du chaos du monde moderne.</p>
                        <a href="enseignement-1.html" class="w-full bg-primary text-paper py-4 rounded-xl hover:bg-primaryLight transition shadow-lg flex items-center justify-center gap-3 font-bold group">
                            <span>Écouter l'Enseignement</span>
                            <i class="fas fa-play-circle text-accent text-xl group-hover:scale-110 transition"></i>
                        </a>
                    </div>
                </div>

                <!-- Carte Enseignement 2 -->
                <div class="bg-paper rounded-2xl overflow-hidden card-hover transition-all duration-300 border border-gray-100 flex flex-col">
                    <div class="h-48 bg-primary/5 flex items-center justify-center relative group">
                        <i class="fas fa-leaf text-5xl text-primary group-hover:text-accent group-hover:scale-110 transition duration-500"></i>
                        <div class="absolute top-4 right-4 bg-primary text-paper px-3 py-1 rounded-full text-xs font-bold tracking-widest ">Philosophie</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="font-serif text-2xl font-bold text-gray-200 mb-3 leading-snug">Les Racines de l'Ancrage</h3>
                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-4 uppercase tracking-tighter">
                            <span><i class="far fa-calendar-alt mr-1 text-accent"></i> 28 Oct 2023</span>
                            <span><i class="far fa-clock mr-1 text-accent"></i> 62 min</span>
                        </div>
                        <p class="text-gray-200 text-sm leading-relaxed mb-8 flex-grow">Pourquoi nous sentons-nous déconnectés ? Ce cours audio travaille sur le premier centre énergétique et notre lien à la Terre.</p>
                        <a href="enseignement-2.html" class="w-full bg-primary text-paper py-4 rounded-xl hover:bg-primaryLight transition shadow-lg flex items-center justify-center gap-3 font-bold group">
                            <span>Écouter l'Enseignement</span>
                            <i class="fas fa-play-circle text-accent text-xl group-hover:scale-110 transition"></i>
                        </a>
                    </div>
                </div>

                <!-- Carte Enseignement 3 -->
                <div class="bg-paper rounded-2xl overflow-hidden card-hover transition-all duration-300 border border-gray-100 flex flex-col">
                    <div class="h-48 bg-primary/5 flex items-center justify-center relative group">
                        <i class="fas fa-heart text-5xl text-primary group-hover:text-accent group-hover:scale-110 transition duration-500"></i>
                        <div class="absolute top-4 right-4 bg-primary text-paper px-3 py-1 rounded-full text-xs font-bold tracking-widest ">Cœur</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="font-serif text-2xl font-bold text-gray-200 mb-3 leading-snug">Le Pardon Radical</h3>
                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-4 uppercase tracking-tighter">
                            <span><i class="far fa-calendar-alt mr-1 text-accent"></i> 05 Nov 2023</span>
                            <span><i class="far fa-clock mr-1 text-accent"></i> 38 min</span>
                        </div>
                        <p class="text-gray-200 text-sm leading-relaxed mb-8 flex-grow">Le pardon n'est pas pour l'autre, mais pour soi. Libérez-vous du poids du passé grâce à cet enseignement transformateur.</p>
                        <a href="enseignement-3.html" class="w-full bg-primary text-paper py-4 rounded-xl hover:bg-primaryLight transition shadow-lg flex items-center justify-center gap-3 font-bold group">
                            <span>Écouter l'Enseignement</span>
                            <i class="fas fa-play-circle text-accent text-xl group-hover:scale-110 transition"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- TEMPLATES (Cachés, utilisés par JS) -->

    <!-- VUE 1: LOGIN -->
    <!-- <template id="tpl-login">
        <div class="flex flex-col items-center justify-center min-h-[60vh] fade-in">
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md border-t-4 border-primary">
                <div class="text-center mb-8">
                    <h2 class="font-serif text-3xl text-primary mb-2">Espace Membre</h2>
                    <p class="text-gray-500 italic">Accédez aux enseignements sacrés</p>
                </div>
                <form onsubmit="app.handleLogin(event)" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Identifiant</label>
                        <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="votre@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded hover:bg-primaryLight transition duration-300 font-semibold shadow-md">
                        Entrer dans le Sanctuaire
                    </button>
                </form>
                <div class="mt-6 text-center text-xs text-gray-400">
                    <p>L'accès est réservé aux membres engagés.</p>
                </div>
            </div>
        </div>
    </template> -->

    <!-- VUE 2: RENOUVELLEMENT ENGAGEMENT (BLOCAGE) -->
    <!-- <template id="tpl-engagement">
        <div class="max-w-3xl mx-auto mt-10 fade-in">
            <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
                <div class="bg-red-50 p-6 border-b border-red-100 flex items-center gap-4">
                    <div class="bg-red-100 p-3 rounded-full text-red-600">
                        <i class="fas fa-lock text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-red-800">Renouvellement Requis</h2>
                        <p class="text-red-600 text-sm">Votre engagement éthique a expiré. Veuillez le renouveler pour accéder aux enseignements.</p>
                    </div>
                </div>

                <div class="p-8 md:p-12 bg-[url('https://www.transparenttextures.com/patterns/cream-paper.png')]">
                    <h3 class="font-serif text-3xl text-center text-primary mb-8">Charte d'Engagement Spirituel</h3>
                    
                    <div class="prose max-w-none text-justify text-gray-700 mb-8 p-6 border border-primary/20 bg-white/50 rounded italic font-serif leading-relaxed">
                        <p>Je soussigné(e), en tant que membre de cette communauté :</p>
                        <p class="mt-4">- M'engage à respecter la confidentialité des enseignements partagés ici.</p>
                        <p>- M'engage à utiliser ces connaissances pour mon élévation personnelle et le bien d'autrui.</p>
                        <p>- Reconnais que cet accès est un privilège qui nécessite une pratique régulière et sincère.</p>
                        <p>- Renouvelle mon vœu de bienveillance envers le formateur et les autres membres.</p>
                        <p class="mt-6 text-right font-bold text-primary">Fait pour valoir ce que de droit.</p>
                    </div>

                    <form onsubmit="app.signContract(event)" class="flex flex-col items-center gap-6">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" required class="w-5 h-5 text-primary focus:ring-primary border-gray-300 rounded">
                            <span class="text-gray-800">Je lis, j'accepte et je signe cet engagement pour ce mois.</span>
                        </label>
                        
                        <div class="w-full h-px bg-gray-200 my-2"></div>

                        <button type="submit" class="bg-accent text-primary px-8 py-3 rounded-full font-bold hover:bg-yellow-500 transition shadow-lg flex items-center gap-2">
                            <i class="fas fa-file-signature"></i>
                            Signer et Accéder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template> -->

    <!-- VUE 3: DASHBOARD (LISTE AUDIOS) -->
    <!-- <template id="tpl-dashboard">
        <div class="fade-in">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b border-primary/10 pb-4">
                <div>
                    <h2 class="font-serif text-4xl text-primary">Enseignements</h2>
                    <p class="text-gray-500 mt-2">Explorez la bibliothèque des sagesses audio.</p>
                </div>
                <div class="bg-green-50 text-green-800 px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2 mt-4 md:mt-0">
                    <i class="fas fa-check-circle"></i> Engagement Actif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="audio-grid">
            </div>
        </div>
    </template> -->

    <!-- VUE 4: DÉTAIL AUDIO & Q&A -->
    <!-- <template id="tpl-audio-detail">
        <div class="fade-in max-w-4xl mx-auto">
            <button onclick="app.loadView('dashboard')" class="text-gray-500 hover:text-primary mb-6 flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Retour aux enseignements
            </button>

            <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-8">
                <div class="bg-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10 transform translate-x-10 -translate-y-10">
                        <i class="fas fa-om text-9xl"></i>
                    </div>
                    <div class="relative z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-2 py-1 rounded mb-2 inline-block" id="detail-category">Catégorie</span>
                        <h2 class="font-serif text-3xl md:text-4xl mb-2" id="detail-title">Titre de l'enseignement</h2>
                        <p class="opacity-80 flex items-center gap-4 text-sm">
                            <span><i class="far fa-calendar-alt mr-1"></i> <span id="detail-date">Date</span></span>
                            <span><i class="far fa-clock mr-1"></i> <span id="detail-duration">Durée</span></span>
                        </p>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                        <audio id="main-audio-player" class="w-full" controls controlsList="nodownload">
                            <source src="" type="audio/mpeg">
                            Votre navigateur ne supporte pas l'audio.
                        </audio>
                        <p class="text-xs text-center text-gray-400 mt-2"><i class="fas fa-shield-alt"></i> Téléchargement désactivé - Écoute en streaming uniquement</p>
                    </div>

                    <div class="prose max-w-none text-gray-200 mb-6">
                        <h4 class="font-bold text-primary mb-2">À propos de cet enseignement</h4>
                        <p id="detail-desc">Description...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <h3 class="font-serif text-2xl text-primary mb-6 flex items-center gap-3">
                    <i class="far fa-comments"></i> Questions & Réponses
                </h3>

                <form onsubmit="app.postQuestion(event)" class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Poser une question au formateur</label>
                    <textarea required id="question-input" rows="3" class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-primary outline-none mb-3" placeholder="Votre question sur cet enseignement..."></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary text-white px-5 py-2 rounded hover:bg-primaryLight transition text-sm">
                            Envoyer la question
                        </button>
                    </div>
                </form>

                <div class="space-y-6" id="qa-list">
                </div>
            </div>
        </div>
    </template> -->

<?php include APP_PATH . 'views/layouts/footer.php'; ?>