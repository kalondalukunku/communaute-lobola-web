<?php
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';

    $enseignantId = htmlspecialchars((string) $Enseignant->enseignant_id);
    $nomComplet = htmlspecialchars((string) ($Enseignant->nom_complet ?? 'Enseignant'));
    $email = htmlspecialchars((string) ($Enseignant->email ?? ''));
    $biographie = trim((string) ($Enseignant->biographie ?? ''));
    $annee = !empty($Enseignant->created_at) ? date('Y', strtotime($Enseignant->created_at)) : '—';
    $avatar = trim((string) ($Enseignant->path_profile ?? ''));
    $initiales = strtoupper(substr(preg_replace('/[^A-Za-zÀ-ÿ]/u', '', $Enseignant->nom_complet ?? 'E'), 0, 2));
?>

<main class="relative overflow-hidden px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
    <div class="pointer-events-none absolute -left-24 top-12 h-72 w-72 rounded-full bg-[#D4AF37]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 top-72 h-96 w-96 rounded-full bg-purple-900/20 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-2 flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.3em] text-[#D4AF37]">
                    <span class="h-px w-8 bg-[#D4AF37]"></span>
                    Espace enseignant
                </p>
                <h1 class="font-serif text-3xl text-white sm:text-4xl">Votre espace de transmission</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-400">
                    Gérez vos enseignements, vos prières et les livres que vous partagez avec la communauté.
                </p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-4 py-2 text-xs font-medium text-emerald-300">
                <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-300"></span>
                Profil actif
            </span>
        </div>

        <section class="relative overflow-hidden rounded-[2rem] border border-[#D4AF37]/20 bg-gradient-to-br from-[#1b0928] via-[#130121] to-[#0d1118] shadow-2xl">
            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 85% 15%, rgba(212,175,55,.35), transparent 25%), radial-gradient(circle at 15% 100%, rgba(110,44,145,.35), transparent 30%);"></div>
            <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[auto_1fr_auto] lg:items-center lg:p-10">
                <div class="relative mx-auto lg:mx-0">
                    <div class="absolute -inset-3 rounded-[2rem] border border-[#D4AF37]/30 rotate-6"></div>
                    <div class="relative h-40 w-40 overflow-hidden rounded-[1.75rem] border-2 border-[#D4AF37]/60 bg-[#24122f] shadow-xl sm:h-48 sm:w-48">
                        <?php if ($avatar): ?>
                            <img src="../../<?= htmlspecialchars($avatar) ?>" alt="Portrait de <?= $nomComplet ?>" class="h-full w-full object-cover transition duration-700 hover:scale-105">
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center font-serif text-5xl text-[#D4AF37]"><?= htmlspecialchars($initiales) ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-[#D4AF37]/40 bg-[#130121] px-4 py-1 text-[10px] font-bold uppercase tracking-[0.25em] text-[#D4AF37]">Guide</span>
                </div>

                <div class="text-center lg:text-left">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-[#D4AF37]/80">Bienvenue dans votre espace</p>
                    <h2 class="mt-3 font-serif text-4xl italic text-white sm:text-5xl"><?= $nomComplet ?></h2>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-gray-300">
                        Accompagnateur d'éveil, de sagesse et de connaissance intérieure.
                        Faites rayonner vos transmissions depuis un espace simple et unifié.
                    </p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3 lg:justify-start">
                        <a href="../enseignements/<?= $enseignantId ?>" class="inline-flex items-center gap-2 rounded-full bg-[#D4AF37] px-5 py-3 text-xs font-bold uppercase tracking-wider text-[#130121] transition hover:-translate-y-0.5 hover:bg-white">
                            <i class="fa-solid fa-book-open-reader"></i>
                            Mes enseignements
                        </a>
                        <a href="../ressources/<?= $enseignantId ?>" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-5 py-3 text-xs font-bold uppercase tracking-wider text-white transition hover:-translate-y-0.5 hover:border-[#D4AF37] hover:text-[#D4AF37]">
                            <i class="fa-solid fa-layer-group"></i>
                            Centre de ressources
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center backdrop-blur">
                        <span class="block text-2xl font-serif text-[#D4AF37]"><?= htmlspecialchars($annee) ?></span>
                        <span class="mt-1 block text-[9px] uppercase tracking-widest text-gray-400">Depuis</span>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center backdrop-blur">
                        <span class="block text-2xl font-serif text-[#D4AF37]"><?= count($allEnseignements) ?></span>
                        <span class="mt-1 block text-[9px] uppercase tracking-widest text-gray-400">Leçons</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.15fr_.85fr]">
            <section class="rounded-[1.75rem] border border-white/10 bg-white/[0.04] p-6 shadow-xl backdrop-blur-sm sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[#D4AF37]">Votre parcours</p>
                        <h3 class="mt-2 font-serif text-2xl text-white">Cheminement & vision</h3>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#D4AF37]/10 text-[#D4AF37]"><i class="fa-solid fa-sparkles"></i></span>
                </div>
                <div class="mt-6 border-l border-[#D4AF37]/40 pl-5 text-sm leading-8 text-gray-300">
                    <?= nl2br(htmlspecialchars($biographie ?: "Une vie dédiée à l'exploration des profondeurs de l'âme et au partage des vérités universelles. À travers ses enseignements, il invite chacun à cultiver la paix intérieure et la présence.")) ?>
                </div>
                <div class="mt-7 flex items-center gap-3 text-sm italic text-gray-500">
                    <span class="h-px w-10 bg-[#D4AF37]/40"></span>
                    La connaissance se transmet, la sagesse se vit.
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-white/10 bg-white/[0.04] p-6 shadow-xl backdrop-blur-sm sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[#D4AF37]">Accès rapide</p>
                <h3 class="mt-2 font-serif text-2xl text-white">Vos espaces de publication</h3>
                <div class="mt-6 grid gap-3">
                    <a href="../prieres/<?= $enseignantId ?>" class="group flex items-center justify-between rounded-2xl border border-white/10 bg-black/10 p-4 transition hover:-translate-y-0.5 hover:border-[#D4AF37]/50 hover:bg-[#D4AF37]/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-hands-praying w-5 text-center text-[#D4AF37]"></i><span class="text-sm font-medium text-white">Mes prières</span></span>
                        <i class="fa-solid fa-arrow-right text-xs text-gray-500 transition group-hover:translate-x-1 group-hover:text-[#D4AF37]"></i>
                    </a>
                    <a href="../livres/<?= $enseignantId ?>" class="group flex items-center justify-between rounded-2xl border border-white/10 bg-black/10 p-4 transition hover:-translate-y-0.5 hover:border-[#D4AF37]/50 hover:bg-[#D4AF37]/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-book w-5 text-center text-[#D4AF37]"></i><span class="text-sm font-medium text-white">Ma bibliothèque</span></span>
                        <i class="fa-solid fa-arrow-right text-xs text-gray-500 transition group-hover:translate-x-1 group-hover:text-[#D4AF37]"></i>
                    </a>
                    <a href="../add/<?= $enseignantId ?>" class="group flex items-center justify-between rounded-2xl border border-white/10 bg-black/10 p-4 transition hover:-translate-y-0.5 hover:border-[#D4AF37]/50 hover:bg-[#D4AF37]/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-brain w-5 text-center text-[#D4AF37]"></i><span class="text-sm font-medium text-white">Nouvel enseignement Mâat</span></span>
                        <i class="fa-solid fa-arrow-right text-xs text-gray-500 transition group-hover:translate-x-1 group-hover:text-[#D4AF37]"></i>
                    </a>
                    <a href="../add_bolokele/<?= $enseignantId ?>" class="group flex items-center justify-between rounded-2xl border border-white/10 bg-black/10 p-4 transition hover:-translate-y-0.5 hover:border-[#D4AF37]/50 hover:bg-[#D4AF37]/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-lightbulb w-5 text-center text-[#D4AF37]"></i><span class="text-sm font-medium text-white">Nouvel enseignement Bolokele</span></span>
                        <i class="fa-solid fa-arrow-right text-xs text-gray-500 transition group-hover:translate-x-1 group-hover:text-[#D4AF37]"></i>
                    </a>
                </div>
            </section>
        </div>

        <section class="mt-6 flex flex-col gap-4 rounded-[1.5rem] border border-white/10 bg-white/[0.03] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-500">Contact professionnel</p>
                <a href="mailto:<?= $email ?>" class="mt-2 inline-block text-sm text-[#D4AF37] transition hover:text-white"><?= $email ?></a>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500"><i class="fa-solid fa-shield-heart text-[#D4AF37]"></i> Un espace dédié à la transmission</div>
        </section>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
