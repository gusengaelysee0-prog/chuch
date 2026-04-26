<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/bootstrap.php';

$admin = currentAdmin();
if (!$admin) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

jsonResponse(['success' => true, 'admin' => $admin]);
