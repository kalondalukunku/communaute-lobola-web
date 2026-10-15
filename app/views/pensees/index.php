<?php
    $title = $title ?? (SITE_NAME . ' | Pensées');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
?>

<main class="flex-grow container mx-auto max-w-4xl px-4 py-10 sm:px-6"
    x-data="thoughtFeed(<?= htmlspecialchars(json_encode(array_values($thoughts), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>)">
    <section class="rounded-[2rem] border border-primary/20 bg-gradient-to-br from-[#130121] via-[#1d0c2d] to-[#0f172a] p-6 shadow-2xl sm:p-8">
        <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[9px] font-bold uppercase text-primary">
            <i class="fa-solid fa-feather-pointed"></i> Communauté Lobola
        </span>
        <h1 class="mt-4 font-serif text-3xl text-white sm:text-4xl">Pensées</h1>
        <p class="mt-3 text-sm leading-6 text-gray-300">Laissez-vous inspirer par les pensées partagées par les administrateurs de la communauté.</p>
    </section>

    <div id="thought-message" class="mt-5 hidden rounded-xl border px-4 py-3 text-sm" role="status" aria-live="polite"></div>

    <section class="mt-8 space-y-5" aria-label="Liste des pensées">
        <template x-for="thought in paginatedThoughts" :key="thought.thought_id">
            <article class="thought-card rounded-[1.75rem] border border-white/10 bg-white/5 p-5 shadow-lg sm:p-6"
                :data-thought-id="thought.thought_id">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="fa-solid fa-feather-pointed"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-white" x-text="thought.author_name"></p>
                        <time class="text-xs text-gray-400" x-text="thought.created_at"></time>
                    </div>
                </div>

                <p class="thought-content mt-5 whitespace-pre-line break-words text-base leading-8 text-gray-100" x-text="thought.content"></p>

                <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-white/10 pb-4 text-xs text-gray-400">
                    <span><strong class="likes-count font-semibold text-gray-200" x-text="thought.likes_count"></strong> J’aime</span>
                    <span><strong class="comments-count font-semibold text-gray-200" x-text="thought.comments_count"></strong> commentaires</span>
                    <span><strong class="saves-count font-semibold text-gray-200" x-text="thought.saves_count"></strong> enregistrements</span>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex flex-wrap gap-1">
                        <button type="button" data-action="like" :aria-pressed="Number(thought.liked) ? 'true' : 'false'"
                            class="thought-action rounded-full px-3 py-2 text-xs font-semibold transition"
                            :class="Number(thought.liked) ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/10'">
                            <i class="fa-heart mr-1" :class="Number(thought.liked) ? 'fa-solid' : 'fa-regular'"></i>
                            <span x-text="Number(thought.liked) ? 'Aimé' : 'J’aime'"></span>
                        </button>
                        <button type="button" data-action="save" :aria-pressed="Number(thought.saved) ? 'true' : 'false'"
                            class="thought-action rounded-full px-3 py-2 text-xs font-semibold transition"
                            :class="Number(thought.saved) ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/10'">
                            <i class="fa-bookmark mr-1" :class="Number(thought.saved) ? 'fa-solid' : 'fa-regular'"></i>
                            <span x-text="Number(thought.saved) ? 'Enregistrée' : 'Enregistrer'"></span>
                        </button>
                        <button type="button" data-action="share" class="thought-action rounded-full px-3 py-2 text-xs font-semibold text-gray-300 transition hover:bg-white/10">
                            <i class="fa-solid fa-share-nodes mr-1"></i>Partager
                        </button>
                    </div>
                    <a :href="`${baseUrl}/pensees/show/${thought.thought_id}`"
                        class="rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold text-primary transition hover:bg-primary hover:text-black">
                        Voir la pensée <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </article>
        </template>

        <div x-show="thoughts.length === 0" class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-300">
            <i class="fa-regular fa-lightbulb text-2xl text-primary"></i>
            <p class="mt-3 text-sm">Aucune pensée n’a encore été publiée.</p>
        </div>
    </section>

    <div x-show="thoughts.length > 0" class="mt-7 flex items-center justify-between gap-3">
        <p class="text-xs text-gray-400">
            Page <strong class="text-white" x-text="currentPage"></strong> /
            <strong class="text-white" x-text="totalPages"></strong>
        </p>
        <div class="flex gap-2">
            <button type="button" @click="currentPage--" :disabled="currentPage <= 1"
                class="rounded-full border border-white/10 bg-black/20 px-4 py-2 text-xs font-semibold text-gray-200 transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40">
                <i class="fa-solid fa-arrow-left mr-1"></i>Précédent
            </button>
            <button type="button" @click="currentPage++" :disabled="currentPage >= totalPages"
                class="rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold text-primary transition hover:bg-primary/20 disabled:cursor-not-allowed disabled:opacity-40">
                Suivant<i class="fa-solid fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>
