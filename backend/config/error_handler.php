<?php
declare(strict_types=1);

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    error_log("PHP Error [{$severity}] {$message} in {$file}:{$line}");
    return true;
});

set_exception_handler(static function (Throwable $exception): void {
    error_log('Uncaught Exception: ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());
    if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again.']);
    } else {
        http_response_code(500);
        echo 'Something went wrong. Please try again.';
    }
    exit;
});
