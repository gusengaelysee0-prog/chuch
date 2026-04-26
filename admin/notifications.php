<?php
require_once __DIR__ . '/_init.php';
requireAuth();

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT id, title, description, publish_date, created_at FROM notifications ORDER BY publish_date DESC, created_at DESC');
$notifications = $stmt->fetchAll();

include __DIR__ . '/notifications.template.php';
