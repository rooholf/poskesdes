<?php
namespace App\Services;

class CsrfService {
    public static function ensure(): void {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    public static function token(): string { return $_SESSION['csrf_token'] ?? ''; }
    public static function validate(?string $token): bool { return is_string($token) && $token !== '' && hash_equals($_SESSION['csrf_token'] ?? '', $token); }
}