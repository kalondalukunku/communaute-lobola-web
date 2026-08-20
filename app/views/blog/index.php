<?php
    $title = $title ?? (SITE_NAME . ' | Blog');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow container mx-auto px-4 sm:px-6 py-10 max-w-6xl"
    x-data="blogList({
        posts: <?= htmlspecialchars(json_encode(array_values($posts), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>
    })">
    <section class="rounded-[2rem] border border-primary/20 bg-gradient-to-br from-[#130121] via-[#1d0c2d] to-[#0f172a] p-6 sm:p-8 shadow-2xl">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.28em] text-primary">
                <i class="fa-solid fa-quote-left"></i>
                Blog
            </span>
            <h1 class="mt-4 font-serif text-3xl sm:text-4xl text-white">Citations, conseils et expériences</h1>
            <p class="mt-4 text-sm sm:text-base text-gray-300 leading-relaxed">
                Un espace de partage spirituel pour inspirer, éclairer et grandir ensemble.
            </p>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-white/10 bg-white/5 p-4 sm:p-5 shadow-lg">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" x-model="searchQuery" placeholder="Rechercher un article, un conseil ou une citation..." class="w-full rounded-xl border border-white/10 bg-black/20 py-3 pl-10 pr-3 text-sm text-white placeholder:text-gray-500 focus:border-primary focus:outline-none">
            </div>

            <div class="flex items-center gap-2 text-xs text-gray-300">
                <label for="perPage" class="uppercase tracking-[0.2em] text-gray-400">Par page</label>
                <select id="perPage" x-model.number="perPage" class="rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white focus:border-primary focus:outline-none">
                    <option value="4">4</option>
                    <option value="6">6</option>
                    <option value="8">8</option>
                </select>
            </div>
        </div>
    </section>

    <section class="mt-8 space-y-6" x-show="filteredPosts.length > 0">
        <template x-for="post in paginatedPosts" :key="post.id">
            <article class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 shadow-lg">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="inline-block rounded-full border border-primary/20 bg-primary/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-primary" x-text="post.type"></span>
                        <h3 class="mt-3 text-xl font-semibold text-white" x-text="post.title"></h3>
                    </div>
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400" x-text="post.created_at"></span>
                </div>

                <p class="mt-4 text-sm leading-7 text-gray-100 line-clamp-4" x-text="excerpt(post.content)"></p>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">
                        <span x-text="post.author"></span>
                    </div>

                    <a :href="'/blog/show/' + post.id" class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold text-primary transition hover:bg-primary hover:text-black">
                        Lire l’article
                    </a>
                </div>
            </article>
        </template>
    </section>

    <div x-show="filteredPosts.length === 0" class="mt-8 rounded-[1.75rem] border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-300">
        <i class="fa-solid fa-magnifying-glass text-xl text-primary"></i>
        <p class="mt-3 text-sm">Aucun article ne correspond à votre recherche.</p>
    </div>

    <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" x-show="filteredPosts.length > 0">
        <p class="text-xs uppercase tracking-[0.2em] text-gray-400">
            Page <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
        </p>

        <div class="flex items-center gap-2">
            <button type="button" @click="prevPage()" :disabled="currentPage === 1" class="rounded-full border border-white/10 bg-black/20 px-3 py-2 text-xs font-semibold text-gray-200 disabled:opacity-40">
                Précédent
            </button>
            <button type="button" @click="nextPage()" :disabled="currentPage === totalPages" class="rounded-full border border-primary/20 bg-primary/10 px-3 py-2 text-xs font-semibold text-primary disabled:opacity-40">
                Suivant
            </button>
        </div>
    </div>
</main>

<script>
    function blogList({ posts }) {
        return {
            posts,
            searchQuery: '',
            currentPage: 1,
            perPage: 4,
            get filteredPosts() {
                const query = this.searchQuery.trim().toLowerCase();
                if (!query) return this.posts;

                return this.posts.filter((post) => {
                    const haystack = [
                        post.title,
                        post.type,
                        post.author,
                        post.content
                    ].join(' ').toLowerCase();
                    return haystack.includes(query);
                });
            },
            get totalPages() {
                return Math.max(1, Math.ceil(this.filteredPosts.length / this.perPage));
            },
            get paginatedPosts() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredPosts.slice(start, start + this.perPage);
            },
            excerpt(value) {
                const text = (value || '').replace(/\s+/g, ' ').trim();
                return text.length > 180 ? text.slice(0, 180) + '...' : text;
            },
            prevPage() {
                if (this.currentPage > 1) this.currentPage -= 1;
            },
            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage += 1;
            },
            init() {
                this.$watch('searchQuery', () => this.currentPage = 1);
                this.$watch('perPage', () => this.currentPage = 1);
            }
        };
    }
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
