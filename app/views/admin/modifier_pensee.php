<?php
    $title = SITE_NAME . ' | Modifier une pensée';
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow flex w-full flex-col">
    <div class="lg:hidden p-4 bg-paper border-b border-slate-200 flex items-center justify-between sticky top-0 z-30 shadow-sm">
        <button @click="sidebarOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600" aria-label="Ouvrir le menu">
            <i class="fas fa-bars-staggered"></i>
        </button>
        <div class="flex items-center gap-2">
            <img class="w-7 h-7 rounded-lg" src="<?= ASSETS ?>images/logo.jpg" alt="">
            <span class="font-bold text-sm text-white tracking-tight"><?= SITE_NAME ?></span>
        </div>
    </div>

    <header class="sticky top-0 z-40 flex min-h-24 items-center justify-between gap-4 border-b border-gray-100 bg-paper px-4 py-4 backdrop-blur-md sm:px-6">
        <div>
            <h1 class="font-serif text-xl font-bold text-primary">Modifier une pensée</h1>
            <p class="mt-1 text-xs font-medium italic text-gray-400">Seul le texte de la pensée sera modifié</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/pensees"
            class="shrink-0 rounded-full border border-white/10 px-4 py-3 text-xs font-semibold text-gray-200 transition hover:bg-white/10 sm:px-5 sm:text-sm">
            <i class="fa-solid fa-arrow-left mr-1"></i>Retour aux pensées
        </a>
    </header>

    <div class="p-4 sm:p-6 lg:p-10">
        <div class="mx-auto max-w-4xl rounded-[2rem] border border-primary/20 bg-white/5 p-5 shadow-xl sm:p-6">
            <div class="mb-5 flex items-center gap-3 text-sm text-gray-300">
                <i class="fa-solid fa-feather-pointed text-primary"></i>
                <span>Publiée par <strong class="text-white"><?= htmlspecialchars($thought['author_name'], ENT_QUOTES, 'UTF-8') ?></strong></span>
                <span class="text-gray-500">·</span>
                <time class="text-xs text-gray-400"><?= htmlspecialchars($thought['created_at'], ENT_QUOTES, 'UTF-8') ?></time>
            </div>

            <form method="POST" action="<?= BASE_URL ?>/admin/update_pensee/<?= htmlspecialchars($thought['thought_id'], ENT_QUOTES, 'UTF-8') ?>" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <div>
                    <label for="content" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Texte de la pensée</label>
                    <textarea id="content" name="content" rows="8" maxlength="5000" required autofocus
                        class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white placeholder:text-gray-500 focus:border-primary focus:outline-none"
                        style="color: #fff !important;"><?= htmlspecialchars($thought['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    <p class="mt-2 text-xs text-gray-500">5 000 caractères maximum. L’auteur, la date, les likes et les commentaires restent inchangés.</p>
                </div>
                <div class="flex flex-wrap justify-end gap-3">
                    <a href="<?= BASE_URL ?>/admin/pensees" class="rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-gray-200 transition hover:bg-white/10">Annuler</a>
                    <button type="submit" class="rounded-full bg-primary px-6 py-3 text-sm font-bold text-black transition hover:bg-primary/80">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
