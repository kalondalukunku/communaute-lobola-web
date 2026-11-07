<?php
    $title = $title ?? (SITE_NAME . ' | Mes prières');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-6xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Gestion</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Mes prières</h1>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="../add_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="bg-[#D4AF37] text-[#130121] font-semibold rounded-full px-5 py-3 text-sm">Ajouter une prière</a>
                <a href="../profile/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour profil</a>
            </div>
        </div>
    </div>

    <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($Series as $serie): ?>
            <article class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 shadow-xl">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37]">Série</p>
                        <h2 class="mt-2 text-xl font-semibold text-white"><?= htmlspecialchars($serie->nom) ?></h2>
                    </div>
                    <div class="flex gap-2">
                        <a href="../view_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($serie->serie_id) ?>" class="text-sm text-[#D4AF37]">Voir</a>
                        <a href="../edit_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($serie->serie_id) ?>" class="text-sm text-[#D4AF37]">Modifier</a>
                        <a href="../delete_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($serie->serie_id) ?>" class="text-sm text-red-400" onclick="return confirm('Supprimer cette série et ses prière(s) ?')">Supprimer</a>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    <?php if (empty($serie->teachings)): ?>
                        <p class="text-sm text-gray-400">Aucune prière ajoutée dans cette série.</p>
                    <?php else: ?>
                        <?php foreach ($serie->teachings as $teaching): ?>
                            <div class="rounded-xl border border-white/10 bg-black/20 p-3">
                                <div class="flex justify-between gap-2 items-start">
                                    <div>
                                        <p class="text-sm font-medium text-white"><?= htmlspecialchars($teaching->title) ?></p>
                                        <p class="text-xs text-gray-400"><?= htmlspecialchars($teaching->description ?: 'Prière audio') ?></p>
                                    </div>
                                    <span class="text-[10px] uppercase tracking-[0.2em] text-[#D4AF37]">Audio</span>
                                </div>
                                <?php if (!empty($teaching->audio_url)): ?>
                                    <audio controls class="mt-3 w-full" preload="none">
                                        <source src="<?= htmlspecialchars(BASE_URL . '/' . ltrim($teaching->audio_url, '/')) ?>" type="audio/mpeg">
                                    </audio>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>

        <?php if (empty($Series)): ?>
            <div class="md:col-span-2 xl:col-span-3 rounded-[1.5rem] border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-300">
                Aucune série de prière n’a encore été publiée. Ajoutez votre première série pour commencer.
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
