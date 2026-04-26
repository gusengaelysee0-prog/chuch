<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';
requireAuth();

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, name, email, message, created_at FROM messages ORDER BY created_at DESC LIMIT 500');
jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
