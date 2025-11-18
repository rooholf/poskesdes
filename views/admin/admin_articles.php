<?php
?><div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="group">Boards</div>
    <a href="/admin">Dashboard</a>
    <a href="/admin/patients">Pasien</a>
    <a href="/admin/visits">Kunjungan</a>
    <a href="/admin/schedules">Jadwal & Pengingat</a>
    <a href="/admin/articles" class="active">Artikel</a>
    <a href="/admin/reports">Laporan</a>
  </aside>
  <section class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Manajemen Artikel</div><a class="btn btn-outline-secondary btn-sm" href="/admin">Kembali</a></div>
    <div class="admin-card mb-2">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="add_article">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
        <div class="col-12 col-md-6"><input name="title" class="form-control" placeholder="Judul"></div>
        <div class="col-12 col-md-3"><input name="category" class="form-control" placeholder="Kategori"></div>
        <div class="col-12"><textarea name="body" class="form-control" placeholder="Isi artikel" rows="4"></textarea></div>
        <div class="col-12 col-md-2"><button class="btn btn-primary w-100">Simpan Artikel</button></div>
      </form>
    </div>
    <div class="admin-card">
      <div class="row g-2">
        <?php if ($pdo) { $rows=$pdo->query("SELECT id,title,category,SUBSTRING(body,1,120) AS snip,created_at FROM articles ORDER BY created_at DESC LIMIT 20")->fetchAll(); foreach($rows as $r){ ?>
        <div class="col-12">
          <div class="card"><div class="card-body"><div class="d-flex justify-content-between"><div class="fw-bold"><?php echo htmlspecialchars($r['title']); ?></div><span class="badge text-bg-light"><?php echo htmlspecialchars($r['category']); ?></span></div><div class="text-muted mt-1"><?php echo htmlspecialchars($r['snip']); ?>...</div></div></div>
        </div>
        <?php } } else { ?>
        <div class="col-12"><div class="alert alert-warning">Artikel memerlukan database</div></div>
        <?php } ?>
      </div>
    </div>
  </section>
</div>