<?php
    $title = $title ?? (SITE_NAME . ' | Centre d’aide');
    include APP_PATH . 'views/layouts/header.php';
?>
</head>

<?php
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow container mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <section class="rounded-3xl border border-primary/20 bg-gradient-to-br from-[#130121] via-[#1d0c2d] to-[#0f172a] p-6 sm:p-8 shadow-2xl">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.28em] text-primary">
                    <i class="fa-solid fa-circle-question"></i>
                    Centre d’aide
                </span>
                <h1 class="mt-4 font-serif text-3xl sm:text-4xl text-white">
                    <?= isset($activeTutorial) && $activeTutorial ? htmlspecialchars($activeTutorial['title']) : 'Trouvez la réponse à votre problème' ?>
                </h1>
                <p class="mt-4 text-sm sm:text-base text-gray-300 leading-relaxed">
                    <?= isset($activeTutorial) && $activeTutorial ? htmlspecialchars($activeTutorial['summary']) : 'Consultez nos tutoriels pas à pas pour apprendre à réaliser vos démarches, résoudre un souci ou mieux utiliser la plateforme.' ?>
                </p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-gray-200 backdrop-blur-sm">
                <p class="font-semibold text-primary">Besoin d’un guide rapide ?</p>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-gray-400">Plusieurs tutos disponibles</p>
            </div>
        </div>
    </section>

    <div class="mt-8 grid gap-8 lg:grid-cols-[300px_minmax(0,1fr)]">
        <aside class="rounded-3xl border border-primary/10 bg-white/70 p-4 shadow-sm backdrop-blur-sm">
            <h2 class="text-lg font-semibold text-secondary">Tutoriels</h2>
            <div class="mt-4 space-y-3">
                <?php if (isset($tutorials) && is_array($tutorials)): foreach ($tutorials as $slug => $tutorial): ?>
                    <a href="/help/<?= $slug ?>"
                       class="block rounded-2xl border p-3 transition <?= (isset($activeSlug) && $activeSlug === $slug) ? 'border-primary bg-primary/10 shadow-sm' : 'border-gray-200 hover:border-primary/40 hover:bg-gray-50' ?>">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-xl bg-secondary/10 text-primary">
                                <i class="<?= htmlspecialchars($tutorial['icon']) ?>"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-secondary">
                                    <?= htmlspecialchars($tutorial['title']) ?>
                                </h3>
                                <p class="mt-1 text-xs leading-relaxed text-gray-500">
                                    <?= htmlspecialchars($tutorial['summary']) ?>
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </aside>

        <section class="space-y-6">
            <?php if (isset($activeTutorial) && $activeTutorial): ?>
                <div class="rounded-3xl border border-primary/10 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-primary">Tutoriel actif</p>
                            <h2 class="mt-2 text-2xl font-semibold text-secondary"><?= htmlspecialchars($activeTutorial['title']) ?></h2>
                        </div>
                        <a href="/help" class="rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-600 transition hover:border-primary hover:text-primary">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Retour à l’accueil
                        </a>
                    </div>

                    <div class="mt-6 space-y-5">
                        <?php if (isset($activeTutorial['steps']) && is_array($activeTutorial['steps'])): foreach ($activeTutorial['steps'] as $index => $step): ?>
                            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                                        <?= $index + 1 ?>
                                    </span>
                                    <h3 class="text-lg font-semibold text-secondary"><?= htmlspecialchars($step['title']) ?></h3>
                                </div>
                                <p class="mt-4 text-sm leading-7 text-gray-600">
                                    <?= htmlspecialchars($step['content']) ?>
                                </p>

                                <?php if (!empty($step['images'])): ?>
                                    <div class="mt-5 grid gap-4 md:grid-cols-<?= min(3, count($step['images'])) ?>">
                                        <?php foreach ($step['images'] as $image): ?>
                                            <img src="<?= htmlspecialchars($image) ?>"
                                                 alt="Illustration de <?= htmlspecialchars($step['title']) ?>"
                                                 class="h-40 w-full rounded-2xl border border-gray-200 object-cover shadow-sm">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="rounded-3xl border border-primary/10 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                    <div class="grid gap-5 md:grid-cols-2">
                        <?php if (isset($tutorials) && is_array($tutorials)): foreach ($tutorials as $slug => $tutorial): ?>
                            <a href="/help/<?= $slug ?>" class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-lg">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-secondary/10 text-primary">
                                    <i class="<?= htmlspecialchars($tutorial['icon']) ?>"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-secondary">
                                    <?= htmlspecialchars($tutorial['title']) ?>
                                </h3>
                                <p class="mt-2 text-sm leading-7 text-gray-600">
                                    <?= htmlspecialchars($tutorial['summary']) ?>
                                </p>
                                <span class="mt-4 inline-flex items-center text-sm font-semibold text-primary">
                                    Consulter le tutoriel
                                    <i class="fa-solid fa-chevron-right ml-2"></i>
                                </span>
                            </a>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
