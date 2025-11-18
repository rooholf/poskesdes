<?php
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) { ini_set('session.cookie_secure', '1'); }
session_start();
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/schema.php";
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/src/Autoloader.php";
\App\Autoloader::register();
\App\Services\CsrfService::ensure();
$uri = $_SERVER["REQUEST_URI"] ?? "/";
if (strpos($uri, "/api.php") === 0) { include __DIR__ . "/api.php"; return; }
$page = isset($_GET["page"]) ? $_GET["page"] : \App\Core\Router::resolve($uri);
$isAdminPage = (substr($page,0,6) === 'admin_');
if ($isAdminPage && !\App\Services\AuthService::isLoggedIn()) { $error = "Silakan login untuk mengakses halaman admin"; $page = 'home'; }
$error = null;
$info = null;
$error_target = null;
if ($page === "home" && $_SERVER["REQUEST_METHOD"] !== "POST") { include __DIR__ . "/views/home.php"; return; }
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrfValid = \App\Services\CsrfService::validate($_POST['csrf_token'] ?? '');
    if (!$csrfValid) { $error = "Invalid CSRF"; }
    $action = isset($_POST["action"]) ? $_POST["action"] : null;
    if ($csrfValid && $action === "login") {
        $u = trim($_POST["username"] ?? "");
        $p = $_POST["password"] ?? "";
        if (!$pdo) { $error = "Database is not configured"; }
        else {
            $auth = new \App\Services\AuthService();
            if ($auth->login($pdo, $u, $p)) { header("Location: /admin"); exit; } else { $error = ((int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0 ? 'Belum ada akun admin' : 'Username atau password salah'); $error_target = 'login'; }
        }
    } elseif ($csrfValid && $action === "register_admin") {
        $u = trim($_POST["username"] ?? "");
        $p = $_POST["password"] ?? "";
        if (!$pdo) { $error = "Database is not configured"; }
        else {
            $auth = new \App\Services\AuthService();
            if ($auth->registerAdmin($pdo, $u, $p)) { $info = "Akun admin dibuat"; } else { $error = "Sudah ada akun atau data tidak valid"; }
        }
    } elseif ($csrfValid && $action === "logout") {
        \App\Services\AuthService::logout();
        header("Location: /");
        exit;
    } elseif ($csrfValid && $action === "add_patient" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $name = trim($_POST["name"] ?? "");
        $dob = trim($_POST["dob"] ?? "");
        $address = trim($_POST["address"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        if ($name !== "") {
            $svc = new \App\Services\PatientsService($pdo);
            $svc->add($name, $dob !== "" ? $dob : null, $address, $phone);
            $info = "Pasien ditambahkan";
        } else { $error = "Nama wajib"; }
    } elseif ($csrfValid && $action === "delete_patient" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $id = (int)($_POST["id"] ?? 0);
        if ($id > 0) {
            $svc = new \App\Services\PatientsService($pdo);
            $svc->delete($id);
            $info = "Pasien dihapus";
        }
    } elseif ($csrfValid && $action === "edit_patient" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $id = (int)($_POST["id"] ?? 0);
        $name = trim($_POST["name"] ?? "");
        $dob = trim($_POST["dob"] ?? "");
        $address = trim($_POST["address"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        if ($id > 0 && $name !== "") {
            $svc = new \App\Services\PatientsService($pdo);
            $svc->edit($id, $name, $dob !== "" ? $dob : null, $address, $phone);
            $info = "Pasien diubah";
        } else { $error = "Nama wajib"; }
    } elseif ($csrfValid && $action === "add_schedule" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $date = trim($_POST["date"] ?? "");
        $service_type = trim($_POST["service_type"] ?? "");
        $time = trim($_POST["time"] ?? "");
        $notes = trim($_POST["notes"] ?? "");
        if ($date !== "" && $service_type !== "") {
            $svc = new \App\Services\ScheduleService($pdo);
            $svc->addSchedule($date, $service_type, $time !== "" ? $time : null, $notes !== "" ? $notes : null);
            $info = "Jadwal ditambahkan";
        } else { $error = "Tanggal dan jenis wajib"; }
    } elseif ($csrfValid && $action === "add_field_note" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $note = trim($_POST["note"] ?? "");
        if ($note !== "") {
            $svc = new \App\Services\FieldNotesService($pdo);
            $svc->add($_SESSION["user_id"], $note);
            $info = "Catatan tersimpan";
        } else { $error = "Catatan kosong"; }
    } elseif ($csrfValid && $action === "add_article" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $title = trim($_POST["title"] ?? "");
        $category = trim($_POST["category"] ?? "");
        $body = trim($_POST["body"] ?? "");
        if ($title !== "" && $body !== "") {
            $svc = new \App\Services\ArticleService($pdo);
            $svc->addArticle($title, $category !== "" ? $category : null, $body);
            $info = "Artikel disimpan";
        } else { $error = "Judul dan isi wajib"; }
    } elseif ($csrfValid && $action === "add_article_note" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $aid = (int)($_POST["article_id"] ?? 0);
        $note = trim($_POST["note"] ?? "");
        if ($aid > 0 && $note !== "") {
            $svc = new \App\Services\ArticleService($pdo);
            $svc->addNote($aid, $_SESSION["user_id"], $note);
            header("Location: index.php?page=article&id=".$aid);
            exit;
        } else { $error = "Catatan atau artikel tidak valid"; }
    } elseif ($csrfValid && $action === "update_schedule_status" && $pdo) {
        \App\Services\AuthService::requireLogin();
        $id = (int)($_POST["id"] ?? 0);
        $status = trim($_POST["status"] ?? "");
        if ($id > 0 && $status !== "") {
            $svc = new \App\Services\ScheduleService($pdo);
            $svc->updateStatus($id, $status);
            $info = "Status diperbarui";
        } else { $error = "Data status tidak valid"; }
    }
}
if ($page === "home") { include __DIR__ . "/views/home.php"; return; }
function titleFor($page) {
    if ($page === "home") return "Beranda";
    if ($page === "schedule") return "Jadwal Layanan";
    if ($page === "articles") return "Informasi Kesehatan";
    if ($page === "profile") return "Profil";
    if ($page === "login") return "Login";
    if ($page === "admin_dashboard") return "Dashboard";
    if ($page === "admin_patients") return "Manajemen Pasien";
    if ($page === "admin_schedules") return "Jadwal & Pengingat";
    if ($page === "admin_articles") return "Artikel";
    if ($page === "admin_reports") return "Laporan";
    if ($page === "admin_visits") return "Kunjungan";
    return "Poskesdes";
}
?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<title>Poskesdes Pagar Ruyung - <?php echo htmlspecialchars(titleFor($page)); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root { --teal:#0ea5a5; --orange:#ff8a4c; }
.brand { color: var(--teal); font-weight: 700; }
.btn-primary { background-color: var(--teal); border-color: var(--teal); }
.btn-warning { background-color: var(--orange); border-color: var(--orange); }
.bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-top: 1px solid #eee; display: flex; justify-content: space-around; padding: .5rem 0; z-index: 1000; }
.bottom-nav a { text-decoration: none; color: #333; font-size: .9rem; }
.content { padding-bottom: 64px; }
 body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
.admin-layout{ display:flex; gap:12px; min-height: calc(100vh - 180px) }
.admin-sidebar{ flex:0 0 250px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:10px }
.admin-sidebar .group{ font-size:.8rem; color:#6b7280; margin:8px 0 6px }
.admin-sidebar a{ display:flex; align-items:center; gap:8px; padding:8px 10px; border-radius:8px; text-decoration:none; color:#374151 }
.admin-sidebar a.active{ background:var(--mint,#F0FDFB); border:1px solid #d1fae5; color:#0f766e }
.admin-content{ flex:1; display:flex; flex-direction:column; min-height: 48vh }
.admin-card{ background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:10px; box-shadow:0 1px 2px rgba(0,0,0,.04); min-height: 140px }
.badge-status{ display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px }
.status-teal{ background:#d1fae5; color:#0f766e }
.status-yellow{ background:#fef3c7; color:#92400e }
.status-orange{ background:#ffe4cc; color:#9a3412 }
.status-gray{ background:#e5e7eb; color:#374151 }
.week-grid{ display:grid; grid-template-columns:repeat(7,1fr); gap:6px }
.week-cell{ background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:8px; text-align:center }
.week-cell .date{ font-weight:700; font-size:12px }
.week-cell .count{ font-size:12px; color:#6b7280 }
@media print{ .admin-sidebar, .bottom-nav, header, nav { display:none !important } body{ background:#fff } }
.stepper{ display:flex; gap:18px; align-items:center; border:1px solid #e5e7eb; border-radius:12px; padding:10px 14px; background:#fff }
.step{ display:flex; align-items:center; gap:8px; color:#374151; font-size:.9rem }
.dot{ width:18px; height:18px; border-radius:50%; background:#dcfce7; border:2px solid #86efac }
.card-shell{ background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:12px; box-shadow:0 1px 2px rgba(0,0,0,.04) }
.option-card{ border:1px solid #e5e7eb; border-radius:10px; padding:10px; background:#fff }
.pill{ background:#f3f4f6; border-radius:10px; padding:6px 8px }
.mint{ background:var(--mint,#F0FDFB) }
@media(max-width:768px){ .admin-layout{ flex-direction:column } .admin-sidebar{ flex:1 } .stepper{ flex-wrap:wrap } }
@media(max-width:768px){ .admin-layout{ flex-direction:column } .admin-sidebar{ flex:1 } }
</style>
</head>
<body class="bg-light">
<header class="border-bottom bg-white">
  <div class="container py-2 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
      <span class="brand">Poskesehatan Desa Pagar Ruyung</span>
    </div>
    <div>
      <?php if (\App\Services\AuthService::isLoggedIn()) { ?>
        <form method="post" class="d-inline">
          <input type="hidden" name="action" value="logout">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
          <button class="btn btn-outline-danger btn-sm">Keluar</button>
        </form>
      <?php } else { ?>
        <a class="btn btn-outline-secondary btn-sm" href="#" id="open-login">Masuk</a>
      <?php } ?>
    </div>
  </div>
</header>
<main class="content container py-3" style="padding-bottom:84px">
  <?php if ($error) { echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; }
        if ($info) { echo '<div class="alert alert-success">'.htmlspecialchars($info).'</div>'; } ?>
  <?php
  $controller = new \App\Controllers\PageController();
  $controller->render($page);
  ?>
</main>
  <?php if (substr($page,0,6) !== 'admin_') { ?>
  <nav class="bottom-nav d-md-none<?php echo (\App\Services\AuthService::isLoggedIn() ? ' logged-in' : ''); ?>">
    <a href="/home">Beranda</a>
    <a href="/schedule">Jadwal</a>
    <a href="/articles">Edukasi</a>
  <?php if (\App\Services\AuthService::isLoggedIn()) { ?>
    <a href="/profile">Profil</a>
  <?php } else { ?>
    <a href="#" id="open-login-bottom">Masuk</a>
  <?php } ?>
  </nav>
  <?php } ?>
<style>
.modal{ position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(2,6,23,0.4); z-index:10000 }
.modal.show{ display:flex }
.modal .panel{ width:min(480px,94%); background:#fff; border-radius:12px; padding:16px }
.modal-open header .container{ justify-content:center }
.modal-open header .container > div:last-child{ display:none }
.bottom-nav{ z-index:10001; position:relative }
@media(max-width:768px){ header .container{ justify-content:center } header .container > div:last-child{ display:none } .bottom-nav{ width:calc(100% - 24px); margin:0 12px; border-radius:14px; overflow-x:auto; -webkit-overflow-scrolling:touch; white-space:nowrap; gap:8px; padding:8px 10px; justify-content:flex-start } .bottom-nav a{ display:inline-flex; flex:0 0 auto } .bottom-nav.logged-in{ justify-content:flex-start; overflow-x:auto } .bottom-nav:not(.logged-in){ justify-content:space-between; overflow-x:hidden } .bottom-nav::before, .bottom-nav::after{ content:""; position:absolute; top:0; bottom:0; width:18px; pointer-events:none } .bottom-nav::before{ left:0; background:linear-gradient(90deg, rgba(255,255,255,1), rgba(255,255,255,0)) } .bottom-nav::after{ right:0; background:linear-gradient(270deg, rgba(255,255,255,1), rgba(255,255,255,0)) } }
</style>
<div id="login-modal" class="modal" aria-hidden="true">
  <div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Masuk</div><button class="btn btn-sm btn-outline-secondary" id="login-close">Tutup</button></div>
    <?php if (($error ?? null) && (($error_target ?? null) === 'login')) { echo '<div class="alert alert-danger mb-2">'.htmlspecialchars($error).'</div>'; } ?>
    <form method="post" class="vstack gap-2">
      <input type="hidden" name="action" value="login">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
      <input name="username" class="form-control" placeholder="Username">
      <input type="password" name="password" class="form-control" placeholder="Password">
      <button class="btn btn-primary">Masuk</button>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){ var top=document.getElementById('open-login'); var bottom=document.getElementById('open-login-bottom'); var modal=document.getElementById('login-modal'); var close=document.getElementById('login-close'); function open(){ if(modal) { modal.classList.add('show'); document.body.classList.add('modal-open'); } } function hide(){ if(modal) { modal.classList.remove('show'); document.body.classList.remove('modal-open'); } } if(top) top.addEventListener('click',function(e){ e.preventDefault(); open(); }); if(bottom) bottom.addEventListener('click',function(e){ e.preventDefault(); open(); }); if(close) close.addEventListener('click',function(e){ e.preventDefault(); hide(); }); });
</script>
<script>
var LOGIN_ERROR = <?php echo (($error ?? null) && (($error_target ?? null) === 'login')) ? 'true' : 'false'; ?>;
document.addEventListener('DOMContentLoaded', function(){ if (LOGIN_ERROR) { var modal=document.getElementById('login-modal'); if(modal){ modal.classList.add('show'); document.body.classList.add('modal-open'); } } });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>