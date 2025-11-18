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
        $q->execute(['anc_records','anc_kunjungan_ke']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN anc_kunjungan_ke INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','anc_k_status']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN anc_k_status VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','anc_usg_status']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN anc_usg_status VARCHAR(8) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','anc_4t_status']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN anc_4t_status VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','gravida']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN gravida INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','para']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN para INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','abortus']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN abortus INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','hpht']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN hpht DATE NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','hpl']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN hpl DATE NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','keluhan_utama']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN keluhan_utama TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','td']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN td VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','nadi']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN nadi INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','suhu']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN suhu DECIMAL(4,1) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','bb']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN bb DECIMAL(6,2) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','tb']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN tb INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','edema']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN edema VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','djj']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN djj INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','tfu']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN tfu DECIMAL(5,2) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','posisi_janin']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN posisi_janin VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','gerak_janin']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN gerak_janin VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','fe_diberikan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN fe_diberikan INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','saran_gizi']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN saran_gizi TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','hb']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN hb DECIMAL(4,1) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','urin']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN urin VARCHAR(32) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','penunjang_lain']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN penunjang_lain TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','faktor_risiko']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN faktor_risiko TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','klasifikasi_risiko']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN klasifikasi_risiko VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','perlu_rujukan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN perlu_rujukan VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','tujuan_rujukan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN tujuan_rujukan VARCHAR(64) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','alasan_rujukan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN alasan_rujukan TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','tatalaksana_awal']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN tatalaksana_awal TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','ringkasan_kunjungan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN ringkasan_kunjungan TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['anc_records','jadwal_kontrol_berikut']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE anc_records ADD COLUMN jadwal_kontrol_berikut DATE NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['kb_records','kb_status_peserta']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE kb_records ADD COLUMN kb_status_peserta VARCHAR(32) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['kb_records','metode_kb']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE kb_records ADD COLUMN metode_kb VARCHAR(32) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['kb_records','tgl_mulai']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE kb_records ADD COLUMN tgl_mulai DATE NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['kb_records','keterangan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE kb_records ADD COLUMN keterangan TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['kb_records','rencana_tindakan']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE kb_records ADD COLUMN rencana_tindakan VARCHAR(32) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['kb_records','jadwal_kontrol_kb']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE kb_records ADD COLUMN jadwal_kontrol_kb DATE NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','keluhan_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN keluhan_lansia TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','diagnosa_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN diagnosa_lansia VARCHAR(160) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','bb_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN bb_lansia DECIMAL(6,2) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','tb_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN tb_lansia INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','td_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN td_lansia VARCHAR(16) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','gds']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN gds INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','asam_urat']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN asam_urat DECIMAL(4,1) NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','kolesterol']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN kolesterol INT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','tindakan_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN tindakan_lansia TEXT NULL"); }
    } catch (Throwable $e) {}
    try {
        $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $q->execute(['lansia_records','jadwal_kontrol_lansia']);
        $cx = (int)$q->fetchColumn();
        if ($cx === 0) { $pdo->exec("ALTER TABLE lansia_records ADD COLUMN jadwal_kontrol_lansia DATE NULL"); }
    } catch (Throwable $e) {}
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