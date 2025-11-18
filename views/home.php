<?php
?><div class="row g-3">
  <div class="col-12">
    <div class="p-4 bg-white rounded shadow-sm d-flex flex-column flex-md-row align-items-center justify-content-between">
      <div>
        <h1 class="h4 mb-1 brand">Poskesehatan Desa Pagar Ruyung</h1>
        <div>Jam layanan: Senin–Jumat 08.00–14.00</div>
        <div class="mt-2">
          <a href="?page=schedule" class="btn btn-primary">Lihat Jadwal Hari Ini</a>
        </div>
      </div>
      <div class="text-center mt-3 mt-md-0">
        <span class="badge text-bg-info">Ibu Hamil</span>
        <span class="badge text-bg-success">Imunisasi</span>
        <span class="badge text-bg-warning">KB</span>
      </div>
    </div>
  </div>
  <div class="col-12">
    <h2 class="h6">Agenda Terdekat</h2>
    <div class="row g-2">
      <?php if (isset($pdo) && $pdo) { $stmt = $pdo->prepare("SELECT * FROM schedules WHERE date >= CURDATE() ORDER BY date ASC LIMIT 3"); $stmt->execute(); $rows = $stmt->fetchAll(); foreach ($rows as $r) { ?>
        <div class="col-12 col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="fw-bold"><?php echo htmlspecialchars($r["service_type"]); ?></div>
              <div><?php echo htmlspecialchars($r["date"]); ?> <?php echo htmlspecialchars($r["time"]); ?></div>
              <div class="text-muted"><?php echo htmlspecialchars($r["notes"]); ?></div>
            </div>
          </div>
        </div>
      <?php } } else { ?>
        <div class="col-12"><div class="alert alert-warning">Jadwal memerlukan konfigurasi database</div></div>
      <?php } ?>
    </div>
  </div>
</div>