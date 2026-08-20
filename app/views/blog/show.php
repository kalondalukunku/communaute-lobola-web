<?php
    $title = $title ?? (SITE_NAME . ' | Article');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow container mx-auto px-4 sm:px-6 py-10 max-w-4xl">
    <article class="rounded-[2rem] border border-white/10 bg-white/5 p-6 sm:p-8 shadow-2xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="inline-block rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-primary"><?= htmlspecialchars($post['type'] ?? 'Article') ?></span>
                <h1 class="mt-4 font-serif text-3xl sm:text-4xl text-white"><?= htmlspecialchars($post['title'] ?? 'Article du blog') ?></h1>
            </div>
            <a href="/blog" class="inline-flex items-center rounded-full border border-white/10 bg-black/20 px-4 py-2 text-xs font-semibold text-gray-200 transition hover:border-primary hover:text-primary">
                <i class="fa-solid fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="mt-6 flex items-center gap-3 text-xs uppercase tracking-[0.2em] text-gray-400">
            <span><?= htmlspecialchars($post['author'] ?? 'Auteur') ?></span>
            <span>•</span>
            <span><?= htmlspecialchars($post['created_at'] ?? '') ?></span>
        </div>

        <div class="mt-8 rounded-2xl border border-white/10 bg-black/20 p-5">
            <p class="text-base leading-8 text-gray-100 whitespace-pre-line"><?= nl2br(htmlspecialchars($post['content'] ?? '')) ?></p>
        </div>

        <section class="mt-10 rounded-2xl border border-white/10 bg-black/20 p-5">
            <h2 class="text-lg font-semibold text-white">Commentaires</h2>

            <?php foreach ($post['comments'] ?? [] as $comment): ?>
                <div class="mt-4 rounded-xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-white"><?= htmlspecialchars($comment['author'] ?? 'Membre') ?></p>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400"><?= htmlspecialchars($comment['role'] ?? 'membre') ?></p>
                        </div>
                        <span class="text-[10px] text-gray-500"><?= htmlspecialchars($comment['created_at'] ?? '') ?></span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-200"><?= nl2br(htmlspecialchars($comment['content'] ?? '')) ?></p>

                    <?php foreach ($comment['replies'] ?? [] as $reply): ?>
                        <div class="mt-3 ml-4 rounded-xl border border-primary/10 bg-primary/5 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-white"><?= htmlspecialchars($reply['author'] ?? 'Réponse') ?></p>
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400"><?= htmlspecialchars($reply['role'] ?? 'admin') ?></p>
                                </div>
                                <span class="text-[10px] text-gray-500"><?= htmlspecialchars($reply['created_at'] ?? '') ?></span>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-gray-200"><?= nl2br(htmlspecialchars($reply['content'] ?? '')) ?></p>
                        </div>
                    <?php endforeach; ?>

                    <div class="mt-3 flex justify-end">
                        <button type="button" class="reply-toggle text-xs font-semibold text-primary hover:text-primary/80" data-comment-id="<?= htmlspecialchars($comment['id'] ?? '') ?>">
                            Répondre
                        </button>
                    </div>

                    <form method="POST" action="/blog/add_comment/<?= htmlspecialchars($post['id'] ?? '') ?>" class="reply-form mt-3 hidden">
                        <input type="hidden" name="reply_to" value="<?= htmlspecialchars($comment['id'] ?? '') ?>">
                        <textarea name="content" rows="2" class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white focus:border-primary focus:outline-none" placeholder="Répondre à ce commentaire..."></textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="submit" class="rounded-full border border-primary/20 bg-primary/10 px-3 py-2 text-xs font-semibold text-primary hover:bg-primary hover:text-black">
                                Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>

            <form method="POST" action="/blog/add_comment/<?= htmlspecialchars($post['id'] ?? '') ?>" class="mt-6 space-y-3">
                <textarea name="content" rows="4" class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white focus:border-primary focus:outline-none" placeholder="Posez votre commentaire..."></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold text-primary hover:bg-primary hover:text-black">
                        Commenter
                    </button>
                </div>
            </form>
        </section>
    </article>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.reply-toggle').forEach((button) => {
            button.addEventListener('click', function () {
                const form = this.closest('.rounded-xl')?.querySelector('.reply-form');
                if (form) {
                    form.classList.toggle('hidden');
                }
            });
        });
    });
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
