<?php
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <title>Poskesdes Pagar Ruyung — Demo Sistem (Gabungan ANC & KB)</title>
  <style>
    :root{
      --teal-600:#0ea5a4;
      --teal-50:#f0fafa;
      --orange-400:#fb923c;
      --muted:#6b7280;
      --danger:#ef4444;
      --warning:#f97316;
      --success:#16a34a;
      --card-shadow:0 6px 18px rgba(16,24,40,0.06);
      font-family:'Plus Jakarta Sans',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--teal-50);color:#04203a}
    header{
      background:#fff;padding:12px 16px;display:flex;
      align-items:center;gap:12px;
      box-shadow:0 1px 0 rgba(0,0,0,0.04);
      position:sticky;top:0;z-index:10
    }
    .logo{display:flex;gap:10px;align-items:center}
    .mark{
      width:44px;height:44px;border-radius:10px;
      background:linear-gradient(135deg,var(--teal-600),#0891b2);
      display:flex;align-items:center;justify-content:center;
      color:#fff;font-weight:700
    }
    nav.top-nav{margin-left:auto;display:flex;gap:10px}
    nav.top-nav a{
      color:var(--muted);text-decoration:none;
      font-weight:600;padding:8px 10px;border-radius:8px
    }
    nav.top-nav a.active{
      background:rgba(14,165,164,0.08);color:var(--teal-600)
    }
    main.container{max-width:980px;margin:18px auto;padding:0 16px}
    .card{
      background:#fff;border-radius:12px;
      padding:14px;box-shadow:var(--card-shadow)
    }
    .hero{display:flex;gap:14px;align-items:center}
    .hero svg{width:96px;height:96px}
    .hero-landing{display:flex;gap:16px;align-items:center;background:linear-gradient(135deg,#0ea5a4,#0891b2);color:#fff;border-radius:14px;padding:16px}
    .hero-landing .title{font-size:22px;font-weight:800}
    .hero-landing .subtitle{opacity:.9}
    .chip-links{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
    .chip-links a{display:inline-block;padding:8px 10px;border-radius:999px;background:#fff;color:#0b3b3b;text-decoration:none;font-weight:700;border:1px solid rgba(255,255,255,0.6)}
    .stats-bar{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px}
    .stat-card{background:#fff;border-radius:12px;padding:10px;box-shadow:var(--card-shadow)}
    .stat-card .label{font-size:12px;color:var(--muted)}
    .stat-card .value{font-size:20px;font-weight:800;color:#04203a}
    .info-grid{display:grid;gap:10px;margin-top:14px}
    @media(min-width:768px){ .info-grid{grid-template-columns:repeat(3,1fr)} }
    .info-item{background:#fff;border-radius:12px;padding:12px;box-shadow:var(--card-shadow)}
    .muted{color:var(--muted)}
    .grid{display:grid;gap:12px}
    .grid.services{grid-template-columns:repeat(1,1fr)}
    .svc{
      display:flex;gap:12px;align-items:center;
      padding:12px;border-radius:10px;
      background:linear-gradient(180deg,#fff,#fbfbfb);
      border:1px solid #f3f7f7;cursor:pointer
    }
    .svc .icon{
      width:56px;height:56px;border-radius:10px;
      background:var(--teal-50);display:flex;
      align-items:center;justify-content:center;
      font-size:24px;
    }
    .btn{
      display:inline-block;padding:8px 12px;border-radius:10px;
      background:var(--teal-600);color:#fff;
      text-decoration:none;font-weight:700;border:none;cursor:pointer
    }
    .small-btn{
      padding:6px 10px;border-radius:8px;
      background:#eef2f3;color:var(--teal-600);
      text-decoration:none;font-weight:700;border:none;cursor:pointer
    }
    .btn-primary{
      display:inline-block;padding:10px 14px;border-radius:10px;
      background:var(--teal-600);color:#fff;border:none;font-weight:700;cursor:pointer
    }
    .agenda .item{
      display:flex;justify-content:space-between;align-items:center;
      padding:10px;border-radius:10px;border:1px solid #f1f5f9
    }
    footer.bottom-fixed{
      position:fixed;left:0;right:0;bottom:10px;
      display:flex;justify-content:center
    }
    .bottom-nav{
      background:#fff;padding:8px 12px;border-radius:999px;
      display:flex;gap:12px;box-shadow:0 10px 30px rgba(2,6,23,0.08);
      position:relative
    }
    .bottom-nav a{
      display:flex;flex-direction:column;align-items:center;
      text-decoration:none;color:var(--muted);
      font-size:12px;padding:6px 10px;cursor:pointer
    }

    section.page{display:none}
    section.page.active{display:block}

    .form-row{display:flex;gap:8px;flex-wrap:wrap}
    .form-row .col{flex:1;min-width:160px}
    input,select,textarea{
      width:100%;padding:10px;border-radius:8px;
      border:1px solid #e6eef0
    }
    label{font-size:13px;font-weight:600;display:block;margin-bottom:6px}

    .modal{
      position:fixed;inset:0;display:none;
      align-items:center;justify-content:center;
      background:rgba(2,6,23,0.4);z-index:50
    }
    .modal.show{display:flex}
    .modal .panel{
      width:min(720px,94%);
      background:#fff;border-radius:12px;padding:16px
    }
    .modal-open header{justify-content:center}
    .modal-open nav.top-nav{display:none}
    .bottom-nav{z-index:100}

    .badge{
      display:inline-block;padding:3px 8px;border-radius:999px;
      font-size:11px;color:#fff;font-weight:600
    }
    .badge-danger{background:var(--danger)}
    .badge-warning{background:var(--orange-400)}
    .badge-success{background:var(--success)}
    .badge-muted{background:var(--muted)}

    .table-like div{
      display:grid;grid-template-columns:36px 1.6fr 1.2fr 1.2fr;
      padding:6px 4px;border-bottom:1px solid #eef2f3;
      font-size:13px
    }
    .table-like div.header{
      font-weight:600;background:#f9fafb;border-radius:8px
    }

    fieldset.card.anc-block{
      border-radius:10px;padding:12px;margin-bottom:12px;
      background:linear-gradient(180deg,#fff,#fbfbfb);border:1px solid #eef6f6;
    }
    fieldset.card.anc-block legend{
      padding:4px 8px;border-radius:6px;background:#f8faf9;font-weight:700;margin-bottom:8px;
    }
    .layanan-detail label{display:block;margin-bottom:8px;font-weight:600;color:#0b3b3b}
    .layanan-detail input, .layanan-detail select, .layanan-detail textarea{
      margin-top:6px;padding:8px;border-radius:8px;border:1px solid #e6eef0;
      font-weight:500;background:#fff
    }
    .layanan-detail .muted{color:var(--muted);font-weight:400}

    .danger-note{color:var(--danger);font-weight:700;margin-top:6px}

    @media(min-width:768px){
      .grid.services{grid-template-columns:repeat(3,1fr)}
      .form-row{flex-wrap:nowrap}
    }
    @media(max-width:768px){
      .hero-landing{flex-direction:column;align-items:flex-start;padding:12px}
      .hero-landing .title{font-size:18px}
      .mark{width:36px;height:36px}
      nav.top-nav{display:none}
      header{justify-content:center}
      .logo{width:100%;justify-content:center}
      .stats-bar{grid-template-columns:1fr}
      .info-grid{grid-template-columns:1fr}
      .agenda .item{flex-direction:column;align-items:flex-start;gap:8px}
      main.container{padding-bottom:84px}
      footer.bottom-fixed{bottom:0}
      .bottom-nav{width:calc(100% - 24px);border-radius:14px;justify-content:flex-start;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;white-space:nowrap}
      .bottom-nav a{flex:0 0 auto}
      .bottom-nav.logged-in{justify-content:flex-start;overflow-x:auto}
      .bottom-nav:not(.logged-in){justify-content:space-between;overflow-x:hidden}
      .bottom-nav::before, .bottom-nav::after{content:"";position:absolute;top:0;bottom:0;width:18px;pointer-events:none}
      .bottom-nav::before{left:0;background:linear-gradient(90deg, rgba(255,255,255,1), rgba(255,255,255,0))}
      .bottom-nav::after{right:0;background:linear-gradient(270deg, rgba(255,255,255,1), rgba(255,255,255,0))}
      .table-like div{grid-template-columns:28px 1.4fr 1fr 0.8fr;font-size:12px}
      .stat-card .value{font-size:18px}
      .chip-links a{padding:6px 8px}
      .form-row .col{min-width:100%}
      .modal .panel{width:94%;max-height:86vh;overflow:auto}
    }
    @media(min-width:769px){
      .bottom-nav{width:auto}
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <div class="mark">PK</div>
      <div>
        <div style="font-weight:700">Poskesdes Pagar Ruyung</div>
        <div class="muted" style="font-size:12px">
          Desa Pagar Ruyung — Jam: 08.00–14.00
        </div>
      </div>
    </div>
    <nav class="top-nav" role="navigation" aria-label="Menu utama">
      <?php $logged = \App\Services\AuthService::isLoggedIn(); ?>
      <a href="#home" data-route="home" class="active">Beranda</a>
      <?php if ($logged) { ?>
        <a href="#pemeriksaan" data-route="pemeriksaan">Pemeriksaan</a>
      <?php } ?>
      <a href="#jadwal" data-route="jadwal">Jadwal</a>
      <?php if ($logged) { ?>
        <a href="#laporan" data-route="laporan">Laporan</a>
        <a href="#ekspor" data-route="ekspor">Ekspor</a>
      <?php } ?>
      <a href="#edukasi" data-route="edukasi">Edukasi</a>
      <?php if ($logged) { ?>
        <a href="#profil" data-route="profil">Profil</a>
      <?php } else { ?>
        <a href="#" id="open-login" title="Masuk">Masuk</a>
      <?php } ?>
    </nav>
  </header>

  <main class="container">
    <?php if (isset($error) && $error) { echo '<div style="padding:10px;border:1px solid #e11d48;background:#fee2e2;color:#991b1b;border-radius:8px;margin:10px 0">'.htmlspecialchars($error).'</div>'; }
          if (isset($info) && $info) { echo '<div style="padding:10px;border:1px solid #059669;background:#d1fae5;color:#065f46;border-radius:8px;margin:10px 0">'.htmlspecialchars($info).'</div>'; } ?>
    <?php include __DIR__ . "/home_sections.php"; ?>
  </main>
  <footer class="bottom-fixed">
    <nav class="bottom-nav<?php echo (\App\Services\AuthService::isLoggedIn() ? ' logged-in' : ''); ?>" role="navigation">
      <?php $logged = isset($logged) ? $logged : \App\Services\AuthService::isLoggedIn(); ?>
      <a data-link="home">Beranda</a>
      <?php if ($logged) { ?><a data-link="pemeriksaan">Pemeriksaan</a><?php } ?>
      <a data-link="jadwal">Jadwal</a>
      <?php if ($logged) { ?><a data-link="laporan">Laporan</a><?php } ?>
      <?php if ($logged) { ?><a data-link="ekspor">Ekspor</a><?php } ?>
      <?php if ($logged) { ?><a data-link="profil">Profil</a><?php } else { ?><a href="#" id="open-login-bottom">Masuk</a><?php } ?>
    </nav>
  </footer>
  <div id="modal" class="modal" aria-hidden="true">
    <div class="panel">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <strong id="modal-title"></strong>
        <button id="modal-close" class="small-btn">Tutup</button>
      </div>
      <div style="height:8px"></div>
      <div id="modal-body" class="muted"></div>
    </div>
  </div>
  <div id="login-modal" class="modal" aria-hidden="true">
    <div class="panel">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <strong>Masuk</strong>
        <button id="login-close" class="small-btn">Tutup</button>
      </div>
      <div style="height:8px"></div>
      <form method="post">
        <input type="hidden" name="action" value="login" />
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>" />
        <label>Username<input type="text" name="username" /></label>
        <label>Password<input type="password" name="password" /></label>
        <div style="text-align:right;margin-top:10px"><button class="btn-primary" type="submit">Masuk</button></div>
      </form>
    </div>
  </div>
  <script>window.__isLoggedIn = <?php echo (\App\Services\AuthService::isLoggedIn() ? 'true' : 'false'); ?>;</script>
  <?php include __DIR__ . "/home_script.php"; ?>
</body>
</html>