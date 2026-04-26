<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$queries = [
    'images' => 'SELECT COUNT(*) AS total FROM images',
    'videos' => 'SELECT COUNT(*) AS total FROM videos',
    'notifications' => 'SELECT COUNT(*) AS total FROM notifications',
    'updates' => 'SELECT COUNT(*) AS total FROM updates',
    'messages' => 'SELECT COUNT(*) AS total FROM messages',
];

$stats = [];
foreach ($queries as $key => $query) {
    $stats[$key] = (int) $pdo->query($query)->fetchColumn();
}

jsonResponse(['success' => true, 'stats' => $stats]);
