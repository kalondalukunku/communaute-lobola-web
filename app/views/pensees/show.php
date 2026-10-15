<?php
    $title = $title ?? (SITE_NAME . ' | Pensée');
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar.php';
?>

<main class="flex-grow container mx-auto max-w-4xl px-4 py-10 sm:px-6">
    <a href="<?= BASE_URL ?>/pensees" class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-primary/80">
        <i class="fa-solid fa-arrow-left"></i> Toutes les pensées
    </a>

    <div id="thought-message" class="mt-5 hidden rounded-xl border px-4 py-3 text-sm" role="status" aria-live="polite"></div>

    <article id="pensee-<?= htmlspecialchars($thought['thought_id'], ENT_QUOTES, 'UTF-8') ?>" class="thought-card mt-5 rounded-[1.75rem] border border-white/10 bg-white/5 p-5 shadow-xl sm:p-8"
        data-thought-id="<?= htmlspecialchars($thought['thought_id'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <i class="fa-solid fa-feather-pointed"></i>
            </span>
            <div>
                <p class="text-sm font-semibold text-white"><?= htmlspecialchars($thought['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
                <time class="text-xs text-gray-400"><?= htmlspecialchars($thought['created_at'], ENT_QUOTES, 'UTF-8') ?></time>
            </div>
        </div>

        <p class="thought-content mt-6 whitespace-pre-line break-words text-base leading-8 text-gray-100"><?= htmlspecialchars($thought['content'], ENT_QUOTES, 'UTF-8') ?></p>

        <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-white/10 pb-4 text-xs text-gray-400">
            <span><strong class="likes-count font-semibold text-gray-200"><?= (int) $thought['likes_count'] ?></strong> J’aime</span>
            <span><strong class="comments-count font-semibold text-gray-200"><?= (int) $thought['comments_count'] ?></strong> commentaires</span>
            <span><strong class="saves-count font-semibold text-gray-200"><?= (int) $thought['saves_count'] ?></strong> enregistrements</span>
        </div>

        <div class="mt-3 flex flex-wrap gap-1">
            <button type="button" data-action="like" aria-pressed="<?= $thought['liked'] ? 'true' : 'false' ?>"
                class="thought-action rounded-full px-3 py-2 text-xs font-semibold transition <?= $thought['liked'] ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/10' ?>">
                <i class="fa-<?= $thought['liked'] ? 'solid' : 'regular' ?> fa-heart mr-1"></i><span><?= $thought['liked'] ? 'Aimé' : 'J’aime' ?></span>
            </button>
            <button type="button" data-action="save" aria-pressed="<?= $thought['saved'] ? 'true' : 'false' ?>"
                class="thought-action rounded-full px-3 py-2 text-xs font-semibold transition <?= $thought['saved'] ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/10' ?>">
                <i class="fa-<?= $thought['saved'] ? 'solid' : 'regular' ?> fa-bookmark mr-1"></i><span><?= $thought['saved'] ? 'Enregistrée' : 'Enregistrer' ?></span>
            </button>
            <button type="button" data-action="share" class="thought-action rounded-full px-3 py-2 text-xs font-semibold text-gray-300 transition hover:bg-white/10">
                <i class="fa-solid fa-share-nodes mr-1"></i>Partager
            </button>
        </div>

        <section class="mt-8" aria-labelledby="comments-heading">
            <div class="flex justify-between gap-6">
                <h2 id="comments-heading" class="text-md font-semibold text-white my-auto">
                    Commentaires (<span class="comments-count"><?= (int) $thought['comments_count'] ?></span>)
                </h2>

                <button type="button" data-action="comment"
                    class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-xs font-bold text-black transition hover:bg-primary/80 my-auto">
                    <i class="fa-regular fa-comment"></i> Écrire un commentaire
                </button>
            </div>
            

            <div class="comments-list mt-6 space-y-4">
                <?php foreach ($thought['comments'] as $comment): ?>
                    <?php renderThoughtDetailComment($comment); ?>
                <?php endforeach; ?>
                <?php if (empty($thought['comments'])): ?>
                    <p class="comments-empty text-sm text-gray-400">Aucun commentaire pour le moment. Soyez le premier à réagir.</p>
                <?php endif; ?>
            </div>

            <div id="reply-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
                role="dialog" aria-modal="true" aria-labelledby="reply-modal-title">
                <div class="w-full max-w-lg rounded-2xl border border-primary/20 bg-paper p-5 shadow-2xl sm:p-7">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 id="reply-modal-title" class="font-serif text-2xl text-white">Écrire un commentaire</h2>
                            <p id="reply-modal-description" class="mt-1 text-sm text-gray-400">Partagez votre réaction à cette pensée.</p>
                            <p id="reply-modal-author" class="mt-2 hidden text-xs font-semibold text-primary"></p>
                        </div>
                        <button type="button" data-close-reply-modal aria-label="Fermer la fenêtre"
                            class="rounded-full p-2 text-gray-400 transition hover:bg-white/10 hover:text-white">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form class="comment-form reply-modal-form flex flex-col gap-4" data-parent-id="">
                        <textarea name="content" rows="5" maxlength="2000" required placeholder="Écrire un commentaire…"
                            class="w-full resize-y rounded-xl border border-white/10 px-4 py-3 text-sm leading-6 text-white placeholder:text-gray-500 focus:border-primary focus:outline-none" style="color: #fff !important;"></textarea>
                        <div class="flex justify-end gap-3">
                            <button type="button" data-close-reply-modal
                                class="rounded-full border border-white/10 px-5 py-2.5 text-xs font-semibold text-gray-300 transition hover:bg-white/10">
                                Annuler
                            </button>
                            <button type="submit" class="reply-modal-submit rounded-full bg-primary px-5 py-2.5 text-xs font-bold text-black transition hover:bg-primary/80">
                                Publier le commentaire
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </article>
</main>

<?php
function renderThoughtDetailComment(array $comment): void
{
?>
    <article class="thought-comment rounded-xl border border-white/10 bg-black/20 p-4" data-comment-id="<?= (int) $comment['comment_id'] ?>">
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
        <a href="<?= BASE_URL ?>/pensees/reponses/<?= (int) $comment['comment_id'] ?>"
            class="mt-3 inline-flex text-xs font-semibold text-primary hover:text-primary/80">
            <?= (int) ($comment['replies_count'] ?? 0) > 0
                ? 'Voir les réponses (' . (int) $comment['replies_count'] . ')'
                : 'Répondre' ?>
        </a>
    </article>
<?php
}
?>

<script>
(() => {
    const csrfToken = <?= json_encode($csrfToken, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const baseUrl = <?= json_encode(rtrim(BASE_URL, '/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const message = document.getElementById('thought-message');
    const card = document.querySelector('.thought-card');
    const replyModal = document.getElementById('reply-modal');
    const replyForm = replyModal.querySelector('.reply-modal-form');
    const replyTextarea = replyForm.querySelector('textarea[name="content"]');
    const replyTitle = document.getElementById('reply-modal-title');
    const replyDescription = document.getElementById('reply-modal-description');
    const replySubmit = replyForm.querySelector('.reply-modal-submit');
    let replyTrigger = null;

    function closeReplyModal() {
        replyModal.classList.add('hidden');
        replyModal.classList.remove('flex');
        replyForm.reset();
        replyForm.dataset.parentId = '';
        replyTitle.textContent = 'Écrire un commentaire';
        replyDescription.textContent = 'Partagez votre réaction à cette pensée.';
        replyTextarea.placeholder = 'Écrire un commentaire…';
        replySubmit.textContent = 'Publier le commentaire';
        document.body.classList.remove('overflow-hidden');
        if (replyTrigger?.isConnected) replyTrigger.focus();
        replyTrigger = null;
    }

    function openCommentModal({ trigger }) {
        replyForm.dataset.parentId = '';
        replyTrigger = trigger;
        replyTitle.textContent = 'Écrire un commentaire';
        replyDescription.textContent = 'Partagez votre réaction à cette pensée.';
        replyTextarea.placeholder = 'Écrire un commentaire…';
        replySubmit.textContent = 'Publier le commentaire';
        replyModal.classList.remove('hidden');
        replyModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        replyTextarea.focus();
    }

    function notify(text, isError = false) {
        message.textContent = text;
        message.className = `mt-5 rounded-xl border px-4 py-3 text-sm ${isError ? 'border-red-400/30 bg-red-500/10 text-red-200' : 'border-primary/30 bg-primary/10 text-primary'}`;
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

    function updateToggle(button, active, activeText, inactiveText, iconName) {
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
        button.classList.toggle('bg-primary/20', active);
        button.classList.toggle('text-primary', active);
        button.classList.toggle('text-gray-300', !active);
        button.classList.toggle('hover:bg-white/10', !active);
        button.querySelector('i').className = `fa-${active ? 'solid' : 'regular'} ${iconName} mr-1`;
        button.querySelector('span').textContent = active ? activeText : inactiveText;
    }

    function makeComment(comment) {
        const article = document.createElement('article');
        article.className = 'thought-comment rounded-xl border border-white/10 bg-black/20 p-4';
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
        const likeCount = document.createElement('span');
        likeCount.className = 'comment-likes-count';
        likeCount.textContent = '0';
        like.append(icon, likeCount);
        header.append(identity, like);

        const content = document.createElement('p');
        content.className = 'mt-2 whitespace-pre-line break-words text-sm leading-6 text-gray-200';
        content.textContent = comment.content;
        const repliesLink = document.createElement('a');
        repliesLink.href = `${baseUrl}/pensees/reponses/${comment.comment_id}`;
        repliesLink.className = 'mt-3 inline-flex text-xs font-semibold text-primary hover:text-primary/80';
        repliesLink.textContent = 'Répondre';
        article.append(header, content, repliesLink);
        return article;
    }

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button || !card.contains(button)) return;
        const thoughtId = card.dataset.thoughtId;
        const action = button.dataset.action;

        if (action === 'comment') {
            openCommentModal({ trigger: button });
            return;
        }

        button.disabled = true;
        try {
            if (action === 'like') {
                const result = await request(`toggle_like/${thoughtId}`);
                card.querySelector('.likes-count').textContent = result.likes_count;
                updateToggle(button, result.liked, 'Aimé', 'J’aime', 'fa-heart');
            } else if (action === 'save') {
                const result = await request(`toggle_save/${thoughtId}`);
                card.querySelector('.saves-count').textContent = result.saves_count;
                updateToggle(button, result.saved, 'Enregistrée', 'Enregistrer', 'fa-bookmark');
            } else if (action === 'comment-like') {
                const comment = button.closest('.thought-comment');
                const result = await request(`toggle_comment_like/${comment.dataset.commentId}`);
                button.setAttribute('aria-pressed', result.liked ? 'true' : 'false');
                button.classList.toggle('text-primary', result.liked);
                button.classList.toggle('text-gray-400', !result.liked);
                button.querySelector('i').className = `fa-${result.liked ? 'solid' : 'regular'} fa-heart`;
                button.querySelector('.comment-likes-count').textContent = result.likes_count;
            } else if (action === 'share') {
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

    replyModal.addEventListener('click', (event) => {
        if (event.target === replyModal || event.target.closest('[data-close-reply-modal]')) {
            closeReplyModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !replyModal.classList.contains('hidden')) {
            closeReplyModal();
        }
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('.comment-form');
        if (!form || !card.contains(form)) return;
        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        const textarea = form.querySelector('textarea[name="content"]');
        const content = textarea.value.trim();
        if (!content) return;

        button.disabled = true;
        try {
            const result = await request(`comment/${card.dataset.thoughtId}`, {
                content,
                parent_id: form.dataset.parentId || null
            });
            const commentElement = makeComment(result.comment);
            card.querySelector('.comments-empty')?.remove();
            card.querySelector('.comments-list').append(commentElement);
            card.querySelectorAll('.comments-count').forEach((counter) => {
                counter.textContent = result.comments_count;
            });
            form.reset();
            if (form === replyForm) closeReplyModal();
            notify('Votre commentaire a été publié.');
        } catch (error) {
            notify(error.message, true);
        } finally {
            if (button.isConnected) button.disabled = false;
        }
    });
})();
</script>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
