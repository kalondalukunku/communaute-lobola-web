<?php 
    $title = "Connexion";
    include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="gradient-bg flex flex-col items-center justify-center min-h-screen p-4">

        <?php include APP_PATH . 'templates/alertView.php'; ?>
    <!-- Conteneur d'animation de fond (couvre tout l'écran) -->
    <!-- <div class=" absolute inset-0 -z-10 opacity-70"></div> -->

    <!-- Carte de Connexion Principale -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:scale-[1.01] backdrop-blur-sm bg-opacity-95 my-auto mx-auto">
        <div class="p-6 sm:p-8">
            <!-- Header/Logo/Titre -->
            <div class="text-center mb-8">
                <!-- Icone de l'organisation publique (avec la couleur secondaire: Bleu Profond) -->
                <svg class="w-14 h-14 text-blue-800 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2h2m-4 2a2 2 0 10-4 0m4 0h-4m2 0H7m0 0v-2a3 3 0 00-3.415-2.615M7 20a2 2 0 10-4 0m4 0h-4"></path>
                </svg>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    Portail Personnel
                </h1>
                <p class="mt-2 text-xs text-gray-500">
                    Connectez-vous pour accéder à l'intranet de l'organisation.
                </p>
            </div>

            <!-- Formulaire de Connexion -->
            <form method="post" id="loginForm">
                <div class="space-y-6">

                    <!-- Champ Nom d'utilisateur/Email -->
                    <div class="relative">
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                            Nom d'utilisateur ou Email
                        </label>
                        <input type="connect" id="connect" name="connect" required
                            value="<?= $data['connect'] ?? '' ; ?>"
                            placeholder="ex: prenom.nom@organisation.gouv"
                            class="input-focus-style block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 transition duration-150 ease-in-out"
                            autocomplete="username">
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="relative">
                        <label for="password" class="flex justify-between items-center text-xs font-medium text-gray-700 mb-1">
                            Mot de passe
                            <a href="#" class="text-xs font-semibold text-orange-700 hover:text-orange-600 transition duration-150 ease-in-out" onclick="showModal('passwordResetModal'); event.preventDefault();">
                                Oublié ?
                            </a>
                        </label>
                        <input type="password" id="password" name="pswd" required
                            placeholder="Saisissez votre mot de passe"
                            class="input-focus-style block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 transition duration-150 ease-in-out pr-10"
                            autocomplete="current-password">
                        
                        <!-- Bouton d'affichage/masquage du mot de passe -->
                        <!-- <button type="button" id="togglePassword" class="absolute right-3 top-[calc(2.2rem+0.5rem)] transform -translate-y-1/2 p-1 text-gray-500 hover:text-orange-700 transition duration-150" aria-label="Afficher/Masquer le mot de passe"> -->
                            <!-- Icone d'oeil (Afficher) -->
                            <!-- <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg> -->
                            <!-- Icone d'oeil barré (Masquer) - Cachée initialement -->
                            <!-- <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.065-7 9.542-7a10.05 10.05 0 011.875.175m-1.875 17.65L.458 5.458M12 17a5 5 0 005-5M6 12a5 5 0 015-5"></path>
                            </svg>
                        </button> -->
                    </div>

                    <!-- Bouton de Connexion -->
                    <div>
                        <button type="submit" id="loginButton"
                            name="mutu_user_login"
                            class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-orange-700 hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                            <span id="buttonText" class="text-xs">Se connecter</span>
                            <!-- Spinner de chargement (initialement caché) -->
                            <svg id="loadingSpinner" class="hidden spinner h-5 w-5 text-white" viewBox="0 0 24 24"></svg>
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Footer Légale / Informations -->
            <div class="px-8 py-4 sm:px-10 bg-gray-50 border-t border-gray-100 text-center text-xs text-gray-500">
                &copy; 2025 Organisation Publique. Tous droits réservés.
                <p class="mt-1">Assistance technique: <a href="mailto:support@organisation.gouv" class="hover:underline text-blue-800">support@organisation.gouv</a></p>
            </div>
        </div>
    </div>

    <!-- Modale de Réinitialisation du Mot de Passe (cachée) -->
    <div id="passwordResetModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center p-4 z-50 transition-opacity duration-300" onclick="hideModal('passwordResetModal')">
        <div class="bg-white rounded-xl p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300" onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Réinitialisation du Mot de Passe</h3>
            <p class="text-gray-600 mb-6 text-xs">Veuillez entrer votre email de travail. Un lien de réinitialisation vous sera envoyé.</p>
            <input type="email" placeholder="Votre email" class="input-focus-style block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 mb-4">
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="hideModal('passwordResetModal')">
                    Annuler
                </button>
                <button type="button" class="px-4 py-2 text-xs font-medium text-white bg-orange-700 rounded-lg hover:bg-orange-600 transition">
                    Envoyer le lien
                </button>
            </div>
        </div>
    </div>

    <!-- Zone de Notification (pour les messages d'erreur/succès) -->
    <div id="notificationArea" class="fixed top-4 right-4 space-y-2 z-50">
        <!-- Les notifications JS seront insérées ici -->
    </div>

</section>

    <!-- Script pour le menu toggle -->
    <script src="<?= ASSETS ?>js/modules/menuToggle.js"></script>
    <!-- Fichier principal -->
    <script src="<?= ASSETS ?>js/app.js"></script>
</body>
</html>