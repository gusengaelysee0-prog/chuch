<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query('SELECT id, title, description, publish_date, created_at FROM notifications ORDER BY publish_date DESC, created_at DESC');
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    $data = getJsonInput();
    $title = cleanString($data['title'] ?? '');
    $description = cleanString($data['description'] ?? '');
    $publishDate = cleanString($data['publish_date'] ?? '');
    if ($title === '' || $description === '' || $publishDate === '') {
        jsonResponse(['success' => false, 'message' => 'All fields are required'], 422);
    }
    $stmt = $pdo->prepare('INSERT INTO notifications (title, description, publish_date, created_by) VALUES (:title, :description, :publish_date, :created_by)');
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'publish_date' => $publishDate,
        'created_by' => currentAdmin()['id'],
    ]);
    jsonResponse(['success' => true, 'message' => 'Notification created']);
}

if ($method === 'PUT' || $method === 'PATCH') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    $title = cleanString($data['title'] ?? '');
    $description = cleanString($data['description'] ?? '');
    $publishDate = cleanString($data['publish_date'] ?? '');
    if ($id < 1 || $title === '' || $description === '' || $publishDate === '') {
        jsonResponse(['success' => false, 'message' => 'Invalid update payload'], 422);
    }
    $stmt = $pdo->prepare('UPDATE notifications SET title = :title, description = :description, publish_date = :publish_date WHERE id = :id');
    $stmt->execute(['title' => $title, 'description' => $description, 'publish_date' => $publishDate, 'id' => $id]);
    jsonResponse(['success' => true, 'message' => 'Notification updated']);
}

if ($method === 'DELETE') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    if ($id < 1) {
        jsonResponse(['success' => false, 'message' => 'Invalid notification id'], 422);
    }
    $pdo->prepare('DELETE FROM notifications WHERE id = :id')->execute(['id' => $id]);
    jsonResponse(['success' => true, 'message' => 'Notification deleted']);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
