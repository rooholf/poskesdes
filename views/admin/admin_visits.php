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
        <div id="fields-anc" class="row g-2">
          <div class="col-12"><div class="text-muted" style="font-size:12px">ANC — Form Kunjungan</div></div>
          <div class="col-6"><input name="anc_kunjungan_ke" type="number" min="1" class="form-control" placeholder="ANC Kunjungan ke-" /></div>
          <div class="col-6">
            <select name="anc_k_status" class="form-select">
              <option value="">Status Kunjungan</option>
              <option value="K1">K1</option>
              <option value="K4">K4</option>
              <option value="K5">K5</option>
              <option value="K6">K6</option>
              <option value="K-Lanjut">K Lanjut</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div class="col-6">
            <select name="anc_usg_status" class="form-select">
              <option value="">USG oleh Dokter</option>
              <option value="Ya">Ya</option>
              <option value="Tidak">Tidak</option>
            </select>
          </div>
          <div class="col-6">
            <select name="anc_4t_status" class="form-select">
              <option value="">Status 4T</option>
              <option value="Tidak">Tidak</option>
              <option value="Ya">Ya</option>
            </select>
          </div>
          <div class="col-4"><input name="gravida" type="number" min="0" class="form-control" placeholder="Gravida" /></div>
          <div class="col-4"><input name="para" type="number" min="0" class="form-control" placeholder="Para" /></div>
          <div class="col-4"><input name="abortus" type="number" min="0" class="form-control" placeholder="Abortus" /></div>
          <div class="col-6"><input name="hpht" type="date" class="form-control" placeholder="HPHT" /></div>
          <div class="col-6"><input name="hpl" type="date" class="form-control" placeholder="HPL" /></div>
          <div class="col-12"><textarea name="keluhan_utama" rows="2" class="form-control" placeholder="Keluhan utama"></textarea></div>
          <div class="col-4"><input name="td" class="form-control" placeholder="Tekanan darah (mmHg)" /></div>
          <div class="col-4"><input name="nadi" type="number" min="0" class="form-control" placeholder="Nadi (/menit)" /></div>
          <div class="col-4"><input name="suhu" type="number" step="0.1" class="form-control" placeholder="Suhu (°C)" /></div>
          <div class="col-4"><input name="bb" type="number" step="0.1" class="form-control" placeholder="Berat badan (kg)" /></div>
          <div class="col-4"><input name="tb" type="number" class="form-control" placeholder="Tinggi badan (cm)" /></div>
          <div class="col-4">
            <select name="edema" class="form-select">
              <option value="">Edema</option>
              <option value="tidak">Tidak</option>
              <option value="ringan">Ringan</option>
              <option value="berat">Berat</option>
            </select>
          </div>
          <div class="col-4"><input name="djj" type="number" min="0" class="form-control" placeholder="DJJ (/menit)" /></div>
          <div class="col-4"><input name="tfu" type="number" step="0.1" class="form-control" placeholder="TFU (cm)" /></div>
          <div class="col-4">
            <select name="posisi_janin" class="form-select">
              <option value="">Posisi janin</option>
              <option value="kepala">Kepala</option>
              <option value="sungsang">Sungsang</option>
              <option value="lintang">Lintang</option>
              <option value="belum_jelas">Belum jelas</option>
            </select>
          </div>
          <div class="col-4">
            <select name="gerak_janin" class="form-select">
              <option value="">Gerak janin</option>
              <option value="aktif">Aktif</option>
              <option value="berkurang">Berkurang</option>
              <option value="tidak_terasa">Tidak terasa</option>
            </select>
          </div>
          <div class="col-4"><input name="fe_diberikan" type="number" min="0" class="form-control" placeholder="Tablet Fe diberikan" /></div>
          <div class="col-12"><textarea name="saran_gizi" rows="2" class="form-control" placeholder="Saran gizi"></textarea></div>
          <div class="col-4"><input name="hb" type="number" step="0.1" class="form-control" placeholder="Hb (g/dL)" /></div>
          <div class="col-4"><input name="urin" class="form-control" placeholder="Urin" /></div>
          <div class="col-12"><textarea name="penunjang_lain" rows="2" class="form-control" placeholder="Pemeriksaan penunjang lain"></textarea></div>
          <div class="col-12"><textarea name="faktor_risiko" rows="2" class="form-control" placeholder="Faktor risiko"></textarea></div>
          <div class="col-4">
            <select name="klasifikasi_risiko" class="form-select">
              <option value="">Klasifikasi risiko</option>
              <option value="rendah">Risiko rendah</option>
              <option value="tinggi">Risiko tinggi</option>
              <option value="gawat_darurat">Gawat darurat</option>
            </select>
          </div>
          <div class="col-4">
            <select name="perlu_rujukan" class="form-select">
              <option value="">Perlu rujukan</option>
              <option value="tidak">Tidak</option>
              <option value="terencana">Rujukan terencana</option>
              <option value="segera">Rujukan segera</option>
            </select>
          </div>
          <div class="col-4"><input name="tujuan_rujukan" class="form-control" placeholder="Tujuan rujukan" /></div>
          <div class="col-12"><textarea name="alasan_rujukan" rows="2" class="form-control" placeholder="Alasan rujukan"></textarea></div>
          <div class="col-12"><textarea name="tatalaksana_awal" rows="2" class="form-control" placeholder="Tatalaksana awal"></textarea></div>
          <div class="col-12"><textarea name="ringkasan_kunjungan" rows="3" class="form-control" placeholder="Ringkasan kunjungan"></textarea></div>
        </div>
        <div id="fields-kb" class="row g-2" style="display:none">
          <div class="col-12"><div class="text-muted" style="font-size:12px">KB — Form Pelayanan</div></div>
          <div class="col-6">
            <select name="kb_status_peserta" class="form-select">
              <option value="">Status Peserta</option>
              <option value="Baru">Baru</option>
              <option value="Lama">Lama (Ganti/lanjut)</option>
              <option value="DropOut">Drop Out</option>
              <option value="Gagal">Gagal/Hamil</option>
              <option value="Komplikasi">Komplikasi</option>
            </select>
          </div>
          <div class="col-6">
            <select name="metode_kb" class="form-select">
              <option value="">Metode KB</option>
              <option value="Pil">Pil</option>
              <option value="Suntik 1 Bln">Suntik 1 Bulan</option>
              <option value="Suntik 3 Bln">Suntik 3 Bulan</option>
              <option value="Implan">Implan</option>
              <option value="IUD">IUD</option>
              <option value="MOW">MOW</option>
              <option value="MOP">MOP</option>
              <option value="Kondom">Kondom</option>
              <option value="MAL">MAL</option>
              <option value="Lain">Lainnya</option>
            </select>
          </div>
          <div class="col-6"><input name="tgl_mulai" type="date" class="form-control" placeholder="Tanggal mulai/pemasangan" /></div>
          <div class="col-6">
            <select name="rencana_tindakan" class="form-select">
              <option value="">Rencana tindakan</option>
              <option value="Kontrol Rutin">Kontrol Rutin</option>
              <option value="Ganti Metode">Ganti/Lepas Metode</option>
              <option value="Suntik Ulang">Suntik Ulang</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div class="col-12"><textarea name="keterangan" rows="2" class="form-control" placeholder="Keterangan"></textarea></div>
        </div>
        <div id="fields-lansia" class="row g-2" style="display:none">
          <div class="col-12"><div class="text-muted" style="font-size:12px">Lansia — Form Pemeriksaan</div></div>
          <div class="col-12"><textarea name="keluhan_lansia" rows="2" class="form-control" placeholder="Keluhan utama"></textarea></div>
          <div class="col-12"><input name="diagnosa_lansia" class="form-control" placeholder="Diagnosa (ringkas)" /></div>
          <div class="col-4"><input name="bb_lansia" type="number" step="0.1" class="form-control" placeholder="Berat badan (kg)" /></div>
          <div class="col-4"><input name="tb_lansia" type="number" class="form-control" placeholder="Tinggi badan (cm)" /></div>
          <div class="col-4"><input name="td_lansia" class="form-control" placeholder="Tekanan darah (mmHg)" /></div>
          <div class="col-4"><input name="gds" type="number" class="form-control" placeholder="GDS (mg/dL)" /></div>
          <div class="col-4"><input name="asam_urat" type="number" step="0.1" class="form-control" placeholder="Asam urat (mg/dL)" /></div>
          <div class="col-4"><input name="kolesterol" type="number" class="form-control" placeholder="Kolesterol (mg/dL)" /></div>
          <div class="col-12"><textarea name="tindakan_lansia" rows="2" class="form-control" placeholder="Tindakan/Pengobatan"></textarea></div>
        </div>
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
    const typeRadios = Array.from(document.querySelectorAll('input[name="tipe"]'));
    typeRadios.forEach(r => r.addEventListener('change', ()=>{
      const t = document.querySelector('input[name="tipe"]:checked')?.value;
      document.getElementById('fields-anc').style.display = (t==='anc')?'flex':'none';
      document.getElementById('fields-kb').style.display = (t==='kb')?'flex':'none';
      document.getElementById('fields-lansia').style.display = (t==='lansia')?'flex':'none';
    }));
    (function(){ const t = document.querySelector('input[name="tipe"]:checked')?.value; document.getElementById('fields-anc').style.display = (t==='anc')?'flex':'none'; document.getElementById('fields-kb').style.display = (t==='kb')?'flex':'none'; document.getElementById('fields-lansia').style.display = (t==='lansia')?'flex':'none'; })();
    document.getElementById('visitForm')?.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const fd=new FormData(e.target);
      const tipe=fd.get('tipe');
      const action = tipe==='anc'?'anc_add':(tipe==='kb'?'kb_add':'lansia_add');
      const url = '/api.php?action='+action;
      const payload=new URLSearchParams();
      ['patient_id','patient_name','tanggal','jadwal_kontrol'].forEach(k=>{ const v=fd.get(k); if(v!==null) payload.append(k,String(v)); });
      if (tipe==='anc') {
        ['anc_kunjungan_ke','anc_k_status','anc_usg_status','anc_4t_status','gravida','para','abortus','hpht','hpl','keluhan_utama','td','nadi','suhu','bb','tb','edema','djj','tfu','posisi_janin','gerak_janin','fe_diberikan','saran_gizi','hb','urin','penunjang_lain','faktor_risiko','klasifikasi_risiko','perlu_rujukan','tujuan_rujukan','alasan_rujukan','tatalaksana_awal','ringkasan_kunjungan'].forEach(k=>{ const v=fd.get(k); if(v!==null) payload.append(k,String(v)); });
      } else if (tipe==='kb') {
        ['kb_status_peserta','metode_kb','tgl_mulai','keterangan','rencana_tindakan'].forEach(k=>{ const v=fd.get(k); if(v!==null) payload.append(k,String(v)); });
      } else if (tipe==='lansia') {
        ['keluhan_lansia','diagnosa_lansia','bb_lansia','tb_lansia','td_lansia','gds','asam_urat','kolesterol','tindakan_lansia'].forEach(k=>{ const v=fd.get(k); if(v!==null) payload.append(k,String(v)); });
      }
      const res=await fetch(url,{ method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:payload.toString() });
      const out=await res.text();
      document.getElementById('visitInfo').textContent = out;
    });
    </script>
  </section>
</div>
