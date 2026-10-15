<?php
    require_once APP_PATH . 'models/Engagement.php';
    require_once APP_PATH . 'models/Membre.php';
    require_once APP_PATH . 'models/Serie.php';
    require_once APP_PATH . 'models/Enseignement.php';
    require_once APP_PATH . 'models/SessionEnseignement.php';
    require_once APP_PATH . 'models/SerieSession.php';
    require_once APP_PATH . 'models/Api.php';
    require_once APP_PATH . 'models/Vues.php';
    require_once APP_PATH . 'models/Appels.php';

class ApiController extends Controller {
    
    private $MembreModel;
    private $EngagementModel;
    private $SerieModel;
    private $SessionSerieModel;
    private $SessionEnseignementModel;
    private $EnseignementModel;
    private $ApiModel;
    private $VuesModel;
    private $AppelsModel;

    public function __construct()
    {
        $this->MembreModel = new Membre();
        $this->EngagementModel = new Engagement();
        $this->SerieModel = new Serie();
        $this->SessionSerieModel = new SerieSession();
        $this->SessionEnseignementModel = new EnseignementSession();
        $this->EnseignementModel = new Enseignement();
        $this->ApiModel = new Api();
        $this->VuesModel = new Vues();
        $this->AppelsModel = new Appels();
    }

    public function backup_database()
    {
        date_default_timezone_set('Africa/Kinshasa');
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

        $respond = static function (int $status, array $payload): void {
            http_response_code($status);
            echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        };

        $backupToken = defined('DB_BACKUP_CRON_TOKEN')
            ? DB_BACKUP_CRON_TOKEN
            : getenv('DB_BACKUP_CRON_TOKEN');
        if (!is_string($backupToken) || $backupToken === '') {
            error_log('Database backup endpoint is disabled: DB_BACKUP_CRON_TOKEN is not configured.');
            $respond(503, ['status' => 'error', 'message' => 'Le service de sauvegarde n’est pas configuré.']);
            return;
        }

        if (PHP_SAPI !== 'cli') {
            $providedToken = isset($_GET['token']) && is_string($_GET['token']) ? $_GET['token'] : '';
            if (!hash_equals($backupToken, $providedToken)) {
                $respond(403, ['status' => 'error', 'message' => 'Accès refusé.']);
                return;
            }
        }

        set_time_limit(0);

        $backupDirectory = BASE_PATH . 'storage/backups';
        if (!is_dir($backupDirectory) && !mkdir($backupDirectory, 0750, true) && !is_dir($backupDirectory)) {
            error_log('Database backup failed: unable to create the backup directory.');
            $respond(500, ['status' => 'error', 'message' => 'Impossible de créer le dossier de sauvegarde.']);
            return;
        }

        $timestamp = date('Ymd_His');
        $fileName = 'database_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', DB_NAME) . '_' . $timestamp . '.sql';
        $filePath = $backupDirectory . DIRECTORY_SEPARATOR . $fileName;
        $temporaryPath = $filePath . '.tmp';
        $file = @fopen($temporaryPath, 'xb');
        if ($file === false) {
            error_log('Database backup failed: unable to create the temporary backup file.');
            $respond(500, ['status' => 'error', 'message' => 'Impossible de créer le fichier de sauvegarde.']);
            return;
        }

        $write = static function ($handle, string $contents): void {
            $length = strlen($contents);
            for ($offset = 0; $offset < $length;) {
                $written = fwrite($handle, substr($contents, $offset));
                if ($written === false || $written === 0) {
                    throw new RuntimeException('Unable to write the database backup.');
                }
                $offset += $written;
            }
        };

        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            $pdo->exec('SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ');
            $pdo->beginTransaction();

            $write($file, "-- Database backup for " . DB_NAME . "\n");
            $write($file, "-- Created at " . date('Y-m-d H:i:s') . " Africa/Kinshasa\n\n");
            $write($file, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

            $tables = $pdo->query('SHOW FULL TABLES')->fetchAll(PDO::FETCH_NUM);
            $views = [];

            foreach ($tables as $tableInfo) {
                $tableName = (string) $tableInfo[0];
                $quotedTable = '`' . str_replace('`', '``', $tableName) . '`';

                if (isset($tableInfo[1]) && strtoupper((string) $tableInfo[1]) === 'VIEW') {
                    $statement = $pdo->query('SHOW CREATE VIEW ' . $quotedTable);
                    $viewDefinition = $statement->fetch(PDO::FETCH_NUM);
                    if (!isset($viewDefinition[1])) {
                        throw new RuntimeException('Unable to read a view definition.');
                    }
                    $views[] = rtrim((string) $viewDefinition[1], ';') . ";\n\n";
                    continue;
                }

                $statement = $pdo->query('SHOW CREATE TABLE ' . $quotedTable);
                $definition = $statement->fetch(PDO::FETCH_NUM);
                if (!isset($definition[1])) {
                    throw new RuntimeException('Unable to read a table definition.');
                }

                $write($file, 'DROP TABLE IF EXISTS ' . $quotedTable . ";\n");
                $write($file, rtrim((string) $definition[1], ';') . ";\n\n");

                $columns = $pdo->query('SHOW COLUMNS FROM ' . $quotedTable)->fetchAll();
                $columnNames = array_map(static function (array $column): string {
                    return '`' . str_replace('`', '``', (string) $column['Field']) . '`';
                }, $columns);
                $binaryColumns = [];
                foreach ($columns as $column) {
                    if (preg_match('/(?:binary|blob)/i', (string) $column['Type'])) {
                        $binaryColumns[(string) $column['Field']] = true;
                    }
                }

                $rows = $pdo->query('SELECT * FROM ' . $quotedTable);
                $valueRows = [];
                while ($row = $rows->fetch()) {
                    $values = [];
                    foreach ($row as $columnName => $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } elseif (isset($binaryColumns[$columnName])) {
                            $values[] = '0x' . bin2hex((string) $value);
                        } else {
                            $quotedValue = $pdo->quote((string) $value);
                            if ($quotedValue === false) {
                                throw new RuntimeException('Unable to encode a database value.');
                            }
                            $values[] = $quotedValue;
                        }
                    }
                    $valueRows[] = '(' . implode(', ', $values) . ')';

                    if (count($valueRows) === 100) {
                        $write($file, 'INSERT INTO ' . $quotedTable . ' (' . implode(', ', $columnNames) . ') VALUES' . "\n");
                        $write($file, implode(",\n", $valueRows) . ";\n");
                        $valueRows = [];
                    }
                }

                if ($valueRows !== []) {
                    $write($file, 'INSERT INTO ' . $quotedTable . ' (' . implode(', ', $columnNames) . ') VALUES' . "\n");
                    $write($file, implode(",\n", $valueRows) . ";\n");
                }
                $write($file, "\n");
            }

