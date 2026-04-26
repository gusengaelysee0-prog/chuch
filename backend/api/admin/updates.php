<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$uploadDir = __DIR__ . '/../../../uploads/updates';
$publicPrefix = 'uploads/updates/';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query('SELECT id, title, content, image_path, created_at FROM updates ORDER BY created_at DESC');
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    $title = cleanString($_POST['title'] ?? '');
    $content = cleanString($_POST['content'] ?? '');
    if ($title === '' || $content === '') {
        jsonResponse(['success' => false, 'message' => 'Title and content are required'], 422);
    }

    $imagePath = null;
    if (!empty($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        try {
            $saved = saveUploadedImage($_FILES['image'], $uploadDir);
            $imagePath = $publicPrefix . $saved;
        } catch (RuntimeException $exception) {
            jsonResponse(['success' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    $stmt = $pdo->prepare('INSERT INTO updates (title, content, image_path, created_by) VALUES (:title, :content, :image_path, :created_by)');
    $stmt->execute([
        'title' => $title,
        'content' => $content,
        'image_path' => $imagePath,
        'created_by' => currentAdmin()['id'],
    ]);
    jsonResponse(['success' => true, 'message' => 'Update created']);
}

if ($method === 'PUT' || $method === 'PATCH') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    $title = cleanString($data['title'] ?? '');
    $content = cleanString($data['content'] ?? '');
    if ($id < 1 || $title === '' || $content === '') {
        jsonResponse(['success' => false, 'message' => 'Invalid payload'], 422);
    }
    $stmt = $pdo->prepare('UPDATE updates SET title = :title, content = :content WHERE id = :id');
    $stmt->execute(['title' => $title, 'content' => $content, 'id' => $id]);
    jsonResponse(['success' => true, 'message' => 'Update updated']);
}

if ($method === 'DELETE') {
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    if ($id < 1) {
        jsonResponse(['success' => false, 'message' => 'Invalid update id'], 422);
    }

    $stmt = $pdo->prepare('SELECT image_path FROM updates WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if (!$row) {
        jsonResponse(['success' => false, 'message' => 'Update not found'], 404);
    }

    $pdo->prepare('DELETE FROM updates WHERE id = :id')->execute(['id' => $id]);
    if (!empty($row['image_path'])) {
        $fullPath = __DIR__ . '/../../../' . $row['image_path'];
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
    jsonResponse(['success' => true, 'message' => 'Update deleted']);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
