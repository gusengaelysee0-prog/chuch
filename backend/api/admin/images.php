<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$uploadDir = __DIR__ . '/../../../uploads/images';
$publicPrefix = 'uploads/images/';
$allowedCategories = ['Featured', 'Choir', 'Leaders', 'Events', 'Others'];

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = cleanString($_GET['search'] ?? '');
    $category = cleanString($_GET['category'] ?? '');
    $sort = cleanString($_GET['sort'] ?? 'newest');

    $sql = 'SELECT id, title, description, category, image_path, created_at FROM images WHERE 1=1';
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
    $rows = $stmt->fetchAll();
    foreach ($rows as &$row) {
        $row['image_url'] = $row['image_path'];
    }
    jsonResponse(['success' => true, 'data' => $rows]);
}

if ($method === 'POST') {
    $title = cleanString($_POST['title'] ?? '');
    $description = cleanString($_POST['description'] ?? '');
    $category = cleanString($_POST['category'] ?? '');
    if ($title === '' || $description === '' || !in_array($category, $allowedCategories, true)) {
        jsonResponse(['success' => false, 'message' => 'Invalid title, description, or category'], 422);
    }

    try {
        $saved = saveUploadedImage($_FILES['image'] ?? [], $uploadDir);
    } catch (RuntimeException $exception) {
        jsonResponse(['success' => false, 'message' => $exception->getMessage()], 422);
    }

    $path = $publicPrefix . $saved;
    $stmt = $pdo->prepare('INSERT INTO images (title, description, category, image_path, created_by) VALUES (:title, :description, :category, :image_path, :created_by)');
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'category' => $category,
        'image_path' => $path,
        'created_by' => currentAdmin()['id'],
    ]);

    jsonResponse(['success' => true, 'message' => 'Image uploaded successfully']);
}

if ($method === 'PUT' || $method === 'PATCH') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    $description = cleanString($data['description'] ?? '');
    $category = cleanString($data['category'] ?? '');
    if ($id < 1 || $description === '' || !in_array($category, $allowedCategories, true)) {
        jsonResponse(['success' => false, 'message' => 'Invalid update payload'], 422);
    }

    $stmt = $pdo->prepare('UPDATE images SET description = :description, category = :category WHERE id = :id');
    $stmt->execute(['description' => $description, 'category' => $category, 'id' => $id]);
    jsonResponse(['success' => true, 'message' => 'Image updated']);
}

if ($method === 'DELETE') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    if ($id < 1) {
        jsonResponse(['success' => false, 'message' => 'Invalid image id'], 422);
    }

    $stmt = $pdo->prepare('SELECT image_path FROM images WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if (!$row) {
        jsonResponse(['success' => false, 'message' => 'Image not found'], 404);
    }

    $pdo->prepare('DELETE FROM images WHERE id = :id')->execute(['id' => $id]);
    $fullPath = __DIR__ . '/../../../' . $row['image_path'];
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
    jsonResponse(['success' => true, 'message' => 'Image deleted']);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
