<?php
namespace App\Services;

use PDO;

class ScheduleService {
    private PDO $pdo;

    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function updateStatus(int $id, string $status): void {
        $stmt = $this->pdo->prepare("UPDATE schedules SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);
    }

    public function addSchedule(string $date, string $serviceType, ?string $time, ?string $notes): void {
        $stmt = $this->pdo->prepare('INSERT INTO schedules(date, service_type, time, notes) VALUES(?, ?, ?, ?)');
        $stmt->execute([$date, $serviceType, $time ?: null, $notes ?: null]);
    }
}