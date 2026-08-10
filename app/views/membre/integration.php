<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'templates/alertView.php'; 
    $paymentMode = $paymentMode ?? false;
    $paymentReturnMode = $paymentReturnMode ?? false;
?>

<div class="container mx-auto py-10 px-4 md:px-0 flex justify-center">
    <div class="document-container fade-in w-full max-w-5xl rounded-[2rem] border border-gray-200/80 bg-white/95 p-6 shadow-[0_20px_80px_rgba(0,0,0,0.08)] backdrop-blur-xl md:p-10">
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary shadow-lg shadow-primary/10">
                <i class="fas fa-universal-access text-2xl"></i>
            </div>
            <h1 class="mt-4 font-serif text-3xl text-primary md:text-4xl">
                <?= $paymentMode || $paymentReturnMode ? 'Paiement USSD KPAY' : 'Formulaire d’Intégration' ?>
            </h1>
            <p class="mt-2 text-[10px] uppercase tracking-[0.35em] text-gray-500">
                <?= $paymentMode || $paymentReturnMode ? ($description ?? 'Abonnement sécurisé de 20 USD') : 'Portail de Candidature à la Communauté LOBOLA' ?>
            </p>
        </div>

        <?php if ($paymentMode || $paymentReturnMode): ?>
            <div class="rounded-[2rem] border border-primary/20 bg-gradient-to-br from-primary/10 via-white to-primary/5 p-6 shadow-sm md:p-8">
                <?php if ($paymentMode): ?>
                    <?php if (!empty($errorMessage)): ?>
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($successMessage)): ?>
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                            <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= htmlspecialchars(BASE_URL . '/pay/afrik_pay', ENT_QUOTES, 'UTF-8'); ?>" class="space-y-6">
                        <input type="hidden" name="memberId" value="<?= htmlspecialchars($memberId ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        <input type="hidden" name="member_country" value="<?= htmlspecialchars($memberCountry ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        <input type="hidden" name="amount" value="20" />

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="group md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Numéro de téléphone</label>
                                <input type="text" name="phoneNumber" required value="<?= htmlspecialchars($phoneNumber ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Ex: 237653456789" />
                            </div>
                            <div class="group md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Opérateur</label>
                                <select name="provider" required class="w-full cursor-pointer appearance-none border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary">
                                    <option value="" disabled selected>Sélectionnez votre opérateur</option>
                                    <?php foreach ($providerCountries ?? [] as $countryName => $countryData): ?>
                                        <optgroup label="<?= htmlspecialchars($countryName, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php foreach ($countryData['providers'] as $provider): ?>
                                                <option value="<?= htmlspecialchars($provider, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($provider, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-gray-200 bg-white/80 p-5 shadow-sm">
                            <p class="text-sm text-gray-700">Montant du paiement : <span class="font-semibold text-primary">20 USD</span></p>
                            <p class="mt-2 text-sm text-gray-500">Le paiement sera initié via KPAY après validation du pays et du fournisseur.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-black transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20">
                                <i class="fas fa-credit-card mr-2"></i>Payer par USSD
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="rounded-[2rem] border border-gray-200 bg-white/80 p-8 text-center shadow-sm">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full <?= (strtoupper((string) ($status ?? 'FAILURE')) === 'SUCCESS') ? 'bg-primary' : 'bg-rose-500'; ?> text-white">
                            <?php if ((strtoupper((string) ($status ?? 'FAILURE')) === 'SUCCESS')): ?>
                                <i class="fas fa-check text-2xl"></i>
                            <?php else: ?>
                                <i class="fas fa-times text-2xl"></i>
                            <?php endif; ?>
                        </div>
                        <h2 class="font-serif text-2xl text-gray-900">
                            <?= (strtoupper((string) ($status ?? 'FAILURE')) === 'SUCCESS') ? 'Abonnement confirmé' : 'Paiement échoué' ?>
                        </h2>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-gray-600">
                            <?= (strtoupper((string) ($status ?? 'FAILURE')) === 'SUCCESS') ? 'Votre abonnement a été validé avec succès. Merci pour votre paiement.' : 'Le paiement n’a pas abouti. Vous pouvez réessayer ou contacter l’assistance.' ?>
                        </p>
                        <div class="mt-6 flex justify-center">
                            <a href="<?= htmlspecialchars(BASE_URL . '/', ENT_QUOTES, 'UTF-8'); ?>" class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-black transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20">
                                <?= (strtoupper((string) ($status ?? 'FAILURE')) === 'SUCCESS') ? 'Retour à l’accueil' : 'Réessayer' ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php if (!$isInvited): ?>
            <div class="rounded-3xl border border-primary/20 bg-primary/5 p-8 text-center">
                <h2 class="font-serif text-2xl text-primary">Accès réservé</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-gray-600">Cette demande d’intégration n’est ouverte qu’aux personnes invitées. Merci d'accéder avec le lien d’invitation partagé par un membre de la communauté.</p>
            </div>
        <?php else: ?>

            <div id="integration-intro" class="rounded-[2rem] border border-primary/20 bg-gradient-to-br from-primary/10 via-white to-primary/5 p-6 shadow-sm md:p-8">
                <div class="max-w-3xl">
                    <p class="text-[10px] font-black uppercase tracking-[0.35em] text-primary">Avant de commencer</p>
                    <h2 class="mt-3 font-serif text-2xl text-gray-900 md:text-3xl">Quelques règles essentielles de la communauté</h2>
                    <p class="mt-3 text-sm leading-7 text-gray-600">Avant de déposer votre candidature, merci de prendre connaissance des règles de base qui encadrent l’accueil, le respect mutuel et le cheminement au sein du sanctuaire.</p>
                </div>

                <div class="mt-6 rounded-[1.5rem] border border-gray-200 bg-white/80 p-5 shadow-sm">
                    <ol class="space-y-3 text-sm leading-7 text-gray-700">
                        <li class="flex gap-3"><span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-[11px] font-bold text-primary">1</span><span><strong>Respecter la Maât</strong> et les principes sacrés de la spiritualité Kamit.</span></li>
                        <li class="flex gap-3"><span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-[11px] font-bold text-primary">2</span><span><strong>Respecter les Statuts</strong>, auquel j’ai pris connaissance.</span></li>
                        <li class="flex gap-3"><span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-[11px] font-bold text-primary">3</span><span><strong>Respecter le Règlement Intérieur</strong>, dont j’ai reçu et pris connaissance d’un exemplaire.</span></li>
                        <li class="flex gap-3"><span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-[11px] font-bold text-primary">4</span><span><strong>Accomplir les initiations à la Maât</strong>, selon les sessions proposées sur la plateforme dédiée.</span></li>
                        <li class="flex gap-3"><span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-[11px] font-bold text-primary">5</span><span><strong>Participer activement à la vie de la communauté</strong> dans l’esprit Ubuntu et la solidarité Kamit.</span></li>
                    </ol>
                    <p class="mt-5 text-sm leading-7 text-gray-700 italic">Je reconnais que tout acte posé a un salaire et je m’engage à marcher dans la Vérité, la Discipline et la Responsabilité.</p>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">En cliquant sur le bouton ci-dessous, vous confirmez avoir pris connaissance de ces règles.</p>
                    <button type="button" id="start-integration-btn" class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-black transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20">
                        <i class="fas fa-arrow-right mr-2"></i>J’accepte et commencer
                    </button>
                </div>
            </div>

            <div id="integration-form-wrapper" class="hidden space-y-6">
                <div class="mb-8">
                    <div class="mb-4 flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400">
                        <span class="step-label text-primary">Identité</span>
                        <span class="step-label">Motivation</span>
                        <span class="step-label">Contact</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="step-dot h-2.5 flex-1 rounded-full bg-primary transition-all"></div>
                        <div class="step-dot h-2.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                        <div class="step-dot h-2.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                    </div>
                </div>

                <form id="integration-form" method="post" class="space-y-6" enctype="multipart/form-data">
                <input type="hidden" name="c_lobola_integration" value="1">

                <section class="step-panel block rounded-3xl border border-gray-100 bg-gray-50/70 p-5 transition-all duration-300 md:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">1</span>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.25em] text-gray-800">Identité & Origines</h3>
                            <p class="text-xs text-gray-500">Quelques informations essentielles pour votre dossier.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Nom complet (Nom du KAMA)</label>
                            <input type="text" name="nom_postnom" value="<?= Helper::getData($_POST, 'nom_postnom') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Ex: Mukendi Kitenge" required>
                        </div>
                        <div class="group relative">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Genre</label>
                            <select name="sexe" class="w-full cursor-pointer appearance-none border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" required>
                                <option value="" disabled selected>Sélectionnez votre genre...</option>
                                <?php foreach(ARRAY_TYPE_SEXE as $sexe): ?>
                                    <option value="<?= $sexe ?>" <?= Helper::getSelectedValue('sexe', $sexe) ?>><?= $sexe ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down pointer-events-none absolute bottom-3 right-0 text-[10px] text-gray-400"></i>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Date de naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="<?= Helper::getData($_POST, 'date_naissance') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" required>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Domaine d'étude</label>
                            <input type="text" name="domaine_etude" value="<?= Helper::getData($_POST, 'domaine_etude') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Ex: Science informatique" required>
                        </div>
                    </div>
                </section>

                <section class="step-panel hidden rounded-3xl border border-gray-100 bg-gray-50/70 p-5 transition-all duration-300 md:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">2</span>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.25em] text-gray-800">Cheminement Spirituel</h3>
                            <p class="text-xs text-gray-500">Expliquez votre motivation et votre niveau de préparation.</p>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="group relative md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Niveau d'initiation</label>
                            <select name="niveau_initiation" class="w-full cursor-pointer appearance-none border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" required>
                                <option value="" disabled selected>Sélectionnez votre niveau d'initiation</option>
                                <?php foreach(ARRAY_TYPE_NIVEAU_INITIATION as $niveau): ?>
                                    <option value="<?= $niveau ?>" <?= Helper::getSelectedValue('niveau_initiation', $niveau) ?>><?= $niveau ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down pointer-events-none absolute bottom-3 right-0 text-[10px] text-gray-400"></i>
                        </div>
                        <div class="group md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Pourquoi souhaitez-vous rejoindre la communauté ?</label>
                            <textarea name="motivation" rows="4" class="w-full resize-none border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Partagez vos motivations profondes..." required><?= Helper::getData($_POST, 'motivation') ?></textarea>
                        </div>
                    </div>
                </section>

                <section class="step-panel hidden rounded-3xl border border-gray-100 bg-gray-50/70 p-5 transition-all duration-300 md:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">3</span>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.25em] text-gray-800">Localisation & Contact</h3>
                            <p class="text-xs text-gray-500">Finalisez vos coordonnées et ajoutez votre photo.</p>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="group relative">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Votre nationalité</label>
                            <select id="select-nationalite" name="nationalite" class="w-full cursor-pointer appearance-none border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" required>
                                <option value="" disabled selected>Sélectionnez votre nationnalité</option>
                                <?php foreach($allPays as $pays): ?>
                                    <option value="<?= $pays->nationalite ?>" <?= Helper::getSelectedValue('nationalite', $pays->nationalite) ?>><?= $pays->nationalite ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down pointer-events-none absolute bottom-3 right-0 text-[10px] text-gray-400"></i>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Lieu de résidence (Adresse)</label>
                            <input type="text" name="adresse" value="<?= Helper::getData($_POST, 'adresse') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Commune, Quartier..." required>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Adresse Email</label>
                            <input type="email" name="email" value="<?= Helper::getData($_POST, 'email') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="exemple@gmail.com" required>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">WhatsApp / Appel</label>
                            <input type="tel" name="phone" value="<?= Helper::getData($_POST, 'phone') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="+243 8*******" required>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Nom de la personne qui vous invite</label>
                            <input type="text" name="inviter_nom" value="<?= Helper::getData($_POST, 'inviter_nom') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Ex: Makasi" required>
                        </div>
                        <div class="group">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Téléphone de l'invitant</label>
                            <input type="tel" name="inviter_phone" value="<?= Helper::getData($_POST, 'inviter_phone') ?>" class="w-full border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="+243 8*******" required>
                        </div>
                        <div class="group md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Où avez-vous connu notre communauté ?</label>
                            <textarea name="ou_connu" rows="3" class="w-full resize-none border-b border-gray-200 bg-transparent py-2 text-sm outline-none transition focus:border-primary" placeholder="Dites-nous comment vous avez découvert notre communauté..." required><?= Helper::getData($_POST, 'ou_connu') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-8 rounded-3xl border border-dashed border-gray-200 bg-white/70 p-6">
                        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Photo de profil</h4>
                                <p class="mt-1 text-sm text-gray-500">Ajoutez une photo nette pour votre future carte de membre.</p>
                            </div>
                            <div class="relative group">
                                <input type="file" name="photo_file" id="photo-input" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" accept="image/*" required>
                                <div id="photo-preview-container" class="flex h-32 w-32 items-center justify-center overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition group-hover:border-primary md:h-36 md:w-36">
                                    <div id="preview-placeholder" class="text-center p-4">
                                        <i class="fas fa-camera mb-2 text-2xl text-primary"></i>
                                        <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-gray-400">Ajouter</p>
                                    </div>
                                    <img id="image-display" src="" alt="Aperçu" class="hidden h-full w-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 pt-2 md:flex-row md:items-center md:justify-between">
                    <button type="button" id="prev-step" class="hidden rounded-full border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">Précédent</button>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center md:ml-auto">
                        <button type="button" id="save-progress" disabled class="rounded-full border border-gray-200 px-6 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">Sauvegarder</button>
                        <button type="button" id="next-step" class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-black transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20">Suivant</button>
                        <button type="submit" id="submit-step" class="hidden rounded-full bg-primary px-6 py-3 text-sm font-semibold text-black transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20" name="c_lobola_integration">
                            <i class="fas fa-paper-plane mr-2"></i>Envoyer ma candidature
                        </button>
                    </div>
                </div>
                <p id="save-status" class="mt-2 text-sm text-green-600"></p>
            </form>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script src="<?= ASSETS ?>js/modules/main2.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const panels = Array.from(document.querySelectorAll('.step-panel'));
        const dots = Array.from(document.querySelectorAll('.step-dot'));
        const labels = Array.from(document.querySelectorAll('.step-label'));
        const nextBtn = document.getElementById('next-step');
        const prevBtn = document.getElementById('prev-step');
        const submitBtn = document.getElementById('submit-step');
        const saveBtn = document.getElementById('save-progress');
        const form = document.getElementById('integration-form');
        const introScreen = document.getElementById('integration-intro');
        const formWrapper = document.getElementById('integration-form-wrapper');
        const startBtn = document.getElementById('start-integration-btn');

        const inputDate = document.getElementById('date_naissance');
        const aujourdhui = new Date();
        
        // On calcule l'année maximum autorisée (Année actuelle - 14)
        const anneeMax = aujourdhui.getFullYear() - 14;
        const mois = String(aujourdhui.getMonth() + 1).padStart(2, '0'); // Les mois vont de 0 à 11 en JS
        const jour = String(aujourdhui.getDate()).padStart(2, '0');

        // Format attendu par l'input date : YYYY-MM-DD
        const dateMaxAutorisee = `${anneeMax}-${mois}-${jour}`;

        // On applique la restriction à l'input et on garde le champ vide au chargement
        if (inputDate) {
            inputDate.setAttribute('max', dateMaxAutorisee);
            if (!inputDate.value) {
                inputDate.value = '';
            }
        }

        if (!panels.length || !nextBtn || !prevBtn || !submitBtn || !form || !saveBtn) {
            return;
        }

        let currentStep = 0;

        function renderStep() {
            panels.forEach((panel, index) => {
                panel.classList.toggle('hidden', index !== currentStep);
            });

            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-primary', index <= currentStep);
                dot.classList.toggle('bg-gray-200', index > currentStep);
            });

            labels.forEach((label, index) => {
                label.classList.toggle('text-primary', index === currentStep);
                label.classList.toggle('text-gray-400', index !== currentStep);
            });

            prevBtn.classList.toggle('hidden', currentStep === 0);
            nextBtn.classList.toggle('hidden', currentStep === panels.length - 1);
            submitBtn.classList.toggle('hidden', currentStep !== panels.length - 1);
        }

        function validateCurrentStep() {
            const panel = panels[currentStep];
            if (!panel) return true;

            const fields = Array.from(panel.querySelectorAll('input, select, textarea'));
            for (const field of fields) {
                if (field.disabled || field.type === 'file') {
                    continue;
                }

                if (field.required) {
                    field.setCustomValidity('');
                    if (!field.checkValidity()) {
                        const value = field.value ? field.value.trim() : '';
                        if (!value) {
                            field.setCustomValidity('Veuillez remplir ce champ.');
                        }
                        field.reportValidity();
                        field.focus();
                        field.classList.add('border-red-400');
                        setTimeout(() => field.classList.remove('border-red-400'), 1800);
                        return false;
                    }
                }
            }

            return true;
        }

        function serializeForm() {
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) {
                if (form.querySelector(`[name="${key}"]`)?.type === 'file') continue;
                data[key] = value;
            }
            return data;
        }

        function restoreSavedProgress() {
            const saved = localStorage.getItem('lobolaIntegrationProgress');
            if (!saved) return;

            try {
                const data = JSON.parse(saved);
                Object.entries(data).forEach(([name, value]) => {
                    const field = form.querySelector(`[name="${name}"]`);
                    if (!field) return;

                    if (field.type === 'radio' || field.type === 'checkbox') {
                        field.checked = field.value === value;
                    } else {
                        field.value = value;
                    }
                });

                const status = document.getElementById('save-status');
                if (status) {
                    status.textContent = 'Progression restaurée.';
                }
            } catch (error) {
                localStorage.removeItem('lobolaIntegrationProgress');
            }
        }

        function saveProgress() {
            const data = serializeForm();
            localStorage.setItem('lobolaIntegrationProgress', JSON.stringify(data));
            const status = document.getElementById('save-status');
            if (status) {
                status.textContent = 'Progression sauvegardée.';
            }
            if (saveBtn) {
                saveBtn.disabled = true;
            }
        }

        function clearSavedProgress() {
            localStorage.removeItem('lobolaIntegrationProgress');
        }

        function enableSaveButton() {
            if (saveBtn) {
                saveBtn.disabled = false;
            }
        }

        function attachChangeListeners() {
            const inputs = Array.from(form.querySelectorAll('input, select, textarea'));
            inputs.forEach((field) => {
                field.addEventListener('input', () => {
                    enableSaveButton();
                });
            });
        }

        startBtn?.addEventListener('click', () => {
            introScreen?.classList.add('hidden');
            formWrapper?.classList.remove('hidden');
            renderStep();
        });

        nextBtn.addEventListener('click', () => {
            if (!validateCurrentStep()) return;
            currentStep = Math.min(currentStep + 1, panels.length - 1);
            renderStep();
        });

        prevBtn.addEventListener('click', () => {
            currentStep = Math.max(currentStep - 1, 0);
            renderStep();
        });

        saveBtn?.addEventListener('click', () => {
            saveProgress();
        });

        form.addEventListener('submit', (event) => {
            if (!validateCurrentStep()) {
                event.preventDefault();
                return;
            }
            clearSavedProgress();
        });

        restoreSavedProgress();
        attachChangeListeners();
        renderStep();
    });
</script>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>