<?php
    $title = SITE_NAME . ' | Pensées';
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow flex w-full flex-col"
    x-data="adminThoughts(<?= htmlspecialchars(json_encode(array_values($thoughts), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>)">
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
            <h1 class="font-serif text-xl font-bold text-primary">Pensées</h1>
            <p class="mt-1 text-xs font-medium italic text-gray-400">Gérez les pensées publiées dans la communauté</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/ajouter_pensee"
            class="shrink-0 rounded-full bg-primary px-4 py-3 text-xs font-bold text-black transition hover:bg-primary/80 sm:px-6 sm:text-sm">
            <i class="fa-solid fa-plus"></i>
            <span class="ml-1">Ajouter une pensée</span>
        </a>
    </header>

    <div class="p-4 sm:p-6 lg:p-10">
        <section class="mx-auto max-w-5xl">
            <div class="mb-5 flex flex-col gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 sm:flex-row sm:items-center sm:justify-between">
                <label class="relative block w-full sm:max-w-xl">
                    <span class="sr-only">Rechercher une pensée</span>
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-primary"></i>
                    <input type="search" x-model="searchQuery" placeholder="Rechercher dans les pensées ou par auteur…"
                        class="w-full rounded-xl border border-white/10 bg-black/20 py-3 pl-10 pr-3 text-sm text-white placeholder:text-gray-500 focus:border-primary focus:outline-none">
                </label>
                <div class="flex items-center gap-2 text-xs text-gray-300">
                    <label for="thoughts-per-page" class="uppercase tracking-wider text-gray-400">Par page</label>
                    <select id="thoughts-per-page" x-model.number="perPage"
                        class="rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white focus:border-primary focus:outline-none">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>

            <div class="mb-4 flex items-center justify-between text-xs text-gray-400">
                <span><strong class="text-white" x-text="filteredThoughts.length"></strong> pensée(s)</span>
                <span x-show="filteredThoughts.length > 0">Page <strong class="text-white" x-text="currentPage"></strong> / <strong class="text-white" x-text="totalPages"></strong></span>
            </div>

            <div class="space-y-4" x-show="paginatedThoughts.length > 0">
                <template x-for="thought in paginatedThoughts" :key="thought.thought_id">
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-5 shadow-lg sm:p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <i class="fa-solid fa-feather-pointed"></i>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-white" x-text="thought.author_name"></p>
                                    <time class="text-xs text-gray-400" x-text="thought.created_at"></time>
                                </div>
                            </div>
                            <!-- <span class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-semibold text-primary">
                                Pensée #<span x-text="thought.thought_id"></span>
                            </span> -->
                        </div>
                        <p class="mt-5 whitespace-pre-line break-words text-sm leading-7 text-gray-100" x-text="thought.content"></p>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-white/10 pt-4">
                            <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs text-gray-400 my-auto">
                                <span><i class="fa-regular fa-heart mr-1 text-primary"></i><strong class="text-gray-200" x-text="thought.likes_count"></strong> J’aime</span>
                                <span><i class="fa-regular fa-comment mr-1 text-primary"></i><strong class="text-gray-200" x-text="thought.comments_count"></strong> commentaires</span>
                                <span><i class="fa-regular fa-bookmark mr-1 text-primary"></i><strong class="text-gray-200" x-text="thought.saves_count"></strong> enregistrements</span>
                            </div>
                            <div class="flex flex-wrap justify-end gap-2">
                                <a :href="'<?= BASE_URL ?>/admin/modifier_pensee/' + thought.thought_id"
                                    class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold text-primary transition hover:bg-primary hover:text-black">
                                    <i class="fa-solid fa-pen-to-square"></i>Modifier le texte
                                </a>
                                <form method="POST" :action="'<?= BASE_URL ?>/admin/supprimer_pensee/' + thought.thought_id"
                                    onsubmit="return confirm('Supprimer définitivement cette pensée ainsi que ses commentaires et réactions ?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-500/10 px-4 py-2 text-xs font-semibold text-red-300 transition hover:bg-red-500 hover:text-white">
                                        <i class="fa-solid fa-trash"></i>Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <div x-show="filteredThoughts.length === 0" class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-300">
                <i class="fa-regular fa-lightbulb text-2xl text-primary"></i>
                <p class="mt-3 text-sm" x-text="searchQuery ? 'Aucune pensée ne correspond à votre recherche.' : 'Aucune pensée publiée pour le moment.'"></p>
            </div>

            <div x-show="filteredThoughts.length > 0" class="mt-6 flex items-center justify-between gap-3">
                <button type="button" @click="currentPage--" :disabled="currentPage <= 1"
                    class="rounded-full border border-white/10 bg-black/20 px-4 py-2 text-xs font-semibold text-gray-200 transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40">
                    <i class="fa-solid fa-arrow-left mr-1"></i>Précédent
                </button>
                <button type="button" @click="currentPage++" :disabled="currentPage >= totalPages"
                    class="rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold text-primary transition hover:bg-primary/20 disabled:cursor-not-allowed disabled:opacity-40">
                    Suivant<i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </section>
    </div>
</main>

 </section>

<script>
    function adminThoughts(thoughts) {
        return {
            thoughts,
            searchQuery: '',
            perPage: 5,
            currentPage: 1,
            get filteredThoughts() {
                const query = this.searchQuery.trim().toLocaleLowerCase();
                if (!query) return this.thoughts;
                return this.thoughts.filter((thought) =>
                    `${thought.content} ${thought.author_name} ${thought.created_at}`.toLocaleLowerCase().includes(query)
                );
            },
            get totalPages() {
                return Math.max(1, Math.ceil(this.filteredThoughts.length / this.perPage));
            },
            get paginatedThoughts() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredThoughts.slice(start, start + this.perPage);
            },
            init() {
                this.$watch('searchQuery', () => this.currentPage = 1);
                this.$watch('perPage', () => this.currentPage = 1);
            }
        };
    }
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
