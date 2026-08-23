<?php
require_once __DIR__ . '/db.php';

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
function require_login(): void {
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}
function require_role(array $roles): void {
    require_login();
    if (!in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }
}
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
function flash(string $type, string $message): void {
    $_SESSION['flash'][] = ['type'=>$type, 'message'=>$message];
}
function flashes(): array {
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}
