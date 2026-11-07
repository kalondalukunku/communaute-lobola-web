<?php
    $title = $title ?? (SITE_NAME . ' | Détail du livre');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-5xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Livre</p>
                <h1 class="mt-2 text-3xl font-bold text-white"><?= htmlspecialchars($Livre->titre) ?></h1>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="../livres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour</a>
                <a href="../edit_livre/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($Livre->livre_id) ?>" class="bg-[#D4AF37] text-[#130121] rounded-full px-5 py-3 text-sm font-semibold">Modifier</a>
            </div>
        </div>
    </div>

    <section class="mt-8 rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
        <?php if (!empty($Livre->url_couverture)): ?>
            <img src="<?= htmlspecialchars(BASE_URL . '/' . ltrim($Livre->url_couverture, '/')) ?>" alt="<?= htmlspecialchars($Livre->titre) ?>" class="w-full max-w-md rounded-xl object-cover mx-auto mb-6">
        <?php endif; ?>

        <div class="space-y-4 text-gray-200">
            <p><span class="text-[#D4AF37] uppercase tracking-[0.2em] text-[10px]">Catégorie</span><br><?= htmlspecialchars($Livre->categorie ?: 'Non renseignée') ?></p>
            <p><span class="text-[#D4AF37] uppercase tracking-[0.2em] text-[10px]">Auteur</span><br><?= htmlspecialchars($Livre->auteur ?: 'Non renseigné') ?></p>
            <p><span class="text-[#D4AF37] uppercase tracking-[0.2em] text-[10px]">Description</span><br><?= nl2br(htmlspecialchars($Livre->description ?: 'Aucune description disponible.')) ?></p>
        </div>

        <?php if (!empty($Livre->url_livre)): ?>
            <div class="mt-6">
                <a href="<?= htmlspecialchars(BASE_URL . '/' . ltrim($Livre->url_livre, '/')) ?>" target="_blank" class="bg-[#D4AF37] text-[#130121] rounded-full px-5 py-3 font-semibold inline-block">Ouvrir le PDF</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
