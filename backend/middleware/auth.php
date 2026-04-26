<?php
declare(strict_types=1);

function currentAdmin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function requireAuth(): void
{
    if (!isset($_SESSION['admin'])) {
        if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
            jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        header('Location: /chuch/login.php');
        exit;
    }
}

function requireRole(array $roles): void
{
    $admin = currentAdmin();
    if (!$admin || !in_array($admin['role'], $roles, true)) {
        jsonResponse(['success' => false, 'message' => 'Forbidden'], 403);
    }
}
