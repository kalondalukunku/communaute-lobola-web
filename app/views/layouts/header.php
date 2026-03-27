<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- supporter tous les navigateur (safari) -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- support navigateur safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= SITE_NAME ?>">
    
    <!-- Chargement de Tailwind CSS -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="<?= ASSETS ?>js/app.js?v=<?= APP_VERSION ?>"></script>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> -->
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Main css -->
    <link rel="stylesheet" href="<?= ASSETS ?>css/main.css?v=<?= APP_VERSION ?>">

    <!-- icon du domaine -->
    <link rel="icon" href="<?= ASSETS ?>images/logo.jpg" type="image/png">

    <!-- Title -->
    <title><?= $title ?? SITE_NAME ?></title>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CFBB30', // Nouvelle couleur principale demandée
                        primaryDark: '#b5a32a',
                        secondary: '#16302B', // L'ancien vert conservé pour le contraste
                        paper: '#Fdfbf7',
                        textMain: '#2D3748',
                    },
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'serif'],
                        sans: ['"Lato"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-paper text-textMain font-sans min-h-screen flex flex-col">
    <div id="spiritual-loader" class="fixed inset-0 z-[10000] flex flex-col items-center justify-center bg-paper transition-opacity duration-700">
        <!-- Animation de Géométrie Sacrée (SVG) -->
        <div class="relative w-18 h-18 mb-8">
            <!-- Cercle pulsant extérieur -->
            <div class="absolute inset-0 rounded-full border-2 border-[#cfbb30] animate-ping"></div>
            
            <!-- Icône centrale (Fleur de vie ou Lotus simplifié) -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-4xl bg-primary shadow-lg shadow-primary/20 -rotate-13 animate-spin-slow">
                <img src="<?= ASSETS ?>images/logo.jpg" alt="Logo" srcset="" class="w-18 h-18 rounded-4xl text-primary">
            </div>
        </div>

        <!-- Texte de chargement spirituel -->
        <div class="text-center">
            <p class="text-primary font-serif italic text-lg animate-pulse mb-2">Éveil de la conscience...</p>
            <p id="loader-quote" class="text-gray-500 text-[10px] uppercase tracking-[0.3em] opacity-0 transition-opacity duration-1000">Purification du temple intérieur</p>
        </div>
    </div>
