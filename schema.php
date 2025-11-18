<?php
function ensureSchema($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(64) UNIQUE NOT NULL, password_hash VARCHAR(255) NOT NULL, role VARCHAR(16) NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS patients (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(128) NOT NULL, dob DATE NULL, address VARCHAR(255) NULL, phone VARCHAR(32) NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS visits (id INT AUTO_INCREMENT PRIMARY KEY, patient_id INT NOT NULL, date DATE NOT NULL, service_type VARCHAR(32) NOT NULL, notes TEXT NULL, CONSTRAINT fk_visits_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS schedules (id INT AUTO_INCREMENT PRIMARY KEY, subject VARCHAR(160) NULL, date DATE NOT NULL, service_type VARCHAR(32) NOT NULL, time VARCHAR(16) NULL, contact_method VARCHAR(32) NULL, status VARCHAR(16) NULL, notes VARCHAR(255) NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(160) NOT NULL, category VARCHAR(64) NULL, body TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS article_notes (id INT AUTO_INCREMENT PRIMARY KEY, article_id INT NOT NULL, user_id INT NOT NULL, note TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_article_notes_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS anc_records (id INT AUTO_INCREMENT PRIMARY KEY, patient_id INT NULL, patient_name VARCHAR(160) NULL, tanggal DATE NOT NULL, data JSON NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS kb_records (id INT AUTO_INCREMENT PRIMARY KEY, patient_id INT NULL, patient_name VARCHAR(160) NULL, tanggal DATE NOT NULL, data JSON NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS lansia_records (id INT AUTO_INCREMENT PRIMARY KEY, patient_id INT NULL, patient_name VARCHAR(160) NULL, tanggal DATE NOT NULL, data JSON NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS field_notes (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, note TEXT NOT NULL, for_date DATE NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['schedules','subject']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE schedules ADD COLUMN subject VARCHAR(160) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['schedules','contact_method']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE schedules ADD COLUMN contact_method VARCHAR(32) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['schedules','status']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE schedules ADD COLUMN status VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
}
function ensureSeed($pdo) {
    $u = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($u === 0) {
        $username = (function_exists('envv') ? envv('POSY_ADMIN_USER', 'admin') : 'admin');
        $password = (function_exists('envv') ? envv('POSY_ADMIN_PASS', '') : '');
        if ($password === '') { $password = 'admin123'; }
        $hp = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users(username, password_hash, role) VALUES(?, ?, 'admin')")->execute([$username, $hp]);
    }
    $c = (int)$pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
    if ($c === 0) {
        $pdo->prepare("INSERT INTO patients(name,address,phone) VALUES(?,?,?)")->execute(["Ibu Siti Aisyah","Dusun I","08123456789"]);
        $pdo->prepare("INSERT INTO patients(name,address,phone) VALUES(?,?,?)")->execute(["Ibu Fatimah Az-Zahra","Dusun II","08529876543"]);
        $pdo->prepare("INSERT INTO patients(name,address,phone) VALUES(?,?,?)")->execute(["Bapak Budi","Dusun III","08123445566"]);
    }
    $s = (int)$pdo->query("SELECT COUNT(*) FROM schedules")->fetchColumn();
    if ($s === 0) {
        $pdo->prepare("INSERT INTO schedules(subject,date,service_type,time,contact_method,notes) VALUES(?,?,?,?,?,?)")
            ->execute(["Imunisasi Polio","2025-11-21","Imunisasi","09:00","Poskesdes","Poskesdes"]);
        $pdo->prepare("INSERT INTO schedules(subject,date,service_type,time,contact_method,notes) VALUES(?,?,?,?,?,?)")
            ->execute(["ANC: Kontrol Ibu Hamil","2025-11-24","ANC Kontrol","10:30","Telepon/WA","Bidan A"]);
    }
}