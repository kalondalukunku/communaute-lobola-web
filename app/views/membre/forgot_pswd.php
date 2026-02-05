<?php 
    $title = "Réinitialisation mot de passe";
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
            <h1 class="text-3xl font-bold text-white tracking-tight">Insérez votre adresse mail</h1>
            <p class="text-slate-400 mt-2">Un lien de réinitialisation vous sera envoyé</p>
        </div>

        <!-- Formulaire -->
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <form method="post" id="loginForm" class="space-y-6">

                <div class="input-group">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5 transition-all">Identifiant ou Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" id="email" required
                            name="email"
                            class="custom-input w-full pl-10 pr-4 py-3 rounded-xl border-none text-white placeholder-slate-500 focus:outline-none"
                            placeholder="exemple@domaine.com"
                            style="color: var(--primary);">
                    </div>
                </div>

                <button type="submit" 
                    name="cllil_membre_forgot_pswd"
                    class="w-full bg-primary hover:bg-primary text-paper font-semibold py-3.5 rounded-xl shadow-lg shadow-primary/25 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <span id="btnText">Envoyer</span>
                    <i id="btnIcon" class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</section>