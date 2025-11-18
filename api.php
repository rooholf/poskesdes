<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . "/src/Autoloader.php";
\App\Autoloader::register();
if (!isset($pdo)) { require __DIR__ . "/db.php"; }
if (!function_exists('ensureSchema')) { require __DIR__ . "/schema.php"; }
header('Content-Type: application/json');
if (!$pdo) { http_response_code(500); echo json_encode(['error'=>'db_unavailable']); exit; }
$action = $_GET['action'] ?? '';
if ($action === 'patients_list') {
    $rows = $pdo->query("SELECT id,name,address,phone FROM patients ORDER BY name ASC")->fetchAll();
    echo json_encode($rows); exit;
}
if ($action === 'patients_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nama'] ?? '');
    $phone = trim($_POST['no_hp'] ?? '');
    $address = trim($_POST['alamat'] ?? '');
    if ($name === '') { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }
    $pdo->prepare("INSERT INTO patients(name,address,phone) VALUES(?,?,?)")->execute([$name,$address,$phone]);
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'schedules_list') {
    $rows = $pdo->query("SELECT id,subject,date,service_type,time,contact_method,notes FROM schedules ORDER BY date ASC")->fetchAll();
    echo json_encode($rows); exit;
}
if ($action === 'schedules_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!\App\Services\AuthService::isLoggedIn()) { http_response_code(403); echo json_encode(['error'=>'unauthorized']); exit; }
    $subject = trim($_POST['nama'] ?? '');
    $date = trim($_POST['tanggal'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $cara = trim($_POST['cara'] ?? '');
    if ($subject === '' || $date === '' || $jenis === '' || $cara === '') { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }
    $pdo->prepare("INSERT INTO schedules(subject,date,service_type,contact_method) VALUES(?,?,?,?)")->execute([$subject,$date,$jenis,$cara]);
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'schedules_update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!\App\Services\AuthService::isLoggedIn()) { http_response_code(403); echo json_encode(['error'=>'unauthorized']); exit; }
    $id = (int)($_POST['id'] ?? 0);
    $subject = trim($_POST['nama'] ?? '');
    $date = trim($_POST['tanggal'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $cara = trim($_POST['cara'] ?? '');
    $time = trim($_POST['waktu'] ?? '');
    $notes = trim($_POST['catatan'] ?? '');
    if ($id <= 0) { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }
    $stmt = $pdo->prepare("UPDATE schedules SET subject=?, date=?, service_type=?, contact_method=?, time=?, notes=? WHERE id=?");
    $stmt->execute([$subject !== '' ? $subject : null, $date !== '' ? $date : null, $jenis !== '' ? $jenis : null, $cara !== '' ? $cara : null, $time !== '' ? $time : null, $notes !== '' ? $notes : null, $id]);
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'schedules_delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!\App\Services\AuthService::isLoggedIn()) { http_response_code(403); echo json_encode(['error'=>'unauthorized']); exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }
    $pdo->prepare("DELETE FROM schedules WHERE id=?")->execute([$id]);
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'anc_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? null;
    $patient_name = $_POST['patient_name'] ?? '';
    $data = $_POST['data_json'] ?? '{}';
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $pdo->prepare("INSERT INTO anc_records(patient_id,patient_name,tanggal,data) VALUES(?,?,?,CAST(? AS JSON))")
        ->execute([$patient_id ?: null, $patient_name, $tanggal, $data]);
    $jk = $_POST['jadwal_kontrol'] ?? '';
    if ($jk !== '') { $pdo->prepare("INSERT INTO schedules(subject,date,service_type,contact_method) VALUES(?,?,?,?)")
        ->execute(["ANC Kontrol: ".$patient_name, $jk, 'ANC Kontrol', 'Poskesdes']); }
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'kb_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? null;
    $patient_name = $_POST['patient_name'] ?? '';
    $data = $_POST['data_json'] ?? '{}';
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $pdo->prepare("INSERT INTO kb_records(patient_id,patient_name,tanggal,data) VALUES(?,?,?,CAST(? AS JSON))")
        ->execute([$patient_id ?: null, $patient_name, $tanggal, $data]);
    $jk = $_POST['jadwal_kontrol'] ?? '';
    if ($jk !== '') { $pdo->prepare("INSERT INTO schedules(subject,date,service_type,contact_method) VALUES(?,?,?,?)")
        ->execute(["KB Kontrol: ".$patient_name, $jk, 'KB Kontrol', 'Telepon/WA']); }
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'lansia_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? null;
    $patient_name = $_POST['patient_name'] ?? '';
    $data = $_POST['data_json'] ?? '{}';
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $pdo->prepare("INSERT INTO lansia_records(patient_id,patient_name,tanggal,data) VALUES(?,?,?,CAST(? AS JSON))")
        ->execute([$patient_id ?: null, $patient_name, $tanggal, $data]);
    $jk = $_POST['jadwal_kontrol'] ?? '';
    if ($jk !== '') { $pdo->prepare("INSERT INTO schedules(subject,date,service_type,contact_method) VALUES(?,?,?,?)")
        ->execute(["Kontrol Lansia: ".$patient_name, $jk, 'Lansia Kontrol', 'Telepon/WA']); }
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'summary') {
    $pendaftar = (int)$pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
    $anc = (int)$pdo->query("SELECT COUNT(*) FROM anc_records")->fetchColumn();
    $kb = (int)$pdo->query("SELECT COUNT(*) FROM kb_records")->fetchColumn();
    $lansia = (int)$pdo->query("SELECT COUNT(*) FROM lansia_records")->fetchColumn();
    $jadwal = (int)$pdo->query("SELECT COUNT(*) FROM schedules WHERE date >= CURDATE()")->fetchColumn();
    echo json_encode(['pendaftar'=>$pendaftar,'anc'=>$anc,'kb'=>$kb,'lansia'=>$lansia,'jadwal_aktif'=>$jadwal]); exit;
}
if ($action === 'export_pendaftar') {
    $rows = $pdo->query("SELECT id,name AS nama, phone AS no_hp, address AS alamat FROM patients ORDER BY id ASC")->fetchAll(); echo json_encode($rows); exit;
}
if ($action === 'export_jadwal') {
    $rows = $pdo->query("SELECT subject AS nama, date AS tanggal, service_type AS jenis, contact_method AS cara FROM schedules ORDER BY date ASC")->fetchAll(); echo json_encode($rows); exit;
}
if ($action === 'export_anc') {
    $rows = $pdo->query("SELECT id,patient_name AS pasien_nama,tanggal,data FROM anc_records ORDER BY id ASC")->fetchAll(); echo json_encode($rows); exit;
}
if ($action === 'export_kb') {
    $rows = $pdo->query("SELECT id,patient_name AS pasien_nama,tanggal,data FROM kb_records ORDER BY id ASC")->fetchAll(); echo json_encode($rows); exit;
}
if ($action === 'export_lansia') {
    $rows = $pdo->query("SELECT id,patient_name AS pasien_nama,tanggal,data FROM lansia_records ORDER BY id ASC")->fetchAll(); echo json_encode($rows); exit;
}
http_response_code(404); echo json_encode(['error'=>'not_found']);