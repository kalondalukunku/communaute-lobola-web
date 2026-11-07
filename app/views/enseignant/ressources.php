<?php
    $title = $title ?? (SITE_NAME . ' | Ressources');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="container mx-auto px-4 py-10 max-w-6xl">
    <div class="rounded-[2rem] border border-[#D4AF37]/30 bg-[#120B1D] p-6 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37] font-bold">Centre de ressources</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Mes contenus pédagogiques</h1>
            </div>
            <a href="../profile/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-5 py-3 text-sm">Retour profil</a>
        </div>
    </div>

    <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <article class="rounded-[1.5rem] border border-white/10 bg-white/5 p-6 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-[#D4AF37] text-3xl"><i class="fa-solid fa-hands-praying"></i></span>
                <span class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37]">Prières</span>
            </div>
            <h2 class="mt-5 text-2xl font-semibold text-white">Gérer mes prières</h2>
            <p class="mt-3 text-sm text-gray-300">Publiez vos séries, ajoutez plusieurs audios et gérez vos contenus spirituels.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="../prieres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="bg-[#D4AF37] text-[#130121] rounded-full px-4 py-2 text-sm font-semibold">Voir</a>
                <a href="../add_priere/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-4 py-2 text-sm">Ajouter</a>
            </div>
        </article>

        <article class="rounded-[1.5rem] border border-white/10 bg-white/5 p-6 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-[#D4AF37] text-3xl"><i class="fa-solid fa-book"></i></span>
                <span class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37]">Livres</span>
            </div>
            <h2 class="mt-5 text-2xl font-semibold text-white">Gérer ma bibliothèque</h2>
            <p class="mt-3 text-sm text-gray-300">Ajoutez vos livres, couvrez les PDF et gérez votre bibliothèque publique.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="../livres/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="bg-[#D4AF37] text-[#130121] rounded-full px-4 py-2 text-sm font-semibold">Voir</a>
                <a href="../add_livre/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="border border-[#D4AF37]/40 text-[#D4AF37] rounded-full px-4 py-2 text-sm">Ajouter</a>
            </div>
        </article>

        <article class="rounded-[1.5rem] border border-white/10 bg-white/5 p-6 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-[#D4AF37] text-3xl"><i class="fa-solid fa-graduation-cap"></i></span>
                <span class="text-[10px] uppercase tracking-[0.28em] text-[#D4AF37]">Enseignements</span>
            </div>
            <h2 class="mt-5 text-2xl font-semibold text-white">Mes enseignements</h2>
            <p class="mt-3 text-sm text-gray-300">Accédez à vos contenus Mâat et Bolokele déjà publiés.</p>
            <div class="mt-6">
                <a href="../enseignements/<?= htmlspecialchars($Enseignant->enseignant_id) ?>" class="bg-[#D4AF37] text-[#130121] rounded-full px-4 py-2 text-sm font-semibold">Ouvrir</a>
            </div>
        </article>
    </section>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
