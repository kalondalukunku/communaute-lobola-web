<?php
    $title = $title ?? (SITE_NAME . ' | Modifier une série');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-5xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Modifier</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Série de prières</h1>
            </div>
            <a href="../prieres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour</a>
        </div>
    </div>

    <section class="mt-8 rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
        <form method="post" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-300 mb-2">Nom de la série</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($Serie->nom) ?>" class="w-full rounded-xl border border-white/10 bg-black/20 text-white px-4 py-3">
            </div>
            <button type="submit" name="cllil_edit_priere_serie" class="bg-[#D4AF37] text-[#130121] rounded-full px-5 py-3 font-semibold">Enregistrer les modifications</button>
        </form>

        <div class="mt-8">
            <h3 class="text-lg font-semibold text-white mb-4">Prieres de cette série</h3>
            <?php if (empty($Teachings)): ?>
                <p class="text-gray-400">Aucune prière enregistrée dans cette série.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($Teachings as $teaching): ?>
                        <div class="rounded-xl border border-white/10 bg-black/20 p-4">
                            <p class="text-white font-medium"><?= htmlspecialchars($teaching->title) ?></p>
                            <p class="text-sm text-gray-400"><?= htmlspecialchars($teaching->description ?: 'Aucune description') ?></p>
                            <?php if (!empty($teaching->audio_url)): ?>
                                <audio controls class="mt-3 w-full" preload="none">
                                    <source src="<?= htmlspecialchars(BASE_URL . '/' . ltrim($teaching->audio_url, '/')) ?>" type="audio/mpeg">
                                </audio>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