            foreach ($views as $viewDefinition) {
                $write($file, $viewDefinition);
            }
            $write($file, "SET FOREIGN_KEY_CHECKS=1;\n");
            $pdo->commit();

            if (!fflush($file)) {
                throw new RuntimeException('Unable to flush the database backup.');
            }
            fclose($file);
            $file = null;

            if (!rename($temporaryPath, $filePath)) {
                throw new RuntimeException('Unable to finalize the database backup.');
            }

            $respond(200, [
                'status' => 'success',
                'file' => 'storage/backups/' . $fileName,
                'size_bytes' => filesize($filePath),
            ]);
        } catch (Throwable $exception) {
            if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if (is_resource($file)) {
                fclose($file);
            }
            if (is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
            error_log('Database backup failed: ' . $exception->getMessage());
            $respond(500, ['status' => 'error', 'message' => 'La sauvegarde a échoué. Consultez les journaux du serveur.']);
        }
    }

    public function enseignement_state_view($serieId) 
    {
        $sessionId = $_GET['ssd'] ?? null;
        
        header('Content-Type: application/json');
        try {
            $sessionSerie = $this->SessionSerieModel->find($sessionId, $serieId);
            // $serie = $this->SerieModel->find($serieId);
            $new_state = $sessionSerie->is_active == 1 ? 0 : 1;
            
            $updateSuccess = $this->SessionSerieModel->update(['is_active' => $new_state, 'ss_id' => $sessionSerie->ss_id]);
            // if($serie->is_active ==! 1) $this->SerieModel->update(['is_active' => 1, 'serie_id' => $serieId]);
            // if($new_state === 0 && $serie->enseignements_count == 0) $this->SerieModel->update(['is_active' => 0, 'serie_id' => $serieId]);

            if ($updateSuccess) {
                echo json_encode([
                    'status' => 'success',
                    'new_state' => $new_state,
                    'message' => 'État mis à jour avec succès'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour en base']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue.']);
            return;
        }
    }

    public function enseignement_state_view_2($enseignementId) 
    {
        $sessionId = $_GET['ssd'] ?? null;
        
        header('Content-Type: application/json');
        try {
            $sessionEnseignement = $this->SessionEnseignementModel->find($sessionId, $enseignementId);
            // $serie = $this->SerieModel->find($enseignementId);
            $new_state = $sessionEnseignement->is_active == 1 ? 0 : 1;
            
            $updateSuccess = $this->SessionEnseignementModel->update(['is_active' => $new_state, 'st_id' => $sessionEnseignement->st_id]);

            if ($updateSuccess) {
                echo json_encode([
                    'status' => 'success',
                    'new_state' => $new_state,
                    'message' => 'État mis à jour avec succès'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour en base']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue.']);
            return;
        }
    }

    public function membres($apiKey)
    {
        if($apiKey === API_KEY_CALL_APP) echo $this->ApiModel->getAllUsersAsJson();
        // header("Location: /www.google.com");
    }

    public function set_live_status()
    {
        // 1. Récupérer le contenu JSON envoyé par l'application Flutter
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Vérifier si la donnée 'isLive' est présente
        if (isset($data['isLive'])) {
            $isLive = $data['isLive'] ? 1 : 0;

            // Connexion BDD (Adaptez à votre instance PDO/DB)
            if($this->AppelsModel->insert(['is_live' => $isLive, 'appel_id' => 1]))
            {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'isLive' => (bool)$isLive]);
            }

        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Données isLive manquantes']);
        }
        exit;
    }

    public function get_live_status()
    {
        $result = $this->AppelsModel->getLiveStatus();

        header('Content-Type: application/json');
        echo json_encode([
            'isLive' => $result ? (bool)$result->is_live : false
        ]);
        exit;
    }
   
}