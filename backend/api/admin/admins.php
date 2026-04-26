<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query('SELECT id, username, role, is_main_admin, created_at FROM admins ORDER BY created_at DESC');
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    requireRole(['Super Admin']);
    $data = getJsonInput();
    $username = cleanString($data['username'] ?? '');
    $password = (string) ($data['password'] ?? '');
    $role = cleanString($data['role'] ?? '');
    $allowedRoles = ['Super Admin', 'Editor', 'Media Manager'];

    if ($username === '' || $password === '' || !in_array($role, $allowedRoles, true)) {
        jsonResponse(['success' => false, 'message' => 'Invalid admin payload'], 422);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, role) VALUES (:username, :password_hash, :role)');
    try {
        $stmt->execute(['username' => $username, 'password_hash' => $hash, 'role' => $role]);
    } catch (PDOException $exception) {
        jsonResponse(['success' => false, 'message' => 'Username already exists'], 409);
    }

    jsonResponse(['success' => true, 'message' => 'Admin created']);
}

if ($method === 'DELETE') {
    requireRole(['Super Admin']);
    $data = getJsonInput();
    $id = (int) ($data['id'] ?? 0);
    if ($id < 1) {
        jsonResponse(['success' => false, 'message' => 'Invalid admin id'], 422);
    }

    $stmt = $pdo->prepare('SELECT is_main_admin FROM admins WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $admin = $stmt->fetch();
    if (!$admin) {
        jsonResponse(['success' => false, 'message' => 'Admin not found'], 404);
    }
    if ((int) $admin['is_main_admin'] === 1) {
        jsonResponse(['success' => false, 'message' => 'Main admin cannot be deleted'], 403);
    }

    $pdo->prepare('DELETE FROM admins WHERE id = :id')->execute(['id' => $id]);
    jsonResponse(['success' => true, 'message' => 'Admin deleted']);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
