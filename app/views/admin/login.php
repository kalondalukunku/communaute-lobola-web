<?php 
    $title = "Admin | Connexion";
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
            <h1 class="text-3xl font-bold text-white tracking-tight">Espace Admin</h1>
            <p class="text-slate-400 mt-2">Veuillez vous identifier pour continuer</p>
        </div>

        <!-- Formulaire -->
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <form method="post" id="loginForm" class="space-y-6">
                <!-- Message d'erreur (masqué par défaut) -->
                <div id="errorMessage" class="hidden bg-red-500/10 border border-red-500/50 text-red-500 text-sm p-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>Identifiants incorrects. Veuillez réessayer.</span>
                </div>

                <div class="input-group">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5 transition-all">Identifiant ou Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" id="email" required
                            name="email"
                            class="custom-input w-full pl-10 pr-4 py-3 rounded-xl border-none text-white placeholder-slate-500 focus:outline-none"
                            placeholder="admin@exemple.com">
                    </div>
                </div>

                <div class="input-group">
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-300 transition-all">Mot de passe</label>
                        <a href="#" class="text-xs text-primary hover:text-primary transition-colors">Oublié ?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" required
                            name="pswd"
                            class="custom-input w-full pl-10 pr-12 py-3 rounded-xl border-none text-white placeholder-slate-500 focus:outline-none"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- <div class="flex items-center">
                    <input type="checkbox" id="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-primary focus:ring-primary focus:ring-offset-slate-900">
                    <label for="remember" class="ml-2 text-sm text-slate-400 select-none">Rester connecté</label>
                </div> -->

                <button type="submit" 
                    name="cllil_admin_login"
                    class="w-full bg-primary hover:bg-primary text-paper font-semibold py-3.5 rounded-xl shadow-lg shadow-primary/25 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <span id="btnText">Se connecter</span>
                    <i id="btnIcon" class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>