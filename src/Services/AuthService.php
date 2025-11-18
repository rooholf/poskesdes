<?php
namespace App\Services;

use PDO;

class AuthService {
    public static function isLoggedIn(): bool { return isset($_SESSION['user_id']); }
    public static function requireLogin(): void { if (!self::isLoggedIn()) { header('Location: /login'); exit; } }
    public static function logout(): void { session_destroy(); }

    public function login(PDO $pdo, string $username, string $password): bool {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function registerAdmin(PDO $pdo, string $username, string $password): bool {
        if ($username === '' || $password === '') { return false; }
        $count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        if ($count > 0) { return false; }
        $hp = password_hash($password, PASSWORD_DEFAULT);
        $ins = $pdo->prepare('INSERT INTO users(username, password_hash, role) VALUES(?, ?, \"admin\")');
        $ins->execute([$username, $hp]);
        return true;
    }
}