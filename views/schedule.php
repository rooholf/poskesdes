<?php
?>
<?php
  $weekStart = isset($_GET['week']) ? $_GET['week'] : null;
  if (!$weekStart) {
    $ts = strtotime(date('Y-m-d'));
    $dow = (int)date('N', $ts);
    $weekStart = date('Y-m-d', strtotime('-'.($dow-1).' days', $ts));
  }
  $prevWeek = date('Y-m-d', strtotime('-7 days', strtotime($weekStart)));
  $nextWeek = date('Y-m-d', strtotime('+7 days', strtotime($weekStart)));
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h5">Jadwal Layanan</h1>
  <div>
    <a class="btn btn-outline-secondary btn-sm" href="/schedule?week=<?php echo $prevWeek; ?>">Minggu Sebelumnya</a>
    <a class="btn btn-outline-secondary btn-sm" href="/schedule?week=<?php echo $nextWeek; ?>">Minggu Berikutnya</a>
  </div>
</div>
<div class="week-grid mb-3">
  <?php
    $days = [];
    for ($i=0; $i<7; $i++) { $d = date('Y-m-d', strtotime('+'.$i.' days', strtotime($weekStart))); $days[] = $d; }
    foreach ($days as $d) {
      $count = 0;
      if ($pdo) { $cst = $pdo->prepare('SELECT COUNT(*) FROM schedules WHERE date = ?'); $cst->execute([$d]); $count = (int)$cst->fetchColumn(); }
      $dn = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'][(int)date('N', strtotime($d))-1];
  ?>
    <a class="week-cell" href="/schedule?date=<?php echo $d; ?>">
      <div class="date"><?php echo $dn; ?> <?php echo date('d/m', strtotime($d)); ?></div>
      <div class="count"><?php echo $count; ?> agenda</div>
    </a>
  <?php } ?>
</div>
<div class="mb-3">
  <div class="btn-group" role="group">
    <a href="/schedule" class="btn btn-outline-secondary">Semua</a>
    <a href="/schedule?filter=Imunisasi" class="btn btn-outline-secondary">Imunisasi</a>
    <a href="/schedule?filter=Ibu Hamil" class="btn btn-outline-secondary">Ibu Hamil</a>
    <a href="/schedule?filter=KB" class="btn btn-outline-secondary">KB</a>
  </div>
  <a class="btn btn-warning btn-sm ms-2" href="/schedule?month=this">Lihat Bulan Ini</a>
</div>
<div class="row g-2">
  <?php
  if ($pdo) {
    $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
    $sql = "SELECT * FROM schedules";
    $params = [];
    if ($filter) { $sql .= " WHERE service_type = ?"; $params[] = $filter; }
    if (isset($_GET['date']) && $_GET['date'] !== '') { $sql .= ($filter?" AND":" WHERE")." date = ?"; $params[] = $_GET['date']; }
    if (isset($_GET['month']) && $_GET['month'] === 'this') { $sql .= $filter ? " AND" : " WHERE"; $sql .= " MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())"; }
    $sql .= " ORDER BY date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) { ?>
      <div class="col-12">
        <div class="card">
          <div class="card-body d-flex justify-content-between">
            <div>
              <div class="fw-bold"><?php echo htmlspecialchars($r['service_type']); ?></div>
              <div class="text-muted"><?php echo htmlspecialchars($r['notes']); ?></div>
            </div>
            <div><?php echo htmlspecialchars($r['date']); ?> <?php echo htmlspecialchars($r['time']); ?></div>
          </div>
        </div>
      </div>
    <?php }
  } else { ?>
    <div class="col-12"><div class="alert alert-warning">Konfigurasi database diperlukan</div></div>
  <?php } ?>
</div>