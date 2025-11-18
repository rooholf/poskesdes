<?php
?><div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="group">Boards</div>
    <a href="/admin">Dashboard</a>
    <a href="/admin/patients">Pasien</a>
    <a href="/admin/visits">Kunjungan</a>
    <a href="/admin/schedules">Jadwal & Pengingat</a>
    <a href="/admin/articles">Artikel</a>
    <a href="/admin/reports" class="active">Laporan</a>
  </aside>
  <section class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Laporan</div><a class="btn btn-outline-secondary btn-sm" href="/admin">Kembali</a></div>
    <div class="admin-card mb-2">
      <form class="row g-2" method="get">
        <input type="hidden" name="page" value="admin_reports">
        <div class="col-6"><input name="start" class="form-control" value="<?php echo htmlspecialchars($_GET['start'] ?? ''); ?>" placeholder="Tanggal mulai"></div>
        <div class="col-6"><input name="end" class="form-control" value="<?php echo htmlspecialchars($_GET['end'] ?? ''); ?>" placeholder="Tanggal akhir"></div>
        <div class="col-12"><button class="btn btn-primary">Filter</button> <a class="btn btn-warning ms-2" href="?page=admin_reports&start=<?php echo urlencode($_GET['start'] ?? ''); ?>&end=<?php echo urlencode($_GET['end'] ?? ''); ?>&print=1">Cetak PDF</a></div>
      </form>
    </div>
    <?php $start = $_GET['start'] ?? ''; $end = $_GET['end'] ?? ''; $clauses=[]; $params=[]; if($start!==''){ $clauses[]='date >= ?'; $params[]=$start; } if($end!==''){ $clauses[]='date <= ?'; $params[]=$end; } $where = !empty($clauses) ? (' WHERE '.implode(' AND ',$clauses)) : ''; ?>
    <?php if (isset($_GET['print']) && $_GET['print'] == '1') { ?>
      <div class="admin-card">
        <div class="fw-bold">Laporan Jadwal</div>
        <div class="text-muted">Periode: <?php echo htmlspecialchars($start ?: '-'); ?> s/d <?php echo htmlspecialchars($end ?: '-'); ?></div>
        <div class="table-responsive mt-2">
        <table class="table">
          <thead><tr><th>Tanggal</th><th>Jenis</th><th>Waktu</th><th>Catatan</th></tr></thead>
          <tbody>
            <?php if ($pdo) { $rows=$pdo->prepare("SELECT date,service_type,time,notes FROM schedules".$where." ORDER BY date ASC"); $rows->execute($params); foreach($rows->fetchAll() as $r){ ?>
            <tr><td><?php echo htmlspecialchars($r['date'] ?? ''); ?></td><td><?php echo htmlspecialchars($r['service_type'] ?? ''); ?></td><td><?php echo htmlspecialchars($r['time'] ?? ''); ?></td><td><?php echo htmlspecialchars($r['notes'] ?? ''); ?></td></tr>
            <?php } } ?>
          </tbody>
        </table>
        </div>
      </div>
      <script>window.print()</script>
    <?php } ?>
    <div class="admin-card">
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>Tanggal</th><th>Jenis</th><th>Waktu</th><th>Catatan</th></tr></thead>
          <tbody>
            <?php if ($pdo) { $stmt=$pdo->prepare("SELECT date,service_type,time,notes FROM schedules".$where." ORDER BY date DESC"); $stmt->execute($params); $rows=$stmt->fetchAll(); foreach($rows as $r){ ?>
            <tr><td><?php echo htmlspecialchars($r['date'] ?? ''); ?></td><td><?php echo htmlspecialchars($r['service_type'] ?? ''); ?></td><td><?php echo htmlspecialchars($r['time'] ?? ''); ?></td><td><?php echo htmlspecialchars($r['notes'] ?? ''); ?></td></tr>
            <?php } } ?>
        </tbody>
        </table>
      </div>
    </div>
    <div class="row g-2 mt-2">
      <div class="col-12 col-md-4"><div class="admin-card"><div class="text-muted" style="font-size:12px">Total Kunjungan ANC</div><div style="font-weight:700;font-size:20px"><?php if($pdo){ $c=$pdo->query("SELECT COUNT(*) FROM anc_records")->fetchColumn(); echo (int)$c; } ?></div></div></div>
      <div class="col-12 col-md-4"><div class="admin-card"><div class="text-muted" style="font-size:12px">Total Kunjungan KB</div><div style="font-weight:700;font-size:20px"><?php if($pdo){ $c=$pdo->query("SELECT COUNT(*) FROM kb_records")->fetchColumn(); echo (int)$c; } ?></div></div></div>
      <div class="col-12 col-md-4"><div class="admin-card"><div class="text-muted" style="font-size:12px">Total Kunjungan Lansia</div><div style="font-weight:700;font-size:20px"><?php if($pdo){ $c=$pdo->query("SELECT COUNT(*) FROM lansia_records")->fetchColumn(); echo (int)$c; } ?></div></div></div>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>