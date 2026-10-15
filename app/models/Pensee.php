<?php
require_once APP_PATH . 'core/Model.php';

class Pensee extends Model
{
    protected $table = 'pensees';

    public function __construct()
    {
        parent::__construct();
        $this->migrateLegacyTables();

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS pensees (
                thought_id CHAR(32) NOT NULL,
                author_id VARCHAR(64) NOT NULL,
                author_name VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (thought_id),
                KEY idx_pensees_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS pensees_likes (
                thought_id CHAR(32) NOT NULL,
                member_id VARCHAR(64) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (thought_id, member_id),
                CONSTRAINT fk_pensees_likes_pensee FOREIGN KEY (thought_id)
                    REFERENCES pensees (thought_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS pensees_enregistrements (
                thought_id CHAR(32) NOT NULL,
                member_id VARCHAR(64) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (thought_id, member_id),
                CONSTRAINT fk_pensees_enregistrements_pensee FOREIGN KEY (thought_id)
                    REFERENCES pensees (thought_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS pensees_commentaires (
                comment_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                thought_id CHAR(32) NOT NULL,
                parent_id BIGINT UNSIGNED DEFAULT NULL,
                member_id VARCHAR(64) NOT NULL,
                author_name VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (comment_id),
                KEY idx_pensees_commentaires_pensee (thought_id),
                KEY idx_pensees_commentaires_parent (parent_id),
                CONSTRAINT fk_pensees_commentaires_pensee FOREIGN KEY (thought_id)
                    REFERENCES pensees (thought_id) ON DELETE CASCADE,
                CONSTRAINT fk_pensees_commentaires_parent FOREIGN KEY (parent_id)
                    REFERENCES pensees_commentaires (comment_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS pensees_commentaires_likes (
                comment_id BIGINT UNSIGNED NOT NULL,
                member_id VARCHAR(64) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (comment_id, member_id),
                CONSTRAINT fk_pensees_commentaires_likes_commentaire FOREIGN KEY (comment_id)
                    REFERENCES pensees_commentaires (comment_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $this->migrateThoughtIdsToUuid();
    }

    private function migrateLegacyTables(): void
    {
        $tables = [
            'thought_comment_likes' => 'pensees_commentaires_likes',
            'thought_comments' => 'pensees_commentaires',
            'thought_likes' => 'pensees_likes',
            'thought_saves' => 'pensees_enregistrements',
            'thoughts' => $this->table,
        ];
        $exists = $this->db->prepare("
            SELECT COUNT(*)
            FROM information_schema.tables
            WHERE table_schema = DATABASE() AND table_name = :table_name
        ");

        foreach ($tables as $oldName => $newName) {
            $exists->execute(['table_name' => $oldName]);
            $hasOldTable = (int) $exists->fetchColumn() > 0;
            $exists->execute(['table_name' => $newName]);
            $hasNewTable = (int) $exists->fetchColumn() > 0;

            if ($hasOldTable && $hasNewTable) {
                throw new RuntimeException(
                    "Migration des tables de pensées impossible : les tables $oldName et $newName existent toutes les deux."
                );
            }

            if ($hasOldTable) {
                $this->db->exec("RENAME TABLE `$oldName` TO `$newName`");
            }
        }
    }

    private function migrateThoughtIdsToUuid(): void
    {
        $column = $this->db->prepare("
            SELECT COLUMN_TYPE
            FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = 'thought_id'
        ");
        $column->execute(['table_name' => $this->table]);
        $columnType = $column->fetchColumn();
        if (!$columnType) {
            return;
        }

        $parentIsUuid = stripos((string) $columnType, 'char(32)') === 0;
        $childTables = ['pensees_likes', 'pensees_enregistrements', 'pensees_commentaires'];
        $childColumn = $this->db->prepare("
            SELECT COLUMN_TYPE
            FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = 'thought_id'
        ");
        $childrenAreUuid = true;
        foreach ($childTables as $childTable) {
            $childColumn->execute(['table_name' => $childTable]);
            $childColumnType = $childColumn->fetchColumn();
            if (!$childColumnType || stripos((string) $childColumnType, 'char(32)') !== 0) {
                $childrenAreUuid = false;
                break;
            }
        }
        $foreignKeyCount = (int) $this->db->query("
            SELECT COUNT(DISTINCT table_name)
            FROM information_schema.key_column_usage
            WHERE table_schema = DATABASE()
                AND referenced_table_name = '{$this->table}'
                AND referenced_column_name = 'thought_id'
        ")->fetchColumn();
        if ($parentIsUuid && $childrenAreUuid && $foreignKeyCount === count($childTables)) {
            return;
        }

        if (!$parentIsUuid) {
            if (!$this->columnExists($this->table, 'uuid_temp')) {
                $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN uuid_temp CHAR(32) NULL");
            }
            $thoughts = $this->db->query("SELECT thought_id, uuid_temp FROM {$this->table}")->fetchAll(PDO::FETCH_ASSOC);
            $updateThought = $this->db->prepare("UPDATE {$this->table} SET uuid_temp = :uuid WHERE thought_id = :id");
            foreach ($thoughts as $thought) {
                if ($thought['uuid_temp'] === null) {
                    $updateThought->execute([
                        'uuid' => Utils::generateUuidV4(),
                        'id' => $thought['thought_id'],
                    ]);
                }
            }
        }

        foreach ($childTables as $childTable) {
            $childColumn->execute(['table_name' => $childTable]);
            $childColumnType = $childColumn->fetchColumn();
            if ($childColumnType && stripos((string) $childColumnType, 'char(32)') === 0) {
                continue;
            }

            if (!$this->columnExists($childTable, 'uuid_temp')) {
                $this->db->exec("ALTER TABLE `$childTable` ADD COLUMN uuid_temp CHAR(32) NULL");
            }
            if ($parentIsUuid && !$this->columnExists($this->table, 'uuid_temp')) {
                throw new RuntimeException(
                    "Migration de $childTable impossible : correspondance UUID des pensées introuvable."
                );
            }

            $mapping = $this->db->query("SELECT thought_id, uuid_temp FROM {$this->table}")->fetchAll(PDO::FETCH_KEY_PAIR);
            $updateChild = $this->db->prepare("
                UPDATE `$childTable`
                SET uuid_temp = :uuid
                WHERE thought_id = :legacy_id AND uuid_temp IS NULL
            ");
            foreach ($mapping as $legacyId => $uuid) {
                $updateChild->execute(['uuid' => $uuid, 'legacy_id' => $legacyId]);
            }
            $missingIds = (int) $this->db->query(
                "SELECT COUNT(*) FROM `$childTable` WHERE uuid_temp IS NULL"
            )->fetchColumn();
            if ($missingIds > 0) {
                throw new RuntimeException(
                    "Migration de $childTable impossible : $missingIds identifiant(s) de pensée sans correspondance."
                );
            }
        }

        $foreignKeys = $this->db->query("
            SELECT DISTINCT table_name, constraint_name
            FROM information_schema.key_column_usage
            WHERE table_schema = DATABASE()
                AND referenced_table_name = '{$this->table}'
                AND referenced_column_name = 'thought_id'
        ")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($foreignKeys as $foreignKey) {
            $tableName = str_replace('`', '``', $foreignKey['table_name']);
            $constraintName = str_replace('`', '``', $foreignKey['constraint_name']);
            $this->db->exec("ALTER TABLE `$tableName` DROP FOREIGN KEY `$constraintName`");
        }

        foreach (['pensees_likes', 'pensees_enregistrements'] as $childTable) {
            $childColumn->execute(['table_name' => $childTable]);
            $childColumnType = $childColumn->fetchColumn();
            if ($childColumnType && stripos((string) $childColumnType, 'char(32)') !== 0) {
                $this->db->exec(
                    "ALTER TABLE `$childTable` DROP PRIMARY KEY, DROP COLUMN thought_id,
                    CHANGE uuid_temp thought_id CHAR(32) NOT NULL, ADD PRIMARY KEY (thought_id, member_id)"
                );
            }
        }

        $childColumn->execute(['table_name' => 'pensees_commentaires']);
        $commentThoughtColumnType = $childColumn->fetchColumn();
        if ($commentThoughtColumnType && stripos((string) $commentThoughtColumnType, 'char(32)') !== 0) {
            $indexes = $this->db->query("
                SELECT DISTINCT index_name
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                    AND table_name = 'pensees_commentaires'
                    AND column_name = 'thought_id'
                    AND index_name <> 'PRIMARY'
            ")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($indexes as $indexName) {
                $escapedIndexName = str_replace('`', '``', $indexName);
                $this->db->exec("ALTER TABLE pensees_commentaires DROP INDEX `$escapedIndexName`");
            }
            $this->db->exec("
                ALTER TABLE pensees_commentaires
                DROP COLUMN thought_id,
                CHANGE uuid_temp thought_id CHAR(32) NOT NULL,
                ADD KEY idx_pensees_commentaires_pensee (thought_id)
            ");
        }

        if (!$parentIsUuid) {
            $this->db->exec("
                ALTER TABLE {$this->table}
                DROP PRIMARY KEY,
                CHANGE thought_id legacy_thought_id BIGINT UNSIGNED NOT NULL,
                CHANGE uuid_temp thought_id CHAR(32) NOT NULL,
                ADD PRIMARY KEY (thought_id)
            ");
            $this->db->exec("ALTER TABLE {$this->table} DROP COLUMN legacy_thought_id");
        }

        $constraints = $this->db->query("
            SELECT DISTINCT table_name, constraint_name
            FROM information_schema.key_column_usage
            WHERE table_schema = DATABASE()
                AND referenced_table_name = '{$this->table}'
                AND referenced_column_name = 'thought_id'
        ")->fetchAll(PDO::FETCH_ASSOC);
        $existingConstraints = [];
        foreach ($constraints as $constraint) {
            $existingConstraints[$constraint['table_name']] = true;
        }
        foreach ([
            'pensees_likes' => 'fk_pensees_likes_pensee',
            'pensees_enregistrements' => 'fk_pensees_enregistrements_pensee',
            'pensees_commentaires' => 'fk_pensees_commentaires_pensee',
        ] as $childTable => $constraintName) {
            if (!isset($existingConstraints[$childTable])) {
                $this->db->exec("
                    ALTER TABLE `$childTable`
                    ADD CONSTRAINT `$constraintName` FOREIGN KEY (thought_id)
                        REFERENCES {$this->table} (thought_id) ON DELETE CASCADE
                ");
            }
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = :column_name
        ");
        $stmt->execute(['table_name' => $table, 'column_name' => $column]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(string $authorId, string $authorName, string $content): bool
    {
        $content = $this->validateContent($content);
        $thoughtId = Utils::generateUuidV4();
        $stmt = $this->db->prepare(
            "INSERT INTO $this->table (thought_id, author_id, author_name, content)
            VALUES (:thought_id, :author_id, :author_name, :content)"
        );
        return $stmt->execute([
            'thought_id' => $thoughtId,
            'author_id' => $authorId,
            'author_name' => $authorName,
            'content' => $content,
        ]);
    }

    public function update(string $thoughtId, string $content): bool
    {
        $content = $this->validateContent($content);
        $this->assertThoughtExists($thoughtId);
        $stmt = $this->db->prepare("UPDATE $this->table SET content = :content WHERE thought_id = :thought_id");
        return $stmt->execute(['content' => $content, 'thought_id' => $thoughtId]);
    }

    public function delete(string $thoughtId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE thought_id = :thought_id");
        $stmt->execute(['thought_id' => $thoughtId]);
        return $stmt->rowCount() > 0;
    }

    public function find(string $thoughtId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE thought_id = :thought_id LIMIT 1");
        $stmt->execute(['thought_id' => $thoughtId]);
        $thought = $stmt->fetch(PDO::FETCH_ASSOC);
        return $thought ?: null;
    }

    public function all(): array
    {
        return $this->allForAdmin();
    }

    public function mostPopular(): array
    {
        $stmt = $this->db->query("
            SELECT ranked.*
            FROM (
                SELECT t.thought_id, t.author_name, t.content, t.created_at,
                    (SELECT COUNT(*) FROM pensees_likes l WHERE l.thought_id = t.thought_id) AS likes_count,
                    (SELECT COUNT(*) FROM pensees_commentaires c WHERE c.thought_id = t.thought_id) AS comments_count
                FROM $this->table t
            ) AS ranked
            ORDER BY
                (ranked.likes_count + ranked.comments_count) DESC,
                ranked.likes_count DESC,
                ranked.comments_count DESC,
                ranked.created_at DESC,
                ranked.thought_id DESC
            LIMIT 2
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function feed(string $memberId): array
    {
        $thoughts = $this->listForMember($memberId);
        if (!$thoughts) {
            return [];
        }

        $thoughtIds = array_column($thoughts, 'thought_id');
        $commentsByThought = $this->commentsForThoughts($thoughtIds, $memberId);
        foreach ($thoughts as &$thought) {
            $thought['comments'] = $this->rootComments($commentsByThought[$thought['thought_id']] ?? []);
        }
        unset($thought);

        return $thoughts;
    }

    public function listForMember(string $memberId): array
    {
        $stmt = $this->db->prepare("
            SELECT t.thought_id, t.author_name, t.content, t.created_at,
                (SELECT COUNT(*) FROM pensees_likes l WHERE l.thought_id = t.thought_id) AS likes_count,
                (SELECT COUNT(*) FROM pensees_commentaires c WHERE c.thought_id = t.thought_id) AS comments_count,
                (SELECT COUNT(*) FROM pensees_enregistrements s WHERE s.thought_id = t.thought_id) AS saves_count,
                EXISTS(SELECT 1 FROM pensees_likes l WHERE l.thought_id = t.thought_id AND l.member_id = :like_member) AS liked,
                EXISTS(SELECT 1 FROM pensees_enregistrements s WHERE s.thought_id = t.thought_id AND s.member_id = :save_member) AS saved
            FROM $this->table t
            ORDER BY t.created_at DESC, t.thought_id DESC
        ");
        $stmt->execute(['like_member' => $memberId, 'save_member' => $memberId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findForMember(string $thoughtId, string $memberId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT t.thought_id, t.author_name, t.content, t.created_at,
                (SELECT COUNT(*) FROM pensees_likes l WHERE l.thought_id = t.thought_id) AS likes_count,
                (SELECT COUNT(*) FROM pensees_commentaires c WHERE c.thought_id = t.thought_id) AS comments_count,
                (SELECT COUNT(*) FROM pensees_enregistrements s WHERE s.thought_id = t.thought_id) AS saves_count,
                EXISTS(SELECT 1 FROM pensees_likes l WHERE l.thought_id = t.thought_id AND l.member_id = :like_member) AS liked,
                EXISTS(SELECT 1 FROM pensees_enregistrements s WHERE s.thought_id = t.thought_id AND s.member_id = :save_member) AS saved
            FROM $this->table t
            WHERE t.thought_id = :thought_id
            LIMIT 1
        ");
        $stmt->execute([
            'like_member' => $memberId,
            'save_member' => $memberId,
            'thought_id' => $thoughtId,
        ]);
        $thought = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$thought) {
            return null;
        }

        $commentsByThought = $this->commentsForThoughts([$thoughtId], $memberId);
        $thought['comments'] = $this->rootComments($commentsByThought[$thoughtId] ?? []);
        return $thought;
    }

    public function findCommentThread(int $commentId, string $memberId): ?array
    {
        $lookup = $this->db->prepare(
            'SELECT thought_id FROM pensees_commentaires WHERE comment_id = :comment_id'
        );
        $lookup->execute(['comment_id' => $commentId]);
        $thoughtId = $lookup->fetchColumn();
        if (!$thoughtId) {
            return null;
        }

        $commentsByThought = $this->commentsForThoughts([(string) $thoughtId], $memberId);
        $comments = $commentsByThought[$thoughtId] ?? [];
        $commentsById = [];
        foreach ($comments as $comment) {
            $commentsById[(int) $comment['comment_id']] = $comment;
        }
        if (!isset($commentsById[$commentId])) {
            return null;
        }

        $root = $commentsById[$commentId];
        $visited = [];
        while ($root['parent_id'] !== null) {
            $parentId = (int) $root['parent_id'];
            if (isset($visited[$parentId]) || !isset($commentsById[$parentId])) {
                return null;
            }
            $visited[$parentId] = true;
            $root = $commentsById[$parentId];
        }

        $replies = [];
        foreach ($comments as $comment) {
            if ((int) $comment['comment_id'] === (int) $root['comment_id']) {
                continue;
            }
            $ancestor = $comment;
            $visited = [];
            while ($ancestor['parent_id'] !== null) {
                $parentId = (int) $ancestor['parent_id'];
                if ($parentId === (int) $root['comment_id']) {
                    $replies[] = $comment;
                    break;
                }
                if (isset($visited[$parentId]) || !isset($commentsById[$parentId])) {
                    break;
                }
                $visited[$parentId] = true;
                $ancestor = $commentsById[$parentId];
            }
        }

        usort($replies, static function (array $left, array $right): int {
            $dateOrder = strcmp((string) $left['created_at'], (string) $right['created_at']);
            return $dateOrder !== 0
                ? $dateOrder
                : ((int) $left['comment_id'] <=> (int) $right['comment_id']);
        });

        $root['replies_count'] = count($replies);
        return [
            'thought_id' => (string) $thoughtId,
            'root_comment' => $root,
            'replies' => $replies,
        ];
    }

    private function commentsForThoughts(array $thoughtIds, string $memberId): array
    {
        if (!$thoughtIds) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($thoughtIds), '?'));
        $commentsStmt = $this->db->prepare("
            SELECT c.comment_id, c.thought_id, c.parent_id, c.member_id, c.author_name, c.content, c.created_at,
                (SELECT COUNT(*) FROM pensees_commentaires_likes l WHERE l.comment_id = c.comment_id) AS likes_count,
                EXISTS(SELECT 1 FROM pensees_commentaires_likes l WHERE l.comment_id = c.comment_id AND l.member_id = ?) AS liked
            FROM pensees_commentaires c
            WHERE c.thought_id IN ($placeholders)
            ORDER BY c.created_at ASC, c.comment_id ASC
        ");
        $commentsStmt->execute(array_merge([$memberId], $thoughtIds));
        $commentsByThought = [];
        foreach ($commentsStmt->fetchAll(PDO::FETCH_ASSOC) as $comment) {
            $comment['replies'] = [];
            $commentsByThought[$comment['thought_id']][] = $comment;
        }

        return $commentsByThought;
    }

    public function allForAdmin(): array
    {
        $stmt = $this->db->query("
            SELECT t.thought_id, t.author_name, t.content, t.created_at,
                (SELECT COUNT(*) FROM pensees_likes l WHERE l.thought_id = t.thought_id) AS likes_count,
                (SELECT COUNT(*) FROM pensees_commentaires c WHERE c.thought_id = t.thought_id) AS comments_count,
                (SELECT COUNT(*) FROM pensees_enregistrements s WHERE s.thought_id = t.thought_id) AS saves_count
            FROM $this->table t
            ORDER BY t.created_at DESC, t.thought_id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleLike(string $thoughtId, string $memberId): array
    {
        $this->assertThoughtExists($thoughtId);
        $stmt = $this->db->prepare('INSERT IGNORE INTO pensees_likes (thought_id, member_id) VALUES (?, ?)');
        $stmt->execute([$thoughtId, $memberId]);
        $liked = $stmt->rowCount() === 1;
        if (!$liked) {
            $delete = $this->db->prepare('DELETE FROM pensees_likes WHERE thought_id = ? AND member_id = ?');
            $delete->execute([$thoughtId, $memberId]);
        }

        return [
            'liked' => $liked,
            'likes_count' => $this->count('pensees_likes', 'thought_id', $thoughtId),
        ];
    }

    public function toggleSave(string $thoughtId, string $memberId): array
    {
        $this->assertThoughtExists($thoughtId);
        $stmt = $this->db->prepare('INSERT IGNORE INTO pensees_enregistrements (thought_id, member_id) VALUES (?, ?)');
        $stmt->execute([$thoughtId, $memberId]);
        $saved = $stmt->rowCount() === 1;
        if (!$saved) {
            $delete = $this->db->prepare('DELETE FROM pensees_enregistrements WHERE thought_id = ? AND member_id = ?');
            $delete->execute([$thoughtId, $memberId]);
        }

        return [
            'saved' => $saved,
            'saves_count' => $this->count('pensees_enregistrements', 'thought_id', $thoughtId),
        ];
    }

    public function addComment(
        string $thoughtId,
        string $memberId,
        string $authorName,
        string $content,
        ?int $parentId
    ): array {
        $this->assertThoughtExists($thoughtId);
        $content = trim($content);
        if ($content === '' || mb_strlen($content) > 2000) {
            throw new InvalidArgumentException('Le commentaire doit contenir entre 1 et 2 000 caractères.');
        }

        if ($parentId !== null) {
            $parentStmt = $this->db->prepare(
                'SELECT comment_id FROM pensees_commentaires WHERE comment_id = ? AND thought_id = ? AND parent_id IS NULL'
            );
            $parentStmt->execute([$parentId, $thoughtId]);
            if (!$parentStmt->fetchColumn()) {
                throw new InvalidArgumentException('La réponse doit être rattachée à un commentaire principal.');
            }
        }

        $stmt = $this->db->prepare("
            INSERT INTO pensees_commentaires (thought_id, parent_id, member_id, author_name, content)
            VALUES (:thought_id, :parent_id, :member_id, :author_name, :content)
        ");
        $stmt->execute([
            'thought_id' => $thoughtId,
            'parent_id' => $parentId,
            'member_id' => $memberId,
            'author_name' => $authorName,
            'content' => $content,
        ]);

        $commentId = (int) $this->db->lastInsertId();
        $commentStmt = $this->db->prepare("
            SELECT comment_id, thought_id, parent_id, author_name, content, created_at
            FROM pensees_commentaires WHERE comment_id = ?
        ");
        $commentStmt->execute([$commentId]);
        $comment = $commentStmt->fetch(PDO::FETCH_ASSOC);
        $comment['likes_count'] = 0;
        $comment['liked'] = false;
        $comment['replies'] = [];

        $result = [
            'comment' => $comment,
            'comments_count' => $this->count('pensees_commentaires', 'thought_id', $thoughtId),
        ];
        if ($parentId !== null) {
            $thread = $this->findCommentThread($parentId, $memberId);
            $result['replies_count'] = $thread ? (int) $thread['root_comment']['replies_count'] : 1;
        }
        return $result;
    }

    public function toggleCommentLike(int $commentId, string $memberId): array
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO pensees_commentaires_likes (comment_id, member_id) VALUES (?, ?)'
        );
        $stmt->execute([$commentId, $memberId]);
        $liked = $stmt->rowCount() === 1;
        if (!$liked) {
            $delete = $this->db->prepare(
                'DELETE FROM pensees_commentaires_likes WHERE comment_id = ? AND member_id = ?'
            );
            $delete->execute([$commentId, $memberId]);
        }

        return [
            'liked' => $liked,
            'likes_count' => $this->count('pensees_commentaires_likes', 'comment_id', $commentId),
        ];
    }

    public function findComment(int $commentId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT comment_id FROM pensees_commentaires WHERE comment_id = ?'
        );
        $stmt->execute([$commentId]);
        $comment = $stmt->fetchColumn();
        return $comment ? ['comment_id' => (int) $comment] : null;
    }

    private function validateContent(string $content): string
    {
        $content = trim($content);
        if ($content === '' || mb_strlen($content) > 5000) {
            throw new InvalidArgumentException('La pensée doit contenir entre 1 et 5 000 caractères.');
        }
        return $content;
    }

    private function assertThoughtExists(string $thoughtId): void
    {
        $stmt = $this->db->prepare("SELECT thought_id FROM $this->table WHERE thought_id = ?");
        $stmt->execute([$thoughtId]);
        if (!$stmt->fetchColumn()) {
            throw new InvalidArgumentException('Cette pensée est introuvable.');
        }
    }

    private function count(string $table, string $column, string|int $id): int
    {
        $allowed = [
            'pensees_likes' => 'thought_id',
            'pensees_enregistrements' => 'thought_id',
            'pensees_commentaires' => 'thought_id',
            'pensees_commentaires_likes' => 'comment_id',
        ];
        if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
            throw new InvalidArgumentException('Table de comptage invalide.');
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` = ?");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    private function rootComments(array $comments): array
    {
        $commentsById = [];
        foreach ($comments as $comment) {
            $commentsById[(int) $comment['comment_id']] = $comment;
        }

        $roots = [];
        $replyCounts = [];
        foreach ($comments as $comment) {
            $ancestor = $comment;
            $visited = [];
            while ($ancestor['parent_id'] !== null) {
                $parentId = (int) $ancestor['parent_id'];
                if (isset($visited[$parentId]) || !isset($commentsById[$parentId])) {
                    break;
                }
                $visited[$parentId] = true;
                $ancestor = $commentsById[$parentId];
            }

            $rootId = (int) $ancestor['comment_id'];
            if ($rootId !== (int) $comment['comment_id']) {
                $replyCounts[$rootId] = ($replyCounts[$rootId] ?? 0) + 1;
            } else {
                $roots[$rootId] = $comment;
            }
        }

        foreach ($roots as $rootId => &$root) {
            $root['replies_count'] = $replyCounts[$rootId] ?? 0;
        }
        unset($root);

        return array_values($roots);
    }
}
