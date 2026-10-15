<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$_SERVER['HTTP_HOST'] = 'communaute-lobola.ankhing.com';
$_SERVER['DOCUMENT_ROOT'] = __DIR__;
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['HTTPS'] = 'on';
$_GET['url'] = 'api/backup_database';

require __DIR__ . '/index.php';
