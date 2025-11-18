<?php
namespace App\Services;

use PDO;

class FieldNotesService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function add(int $userId, string $note): void {
        $stmt = $this->pdo->prepare('INSERT INTO field_notes(user_id, note, for_date) VALUES(?, ?, CURDATE())');
        $stmt->execute([$userId, $note]);
    }
}