<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 404 - Page Introuvable</title>
    <!-- Chargement de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Configuration de la police Inter, cohérente avec un design moderne */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        :root {
            --mosali-blue-dark: #1e40af; /* Bleu profond, inspiré de l'en-tête */
            --mosali-blue-light: #3b82f6; /* Bleu plus clair pour les accents */
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb; /* Très léger gris pour un fond doux */
        }
        /* Style pour l'animation subtile des boutons au survol */
        .btn-primary {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(30, 64, 175, 0.2); /* Ombre légère bleue */
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(30, 64, 175, 0.3);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 sm:p-8">

    <div class="max-w-4xl w-full bg-white p-6 sm:p-12 lg:p-16 rounded-2xl shadow-2xl text-center">
        
        <!-- Conteneur pour l'illustration (SVG d'un chemin brisé/perdu) -->
        <div class="mb-2 flex justify-center">
            <!-- Simple SVG pour un look clean et professionnel -->
            <svg class="w-24 h-24 text-blue-700 sm:w-32 sm:h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <!-- Icône représentant un chemin perdu ou une recherche manquée -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Grand titre d'erreur (impactant) -->
        <p class="text-7xl sm:text-7xl font-extrabold text-blue-800 mb-4 tracking-tight">
            404
        </p>

        <!-- Message Principal -->
        <h1 class="text-3xl sm:text-3xl font-bold text-gray-900 mb-4">
            Oups ! Page Introuvable.
        </h1>

        <!-- Description Détaillée -->
        <p class="text-md text-gray-600 mb-5 max-w-xl mx-auto">
            Il semblerait que nous n'ayons pas trouvé la page que vous cherchez.
            Peut-être que l'adresse a été mal saisie ou que le contenu a été déplacé.
        </p>
        <p class="text-md text-gray-600 mb-5 max-w-xl mx-auto"><?= $message ?></p>

        <!-- Section des actions (Boutons Clairs) -->
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            
            <!-- Bouton Principal (Retour à l'accueil) -->
            <button onclick="window.location.href = '/';" class="btn-primary flex items-center justify-center px-8 py-3 text-base text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <!-- Icône Accueil (lucide-react équivalent) -->
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-2-2v10a1 1 0 01-1 1h-6a1 1 0 01-1-1v-4a1 1 0 00-1-1h-2a1 1 0 00-1 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-10l2-2z"></path></svg>
                Retourner à l'accueil
            </button>

            <!-- Bouton Secondaire (Reporting/Contact) -->
            <button onclick="console.log('Action : Signaler un problème'); /* Remplacez par votre logique de reporting */" class="px-8 py-3 text-base text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-xl shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                Signaler ce problème
            </button>

        </div>
        
    </div>

    <!-- Script JS simple pour gérer l'action des boutons si nécessaire -->
    <script>
        // Le bouton "Retourner à l'accueil" utilise déjà window.location.href = '/';
        // Vous pouvez ajouter ici une logique plus complexe pour le bouton de signalement.
    </script>

</body>
</html>