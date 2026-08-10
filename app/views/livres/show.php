<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php'; 
?>
</head>
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
        include APP_PATH . 'templates/alertView.php'; 
    ?>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 max-w-6xl">
        <nav class="flex mb-8 text-xs font-medium text-gray-500 tracking-wide uppercase" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="/livres" class="hover:text-[#cfbb30] transition-colors">Bibliothèque</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    <span class="text-primary line-clamp-1 max-w-[200px]"><?= htmlspecialchars($Livre->titre) ?></span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            <div class="lg:col-span-5 top-6 px-12">
                <div class="relative aspect-[3/4] w-full h-75 md:h-full rounded-2xl bg-secondary shadow-md hover:shadow-xl color-border overflow-hidden group transition-all duration-300">
                    <?php if (!empty($Livre->url_couverture)): ?>
                        <img src="../../<?= htmlspecialchars($Livre->url_couverture) ?>" 
                            alt="Couverture de <?= htmlspecialchars($Livre->titre) ?>"
                            class="w-full h-full object-cover transform group-hover:scale-[1.02] transition-transform duration-500 ease-out">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-16 h-16 mb-2 opacity-50">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span class="text-xs uppercase font-medium tracking-wider">Aucun visuel</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-7 flex flex-col justify-between h-full">
                <div>
                    <div class="flex flex-wrap items-center text-sm gap-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wider bg-secondary text-gray-400">
                            <?= htmlspecialchars($Livre->categorie ?? 'Spiritualitée') ?>
                        </span>
                        <!-- <div class="flex items-center gap-1 text-sm font-medium text-gray-400">
                            <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.8１h3.46１a１ １ ０ ００．９５１－．６９l１．０７－３．２９２z"/></svg>
                            <span>4.8</span> <span class="text-gray-400 text-xs">(124 avis)</span>
                        </div> -->
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary mb-2 leading-tight">
                        <?= htmlspecialchars($Livre->titre) ?>
                    </h1>

                    <p class="text-md font-medium text-gray-500 mb-8">
                        Par <span class="text-primary hover:underline cursor-pointer"><?= htmlspecialchars($Livre->auteur ?? 'Auteur Inconnu') ?></span>
                    </p>

                    <div class="flex items-baseline gap-3 mb-8 pb-8 bocolor-rder-b">
                        <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary"><?= htmlspecialchars($Livre->prix ?? '10') ?> $</span>
                        <span class="text-xs font-medium text-emerald-700 bg-secondary px-3 py-1.5 rounded-md">En stock</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-10">
                        <?php if(isset($PayLivre->status) && $PayLivre->status === ARRAY_KPAY_STATUS[1]): ?>
                            <a href="../../<?= $Livre->url_livre ?>" download
                            class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-primary hover:bg-blue-600 font-medium rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 gap-3">
                                <i class="fa-solid fa-download"></i>
                                <span>Télécharger</span>
                            </a>
                        <?php else : ?>
                            <form action="" method="post" class="flex flex-col sm:flex-row gap-4 w-full">
                                <button type="submit" name="cllil_membre_pay_livre"
                                class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-primary hover:bg-blue-600 font-medium rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                    <span>Acheter</span>
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <!-- <button class="inline-flex items-center justify-center p-4 border border-gray-200 hover:border-gray-300 hover:bg-gray-50 rounded-xl transition-colors text-gray-400 hover:text-red-500" title="Ajouter aux favoris">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 fill-transparent hover:fill-current">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.092-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                        </button> -->
                    </div>

                    <div class="mb-10">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Synopsis</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            <?= nl2br(htmlspecialchars($Livre->description)) ?>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 p-5 bg-secondary rounded-xl color-border">
                    <!-- <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Pages</span>
                        <span class="text-sm font-semibold text-gray-200"><?= htmlspecialchars($Livre->pages ?? '320') ?></span>
                    </div> -->
                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Format</span>
                        <span class="text-sm font-semibold text-gray-200"><?= htmlspecialchars($Livre->format ?? 'PDF') ?></span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Langue</span>
                        <span class="text-sm font-semibold text-gray-200"><?= htmlspecialchars($Livre->langue ?? 'Français') ?></span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Publication</span>
                        <span class="text-sm font-semibold text-gray-200"><?= htmlspecialchars(date('Y', strtotime($Livre->created_at)) ?? '2024') ?></span>
                    </div>
                </div>
            </div>

        </div>
    </main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>