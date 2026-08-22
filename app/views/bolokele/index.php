<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <?php if(Session::get('enseignant') 
        || (Session::get('membre')['niveau_initiation'] === ARRAY_TYPE_NIVEAU_INITIATION[3] 
        && isset($paiedMembre->payment_status) 
        && $paiedMembre->payment_status  === ARRAY_PAYMENT_STATUS[1]
        && Session::get('membre')['bolokele'] === '1')
    ): ?>
        <main class="flex-grow container mx-auto px-4 py-12">
            <div class="fade-in">
                <!-- Section Titre -->
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-primary/10 pb-6 my-auto gap-6">
                    <div class="max-w-3xl">
                        <h2 class="font-serif text-3xl text-primary mb-4">Bibliothèque Sacrée de l'enseignement avancé : <span class="text-purple-400">Bolokele</span></h2>
                        <p class="text-gray-500 text-sm italic">Découvrez les enseignements hautement spirituels enseignés par les maîtres LOBOLA LO ILONDO et reservés uniquement aux membres engagés.</p>
                    </div>
                    <!-- Compteur date de fin de l abonnement -->
                    <div class="p-4 rounded-2xl shadow-xl border border-gray-700 max-w-md w-75">
                        <!-- <h2 class="text-xl font-bold mb-4 text-gray-200 border-b border-gray-700 pb-2">Statut de votre abonnement</h2> -->

                        <!-- Votre code d'origine adapté avec un ID pour le temps restant -->
                        <div class="text-right pb-1">
                            <span class="text-gray-400 text-xs">Votre accès est valide jusqu'au :</span>
                            <!-- La date PHP est injectée ici. On ajoute un attribut data-date pour que le JS sache quelle date cibler -->
                            <p class="text-primary font-semibold text-sm" id="expiry-date" data-date="<?= date('Y-m-d H:i:s', strtotime($paiedMembre->payment_prochain)) ?>">
                                <?= date('d/m/Y à H:i:s', strtotime($paiedMembre->payment_prochain)) ?>
                            </p>
                            <!-- Zone d'affichage du compte à rebours en direct -->
                            <div class="mt-2 text-xs font-mono bg-gray-900 px-3 py-1.5 rounded text-amber-400 inline-block border border-gray-700">
                                Temps restant : <span id="countdown-timer" class="font-bold">Calcul en cours...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grille des Enseignements -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                    <?php foreach ($Series as $item): ?>
                        <?php 
                            // Vérification si l'enseignement a moins de 24h
                            $isNew = false;
                            if (!empty($item->updated_at)) {
                                $createdAt = new DateTime($item->updated_at);
                                $now = new DateTime();
                                $diff = $now->diff($createdAt);
                                // Vérifie si la différence totale en heures est < 24
                                $hours = ($diff->days * 24) + $diff->h;
                                if ($hours < 24 && $diff->invert == 1) {
                                    $isNew = true;
                                }
                            }
                        ?>
                        <div class="audio-card bg-gradient-to-b from-[#19012b] to-[#0c0115] border border-white/5 rounded-2xl group relative overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.5)] hover:shadow-primary/20 flex flex-col h-full">
                            
                            <!-- Effet de lueur interne au survol -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-primary/0 via-primary/0 to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                            <!-- Contenu de la carte -->
                            <div class="p-6 sm:p-7 flex flex-col flex-grow relative z-10">
                                <!-- Catégorie & Vues -->
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20">
                                            <i class="fas fa-headphones text-[10px] text-primary"></i>
                                        </div>
                                        <span class="text-gray-400 text-[9px] uppercase tracking-widest font-semibold truncate max-w-[120px]"><?= $item->nom !== null ? htmlspecialchars($item->nom) : '' ?></span>
                                    </div>
                                    <?php if ($isNew): ?>
                                        <div class="flex items-center gap-1.5 bg-primary/10 border border-primary/30 text-primary text-[8px] font-bold uppercase px-3 py-1.5 rounded-full backdrop-blur-md shadow-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                            Nouveau
                                        </div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-[10px] font-mono px-2.5 py-1 rounded-md border border-white/5">
                                            <i class="far fa-eye text-[9px] opacity-70"></i>
                                            <?php $vues = $VuesModel->countAll(['serie_id' => $item->serie_id]); ?>
                                            <?= $vues; ?> vue<?= $vues > 1 ? 's' : '' ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Titre & Description -->
                                <h3 class="text-lg sm:text-xl font-bold text-white leading-snug line-clamp-2 mb-3 group-hover:text-primary transition-colors duration-300 flex-grow">
                                    <?= Helper::textTruncate($item->nom, 35) ?>
                                </h3>

                                <p class="text-gray-400 text-xs sm:text-sm mb-6 flex items-center gap-2">
                                    <i class="fas fa-layer-group text-white/20 text-[10px]"></i>
                                    Série de <strong class="text-gray-200"><?= count($item->teachings) ?></strong> enseignement<?= count($item->teachings) > 1 ? 's' : '' ?>
                                </p>
                                
                                <!-- Séparateur discret -->
                                <div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-5"></div>

                                <!-- Footer de la carte : Date & Action -->
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-500 uppercase tracking-wider mb-0.5">Dernier ajout</span>
                                        <span class="text-[11px] text-gray-300 font-medium"><?= Helper::formatDate($item->updated_at) ?></span>
                                    </div>

                                        <a href="../../bolokele/show/<?= $item->serie_id ?>?ssd=<?= $item->session_id ?>" class="group/btn inline-flex items-center justify-center gap-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-primary/10 border border-primary/20 text-primary hover:bg-primary hover:text-black px-4 py-2 rounded-lg transition-all duration-300 shadow-[0_0_15px_rgba(0,0,0,0)] hover:shadow-primary/30">
                                            Écouter <i class="fas fa-play text-[9px] transition-transform duration-300 group-hover/btn:scale-110"></i>
                                        </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </main>
    <?php elseif(Session::get('membre')['bolokele'] === '2' || $paiedMembre->payment_prochain > date('Y-m-d')): ?>
        <section class="flex items-center justify-center px-4 py-20 animate-fade-in-up">
            <div class="w-full max-w-md rounded-3xl border border-slate-200/60 shadow-2xl shadow-primary glass-effect p-8 sm:p-10 text-center relative overflow-hidden">
                
                <!-- Ligne décorative accentuant le haut de la carte -->
                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

                <!-- Icône décorative -->
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-secondary text-primary">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>

                <h2 class="font-serif text-3xl md:text-4xl text-primary mb-4 tracking-tight">Reabonnement</h2>
                
                <p class="text-slate-600 leading-relaxed mb-8 px-2">
                    Veuillez renouveler votre engagement en effectuant le paiement pour accéder aux enseignements.
                </p>

                <!-- Bouton d'action optimisé -->
                <a href="pay/afrik_pay/<?= Session::get('membre')['member_id'] ?>" 
                class="group relative inline-flex items-center justify-center w-full px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 font-bold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary/20">
                    <span>Renouveler mon accès</span>
                    <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </section>
    <?php else: ?>
        <section class="flex items-center justify-center px-4 py-20 animate-fade-in-up">
            <div class="w-full max-w-md rounded-3xl border border-slate-200/60 shadow-2xl shadow-primary/50 glass-effect p-8 sm:p-10 text-center relative overflow-hidden">
                
                <!-- Ligne décorative accentuant le haut de la carte -->
                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

                <!-- Icône de sécurité -->
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-secondary text-primary">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>

                <h2 class="font-serif text-3xl md:text-4xl text-primary mb-4 tracking-tight">Accès Restreint</h2>
                
                <p class="text-slate-600 leading-relaxed mb-8 px-2">
                    Pour accéder à ce contenu, veuillez effectuer une demande d'engagement.
                </p>

                <!-- Bouton d'action optimisé -->
                <a href="../../membre/engagement/<?= Session::get('membre')['member_id'] ?>" 
                class="group relative inline-flex items-center justify-center w-full px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 font-bold transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary/20">
                    <span>S'engager pour accéder</span>
                    <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </section>
    <?php endif; ?>

    <script>
        // Fonction pour initialiser et mettre à jour le compte à rebours
        function initCountdown() {
            const dateElement = document.getElementById('expiry-date');
            const timerElement = document.getElementById('countdown-timer');

            if (!dateElement || !timerElement) return;

            // Récupérer la date cible depuis l'attribut data-date (format standard YYYY-MM-DD HH:mm:ss ou ISO)
            // Vous pouvez remplacer la valeur de data-date dynamiquement via PHP : data-date="<?= $paiedMembre->payment_prochain ?>"
            const targetDateStr = dateElement.getAttribute('data-date');
            const targetTime = new Date(targetDateStr.replace(' ', 'T')).getTime();

            function updateTimer() {
                const now = new Date().getTime();
                const difference = targetTime - now;

                if (difference <= 0) {
                    timerElement.textContent = "Expiré";
                    timerElement.classList.remove('text-amber-400');
                    timerElement.classList.add('text-red-500');
                    clearInterval(intervalId);
                    return;
                }

                // Calculs du temps restant (jours, heures, minutes, secondes)
                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                // Formater l'affichage proprement
                let timeString = "";
                if (days > 0) {
                    timeString += `${days}j `;
                }
                timeString += `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;

                timerElement.textContent = timeString;
            }

            // Exécuter immédiatement puis toutes les secondes
            updateTimer();
            const intervalId = setInterval(updateTimer, 1000);
        }

        // Lancer le script au chargement de la page
        document.addEventListener('DOMContentLoaded', initCountdown);
    </script>
<?php include APP_PATH . 'views/layouts/footer.php'; ?>