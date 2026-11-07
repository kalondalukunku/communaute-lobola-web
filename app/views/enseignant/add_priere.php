<?php
    $title = $title ?? (SITE_NAME . ' | Ajouter une prière');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-5xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Séries / prières</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Ajouter une série ou une prière</h1>
            </div>
            <a href="../prieres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour</a>
        </div>
    </div>

    <div class="mt-8">
        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <h2 class="text-xl font-semibold text-white">Publier une prière audio</h2>
            <p class="mt-2 text-sm text-gray-400">Organisez vos audios dans une série existante ou créez-en une nouvelle.</p>
            <a href="../add_serie_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="mt-5 inline-flex items-center gap-2 rounded-full border border-[#D4AF37]/50 px-4 py-2 text-sm font-semibold text-[#D4AF37] transition hover:bg-[#D4AF37] hover:text-[#130121]">
                <i class="fa-solid fa-plus"></i>
                Ajouter une série de prières
            </a>
            <form method="post" enctype="multipart/form-data" class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Titre</label>
                    <input type="text" name="titre" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white placeholder:text-gray-500 caret-[#D4AF37] px-4 py-3 focus:border-[#D4AF37] focus:outline-none" placeholder="Ex : Prière du matin">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Série</label>
                    <select name="serie" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white px-4 py-3 focus:border-[#D4AF37] focus:outline-none">
                        <option value="">Choisir une série</option>
                        <?php foreach ($dbSeries as $serie): ?>
                            <option value="<?= htmlspecialchars($serie) ?>"><?= htmlspecialchars($serie) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-xl border border-white/10 bg-black/20 !text-white placeholder:text-gray-500 caret-[#D4AF37] px-4 py-3 focus:border-[#D4AF37] focus:outline-none" placeholder="Courte description de la prière..."></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Fichier audio</label>
                    <input type="file" name="audio_data" accept="audio/*" class="w-full rounded-xl border border-dashed border-white/15 bg-black/20 !text-gray-200 file:mr-3 file:rounded-lg file:border-0 file:bg-[#D4AF37] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-[#130121] px-4 py-3">
                </div>
                <button type="submit" name="cllil_add_priere_enseignement" class="bg-[#D4AF37] text-[#130121] rounded-full px-5 py-3 font-semibold">Publier la prière</button>
            </form>
        </section>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
