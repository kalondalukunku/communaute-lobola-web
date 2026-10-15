<?php
require_once APP_PATH . 'models/Pensee.php';

class PenseesController extends Controller
{
    public function index()
    {
        Auth::requireLogin('membre');
        $member = Session::get('membre');
        $csrfToken = $this->csrfToken();
        $model = new Pensee();

        $this->view('pensees/index', [
            'title' => SITE_NAME . ' | Pensées',
            'description' => 'Les pensées partagées par les administrateurs de la communauté.',
            'thoughts' => $model->listForMember((string) $member['member_id']),
            'csrfToken' => $csrfToken,
        ]);
    }

    public function show($thoughtId)
    {
        Auth::requireLogin('membre');
        $member = Session::get('membre');

        try {
            $thought = (new Pensee())->findForMember(
                $this->validThoughtId($thoughtId),
                (string) $member['member_id']
            );
        } catch (InvalidArgumentException $exception) {
            $thought = null;
        }

        if (!$thought) {
            Session::setFlash('error', 'Cette pensée est introuvable.');
            Utils::redirect('/pensees');
            return;
        }

        $this->view('pensees/show', [
            'title' => SITE_NAME . ' | Pensée',
            'description' => mb_substr($thought['content'], 0, 160),
            'thought' => $thought,
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function reponses($commentId)
    {
        Auth::requireLogin('membre');
        $member = Session::get('membre');
        $id = filter_var($commentId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $thread = $id === false
            ? null
            : (new Pensee())->findCommentThread((int) $id, (string) $member['member_id']);

        if (!$thread) {
            Session::setFlash('error', 'Ce fil de commentaires est introuvable.');
            Utils::redirect('/pensees');
            return;
        }

        $thought = (new Pensee())->findForMember($thread['thought_id'], (string) $member['member_id']);
        if (!$thought) {
            Session::setFlash('error', 'Cette pensée est introuvable.');
            Utils::redirect('/pensees');
            return;
        }

        $this->view('pensees/reponses', [
            'title' => SITE_NAME . ' | Réponses au commentaire',
            'description' => mb_substr($thread['root_comment']['content'], 0, 160),
            'thread' => $thread,
            'thought' => $thought,
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function toggle_like($thoughtId)
    {
        $this->runAction(function (Pensee $model, string $memberId, array $payload) use ($thoughtId) {
            return $model->toggleLike($this->validThoughtId($thoughtId), $memberId);
        });
    }

    public function toggle_save($thoughtId)
    {
        $this->runAction(function (Pensee $model, string $memberId, array $payload) use ($thoughtId) {
            return $model->toggleSave($this->validThoughtId($thoughtId), $memberId);
        });
    }

    public function comment($thoughtId)
    {
        $this->runAction(function (Pensee $model, string $memberId, array $payload) use ($thoughtId) {
            $content = trim($payload['content'] ?? '');
            if ($content === '' || mb_strlen($content) > 2000) {
                throw new InvalidArgumentException('Le commentaire doit contenir entre 1 et 2 000 caractères.');
            }

            $parentId = null;
            if (isset($payload['parent_id']) && $payload['parent_id'] !== '') {
                $parentId = $this->validId($payload['parent_id']);
            }

            $member = Session::get('membre');
            return $model->addComment(
                $this->validThoughtId($thoughtId),
                $memberId,
                trim((string) ($member['nom_postnom'] ?? 'Membre')),
                $content,
                $parentId
            );
        });
    }

    public function toggle_comment_like($commentId)
    {
        $this->runAction(function (Pensee $model, string $memberId, array $payload) use ($commentId) {
            $id = $this->validId($commentId);
            if (!$model->findComment($id)) {
                throw new InvalidArgumentException('Ce commentaire est introuvable.');
            }
            return $model->toggleCommentLike($id, $memberId);
        });
    }

    private function runAction(callable $action): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(405, ['message' => 'Méthode non autorisée.']);
            return;
        }

        $member = Session::get('membre');
        if (!$member || empty($member['member_id'])) {
            $this->respond(401, ['message' => 'Connectez-vous avec un compte membre pour continuer.']);
            return;
        }

        $providedToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $sessionToken = $_SESSION['pensees_csrf'] ?? '';
        if (!is_string($providedToken) || $sessionToken === '' || !hash_equals($sessionToken, $providedToken)) {
            $this->respond(403, ['message' => 'Votre session a expiré. Rechargez la page puis réessayez.']);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $this->respond(400, ['message' => 'La requête envoyée est invalide.']);
            return;
        }

        try {
            $result = $action(new Pensee(), (string) $member['member_id'], $payload);
            $this->respond(200, ['success' => true] + $result);
        } catch (InvalidArgumentException $exception) {
            $this->respond(422, ['message' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            error_log('Erreur dans les pensées : ' . $exception->getMessage());
            $this->respond(500, ['message' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }

    private function validId($value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            throw new InvalidArgumentException('Identifiant invalide.');
        }
        return $id;
    }

    private function validThoughtId($value): string
    {
        if (!is_string($value) || !preg_match('/^[a-f0-9]{32}$/i', $value)) {
            throw new InvalidArgumentException('Identifiant de pensée invalide.');
        }
        return strtolower($value);
    }

    private function csrfToken(): string
    {
        if (empty($_SESSION['pensees_csrf'])) {
            $_SESSION['pensees_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['pensees_csrf'];
    }

    private function respond(int $status, array $data): void
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
