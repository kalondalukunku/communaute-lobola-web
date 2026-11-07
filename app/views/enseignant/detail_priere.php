<?php
    $title = $title ?? (SITE_NAME . ' | Détail de prière');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-5xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Série</p>
                <h1 class="mt-2 text-3xl font-bold text-white"><?= htmlspecialchars($Serie->nom) ?></h1>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="../prieres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour</a>
                <a href="../edit_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($Serie->serie_id) ?>" class="bg-[#D4AF37] text-[#130121] rounded-full px-5 py-3 text-sm font-semibold">Modifier</a>
            </div>
        </div>
    </div>

    <section class="mt-8 space-y-6">
        <?php if (empty($Teachings)): ?>
            <div class="rounded-[1.5rem] border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-300">
                Aucune prière n’a été ajoutée à cette série.
            </div>
        <?php else: ?>
            <?php foreach ($Teachings as $teaching): ?>
                <article class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Prière</p>
                            <h2 class="mt-2 text-xl font-semibold text-white"><?= htmlspecialchars($teaching->title) ?></h2>
                        </div>
                        <span class="text-xs uppercase tracking-[0.2em] text-gray-400"><?= htmlspecialchars($teaching->duration_minutes ?: 'Durée non précisée') ?></span>
                    </div>

                    <?php if (!empty($teaching->description)): ?>
                        <p class="mt-4 text-sm text-gray-300"><?= htmlspecialchars($teaching->description) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($teaching->audio_url)): ?>
                        <audio controls class="mt-4 w-full" preload="none">
                            <source src="<?= htmlspecialchars(BASE_URL . '/' . ltrim($teaching->audio_url, '/')) ?>" type="audio/mpeg">
                        </audio>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
