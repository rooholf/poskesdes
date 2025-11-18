<?php
namespace App\Core;

class Router {
    public static function resolve(string $uri): string {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $map = [
            '/' => 'home',
            '/home' => 'home',
            '/schedule' => 'schedule',
            '/articles' => 'articles',
            '/article' => 'article',
            '/profile' => 'profile',
            '/login' => 'login',
            '/admin' => 'admin_dashboard',
            '/admin/patients' => 'admin_patients',
            '/admin/visits' => 'admin_visits',
            '/admin/schedules' => 'admin_schedules',
            '/admin/articles' => 'admin_articles',
            '/admin/reports' => 'admin_reports',
        ];
        return $map[$path] ?? 'home';
    }
}