<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Africa/Kigali');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/error_handler.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../middleware/http.php';
require_once __DIR__ . '/../middleware/upload.php';

try {
    $pdo = getDbConnection();
    $pdo->exec("ALTER TABLE images MODIFY category ENUM('Featured', 'Choir', 'Leaders', 'Events', 'Others') NOT NULL");
    $pdo->exec("ALTER TABLE videos MODIFY category ENUM('Featured', 'Choir', 'Priest', 'Events') NOT NULL");
} catch (PDOException $exception) {
    // Ignore schema update failure in environments where the database is already compatible.
}
