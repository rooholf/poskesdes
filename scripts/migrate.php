<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../schema.php';
if (!$pdo) { echo "DB not configured\n"; exit(1); }
ensureSchema($pdo);
ensureSeed($pdo);
echo "Migration completed\n";