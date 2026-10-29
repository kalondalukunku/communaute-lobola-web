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

    <section class="mt-8 rounded-[1.75rem] border border-primary/20 bg-gradient-to-b from-[#0a1b1a] to-[#071311] p-5 shadow-2xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-primary font-bold">Lecteur audio</p>
                <h2 class="mt-2 text-xl font-semibold text-white">Comment prier</h2>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-primary">
                <i class="fa-solid fa-circle-play"></i>
                Audio guide
            </span>
        </div>

        <div class="mt-5 rounded-2xl border border-white/10 bg-black/20 p-4">
            <div class="flex items-center gap-3">
                <button id="prayerAudioToggle" type="button" class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-black shadow-lg shadow-primary/30 transition hover:scale-105 active:scale-95" aria-label="Lecture audio">
                    <i class="fa-solid fa-play text-lg" id="prayerAudioIcon"></i>
                </button>

                <div class="flex-1 rounded-full border border-white/10 bg-white/5 px-3 py-2">
                    <div class="relative h-1.5 w-full overflow-hidden rounded-full bg-white/10">
                        <div id="prayerAudioProgress" class="h-full w-0 rounded-full bg-primary transition-all"></div>
                    </div>
                </div>
            </div>

            <audio id="prayerAudio" preload="metadata" controlsList="nodownload" oncontextmenu="return false;">
                <source src="<?= htmlspecialchars($audioGuideUrl ?? '') ?>" type="audio/mpeg">
                Votre navigateur ne supporte pas l'audio HTML5.
            </audio>
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
        const prayerAudio = document.getElementById('prayerAudio');
        const prayerAudioToggle = document.getElementById('prayerAudioToggle');
        const prayerAudioIcon = document.getElementById('prayerAudioIcon');
        const prayerAudioProgress = document.getElementById('prayerAudioProgress');

        if (prayerAudio && prayerAudioToggle) {
            const setAudioState = () => {
                const isPlaying = !prayerAudio.paused && !prayerAudio.ended;
                prayerAudioIcon.classList.toggle('fa-play', !isPlaying);
                prayerAudioIcon.classList.toggle('fa-pause', isPlaying);
                prayerAudioToggle.setAttribute('aria-label', isPlaying ? 'Pause audio' : 'Lecture audio');
            };

            prayerAudioToggle.addEventListener('click', function () {
                if (prayerAudio.paused) {
                    prayerAudio.play();
                } else {
                    prayerAudio.pause();
                }
                setAudioState();
            });

            prayerAudio.addEventListener('play', setAudioState);
            prayerAudio.addEventListener('pause', setAudioState);
            prayerAudio.addEventListener('ended', setAudioState);
            prayerAudio.addEventListener('timeupdate', function () {
                if (!prayerAudio.duration) {
                    return;
                }
                const progress = (prayerAudio.currentTime / prayerAudio.duration) * 100;
                prayerAudioProgress.style.width = progress + '%';
            });
        }

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
