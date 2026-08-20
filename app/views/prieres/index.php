<?php
    $title = $title ?? (SITE_NAME . ' | Prières');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow container mx-auto px-4 sm:px-6 py-10 max-w-6xl">
    <section class="rounded-[2rem] border border-primary/20 bg-gradient-to-br from-[#130121] via-[#1d0c2d] to-[#0f172a] p-6 sm:p-8 shadow-2xl">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.28em] text-primary">
                <i class="fa-solid fa-hands-praying"></i>
                Prières
            </span>
            <h1 class="mt-4 font-serif text-3xl sm:text-4xl text-white">Prière de la Mère Divine</h1>
            <p class="mt-4 text-sm sm:text-base text-gray-300 leading-relaxed whitespace-pre-line">
                <?= htmlspecialchars($prieres[0]['intro']) ?>
            </p>
        </div>
    </section>

    <section class="mt-8">
        <?php foreach ($prieres as $index => $priere): ?>
            <article class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 shadow-lg backdrop-blur-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.28em] text-primary font-bold"><?= htmlspecialchars($priere['categorie']) ?></p>
                        <h2 class="mt-2 text-xl font-semibold text-white"><?= htmlspecialchars($priere['titre']) ?></h2>
                    </div>
                    <button type="button" class="copy-prayer-btn rounded-full border border-primary/20 bg-primary/10 px-3 py-2 text-xs font-semibold text-primary transition hover:bg-primary hover:text-black" data-prayer-index="<?= $index ?>">
                        <i class="fa-regular fa-copy mr-2"></i>Copier
                    </button>
                </div>

                <?php
                    $defaultLang = $priere['default_lang'] ?? array_key_first($priere['langues']);
                ?>
                <div class="mt-5">
                    <div class="mb-3 flex flex-wrap gap-2">
                        <?php foreach ($priere['langues'] as $code => $texte): ?>
                            <button type="button" class="language-btn rounded-full border px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide transition hover:border-primary hover:text-primary <?= $code === $defaultLang ? 'border-primary bg-primary/10 text-primary' : 'border-gray-700 text-gray-300' ?>" data-prayer-index="<?= $index ?>" data-lang="<?= htmlspecialchars($code) ?>">
                                <?= htmlspecialchars(strtoupper($code)) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                        <p class="text-sm leading-7 text-gray-100 prayer-text whitespace-pre-line" data-prayer-index="<?= $index ?>" data-default-lang="<?= htmlspecialchars($defaultLang) ?>">
                            <?= htmlspecialchars($priere['langues'][$defaultLang] ?? reset($priere['langues'])) ?>
                        </p>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const prayersData = <?= json_encode($prieres, JSON_UNESCAPED_UNICODE) ?>;

        document.querySelectorAll('.language-btn').forEach((button) => {
            button.addEventListener('click', function () {
                const prayerIndex = this.dataset.prayerIndex;
                const lang = this.dataset.lang;
                const textNode = document.querySelector('.prayer-text[data-prayer-index="' + prayerIndex + '"]');
                const pair = document.querySelectorAll('.language-btn[data-prayer-index="' + prayerIndex + '"]');

                pair.forEach((btn) => {
                    btn.classList.remove('border-primary', 'bg-primary/10', 'text-primary');
                    btn.classList.add('border-gray-700', 'text-gray-300');
                });

                this.classList.remove('border-gray-700', 'text-gray-300');
                this.classList.add('border-primary', 'bg-primary/10', 'text-primary');

                const prayer = prayersData[prayerIndex] || {};
                const content = prayer.langues && prayer.langues[lang] ? prayer.langues[lang] : (prayer.langues && prayer.langues.fr ? prayer.langues.fr : '');
                textNode.textContent = content;
            });
        });

        document.querySelectorAll('.copy-prayer-btn').forEach((button) => {
            button.addEventListener('click', function () {
                const prayerIndex = this.dataset.prayerIndex;
                const textNode = document.querySelector('.prayer-text[data-prayer-index="' + prayerIndex + '"]');
                const text = textNode.textContent.trim();

                if (!text) return;

                navigator.clipboard.writeText(text).then(() => {
                    const previous = this.innerHTML;
                    this.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Copié';
                    setTimeout(() => {
                        this.innerHTML = previous;
                    }, 1200);
                }).catch(() => {
                    this.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-2"></i>Erreur';
                });
            });
        });
    });
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
