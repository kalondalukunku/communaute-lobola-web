<?php
    $title = $title ?? (SITE_NAME . ' | Réponses');
    $rootComment = $thread['root_comment'];
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
?>

<main class="flex-grow container mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <a href="<?= BASE_URL ?>/pensees/show/<?= htmlspecialchars($thought['thought_id'], ENT_QUOTES, 'UTF-8') ?>"
        class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-primary/80">
        <i class="fa-solid fa-arrow-left"></i> Retour à la pensée
    </a>

    <article class="mt-5 rounded-[1.75rem] border border-white/10 bg-white/5 p-5 shadow-xl sm:p-8"
        id="reply-thread" data-thought-id="<?= htmlspecialchars($thought['thought_id'], ENT_QUOTES, 'UTF-8') ?>"
        data-root-id="<?= (int) $rootComment['comment_id'] ?>">
        <div class="border-b border-white/10 pb-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-primary">Fil de discussion</p>
            <p class="mt-3 whitespace-pre-line break-words text-sm leading-6 text-gray-300">
                <?= htmlspecialchars($thought['content'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

        <section class="mt-6" aria-labelledby="thread-heading">
            <h1 id="thread-heading" class="mb-4 text-lg font-semibold text-white">Fil de discussion</h1>
            <div class="space-y-3">
                <?php renderPenseeThreadComment($rootComment, true); ?>
            </div>

            <h2 class="mt-7 text-sm font-semibold text-gray-300">
                Réponses (<span class="replies-count"><?= (int) $rootComment['replies_count'] ?></span>)
            </h2>
            <div class="thread-replies mt-3 space-y-3" aria-live="polite">
                <?php foreach ($thread['replies'] as $reply): ?>
                    <?php renderPenseeThreadComment($reply); ?>
                <?php endforeach; ?>
                <?php if (!$thread['replies']): ?>
                    <p class="thread-empty rounded-xl border border-dashed border-white/10 px-4 py-6 text-center text-sm text-gray-400">
                        Aucune réponse pour le moment. Soyez le premier à répondre.
                    </p>
                <?php endif; ?>
            </div>

            <form class="thread-reply-form mt-6 flex flex-col gap-3">
                <label for="reply-content" class="text-sm font-semibold text-white">Votre réponse</label>
                <textarea id="reply-content" name="content" rows="4" maxlength="2000" required
                    placeholder="Écrire une réponse…"
                    class="w-full resize-y rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm leading-6 text-white placeholder:text-gray-500 focus:border-primary focus:outline-none" style="color: #fff;"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-full bg-primary px-5 py-2.5 text-xs font-bold text-black transition hover:bg-primary/80">
                        Publier la réponse
                    </button>
                </div>
            </form>
        </section>
        <div id="thread-message" class="mt-4 hidden rounded-xl border px-4 py-3 text-sm" role="status" aria-live="polite"></div>
    </article>
</main>

<?php
function renderPenseeThreadComment(array $comment, bool $isRoot = false): void
{
?>
    <article class="thread-comment rounded-xl border border-white/10 bg-black/20 p-4" data-comment-id="<?= (int) $comment['comment_id'] ?>">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <p class="text-[12px] my-auto font-semibold text-white"><?= htmlspecialchars($comment['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
                <time class="text-[10px] my-auto text-gray-500"><?= htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8') ?></time>
            </div>
            <button type="button" data-action="comment-like" aria-pressed="<?= $comment['liked'] ? 'true' : 'false' ?>"
                class="inline-flex items-center gap-1 text-xs <?= $comment['liked'] ? 'text-primary' : 'text-gray-400 hover:text-primary' ?>">
                <i class="fa-<?= $comment['liked'] ? 'solid' : 'regular' ?> fa-heart"></i>
                <span class="comment-likes-count"><?= (int) $comment['likes_count'] ?></span>
            </button>
        </div>
        <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-gray-200"><?= htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php if (!$isRoot): ?>
            <!-- <span class="mt-3 inline-flex text-xs text-gray-500">Réponse au commentaire principal</span> -->
        <?php endif; ?>
    </article>
<?php
}
?>

<script>
(() => {
    const thread = document.getElementById('reply-thread');
    const form = thread.querySelector('.thread-reply-form');
    const message = document.getElementById('thread-message');
    const csrfToken = <?= json_encode($csrfToken, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const baseUrl = <?= json_encode(rtrim(BASE_URL, '/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    function notify(text, isError = false) {
        message.textContent = text;
        message.className = `mt-4 rounded-xl border px-4 py-3 text-sm ${isError ? 'border-red-400/30 bg-red-500/10 text-red-200' : 'border-primary/30 bg-primary/10 text-primary'}`;
        message.classList.remove('hidden');
        window.clearTimeout(notify.timeout);
        notify.timeout = window.setTimeout(() => message.classList.add('hidden'), 4000);
    }

    async function request(path, payload = {}) {
        const response = await fetch(`${baseUrl}/pensees/${path}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
            body: JSON.stringify(payload)
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'La demande a échoué.');
        return result;
    }

    function makeReply(comment) {
        const article = document.createElement('article');
        article.className = 'thread-comment rounded-xl border border-white/10 bg-black/20 p-4';
        article.dataset.commentId = comment.comment_id;
        const header = document.createElement('div');
        header.className = 'flex items-start justify-between gap-3';
        const identity = document.createElement('div');
        const author = document.createElement('p');
        author.className = 'text-sm font-semibold text-white';
        author.textContent = comment.author_name;
        const time = document.createElement('time');
        time.className = 'text-[10px] text-gray-500';
        time.textContent = comment.created_at;
        identity.append(author, time);
        const like = document.createElement('button');
        like.type = 'button';
        like.dataset.action = 'comment-like';
        like.setAttribute('aria-pressed', 'false');
        like.className = 'inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary';
        const icon = document.createElement('i');
        icon.className = 'fa-regular fa-heart';
        const count = document.createElement('span');
        count.className = 'comment-likes-count';
        count.textContent = '0';
        like.append(icon, count);
        header.append(identity, like);
        const content = document.createElement('p');
        content.className = 'mt-2 whitespace-pre-line break-words text-sm leading-6 text-gray-200';
        content.textContent = comment.content;
        const note = document.createElement('span');
        note.className = 'mt-3 inline-flex text-xs text-gray-500';
        note.textContent = 'Réponse au commentaire principal';
        article.append(header, content, note);
        return article;
    }

    thread.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action="comment-like"]');
        if (!button || !thread.contains(button)) return;
        button.disabled = true;
        try {
            const comment = button.closest('.thread-comment');
            const result = await request(`toggle_comment_like/${comment.dataset.commentId}`);
            button.setAttribute('aria-pressed', result.liked ? 'true' : 'false');
            button.classList.toggle('text-primary', result.liked);
            button.classList.toggle('text-gray-400', !result.liked);
            button.querySelector('i').className = `fa-${result.liked ? 'solid' : 'regular'} fa-heart`;
            button.querySelector('.comment-likes-count').textContent = result.likes_count;
        } catch (error) {
            notify(error.message, true);
        } finally {
            button.disabled = false;
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        const textarea = form.querySelector('textarea[name="content"]');
        const content = textarea.value.trim();
        if (!content) return;
        button.disabled = true;
        try {
            const result = await request(`comment/${thread.dataset.thoughtId}`, {
                content,
                parent_id: thread.dataset.rootId
            });
            thread.querySelector('.thread-empty')?.remove();
            thread.querySelector('.thread-replies').append(makeReply(result.comment));
            thread.querySelector('.replies-count').textContent = result.replies_count;
            form.reset();
            notify('Votre réponse a été publiée.');
        } catch (error) {
            notify(error.message, true);
        } finally {
            button.disabled = false;
        }
    });
})();
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
