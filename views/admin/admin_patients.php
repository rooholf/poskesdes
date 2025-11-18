<?php
?><div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="group">Boards</div>
    <a href="/admin">Dashboard</a>
    <a href="/admin/patients" class="active">Pasien</a>
    <a href="/admin/visits">Kunjungan</a>
    <a href="/admin/schedules">Jadwal & Pengingat</a>
    <a href="/admin/articles">Artikel</a>
    <a href="/admin/reports">Laporan</a>
  </aside>
  <section class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Manajemen Pasien</div><a class="btn btn-outline-secondary btn-sm" href="/admin">Kembali</a></div>
    <div class="admin-card mb-2">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="add_patient">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
        <div class="col-12 col-md-3"><input name="name" class="form-control" placeholder="Nama"></div>
        <div class="col-12 col-md-3"><input name="dob" class="form-control" placeholder="Tanggal Lahir"></div>
        <div class="col-12 col-md-3"><input name="address" class="form-control" placeholder="Alamat"></div>
        <div class="col-12 col-md-2"><input name="phone" class="form-control" placeholder="No. HP"></div>
        <div class="col-12 col-md-1"><button class="btn btn-primary w-100">Tambah</button></div>
      </form>
    </div>
    <?php $editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0; if ($editId && $pdo) { $stE=$pdo->prepare('SELECT id,name,dob,address,phone FROM patients WHERE id=? LIMIT 1'); $stE->execute([$editId]); $e=$stE->fetch(PDO::FETCH_ASSOC); if ($e) { ?>
    <div class="admin-card mb-2">
      <div class="fw-bold mb-2">Ubah Pasien</div>
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="edit_patient">
        <input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
        <div class="col-12 col-md-3"><input name="name" class="form-control" value="<?php echo htmlspecialchars($e['name'] ?? ''); ?>" placeholder="Nama"></div>
        <div class="col-12 col-md-3"><input name="dob" class="form-control" value="<?php echo htmlspecialchars($e['dob'] ?? ''); ?>" placeholder="Tanggal Lahir"></div>
        <div class="col-12 col-md-3"><input name="address" class="form-control" value="<?php echo htmlspecialchars($e['address'] ?? ''); ?>" placeholder="Alamat"></div>
        <div class="col-12 col-md-2"><input name="phone" class="form-control" value="<?php echo htmlspecialchars($e['phone'] ?? ''); ?>" placeholder="No. HP"></div>
        <div class="col-12 col-md-1"><button class="btn btn-primary w-100">Simpan</button></div>
      </form>
    </div>
    <?php } } ?>
    <div class="admin-card">
      <div class="mb-2">
        <form class="row g-2" method="get">
          <input type="hidden" name="page" value="admin_patients">
          <div class="col-12 col-md-6"><input name="q" class="form-control" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Cari pasien"></div>
          <div class="col-6 col-md-2"><input name="per" class="form-control" value="<?php echo htmlspecialchars($_GET['per'] ?? '10'); ?>" placeholder="Per halaman"></div>
          <div class="col-6 col-md-2"><input name="pg" class="form-control" value="<?php echo htmlspecialchars($_GET['pg'] ?? '1'); ?>" placeholder="Halaman"></div>
          <div class="col-12 col-md-2"><button class="btn btn-outline-secondary w-100">Terapkan</button></div>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>Nama</th><th>Alamat</th><th>No. HP</th><th></th></tr></thead>
          <tbody>
          <?php if ($pdo) { 
            $q = isset($_GET['q']) ? trim($_GET['q']) : ''; 
            $per = isset($_GET['per']) ? (int)$_GET['per'] : 10; if ($per <= 0) { $per = 10; } if ($per > 100) { $per = 100; }
            $pg = isset($_GET['pg']) ? (int)$_GET['pg'] : 1; if ($pg <= 0) { $pg = 1; }
            $offset = ($pg - 1) * $per;
            $sql = 'SELECT id,name,address,phone FROM patients';
            $params = [];
            if ($q !== '') { $sql .= ' WHERE name LIKE ? OR address LIKE ? OR phone LIKE ?'; $params = ['%'.$q.'%','%'.$q+'%','%'.$q+'%']; }
            $sql .= ' ORDER BY name ASC LIMIT ' . (int)$per . ' OFFSET ' . (int)$offset;
            $st = $pdo->prepare($sql); $st->execute($params); $rows = $st->fetchAll(); foreach($rows as $r){ ?>
            <tr>
              <td><?php echo htmlspecialchars($r['name']); ?></td>
              <td><?php echo htmlspecialchars($r['address']); ?></td>
              <td><?php echo htmlspecialchars($r['phone']); ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="?page=admin_patients&edit_id=<?php echo (int)$r['id']; ?>">Ubah</a>
                <form method="post" class="d-inline ms-1">
                  <input type="hidden" name="action" value="delete_patient">
                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
                  <button class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          <?php } } ?>
          </tbody>
        </table>
      </div>
      <?php if ($pdo) {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $per = isset($_GET['per']) ? (int)$_GET['per'] : 10; if ($per <= 0) { $per = 10; } if ($per > 100) { $per = 100; }
        $pg = isset($_GET['pg']) ? (int)$_GET['pg'] : 1; if ($pg <= 0) { $pg = 1; }
        $offset = ($pg - 1) * $per;
        $cond = '';
        $params = [];
        if ($q !== '') { $cond = ' WHERE name LIKE ? OR address LIKE ? OR phone LIKE ?'; $params = ['%'.$q.'%','%'.$q+'%','%'.$q+'%']; }
        $cnt = $pdo->prepare('SELECT COUNT(*) FROM patients' . $cond);
        $cnt->execute($params);
        $total = (int)$cnt->fetchColumn();
        $from = $offset + 1;
        $to = min($offset + $per, $total);
        $prev = max($pg - 1, 1);
        $next = $pg + 1;
        $hasNext = ($to < $total);
      ?>
      <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="text-muted">Menampilkan <?php echo $from; ?>–<?php echo $to; ?> dari <?php echo $total; ?></div>
        <div class="btn-group">
          <a class="btn btn-outline-secondary" href="?page=admin_patients&pg=<?php echo $prev; ?>&per=<?php echo (int)$per; ?>&q=<?php echo urlencode($q); ?>">Sebelumnya</a>
          <?php if ($hasNext) { ?><a class="btn btn-outline-secondary" href="?page=admin_patients&pg=<?php echo $next; ?>&per=<?php echo (int)$per; ?>&q=<?php echo urlencode($q); ?>">Berikutnya</a><?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
</div>