<?php
require __DIR__ . "/config.php";
$pdo = null;
$db_error = null;
if ($DB_NAME !== '' && $DB_USER !== '') {
    try {
        $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e) {
        $db_error = "Koneksi database gagal: " . $e->getMessage();
    }
} else {
    $db_error = "Konfigurasi database belum diisi";
}