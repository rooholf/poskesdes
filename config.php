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
        if ($key !== '') { $_ENV[$key] = $val; $_SERVER[$key] = $val; }
    }
}
function envv($key, $default = '') {
    $v = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    return ($v === false || $v === null || $v === '') ? $default : $v;
}
$DB_HOST = envv('POSY_DB_HOST', 'localhost');
$DB_NAME = envv('POSY_DB_NAME', '');
$DB_USER = envv('POSY_DB_USER', '');
$DB_PASS = envv('POSY_DB_PASS', '');
$DB_CHARSET = 'utf8mb4';