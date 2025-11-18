<?php
?><div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="group">Boards</div>
    <a href="/admin">Dashboard</a>
    <a href="/admin/patients">Pasien</a>
    <a href="/admin/visits" class="active">Kunjungan</a>
    <a href="/admin/schedules">Jadwal & Pengingat</a>
    <a href="/admin/articles">Artikel</a>
    <a href="/admin/reports">Laporan</a>
  </aside>
  <section class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Kunjungan Pasien</div><a class="btn btn-outline-secondary btn-sm" href="/admin">Kembali</a></div>
    <div class="admin-card mb-2">
      <form id="visitForm" class="row g-2">
        <div class="col-12 col-md-4">
          <select name="patient_id" class="form-select">
            <option value="">Pilih Pasien</option>
            <?php if ($pdo) { $rows=$pdo->query("SELECT id,name FROM patients ORDER BY name ASC")->fetchAll(); foreach($rows as $r){ ?>
              <option value="<?php echo (int)$r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="col-12 col-md-4"><input name="patient_name" class="form-control" placeholder="Nama pasien (opsional)"></div>
        <div class="col-12 col-md-4">
          <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="tipe" id="t-anc" value="anc" checked>
            <label class="btn btn-outline-secondary" for="t-anc">ANC</label>
            <input type="radio" class="btn-check" name="tipe" id="t-kb" value="kb">
            <label class="btn btn-outline-secondary" for="t-kb">KB</label>
            <input type="radio" class="btn-check" name="tipe" id="t-lansia" value="lansia">
            <label class="btn btn-outline-secondary" for="t-lansia">Lansia</label>
          </div>
        </div>
        <div class="col-12 col-md-3"><input name="tanggal" class="form-control" placeholder="Tanggal kunjungan"></div>
        <div class="col-12 col-md-3"><input name="jadwal_kontrol" class="form-control" placeholder="Jadwal kontrol"></div>
        <div class="col-12"><textarea name="data_json" class="form-control" rows="4" placeholder='Data JSON {"berat":...}'></textarea></div>
        <div class="col-12 col-md-2"><button class="btn btn-primary w-100" type="submit">Simpan Kunjungan</button></div>
      </form>
      <div id="visitInfo" class="mt-2 text-muted"></div>
    </div>
    <div class="admin-card">
      <div class="fw-bold mb-2">Riwayat Terakhir</div>
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>Tanggal</th><th>Jenis</th><th>Pasien</th></tr></thead>
          <tbody>
            <?php if ($pdo) { $rows=$pdo->query("SELECT tanggal,'ANC' AS jenis,patient_name FROM anc_records ORDER BY id DESC LIMIT 5")->fetchAll(); foreach($rows as $r){ ?>
              <tr><td><?php echo htmlspecialchars($r['tanggal']); ?></td><td><?php echo htmlspecialchars($r['jenis']); ?></td><td><?php echo htmlspecialchars($r['patient_name']); ?></td></tr>
            <?php } $rows=$pdo->query("SELECT tanggal,'KB' AS jenis,patient_name FROM kb_records ORDER BY id DESC LIMIT 5")->fetchAll(); foreach($rows as $r){ ?>
              <tr><td><?php echo htmlspecialchars($r['tanggal']); ?></td><td><?php echo htmlspecialchars($r['jenis']); ?></td><td><?php echo htmlspecialchars($r['patient_name']); ?></td></tr>
            <?php } $rows=$pdo->query("SELECT tanggal,'Lansia' AS jenis,patient_name FROM lansia_records ORDER BY id DESC LIMIT 5")->fetchAll(); foreach($rows as $r){ ?>
              <tr><td><?php echo htmlspecialchars($r['tanggal']); ?></td><td><?php echo htmlspecialchars($r['jenis']); ?></td><td><?php echo htmlspecialchars($r['patient_name']); ?></td></tr>
            <?php } } ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
    document.getElementById('visitForm')?.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const fd=new FormData(e.target);
      const tipe=fd.get('tipe');
      const action = tipe==='anc'?'anc_add':(tipe==='kb'?'kb_add':'lansia_add');
      const url = 'api.php?action='+action;
      const payload=new URLSearchParams();
      ['patient_id','patient_name','tanggal','jadwal_kontrol','data_json'].forEach(k=>{ const v=fd.get(k); if(v!==null) payload.append(k,String(v)); });
      const res=await fetch(url,{ method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:payload.toString() });
      const out=await res.text();
      document.getElementById('visitInfo').textContent = out;
    });
    </script>
  </section>
</div>
