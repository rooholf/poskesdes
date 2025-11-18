<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . "/src/Autoloader.php";
\App\Autoloader::register();
if (!isset($pdo)) { require __DIR__ . "/db.php"; }
if (!function_exists('ensureSchema')) { require __DIR__ . "/schema.php"; }
header('Content-Type: application/json');
if (!$pdo) { http_response_code(500); echo json_encode(['error'=>'db_unavailable']); exit; }
if (function_exists('ensureSchema')) {
    if (empty($_SESSION['__schema_ok'])) {
        try { ensureSchema($pdo); $_SESSION['__schema_ok'] = time(); } catch (Throwable $e) {}
    }
}
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
if ($action === 'articles_latest') {
    $rows = $pdo->query("SELECT id,title,COALESCE(category,'Tanpa Kategori') AS category,SUBSTRING(body,1,100) AS snip,created_at FROM articles ORDER BY created_at DESC LIMIT 3")->fetchAll();
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
    if (!\App\Services\AuthService::isLoggedIn()) { http_response_code(403); echo json_encode(['error'=>'unauthorized']); exit; }
    $patient_id = $_POST['patient_id'] ?? null;
    $patient_name = trim($_POST['patient_name'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
    $jk = trim($_POST['jadwal_kontrol'] ?? '');
    $anc_kunjungan_ke = ($_POST['anc_kunjungan_ke'] ?? '') !== '' ? (int)$_POST['anc_kunjungan_ke'] : null;
    $anc_k_status = ($t = trim($_POST['anc_k_status'] ?? '')) !== '' ? $t : null;
    $anc_usg_status = ($t = trim($_POST['anc_usg_status'] ?? '')) !== '' ? $t : null;
    $anc_4t_status = ($t = trim($_POST['anc_4t_status'] ?? '')) !== '' ? $t : null;
    $gravida = ($_POST['gravida'] ?? '') !== '' ? (int)$_POST['gravida'] : null;
    $para = ($_POST['para'] ?? '') !== '' ? (int)$_POST['para'] : null;
    $abortus = ($_POST['abortus'] ?? '') !== '' ? (int)$_POST['abortus'] : null;
    $hpht = ($t = trim($_POST['hpht'] ?? '')) !== '' ? $t : null;
    $hpl = ($t = trim($_POST['hpl'] ?? '')) !== '' ? $t : null;
    $keluhan_utama = ($t = trim($_POST['keluhan_utama'] ?? '')) !== '' ? $t : null;
    $td = ($t = trim($_POST['td'] ?? '')) !== '' ? $t : null;
    $nadi = ($_POST['nadi'] ?? '') !== '' ? (int)$_POST['nadi'] : null;
    $suhu = ($_POST['suhu'] ?? '') !== '' ? (float)$_POST['suhu'] : null;
    $bb = ($_POST['bb'] ?? '') !== '' ? (float)$_POST['bb'] : null;
    $tb = ($_POST['tb'] ?? '') !== '' ? (int)$_POST['tb'] : null;
    $edema = ($t = trim($_POST['edema'] ?? '')) !== '' ? $t : null;
    $djj = ($_POST['djj'] ?? '') !== '' ? (int)$_POST['djj'] : null;
    $tfu = ($_POST['tfu'] ?? '') !== '' ? (float)$_POST['tfu'] : null;
    $posisi_janin = ($t = trim($_POST['posisi_janin'] ?? '')) !== '' ? $t : null;
    $gerak_janin = ($t = trim($_POST['gerak_janin'] ?? '')) !== '' ? $t : null;
    $fe_diberikan = ($_POST['fe_diberikan'] ?? '') !== '' ? (int)$_POST['fe_diberikan'] : null;
    $saran_gizi = ($t = trim($_POST['saran_gizi'] ?? '')) !== '' ? $t : null;
    $hb = ($_POST['hb'] ?? '') !== '' ? (float)$_POST['hb'] : null;
    $urin = ($t = trim($_POST['urin'] ?? '')) !== '' ? $t : null;
    $penunjang_lain = ($t = trim($_POST['penunjang_lain'] ?? '')) !== '' ? $t : null;
    $faktor_risiko = ($t = trim($_POST['faktor_risiko'] ?? '')) !== '' ? $t : null;
    $klasifikasi_risiko = ($t = trim($_POST['klasifikasi_risiko'] ?? '')) !== '' ? $t : null;
    $perlu_rujukan = ($t = trim($_POST['perlu_rujukan'] ?? '')) !== '' ? $t : null;
    $tujuan_rujukan = ($t = trim($_POST['tujuan_rujukan'] ?? '')) !== '' ? $t : null;
    $alasan_rujukan = ($t = trim($_POST['alasan_rujukan'] ?? '')) !== '' ? $t : null;
    $tatalaksana_awal = ($t = trim($_POST['tatalaksana_awal'] ?? '')) !== '' ? $t : null;
    $ringkasan_kunjungan = ($t = trim($_POST['ringkasan_kunjungan'] ?? '')) !== '' ? $t : null;
    $jadwal_kontrol_berikut = $jk !== '' ? $jk : null;
    $stmt = $pdo->prepare("INSERT INTO anc_records(patient_id,patient_name,tanggal,anc_kunjungan_ke,anc_k_status,anc_usg_status,anc_4t_status,gravida,para,abortus,hpht,hpl,keluhan_utama,td,nadi,suhu,bb,tb,edema,djj,tfu,posisi_janin,gerak_janin,fe_diberikan,saran_gizi,hb,urin,penunjang_lain,faktor_risiko,klasifikasi_risiko,perlu_rujukan,tujuan_rujukan,alasan_rujukan,tatalaksana_awal,ringkasan_kunjungan,jadwal_kontrol_berikut) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$patient_id ?: null, $patient_name !== '' ? $patient_name : null, $tanggal !== '' ? $tanggal : null, $anc_kunjungan_ke, $anc_k_status, $anc_usg_status, $anc_4t_status, $gravida, $para, $abortus, $hpht, $hpl, $keluhan_utama, $td, $nadi, $suhu, $bb, $tb, $edema, $djj, $tfu, $posisi_janin, $gerak_janin, $fe_diberikan, $saran_gizi, $hb, $urin, $penunjang_lain, $faktor_risiko, $klasifikasi_risiko, $perlu_rujukan, $tujuan_rujukan, $alasan_rujukan, $tatalaksana_awal, $ringkasan_kunjungan, $jadwal_kontrol_berikut]);
    if ($jk !== '') { $pdo->prepare("INSERT INTO schedules(subject,date,service_type,contact_method) VALUES(?,?,?,?)")
        ->execute(["ANC Kontrol: ".$patient_name, $jk, 'ANC Kontrol', 'Poskesdes']); }
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'kb_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!\App\Services\AuthService::isLoggedIn()) { http_response_code(403); echo json_encode(['error'=>'unauthorized']); exit; }
    $patient_id = $_POST['patient_id'] ?? null;
    $patient_name = trim($_POST['patient_name'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
    $jk = trim($_POST['jadwal_kontrol'] ?? '');
    $kb_status_peserta = ($t = trim($_POST['kb_status_peserta'] ?? '')) !== '' ? $t : null;
    $metode_kb = ($t = trim($_POST['metode_kb'] ?? '')) !== '' ? $t : null;
    $tgl_mulai = ($t = trim($_POST['tgl_mulai'] ?? '')) !== '' ? $t : null;
    $keterangan = ($t = trim($_POST['keterangan'] ?? '')) !== '' ? $t : null;
    $rencana_tindakan = ($t = trim($_POST['rencana_tindakan'] ?? '')) !== '' ? $t : null;
    $jadwal_kontrol_kb = $jk !== '' ? $jk : null;
    $stmt = $pdo->prepare("INSERT INTO kb_records(patient_id,patient_name,tanggal,kb_status_peserta,metode_kb,tgl_mulai,keterangan,rencana_tindakan,jadwal_kontrol_kb) VALUES(?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$patient_id ?: null, $patient_name !== '' ? $patient_name : null, $tanggal !== '' ? $tanggal : null, $kb_status_peserta, $metode_kb, $tgl_mulai, $keterangan, $rencana_tindakan, $jadwal_kontrol_kb]);
    if ($jk !== '') { $pdo->prepare("INSERT INTO schedules(subject,date,service_type,contact_method) VALUES(?,?,?,?)")
        ->execute(["KB Kontrol: ".$patient_name, $jk, 'KB Kontrol', 'Telepon/WA']); }
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'lansia_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!\App\Services\AuthService::isLoggedIn()) { http_response_code(403); echo json_encode(['error'=>'unauthorized']); exit; }
    $patient_id = $_POST['patient_id'] ?? null;
    $patient_name = trim($_POST['patient_name'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
    $jk = trim($_POST['jadwal_kontrol'] ?? '');
    $keluhan_lansia = ($t = trim($_POST['keluhan_lansia'] ?? '')) !== '' ? $t : null;
    $diagnosa_lansia = ($t = trim($_POST['diagnosa_lansia'] ?? '')) !== '' ? $t : null;
    $bb_lansia = ($_POST['bb_lansia'] ?? '') !== '' ? (float)$_POST['bb_lansia'] : null;
    $tb_lansia = ($_POST['tb_lansia'] ?? '') !== '' ? (int)$_POST['tb_lansia'] : null;
    $td_lansia = ($t = trim($_POST['td_lansia'] ?? '')) !== '' ? $t : null;
    $gds = ($_POST['gds'] ?? '') !== '' ? (int)$_POST['gds'] : null;
    $asam_urat = ($_POST['asam_urat'] ?? '') !== '' ? (float)$_POST['asam_urat'] : null;
    $kolesterol = ($_POST['kolesterol'] ?? '') !== '' ? (int)$_POST['kolesterol'] : null;
    $tindakan_lansia = ($t = trim($_POST['tindakan_lansia'] ?? '')) !== '' ? $t : null;
    $jadwal_kontrol_lansia = $jk !== '' ? $jk : null;
    $stmt = $pdo->prepare("INSERT INTO lansia_records(patient_id,patient_name,tanggal,keluhan_lansia,diagnosa_lansia,bb_lansia,tb_lansia,td_lansia,gds,asam_urat,kolesterol,tindakan_lansia,jadwal_kontrol_lansia) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$patient_id ?: null, $patient_name !== '' ? $patient_name : null, $tanggal !== '' ? $tanggal : null, $keluhan_lansia, $diagnosa_lansia, $bb_lansia, $tb_lansia, $td_lansia, $gds, $asam_urat, $kolesterol, $tindakan_lansia, $jadwal_kontrol_lansia]);
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