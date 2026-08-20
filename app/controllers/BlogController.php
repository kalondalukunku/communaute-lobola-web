<?php

class BlogController extends Controller
{
    private $storageFile;

    public function __construct()
    {
        Auth::requireLogin(['membre', 'enseignant', 'admin']);
        $this->storageFile = APP_PATH . '../storage/blog_posts.json';
    }

    public function index()
    {
        $posts = $this->loadPosts();
        usort($posts, function ($a, $b) {
            return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
        });

        $data = [
            'title' => SITE_NAME . ' | Blog',
            'description' => 'Partagez des citations, conseils, expériences et échanges inspirants.',
            'posts' => $posts,
            'isAdmin' => Session::isLogged('admin'),
        ];

        $this->view('blog/index', $data);
    }

    public function show($postId)
    {
        $posts = $this->loadPosts();
        $post = null;

        foreach ($posts as $item) {
            if (($item['id'] ?? null) === $postId) {
                $post = $item;
                break;
            }
        }

        if (!$post) {
            Session::setFlash('error', 'Article introuvable.');
            Utils::redirect('/blog');
            return;
        }

        $data = [
            'title' => SITE_NAME . ' | ' . ($post['title'] ?? 'Article du blog'),
            'description' => ($post['content'] ?? 'Article du blog'),
            'post' => $post,
            'isAdmin' => Session::isLogged('admin'),
        ];

        $this->view('blog/show', $data);
    }

    public function add_comment($postId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Utils::redirect('/blog');
            return;
        }

        $content = trim($_POST['content'] ?? '');
        $replyTo = $_POST['reply_to'] ?? null;

        if ($content === '') {
            Session::setFlash('error', 'Le commentaire est vide.');
            Utils::redirect('/blog');
            return;
        }

        $user = Session::get('admin') ?? Session::get('enseignant') ?? Session::get('membre');
        $displayName = $user['nom'] ?? ($user['nom_postnom'] ?? 'Membre');
        $role = Session::isLogged('admin') ? 'admin' : (Session::isLogged('enseignant') ? 'enseignant' : 'membre');

        $posts = $this->loadPosts();
        $updated = false;

        foreach ($posts as &$post) {
            if ($post['id'] !== $postId) {
                continue;
            }

            $comment = [
                'id' => uniqid('comment_'),
                'author' => $displayName,
                'role' => $role,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'replies' => [],
            ];

            if ($replyTo && isset($post['comments'])) {
                $comment['reply_to'] = $replyTo;
                $this->appendReply($post['comments'], $replyTo, $comment);
            } else {
                $post['comments'][] = $comment;
            }

            $updated = true;
            break;
        }

        if (!$updated) {
            Session::setFlash('error', 'Publication introuvable.');
            Utils::redirect('/blog');
            return;
        }

        $this->savePosts($posts);
        Session::setFlash('success', 'Votre message a bien été ajouté.');
        Utils::redirect('/blog/show/' . $postId);
    }

    private function appendReply(&$comments, $replyTo, $newReply)
    {
        foreach ($comments as &$comment) {
            if (($comment['id'] ?? null) === $replyTo) {
                $comment['replies'][] = $newReply;
                return;
            }

            if (!empty($comment['replies'])) {
                $this->appendReply($comment['replies'], $replyTo, $newReply);
            }
        }
    }

    private function loadPosts()
    {
        if (!file_exists($this->storageFile)) {
            $this->savePosts([
                [
                    'id' => 'post_demo',
                    'title' => 'La sagesse commence par la conscience',
                    'type' => 'Conseil',
                    'content' => 'Le vrai changement commence par une conscience claire et un cœur humble. Prenez le temps de vous réajuster avant de juger les autres.',
                    'author' => 'Administrateur',
                    'role' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'comments' => [
                        [
                            'id' => 'comment_demo',
                            'author' => 'Membre',
                            'role' => 'membre',
                            'content' => 'Merci pour ce conseil. Il m’aide à mieux respirer dans les situations difficiles.',
                            'created_at' => date('Y-m-d H:i:s'),
                            'replies' => [
                                [
                                    'id' => 'reply_demo',
                                    'author' => 'Administrateur',
                                    'role' => 'admin',
                                    'content' => 'C’est exactement le but : inspirer la paix avant la réaction.',
                                    'created_at' => date('Y-m-d H:i:s'),
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $content = file_get_contents($this->storageFile);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    private function savePosts($posts)
    {
        $dir = dirname($this->storageFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($this->storageFile, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
