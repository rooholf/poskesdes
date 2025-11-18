<?php
namespace App\Core;

class View {
    public static function render(string $view, array $vars = []): void {
        global $pdo, $db_error;
        extract($vars);
        $path = dirname(__DIR__, 2) . '/views/' . $view . '.php';
        if (file_exists($path)) { include $path; }
    }
}