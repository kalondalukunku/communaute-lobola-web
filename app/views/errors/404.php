<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #000f0e;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .status-container {
            background: rgba(255, 255, 255, 0.932);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container mx-auto py-[100px] px-4 md:px-0">
    
    <div class="status-container fade-in text-center p-8 md:p-12 border-t-4 border-gray-400">
        
        <!-- Illustration 404 Formelle -->
        <div class="mb-8 flex justify-center">
            <div class="relative">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100">
                    <span class="text-3xl font-serif font-bold text-[#cfbb30] italic">404</span>
                </div>
                <div class="absolute -bottom-2 -right-2 bg-white p-2 rounded-full shadow-sm">
                    <i class="fas fa-map-signs text-[#cfbb30] text-xl"></i>
                </div>
            </div>
        </div>

        <h1 class="font-serif text-3xl text-[#cfbb30] mb-6">Page introuvable</h1>
        
        <div class="space-y-6 mb-10">
            <p class="text-gray-600 leading-relaxed text-sm">
                Le chemin que vous tentez d'emprunter ne semble plus exister ou a été déplacé de manière permanente.
            </p>
            
            <p class="text-gray-500 text-sm italic border-l-2 border-[#cfbb30]/20 pl-4 py-2 bg-gray-50/50">
                L'accès à cette ressource n'a pu être validé par notre serveur.
            </p>
            <p><?= $message ?></p>
        </div>

        <!-- Section Navigation -->
        <div class="bg-[#cfbb30]/5 p-6 rounded-xl mb-8 border border-[#cfbb30]/10">
            <h2 class="font-bold text-[#cfbb30] uppercase tracking-widest text-[10px] mb-4">Que souhaitez-vous faire ?</h2>
            
            <div class="flex flex-col md:flex-row gap-3 justify-center">
                <a href="/" class="inline-flex items-center justify-center bg-[#cfbb30] text-white py-3 px-8 rounded-lg text-sm font-bold hover:bg-[#000f0e] transition-all">
                    <i class="fas fa-home mr-2"></i> Page d'accueil
                </a>
                <button onclick="window.history.back()" class="inline-flex items-center justify-center bg-white border border-[#cfbb30]/20 text-[#cfbb30] py-3 px-8 rounded-lg text-sm font-bold hover:bg-gray-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Page précédente
                </button>
            </div>
        </div>

        <div class="pt-4">
            <p class="text-xs text-gray-400">
                Si vous pensez qu'il s'agit d'une erreur technique, merci de 
                <a href="mailto:<?= ADMIN_EMAIL ?>" class="text-[#cfbb30] underline decoration-dotted font-medium">contacter le support</a>.
            </p>
        </div>

    </div>
</div>

</body>
</html>