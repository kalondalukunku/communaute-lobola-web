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
            <h1 class="text-3xl font-bold text-white tracking-tight">Espace Enseignant</h1>
            <p class="text-slate-400 mt-2">Mise à jour du mot de passe</p>
        </div>

        <!-- Formulaire -->
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <form method="post" id="loginForm" class="space-y-6">

                <div class="input-group">
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-300 transition-all">Mot de passe</label>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password1" required
                            name="pswd"
                            class="custom-input w-full pl-10 pr-12 py-3 rounded-xl border-none text-white placeholder-slate-500 focus:outline-none"
                            placeholder="••••••••" style="color: var(--primary);"
                            value="<?= Helper::getData($_POST, 'pswd') ?>">
                        <button type="button" onclick="togglePassword1()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <i id="eyeIcon1" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-300 transition-all">Confirme le mot de passe</label>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password2" required
                            name="confirm_pswd"
                            class="custom-input w-full pl-10 pr-12 py-3 rounded-xl border-none text-white placeholder-slate-500 focus:outline-none"
                            placeholder="••••••••" style="color: var(--primary);"
                            value="<?= Helper::getData($_POST, 'confirm_pswd') ?>">
                        <button type="button" onclick="togglePassword2()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <i id="eyeIcon2" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                    name="cllil_enseignant_add_pswd"
                    class="w-full bg-primary hover:bg-primary text-paper font-semibold py-3.5 rounded-xl shadow-lg shadow-primary/25 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <span id="btnText">Ajouter</span>
                    <i class="fas fa-plus"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    function togglePassword1() {
        const pwd = document.getElementById('password1');
        const icon = document.getElementById('eyeIcon1');
        if (pwd.type === 'password' ) {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    function togglePassword2() {
        const pwd2 = document.getElementById('password2');
        const icon2 = document.getElementById('eyeIcon2');

        if (pwd2.type === 'password') {
            pwd2.type = 'text';
            icon2.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd2.type = 'password';
            icon2.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>