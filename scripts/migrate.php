<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../schema.php';
if (!$pdo) { echo "DB not configured\n"; exit(1); }
ensureSchema($pdo);
ensureSeed($pdo);
echo "Migration completed\n";