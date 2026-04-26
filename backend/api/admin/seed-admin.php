<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$pdo = getDbConnection();
$existing = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($existing > 0) {
    jsonResponse(['success' => false, 'message' => 'Admin seed already completed'], 409);
}

$data = getJsonInput();
$username = cleanString($data['username'] ?? '');
$password = (string) ($data['password'] ?? '');
if ($username === '' || $password === '') {
    jsonResponse(['success' => false, 'message' => 'Username and password are required'], 422);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, role, is_main_admin) VALUES (:username, :password_hash, :role, 1)');
$stmt->execute(['username' => $username, 'password_hash' => $hash, 'role' => 'Super Admin']);

jsonResponse(['success' => true, 'message' => 'Main admin created successfully']);