</main>

<script>
    function thoughtFeed(thoughts) {
        return {
            thoughts,
            currentPage: 1,
            perPage: 6,
            baseUrl: <?= json_encode(rtrim(BASE_URL, '/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            get totalPages() {
                return Math.max(1, Math.ceil(this.thoughts.length / this.perPage));
            },
            get paginatedThoughts() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.thoughts.slice(start, start + this.perPage);
            }
        };
    }
</script>

<script>
(() => {
    const csrfToken = <?= json_encode($csrfToken, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const message = document.getElementById('thought-message');
    const baseUrl = <?= json_encode(rtrim(BASE_URL, '/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    function notify(text, isError = false) {
        message.textContent = text;
        message.className = `mt-5 rounded-xl border px-4 py-3 text-sm ${isError ? 'border-red-400/30 bg-red-500/10 text-red-200' : 'border-primary/30 bg-primary/10 text-primary'}`;
        message.classList.remove('hidden');
        window.clearTimeout(notify.timeout);
        notify.timeout = window.setTimeout(() => message.classList.add('hidden'), 4000);
    }

    async function request(path) {
        const response = await fetch(`${baseUrl}/pensees/${path}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
            body: JSON.stringify({})
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'La demande a échoué.');
        return result;
    }

    function updateToggle(button, active, activeText, inactiveText, icon) {
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
        button.classList.toggle('bg-primary/20', active);
        button.classList.toggle('text-primary', active);
        button.classList.toggle('text-gray-300', !active);
        button.classList.toggle('hover:bg-white/10', !active);
        button.querySelector('i').className = `fa-${active ? 'solid' : 'regular'} ${icon} mr-1`;
        button.querySelector('span').textContent = active ? activeText : inactiveText;
    }

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button) return;
        const card = button.closest('.thought-card');
        const thoughtId = card.dataset.thoughtId;
        button.disabled = true;
        try {
            if (button.dataset.action === 'like') {
                const result = await request(`toggle_like/${thoughtId}`);
                card.querySelector('.likes-count').textContent = result.likes_count;
                updateToggle(button, result.liked, 'Aimé', 'J’aime', 'fa-heart');
                const feed = window.Alpine.$data(card.closest('[x-data]'));
                const thought = feed.thoughts.find((item) => String(item.thought_id) === thoughtId);
                if (thought) {
                    thought.liked = result.liked;
                    thought.likes_count = result.likes_count;
                }
            } else if (button.dataset.action === 'save') {
                const result = await request(`toggle_save/${thoughtId}`);
                card.querySelector('.saves-count').textContent = result.saves_count;
                updateToggle(button, result.saved, 'Enregistrée', 'Enregistrer', 'fa-bookmark');
                const feed = window.Alpine.$data(card.closest('[x-data]'));
                const thought = feed.thoughts.find((item) => String(item.thought_id) === thoughtId);
                if (thought) {
                    thought.saved = result.saved;
                    thought.saves_count = result.saves_count;
                }
            } else if (button.dataset.action === 'share') {
                const shareUrl = `${baseUrl}/pensees/show/${thoughtId}`;
                if (navigator.share) {
                    await navigator.share({
                        title: 'Pensée de la communauté',
                        text: card.querySelector('.thought-content').textContent,
                        url: shareUrl
                    });
                } else if (navigator.clipboard) {
                    await navigator.clipboard.writeText(shareUrl);
                    notify('Lien copié. Vous pouvez partager cette pensée.');
                } else {
                    throw new Error('Le partage n’est pas disponible sur cet appareil.');
                }
            }
        } catch (error) {
            if (error.name !== 'AbortError') notify(error.message, true);
        } finally {
            button.disabled = false;
        }
    });
})();
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
