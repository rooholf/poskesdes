<?php
function usersCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    return (int)$stmt->fetchColumn();
}