<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';

$pdo = getDbConnection();
$type = cleanString($_GET['type'] ?? '');

switch ($type) {
    case 'images':
        $stmt = $pdo->query('SELECT id, title, description, category, image_path, created_at FROM images ORDER BY created_at DESC');
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
        break;
    case 'videos':
        $stmt = $pdo->query('SELECT id, title, description, youtube_url, category, created_at FROM videos ORDER BY created_at DESC');
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
        break;
    case 'notifications':
        $stmt = $pdo->query('SELECT id, title, description, publish_date, created_at FROM notifications ORDER BY publish_date DESC');
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
        break;
    case 'updates':
        $stmt = $pdo->query('SELECT id, title, content, image_path, created_at FROM updates ORDER BY created_at DESC');
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid content type'], 422);
}
