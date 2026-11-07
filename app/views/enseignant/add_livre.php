<?php
    $title = $title ?? (SITE_NAME . ' | Ajouter un livre');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-5xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Bibliothèque</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Ajouter un livre</h1>
            </div>
            <a href="../livres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour</a>
        </div>
    </div>

    <section class="mt-8 rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
        <form method="post" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-sm text-gray-300 mb-2">Titre du livre</label>
                <input type="text" name="titre" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white placeholder:text-gray-500 caret-[#D4AF37] px-4 py-3 focus:border-[#D4AF37] focus:outline-none" placeholder="Ex : La sagesse devant l'âme">
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-2">Catégorie</label>
                <input type="text" name="categorie" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white placeholder:text-gray-500 caret-[#D4AF37] px-4 py-3 focus:border-[#D4AF37] focus:outline-none" placeholder="Ex : Initiation, Guidance, Tradition...">
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-2">Auteur</label>
                <input type="text" name="auteur" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white placeholder:text-gray-500 caret-[#D4AF37] px-4 py-3 focus:border-[#D4AF37] focus:outline-none" placeholder="Nom de l’auteur">
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white placeholder:text-gray-500 caret-[#D4AF37] px-4 py-3 focus:border-[#D4AF37] focus:outline-none" placeholder="Description du livre..."></textarea>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Fichier PDF</label>
                    <input type="file" name="pdf_file" accept="application/pdf" class="w-full rounded-xl border border-dashed border-white/15 bg-black/20 !text-gray-200 file:mr-3 file:rounded-lg file:border-0 file:bg-[#D4AF37] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-[#130121] px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Couverture</label>
                    <input type="file" name="cover_file" accept="image/*" class="w-full rounded-xl border border-dashed border-white/15 bg-black/20 !text-gray-200 file:mr-3 file:rounded-lg file:border-0 file:bg-[#D4AF37] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-[#130121] px-4 py-3">
                </div>
            </div>
            <button type="submit" name="cllil_enseignant_add_livre" class="bg-[#D4AF37] text-[#130121] rounded-full px-5 py-3 font-semibold">Publier le livre</button>
        </form>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
