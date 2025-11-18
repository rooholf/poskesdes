<?php
?><div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="group">Boards</div>
    <a href="/admin" class="<?php echo ($page==='admin_dashboard'?'active':''); ?>">Dashboard</a>
    <a href="/admin/patients" class="<?php echo ($page==='admin_patients'?'active':''); ?>">Pasien</a>
    <a href="/admin/visits" class="<?php echo ($page==='admin_visits'?'active':''); ?>">Kunjungan</a>
    <a href="/admin/schedules" class="<?php echo ($page==='admin_schedules'?'active':''); ?>">Jadwal & Pengingat</a>
    <a href="/admin/articles" class="<?php echo ($page==='admin_articles'?'active':''); ?>">Artikel</a>
    <a href="/admin/reports" class="<?php echo ($page==='admin_reports'?'active':''); ?>">Laporan</a>
    <div class="group">Other</div>
    <a href="#">Pengaturan</a>
    <a href="#">Integrasi</a>
    <a href="#">Bantuan</a>
  </aside>
  <section class="admin-content">
    <div class="text-muted mb-2">← Kembali</div>
    <div class="stepper mb-2">
      <div class="step"><span class="dot"></span><span>Mulai</span></div>
      <div class="step"><span class="dot"></span><span>Jenis</span></div>
      <div class="step"><span class="dot"></span><span>Tambah</span></div>
      <div class="step"><span class="dot"></span><span>Konfirmasi</span></div>
      <div class="step"><span class="dot"></span><span>Selesai</span></div>
    </div>
    <div class="card-shell mb-3">
      <div class="fw-bold mb-2">Ajukan Jadwal Pemeriksaan</div>
      <div class="row g-2">
        <div class="col-12 col-md-6"><div class="option-card"><div class="fw-bold">Mulai dari kosong</div><div class="text-muted" style="font-size:12px">Isi semua informasi jadwal di website</div></div></div>
        <div class="col-12 col-md-6"><div class="option-card"><div class="fw-bold">Unggah file</div><div class="text-muted" style="font-size:12px">Unggah daftar jadwal, tidak dapat diedit</div></div></div>
      </div>
      <div class="mt-3">
      <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Jadwal sebelumnya</div><a href="/admin/schedules" class="text-decoration-none">Lihat semua</a></div>
        <div class="row g-2">
          <?php if ($pdo) { $rows=$pdo->query("SELECT subject AS nama FROM schedules ORDER BY id DESC LIMIT 6")->fetchAll(); foreach($rows as $r){ ?>
            <div class="col-12 col-md-4"><div class="pill"><?php echo htmlspecialchars($r['nama'] ?: 'Jadwal'); ?></div></div>
          <?php } } else { ?>
            <div class="col-12"><div class="pill">Contoh Jadwal</div></div>
          <?php } ?>
        </div>
      </div>
      <div class="d-flex justify-content-end mt-3"><a class="btn btn-dark" href="/admin/schedules">Lanjutkan</a></div>
    </div>
    <div class="row g-2">
      <div class="col-12 col-md-6">
        <div class="admin-card mint">
          <div class="d-flex justify-content-between align-items-center"><div class="fw-bold">Jadwal Hari Ini</div><a class="btn btn-sm btn-outline-secondary" href="/admin/schedules">Lihat Semua</a></div>
          <div class="mt-2">
            <?php if ($pdo) { $stmt=$pdo->prepare("SELECT service_type,time,notes FROM schedules WHERE date=CURDATE() ORDER BY time ASC"); $stmt->execute(); $rows=$stmt->fetchAll(); if(count($rows)===0){ echo '<div class=\"text-muted\">Tidak ada jadwal hari ini</div>'; } foreach($rows as $r){ ?>
            <div class="d-flex justify-content-between align-items-center" style="padding:6px 0;border-bottom:1px solid #eee"><div><div class="fw-bold"><?php echo htmlspecialchars($r["service_type"] ?? ''); ?></div><div class="text-muted" style="font-size:12px"><?php echo htmlspecialchars($r["notes"] ?? ''); ?></div></div><div><?php echo htmlspecialchars($r["time"] ?? ''); ?></div></div>
            <?php } } ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="admin-card">
          <div class="fw-bold">Catatan Lapangan</div>
          <form method="post" class="vstack gap-2 mt-2">
            <input type="hidden" name="action" value="add_field_note">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
            <textarea name="note" class="form-control" placeholder="Tuliskan catatan hari ini"></textarea>
            <button class="btn btn-primary">Simpan</button>
          </form>
          <div class="mt-2">
            <?php if ($pdo) { $stmt=$pdo->prepare("SELECT note, created_at FROM field_notes WHERE for_date=CURDATE() ORDER BY created_at DESC"); $stmt->execute(); $rows=$stmt->fetchAll(); foreach($rows as $r){ ?>
              <div style="padding:8px;border:1px solid #eee;border-radius:8px;margin-bottom:8px"><div><?php echo htmlspecialchars($r["note"]); ?></div><div class="text-muted" style="font-size:12px"><?php echo htmlspecialchars($r["created_at"]); ?></div></div>
            <?php } } ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>