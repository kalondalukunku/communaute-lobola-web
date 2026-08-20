<?php 
    $title = "Admin - Blog";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

<main class="flex-grow flex flex-col min-w-0">
    <header class="h-24 bg-paper backdrop-blur-md border-b border-gray-100 px-3 flex justify-between items-center sticky top-0 z-40">
        <div class="pl-2">
            <h1 class="font-serif text-xl md:text-md font-bold text-primary">Publier dans le blog</h1>
            <p class="text-xs text-gray-400 mt-1 font-medium italic">Citation, conseil, expérience ou autre</p>
        </div>
    </header>

    <div class="p-6 lg:p-10">
        <div class="max-w-4xl mx-auto rounded-[2rem] border border-primary/20 bg-white/5 p-6 shadow-xl">
            <form method="POST" action="/admin/create_blog" class="space-y-5">
                <div>
                    <label for="type" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Type</label>
                    <select id="type" name="type" class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white focus:border-primary focus:outline-none">
                        <option value="Citation">Citation</option>
                        <option value="Conseil">Conseil</option>
                        <option value="Expérience">Expérience</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <div>
                    <label for="title" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Titre</label>
                    <input id="title" type="text" name="title" class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white placeholder:text-gray-500 focus:border-primary focus:outline-none" placeholder="Ex: La sagesse commence par la conscience" required>
                </div>

                <div>
                    <label for="content" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Contenu</label>
                    <textarea id="content" name="content" rows="8" class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white placeholder:text-gray-500 focus:border-primary focus:outline-none" placeholder="Rédigez votre message, conseil ou expérience..." required></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-full bg-primary px-6 py-3 text-sm font-bold text-black transition hover:bg-primary/80">
                        Publier le blog
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
