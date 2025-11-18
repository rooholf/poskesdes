<?php
?><div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="group">Boards</div>
    <a href="/admin">Dashboard</a>
    <a href="/admin/patients">Pasien</a>
    <a href="/admin/visits">Kunjungan</a>
    <a href="/admin/schedules" class="active">Jadwal & Pengingat</a>
    <a href="/admin/articles">Artikel</a>
    <a href="/admin/reports">Laporan</a>
  </aside>
  <section class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Jadwal & Pengingat</div><a class="btn btn-outline-secondary btn-sm" href="/admin">Kembali</a></div>
    <div class="admin-card mb-2">
      <div class="btn-group" role="group">
        <a href="?page=admin_schedules&tab=agenda" class="btn btn-outline-secondary<?php echo (($_GET['tab']??'agenda')==='agenda'?' active':''); ?>">Agenda</a>
        <a href="?page=admin_schedules&tab=riwayat" class="btn btn-outline-secondary<?php echo (($_GET['tab']??'agenda')==='riwayat'?' active':''); ?>">Riwayat</a>
      </div>
      <form class="row g-2 mt-2" method="get">
        <input type="hidden" name="page" value="admin_schedules">
        <div class="col-6"><input name="start" class="form-control" placeholder="Tanggal mulai"></div>
        <div class="col-6"><input name="end" class="form-control" placeholder="Tanggal akhir"></div>
        <div class="col-12"><button class="btn btn-primary">Filter</button></div>
      </form>
    </div>
    <div class="admin-card mb-2">
      <form id="f-add" method="post" class="row g-2">
        <input type="hidden" name="action" value="add_schedule">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
        <div class="col-12 col-md-3"><input name="date" class="form-control" placeholder="Tanggal"></div>
        <div class="col-12 col-md-3"><input name="service_type" class="form-control" placeholder="Jenis"></div>
        <div class="col-12 col-md-2"><input name="time" class="form-control" placeholder="Waktu"></div>
        <div class="col-12 col-md-3"><input name="notes" class="form-control" placeholder="Catatan"></div>
        <div class="col-12 col-md-1"><button class="btn btn-primary w-100">Tambah</button></div>
      </form>
    </div>
    <div class="admin-card">
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>Tanggal</th><th>Jenis</th><th>Waktu</th><th>Status</th><th>Catatan</th><th></th></tr></thead>
          <tbody>
          <?php if ($pdo) { $tab=isset($_GET['tab'])?$_GET['tab']:'agenda'; $base='SELECT id,date,service_type,time,notes,status FROM schedules'; if($tab==='agenda'){ $base.=' WHERE date>=CURDATE()'; } else { $base.=' WHERE date<CURDATE()'; } $base.=' ORDER BY date ASC'; $rows=$pdo->query($base)->fetchAll(); $today=date('Y-m-d'); foreach($rows as $r){ $d=$r['date']; $n=strtolower((string)($r['notes']??'')); $st=strtolower((string)($r['status']??'')); $status=''; $cls=''; if($st==='arrived'){ $status='Sudah datang'; $cls='status-teal'; } elseif($st==='today'){ $status='Hari ini'; $cls='status-yellow'; } elseif($st==='late'){ $status='Telat kontrol'; $cls='status-orange'; } elseif($st==='cancelled'){ $status='Batal/Ditunda'; $cls='status-gray'; } else { if(strpos($n,'batal')!==false||strpos($n,'tunda')!==false){ $status='Batal/Ditunda'; $cls='status-gray'; } elseif($d===$today){ $status='Hari ini'; $cls='status-yellow'; } elseif($d<$today){ $status='Telat kontrol'; $cls='status-orange'; } else { $status='Agenda'; $cls='status-teal'; } } ?>
            <tr>
              <td><?php echo htmlspecialchars($r['date']); ?></td>
              <td><?php echo htmlspecialchars($r['service_type'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($r['time'] ?? ''); ?></td>
              <td><span class="badge-status <?php echo $cls; ?>"><?php echo $status; ?></span></td>
              <td><?php echo htmlspecialchars($r['notes'] ?? ''); ?></td>
              <td>
                <form method="post" class="d-inline">
                  <input type="hidden" name="action" value="update_schedule_status">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                  <select name="status" class="form-select form-select-sm" style="width:auto;display:inline-block">
                    <option value="">Pilih status</option>
                    <option value="arrived"<?php echo ($st==='arrived'?' selected':''); ?>>Sudah datang</option>
                    <option value="today"<?php echo ($st==='today'?' selected':''); ?>>Hari ini</option>
                    <option value="late"<?php echo ($st==='late'?' selected':''); ?>>Telat kontrol</option>
                    <option value="cancelled"<?php echo ($st==='cancelled'?' selected':''); ?>>Batal/Ditunda</option>
                  </select>
                  <button class="btn btn-outline-secondary btn-sm">Simpan</button>
                </form>
              </td>
            </tr>
          <?php } } ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>