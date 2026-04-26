<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$data = getJsonInput();
$username = cleanString($data['username'] ?? '');
$password = (string) ($data['password'] ?? '');

if ($username === '' || $password === '') {
    jsonResponse(['success' => false, 'message' => 'Username and password are required'], 422);
}

$pdo = getDbConnection();
$stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM admins WHERE username = :username LIMIT 1');
$stmt->execute(['username' => $username]);
$admin = $stmt->fetch();

if (!$admin) {
    $adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($adminCount === 0 && $username === 'pastor' && $password === 'pastor') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = $pdo->prepare('INSERT INTO admins (username, password_hash, role, is_main_admin) VALUES (:username, :password_hash, :role, 1)');
        $insert->execute(['username' => $username, 'password_hash' => $hash, 'role' => 'Super Admin']);
        $admin = [
            'id' => (int) $pdo->lastInsertId(),
            'username' => $username,
            'password_hash' => $hash,
            'role' => 'Super Admin',
        ];
    }
}

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid credentials'], 401);
}

$_SESSION['admin'] = [
    'id' => (int) $admin['id'],
    'username' => $admin['username'],
    'role' => $admin['role'],
];

jsonResponse(['success' => true, 'message' => 'Login successful', 'admin' => $_SESSION['admin']]);
