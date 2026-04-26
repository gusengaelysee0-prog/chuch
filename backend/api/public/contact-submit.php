<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$data = getJsonInput();
$name = cleanString($data['name'] ?? '');
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
$message = cleanString($data['message'] ?? '');

if ($name === '' || !$email || $message === '') {
    jsonResponse(['success' => false, 'message' => 'Please provide valid name, email, and message'], 422);
}

if (strlen($message) < 5) {
    jsonResponse(['success' => false, 'message' => 'Message is too short'], 422);
}

$pdo = getDbConnection();
$stmt = $pdo->prepare('INSERT INTO messages (name, email, message, ip_address) VALUES (:name, :email, :message, :ip_address)');
$stmt->execute([
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
]);

jsonResponse(['success' => true, 'message' => 'Message sent successfully']);
