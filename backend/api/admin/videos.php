<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$allowedCategories = ['Featured', 'Choir', 'Priest', 'Events'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = cleanString($_GET['search'] ?? '');
    $category = cleanString($_GET['category'] ?? '');
    $sort = cleanString($_GET['sort'] ?? 'newest');

    $sql = 'SELECT id, title, description, youtube_url, category, created_at FROM videos WHERE 1=1';
    $params = [];
    if ($search !== '') {
        $sql .= ' AND (title LIKE :search OR category LIKE :search)';
        $params['search'] = '%' . $search . '%';
    }
    if ($category !== '' && in_array($category, $allowedCategories, true)) {
        $sql .= ' AND category = :category';
        $params['category'] = $category;
    }
    $sql .= $sort === 'oldest' ? ' ORDER BY created_at ASC' : ($sort === 'category' ? ' ORDER BY category ASC, created_at DESC' : ' ORDER BY created_at DESC');
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    $data = getJsonInput();
    $title = cleanString($data['title'] ?? '');
    $description = cleanString($data['description'] ?? '');
    $youtube = cleanString($data['youtube_url'] ?? '');
    $category = cleanString($data['category'] ?? '');

    if ($title === '' || $description === '' || $youtube === '' || !in_array($category, $allowedCategories, true)) {
        jsonResponse(['success' => false, 'message' => 'Invalid video payload'], 422);
    }

    $stmt = $pdo->prepare('INSERT INTO videos (title, description, youtube_url, category, created_by) VALUES (:title, :description, :youtube_url, :category, :created_by)');
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'youtube_url' => $youtube,
        'category' => $category,
        'created_by' => currentAdmin()['id'],
    ]);
    jsonResponse(['success' => true, 'message' => 'Video added']);
}

if ($method === 'PUT' || $method === 'PATCH') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    $title = cleanString($data['title'] ?? '');
    $description = cleanString($data['description'] ?? '');
    $youtube = cleanString($data['youtube_url'] ?? '');
    $category = cleanString($data['category'] ?? '');

    if ($id < 1 || $title === '' || $description === '' || $youtube === '' || !in_array($category, $allowedCategories, true)) {
        jsonResponse(['success' => false, 'message' => 'Invalid update payload'], 422);
    }

    $stmt = $pdo->prepare('UPDATE videos SET title = :title, description = :description, youtube_url = :youtube_url, category = :category WHERE id = :id');
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'youtube_url' => $youtube,
        'category' => $category,
        'id' => $id,
    ]);
    jsonResponse(['success' => true, 'message' => 'Video updated']);
}

if ($method === 'DELETE') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    if ($id < 1) {
        jsonResponse(['success' => false, 'message' => 'Invalid video id'], 422);
    }
    $pdo->prepare('DELETE FROM videos WHERE id = :id')->execute(['id' => $id]);
    jsonResponse(['success' => true, 'message' => 'Video deleted']);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
