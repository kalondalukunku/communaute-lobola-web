<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Chargement de Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Main css -->
    <link rel="stylesheet" href="<?= ASSETS ?>css/main.css">

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
