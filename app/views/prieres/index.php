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
            <h1 class="mt-4 font-serif text-3xl sm:text-4xl text-white">Les prières de la communauté</h1>
            <p class="mt-4 text-sm sm:text-base text-gray-300 leading-relaxed">
                Une sélection de séries et d’audios mis en ligne par les enseignants, dans un espace réservé visible seulement depuis la page de prières.
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

            <audio id="prayerAudio" preload="auto" controlsList="nodownload" oncontextmenu="return false;">
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

    <?php if (!empty($prieresAudio)): ?>
        <section class="mt-12">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.28em] text-primary">
                        <span class="h-px w-8 bg-primary"></span>
                        Audios de la communauté
                    </p>
                    <h2 class="mt-2 font-serif text-2xl text-white sm:text-3xl">Prières audio publiées par les enseignants</h2>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-gray-400">
                    <i class="fa-solid fa-headphones text-primary"></i>
                    <?= count($prieresAudio) ?> audio<?= count($prieresAudio) > 1 ? 's' : '' ?>
                </span>
            </div>

            <div id="teacherPrayerAudioCards" class="grid gap-5 md:grid-cols-2">
                <?php foreach ($prieresAudio as $audioIndex => $audio): ?>
                    <article class="teacher-prayer-card group relative overflow-hidden rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-white/[0.09] to-white/[0.03] p-5 shadow-xl shadow-black/10 backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-primary/10" data-audio-index="<?= $audioIndex ?>">
                        <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-primary/10 blur-2xl transition duration-500 group-hover:bg-primary/20"></div>
                        <div class="relative">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/15 text-primary ring-1 ring-primary/20">
                                    <i class="fa-solid fa-waveform-lines text-lg"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[10px] font-bold uppercase tracking-[0.22em] text-primary"><?= htmlspecialchars($audio['serie']) ?></p>
                                    <h3 class="mt-1 truncate text-lg font-semibold text-white"><?= htmlspecialchars($audio['titre']) ?></h3>
                                </div>
                                <span class="teacherPrayerAudioDuration shrink-0 rounded-full border border-primary/20 bg-primary/10 px-2.5 py-1 text-[10px] font-semibold text-primary" aria-live="polite">--:--</span>
                            </div>

                            <?php if (!empty($audio['description'])): ?>
                                <p class="mt-4 line-clamp-2 text-sm leading-6 text-gray-400"><?= htmlspecialchars($audio['description']) ?></p>
                            <?php endif; ?>

                            <div class="mt-5 rounded-2xl border border-white/10 bg-black/20 p-4">
                                <div class="flex items-center gap-3">
                                    <button type="button" class="teacherPrayerAudioToggle flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-black shadow-lg shadow-primary/30 transition hover:scale-105 active:scale-95" aria-label="Lecture audio">
                                        <i class="fa-solid fa-play text-lg teacherPrayerAudioIcon"></i>
                                    </button>

                                    <div class="flex-1 rounded-full border border-white/10 bg-white/5 px-3 py-2">
                                        <div class="relative h-1.5 w-full overflow-hidden rounded-full bg-white/10">
                                            <div class="teacherPrayerAudioProgress h-full w-0 rounded-full bg-primary transition-all"></div>
                                        </div>
                                    </div>
                                </div>

                                <audio class="teacherPrayerAudio hidden" preload="auto" controlsList="nodownload" oncontextmenu="return false;">
                                    <source src="<?= htmlspecialchars(BASE_URL . '/' . ltrim($audio['audio_url'], '/')) ?>" type="audio/mpeg">
                                    Votre navigateur ne supporte pas l’audio HTML5.
                                </audio>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <nav id="teacherPrayerPagination" class="mt-7 flex items-center justify-center gap-2" aria-label="Pagination des prières audio"></nav>
        </section>
    <?php endif; ?>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const prayersData = <?= json_encode($prieres, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
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
                    const playRequest = prayerAudio.play();
                    if (playRequest && typeof playRequest.catch === 'function') {
                        playRequest.catch(() => {
                            prayerAudioIcon.classList.remove('fa-pause');
                            prayerAudioIcon.classList.add('fa-play');
                        });
                    }
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

        document.querySelectorAll('.teacherPrayerAudioToggle').forEach((toggle) => {
            const player = toggle.closest('.teacherPrayerAudioPlayer') || toggle.closest('article');
            const audio = player ? player.querySelector('.teacherPrayerAudio') : null;
            const icon = player ? player.querySelector('.teacherPrayerAudioIcon') : null;
            const progress = player ? player.querySelector('.teacherPrayerAudioProgress') : null;

            if (!audio || !icon || !progress) {
                return;
            }

            const setTeacherAudioState = () => {
                const isPlaying = !audio.paused && !audio.ended;
                icon.classList.toggle('fa-play', !isPlaying);
                icon.classList.toggle('fa-pause', isPlaying);
                toggle.setAttribute('aria-label', isPlaying ? 'Pause audio' : 'Lecture audio');
            };

            toggle.addEventListener('click', function () {
                if (audio.paused) {
                    document.querySelectorAll('.teacherPrayerAudio').forEach((otherAudio) => {
                        if (otherAudio !== audio) {
                            otherAudio.pause();
                        }
                    });

                    const playRequest = audio.play();
                    if (playRequest && typeof playRequest.catch === 'function') {
                        playRequest.catch(setTeacherAudioState);
                    }
                } else {
                    audio.pause();
                }
                setTeacherAudioState();
            });

            audio.addEventListener('play', setTeacherAudioState);
            audio.addEventListener('pause', setTeacherAudioState);
            audio.addEventListener('ended', setTeacherAudioState);
            audio.addEventListener('timeupdate', function () {
                if (audio.duration) {
                    progress.style.width = ((audio.currentTime / audio.duration) * 100) + '%';
                }
            });
            audio.addEventListener('loadedmetadata', function () {
                const durationBadge = player.querySelector('.teacherPrayerAudioDuration');
                if (!durationBadge || !Number.isFinite(audio.duration)) {
                    return;
                }

                const totalSeconds = Math.round(audio.duration);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = String(totalSeconds % 60).padStart(2, '0');
                durationBadge.textContent = minutes + ':' + seconds;
            });
        });

        const teacherPrayerCards = Array.from(document.querySelectorAll('.teacher-prayer-card'));
        const teacherPrayerPagination = document.getElementById('teacherPrayerPagination');
        const cardsPerPage = 4;

        if (teacherPrayerCards.length && teacherPrayerPagination) {
            const pageCount = Math.ceil(teacherPrayerCards.length / cardsPerPage);
            let currentPage = 1;

            const renderTeacherPrayerPage = (page) => {
                currentPage = page;
                const firstCard = (page - 1) * cardsPerPage;

                teacherPrayerCards.forEach((card, index) => {
                    card.classList.toggle('hidden', index < firstCard || index >= firstCard + cardsPerPage);
                });

                teacherPrayerPagination.innerHTML = '';
                for (let pageNumber = 1; pageNumber <= pageCount; pageNumber += 1) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = pageNumber;
                    button.className = 'flex h-9 w-9 items-center justify-center rounded-full border text-xs font-semibold transition ' +
                        (pageNumber === currentPage
                            ? 'border-primary bg-primary text-black'
                            : 'border-white/10 bg-white/5 text-gray-300 hover:border-primary hover:text-primary');
                    button.setAttribute('aria-label', 'Page ' + pageNumber);
                    button.setAttribute('aria-current', pageNumber === currentPage ? 'page' : 'false');
                    button.addEventListener('click', () => renderTeacherPrayerPage(pageNumber));
                    teacherPrayerPagination.appendChild(button);
                }
            };

            renderTeacherPrayerPage(1);
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

                const prayer = prayersData[Number(prayerIndex)] || {};
                const value = prayer.langues && prayer.langues[lang] ? prayer.langues[lang] : '';
                if (textNode) {
                    textNode.textContent = value;
                }
            });
        });


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
