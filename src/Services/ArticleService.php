<?php
namespace App\Services;

use PDO;

class ArticleService {
    private PDO $pdo;

    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function addNote(int $articleId, int $userId, string $note): void {
        $stmt = $this->pdo->prepare("INSERT INTO article_notes(article_id, user_id, note) VALUES(?, ?, ?)");
        $stmt->execute([$articleId, $userId, $note]);
    }

    public function addArticle(string $title, ?string $category, string $body): void {
        $stmt = $this->pdo->prepare('INSERT INTO articles(title, category, body) VALUES(?, ?, ?)');
        $stmt->execute([$title, $category ?: null, $body]);
    }
}