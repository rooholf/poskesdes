<?php
if (file_exists(__DIR__.'/.env')) {
    $lines = file(__DIR__.'/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $t = trim($line);
        if ($t === '' || $t[0] === '#') { continue; }
        $pos = strpos($t, '=');
        if ($pos === false) { continue; }
        $key = trim(substr($t, 0, $pos));
        $val = trim(substr($t, $pos + 1));
        if (getenv($key) === false) { putenv("{$key}={$val}"); $_ENV[$key] = $val; }
    }
}
$DB_HOST = getenv('POSY_DB_HOST') ?: 'localhost';
$DB_NAME = getenv('POSY_DB_NAME') ?: '';
$DB_USER = getenv('POSY_DB_USER') ?: '';
$DB_PASS = getenv('POSY_DB_PASS') ?: '';
$DB_CHARSET = 'utf8mb4';