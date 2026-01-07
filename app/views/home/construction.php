<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauté LOBOLA - En Construction</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="logo.jpg" type="image/jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;1,400&family=Inter:wght@300;400&display=swap');

        body {
            background-color: #000f0e;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Centrage absolu */
        .centered-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .gold-text {
            color: #cfbb30;
        }

        /* Animation douce pour le SVG */
        .symbol-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .rotate-slow {
            animation: rotation 20s infinite linear;
        }

        @keyframes rotation {
            from { transform: rotate(0deg); }
            to { transform: rotate(359deg); }
        }
    </style>
</head>
<body>

    <div class="centered-container">
        
        <!-- Symboles SVG Ancestraux -->
        <div class="symbol-container">
            <!-- Cercle de protection (Style Égyptien / Géométrie Sacrée) -->
            <svg class="w-48 h-48 md:w-64 md:h-64 rotate-slow opacity-30" viewBox="0 0 100 100" fill="none" stroke="#cfbb30">
                <circle cx="50" cy="50" r="45" stroke-width="0.5" stroke-dasharray="2 2" />
                <path d="M50 5 L50 95 M5 50 L95 50" stroke-width="0.2" />
                <!-- Motifs répétés type Bogolan -->
                <g stroke-width="0.5">
                    <path d="M45 10 L50 5 L55 10 M45 90 L50 95 L55 90" />
                    <path d="M10 45 L5 50 L10 55 M90 45 L95 50 L100 55" />
                </g>
            </svg>

            <!-- Symbole Central (Ankh Épuré / Croix de Vie) -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#cfbb30" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 10V22M12 10C14.2091 10 16 8.20914 16 6C16 3.79086 14.2091 2 12 2C9.79086 2 8 3.79086 8 6C8 8.20914 9.79086 10 12 10Z" />
                    <path d="M8 13H16" />
                </svg>
            </div>
        </div>

        <!-- Contenu Texte -->
        <h1 class="font-serif text-4xl md:text-6xl italic mb-4">Communauté LOBOLA</h1>
        <p class="gold-text uppercase tracking-[0.3em] text-xs mb-8">Bientôt accessible</p>
        
        <div class="max-w-md w-full">
            <p class="text-gray-400 text-sm mb-10 leading-relaxed font-light">
                Nous peaufinons l'espace sacré pour votre éveil spirituel. Les portes s'ouvriront très prochainement pour une expérience inédite.
            </p>
        </div>

    </div>

</body>
</html>