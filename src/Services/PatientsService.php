<?php
namespace App\Services;

use PDO;

class PatientsService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function add(string $name, ?string $dob, ?string $address, ?string $phone): void {
        $stmt = $this->pdo->prepare('INSERT INTO patients(name, dob, address, phone) VALUES(?, ?, ?, ?)');
        $stmt->execute([$name, $dob ?: null, $address ?: null, $phone ?: null]);
    }
    public function edit(int $id, string $name, ?string $dob, ?string $address, ?string $phone): void {
        $stmt = $this->pdo->prepare('UPDATE patients SET name=?, dob=?, address=?, phone=? WHERE id=?');
        $stmt->execute([$name, $dob ?: null, $address ?: null, $phone ?: null, $id]);
    }
    public function delete(int $id): void {
        $this->pdo->prepare('DELETE FROM visits WHERE patient_id = ?')->execute([$id]);
        $this->pdo->prepare('DELETE FROM patients WHERE id = ?')->execute([$id]);
    }
}