<?php
    $title = $title ?? (SITE_NAME . ' | Mes livres');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-6xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Bibliothèque</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Mes livres</h1>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="../add_livre/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="bg-[#D4AF37] text-[#130121] font-semibold rounded-full px-5 py-3 text-sm">Ajouter un livre</a>
                <a href="../profile/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour profil</a>
            </div>
        </div>
    </div>

    <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($Livres as $livre): ?>
            <article class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4 shadow-xl">
                <?php if (!empty($livre->url_couverture)): ?>
                    <img src="<?= htmlspecialchars(BASE_URL . '/' . ltrim($livre->url_couverture, '/')) ?>" alt="<?= htmlspecialchars($livre->titre) ?>" class="w-full h-52 object-cover rounded-xl mb-4">
                <?php else: ?>
                    <div class="flex h-52 items-center justify-center rounded-xl bg-black/20 text-gray-400 mb-4">Couverture</div>
                <?php endif; ?>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37]"><?= htmlspecialchars($livre->categorie ?: 'Livre') ?></p>
                        <h2 class="mt-2 text-xl font-semibold text-white"><?= htmlspecialchars($livre->titre) ?></h2>
                    </div>
                    <div class="flex gap-2 text-sm">
                        <a href="../view_livre/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($livre->livre_id) ?>" class="text-[#D4AF37]">Voir</a>
                        <a href="../edit_livre/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($livre->livre_id) ?>" class="text-[#D4AF37]">Modifier</a>
                        <a href="../delete_livre/<?= htmlspecialchars($Enseignant->enseignant_id) ?>/<?= htmlspecialchars($livre->livre_id) ?>" class="text-red-400" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
                    </div>
                </div>

                <p class="mt-3 text-sm text-gray-300"><?= htmlspecialchars($livre->description ?: 'Aucune description') ?></p>
                <p class="mt-2 text-xs uppercase tracking-[0.2em] text-gray-400">Auteur : <?= htmlspecialchars($livre->auteur ?: 'Inconnu') ?></p>

                <?php if (!empty($livre->url_livre)): ?>
                    <a href="<?= htmlspecialchars(BASE_URL . '/' . ltrim($livre->url_livre, '/')) ?>" target="_blank" class="mt-4 inline-block rounded-full border border-[#D4AF37]/40 text-[#D4AF37] px-4 py-2 text-sm">Ouvrir le PDF</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>

        <?php if (empty($Livres)): ?>
            <div class="md:col-span-2 xl:col-span-3 rounded-[1.5rem] border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-300">
                Aucun livre n’a encore été publié.
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
