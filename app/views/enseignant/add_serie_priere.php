<?php
    $title = $title ?? (SITE_NAME . ' | Ajouter une série de prières');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto max-w-3xl px-4 py-10">
    <section class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#D4AF37]">Séries / prières</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Ajouter une série de prières</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-gray-400">
                    Créez un espace thématique qui pourra contenir plusieurs enseignements audio.
                </p>
            </div>
            <a href="../add_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="inline-flex w-fit items-center gap-2 rounded-full border border-white/15 px-4 py-2 text-sm text-gray-300 transition hover:border-[#D4AF37] hover:text-[#D4AF37]">
                <i class="fa-solid fa-arrow-left"></i>
                Retour
            </a>
        </div>

        <form method="post" class="mt-8 space-y-5">
            <div>
                <label for="nom" class="mb-2 block text-sm font-medium text-gray-300">Nom de la série</label>
                <input id="nom" type="text" name="nom" required autofocus
                    class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 !text-white caret-[#D4AF37] placeholder:text-gray-500 focus:border-[#D4AF37] focus:outline-none"
                    placeholder="Ex : Prières du matin">
            </div>
            <div class="rounded-2xl border border-[#D4AF37]/15 bg-[#D4AF37]/5 p-4 text-sm leading-6 text-gray-300">
                <i class="fa-solid fa-circle-info mr-2 text-[#D4AF37]"></i>
                Après l'enregistrement, vous serez redirigé vers le formulaire pour publier le premier audio de cette série.
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#D4AF37] px-6 py-3 font-semibold text-[#130121] transition hover:-translate-y-0.5 hover:bg-white">
                <i class="fa-solid fa-folder-plus"></i>
                Enregistrer la série
            </button>
        </form>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
