<?php 
    $title = "Reabonnement | " . SITE_NAME;
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'templates/alertView.php'; 

?>

<section class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full animate-fade-in">
        <!-- Logo ou Icône -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary mb-4 shadow-lg shadow-primary/20">
                <img class="w-15 rounded-2xl" src="<?= ASSETS ?>images/logo.jpg" alt="">
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Reabonnement</h1>
            <p class="text-slate-400 mt-2">Veuillez remplir les informations ci-dessous pour procéder au reabonnement</p>
        </div>

        <!-- card montant de l'engagement -->
        <div class="mb-6 rounded-lg color-border p-4 text-primary">
            <p class="text-center"><strong>Montant à payer :</strong> <?= $montant ?> USD</p>
            <!-- <p class="mt-1">Le paiement sera lancé après validation du pays et de l’opérateur.</p> -->
        </div>

        <!-- Formulaire -->
        <div class=" rounded-3xl p-8 md:p-10">
            <form method="post" id="loginForm" class="space-y-6">
                <!-- Message d'erreur (masqué par défaut) -->
                <div id="errorMessage" class="hidden bg-red-500/10 border border-red-500/50 text-red-500 text-sm p-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>Identifiants incorrects. Veuillez réessayer.</span>
                </div>

                <!-- <div class="input-group">
                    <label for="phoneNumber" class="block text-sm font-medium text-slate-300 mb-1.5 transition-all">Numéro de téléphone</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="tel" id="phoneNumber" required
                            name="phoneNumber"
                            class="custom-input w-full pl-10 pr-4 py-3 rounded-xl border-none text-white placeholder-slate-500 focus:outline-none"
                            placeholder="+243 ********"
                            value="<?= Helper::getData($_POST, 'phoneNumber') ?>"
                            style="color: var(--primary);">
                    </div>
                </div> -->
                <!-- select operateur -->
                <!-- <div class="input-group">
                    <label for="operateur" class="block text-sm font-medium text-slate-300 transition-all">Opérateur</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-mobile-alt"></i>
                        </span>
                        <select id="operateur" name="operateur" required
                            class="bg-paper custom-input w-full pl-10 pr-4 py-3 rounded-xl border-none text-primary placeholder-slate-500 focus:outline-none"
                            style="background-color: var(--paper);">
                            <option value="">Sélectionnez un opérateur</option>
                            <?php foreach ($providerCountries as $countryName => $countryData): ?>
                                <?php foreach ($countryData['providers'] as $provider => $value): ?>
                                    <option value="<?= htmlspecialchars($provider, ENT_QUOTES, 'UTF-8'); ?>" <?= Helper::getData($_POST, 'operateur') === $provider ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div> -->

                <button type="submit" 
                    name="cllil_membre_pay_engagement"
                    class="w-full bg-primary hover:bg-primary text-paper font-semibold py-3.5 rounded-xl shadow-lg shadow-primary/25 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <span id="btnText">Payer</span>
                    <i id="btnIcon" class="fas fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>