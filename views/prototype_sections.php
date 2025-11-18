<section id="home" class="page active" data-page>
  <div class="hero-landing">
    <div class="mark">PK</div>
    <div>
      <div class="title">Pusat Informasi Poskesdes Pagar Ruyung</div>
      <div class="subtitle">Layanan kesehatan desa, jadwal, edukasi, dan pencatatan kunjungan</div>
      <div class="chip-links">
        <a data-link="jadwal">Jadwal Layanan</a>
        <a data-link="edukasi">Materi Edukasi</a>
        <?php $logged = \App\Services\AuthService::isLoggedIn(); if ($logged) { ?>
          <a data-link="pemeriksaan">Pemeriksaan</a>
          <a data-link="ekspor">Ekspor Laporan</a>
          <a data-link="register">Daftar Pasien</a>
        <?php } ?>
      </div>
    </div>
  </div>
  <div style="height:14px"></div>
  <div class="stats-bar">
    <div class="stat-card"><div class="label">Jadwal Aktif</div><div class="value" id="home-total-jadwal">0</div></div>
    <div class="stat-card"><div class="label">Total Pasien</div><div class="value" id="home-total-pendaftar">0</div></div>
    <div class="stat-card"><div class="label">Rekam Kunjungan</div><div class="value" id="home-total-kunjungan">0</div></div>
  </div>
  <div style="height:14px"></div>
  <div class="info-grid">
    <div class="info-item"><div style="font-weight:700">Jam Layanan</div><div class="muted">Senin–Jumat, 08.00–14.00 WIB</div></div>
    <div class="info-item"><div style="font-weight:700">Alamat</div><div class="muted">Poskesdes Pagar Ruyung, dekat Balai Desa</div></div>
    <div class="info-item"><div style="font-weight:700">Kontak</div><div class="muted">WhatsApp: 0812-XXXX-XXXX</div></div>
  </div>
  <div style="height:14px"></div>
  <div class="card agenda">
    <div style="display:flex;justify-content:space-between;align-items:center"><strong>Agenda Terdekat</strong><button class="small-btn" data-link="jadwal">Lihat Semua</button></div>
    <div style="height:10px"></div>
    <div id="home-agenda"></div>
  </div>
</section>

<?php /* Remaining sections copied from prototype for SPA */ ?>
<section id="register" class="page" data-page>
  <div class="card layanan-detail">
    <h2>Pendaftaran Pasien Baru</h2>
    <p class="muted">Isi form berikut untuk mendaftarkan pasien baru (Ibu Hamil/Akseptor KB/Lansia). Data ini akan muncul di dropdown pemeriksaan.</p>
    <form id="form-register-pasien">
      <fieldset class="card anc-block">
        <legend>Data Dasar Pasien</legend>
        <label>Nama Lengkap Pasien:<input type="text" name="nama" id="reg_nama" required placeholder="Contoh: Ibu Siti Aisyah" /></label>
        <div class="form-row">
          <div class="col"><label>NIK Pasien:<input type="text" name="nik" id="reg_nik" placeholder="Contoh: 17xxxxxxxxxxxxxx" /></label></div>
          <div class="col"><label>No Kartu Keluarga (KK):<input type="text" name="no_kk" id="reg_no_kk" placeholder="Contoh: 17xxxxxxxxxxxxxx" /></label></div>
        </div>
        <label>Nomor HP (WhatsApp):<input type="text" name="no_hp" id="reg_no_hp" required placeholder="Contoh: 08123456789" /><p class="muted" style="font-size:12px; margin-top:4px">Nomor ini akan digunakan untuk pengingat jadwal.</p></label>
        <div class="form-row">
          <div class="col"><label>Pendidikan Terakhir:<select name="pendidikan" id="reg_pendidikan"><option value="">Pilih</option><option value="SD">SD</option><option value="SLTP">SLTP</option><option value="SLTA">SLTA</option><option value="Perguruan Tinggi">Perguruan Tinggi</option></select></label></div>
          <div class="col"><label>No KIS/BPJS:<input type="text" name="no_kis" id="reg_no_kis" placeholder="Opsional" /></label></div>
        </div>
        <label>Alamat Ringkas:<input type="text" name="alamat" id="reg_alamat" placeholder="Contoh: Dusun I, RT 01" /></label>
        <label>Usia Kehamilan (Minggu, isi '0' jika bukan ibu hamil):<input type="number" name="usia_kehamilan" id="reg_usia_kehamilan" min="0" required value="0" /></label>
      </fieldset>
      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button type="button" class="small-btn" data-link="home">Batal</button><button type="submit" class="btn-primary">Daftar Pasien</button></div>
    </form>
  </div>
</section>

<section id="pemeriksaan" class="page" data-page>
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center"><div><h2 style="margin:0">Pemeriksaan Ibu Hamil (ANC)</h2><div class="muted">Lihat isi pemeriksaan, daftar, dan rekam kunjungan</div></div><div style="display:flex;gap:8px"><button class="btn" data-link="anc-detail">Mulai Pemeriksaan ANC</button></div></div>
    <div style="height:12px"></div>
    <div style="display:grid;grid-template-columns:1fr;gap:10px">
      <div style="background:var(--teal-50);padding:12px;border-radius:10px"><strong>Isi Pemeriksaan</strong><ul class="muted"><li>Riwayat kehamilan & keluhan utama</li><li>Pemeriksaan fisik: tekanan darah, nadi, suhu</li><li>Pemeriksaan janin: DJJ, TFU, posisi janin</li><li>Pemberian tablet Fe & konseling gizi</li><li>Pemeriksaan penunjang sederhana (Hb, urin) bila perlu</li><li>Skrining risiko kehamilan dan rujukan</li></ul></div>
      <div class="card"><h3 style="margin:0 0 8px 0">Rekam Kunjungan (Ringkas)</h3><div id="rekam-list" class="muted">Belum ada kunjungan terdaftar.</div></div>
    </div>
  </div>
</section>

<section id="anc-detail" class="page" data-page>
  <div class="card layanan-detail">
    <h2>Pemeriksaan Ibu Hamil (ANC) — Form Lengkap</h2>
    <p class="muted">Isi form berikut untuk merekam pemeriksaan ANC. Data akan disimpan ke perangkat (localStorage).</p>

    <div class="card anc-block">
      <label>
        Pilih Pasien:
        <select id="select-pasien-anc" data-info-target="info-pasien-anc">
          <option value="">-- Pilih Pasien Terdaftar --</option>
        </select>
      </label>
      <p id="info-pasien-anc" style="margin-top:8px; font-weight:bold; color:#444;"></p>
    </div>

    <form id="form-anc">
      <fieldset class="card anc-block">
        <legend>Data Kunjungan (Untuk Laporan)</legend>
        <div class="form-row">
            <div class="col">
                <label>
                  ANC Kunjungan Ke-:
                  <input type="number" name="anc_kunjungan_ke" min="1" id="anc_kunjungan_ke" placeholder="Contoh: 1, 2, 3, ..." required />
                </label>
            </div>
            <div class="col">
                <label>
                  Status Kunjungan:
                  <select name="anc_k_status" id="anc_k_status" required>
                    <option value="">Pilih</option>
                    <option value="K1">K1 (Kunjungan Pertama)</option>
                    <option value="K-Lanjut">K Lanjut (K4/K5/K6)</option>
                    <option value="K4">K4</option>
                    <option value="K5">K5</option>
                    <option value="K6">K6</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </label>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label>
                  USG oleh Dokter:
                  <select name="anc_usg_status" id="anc_usg_status">
                    <option value="Tidak">Tidak</option>
                    <option value="Ya">Ya</option>
                  </select>
                </label>
            </div>
            <div class="col">
                <label>
                  Status 4T (Risiko):
                  <select name="anc_4t_status" id="anc_4t_status">
                    <option value="Tidak">Tidak 4T</option>
                    <option value="Ya">Ya (Terlalu Muda/Tua/Rapat/Banyak)</option>
                  </select>
                </label>
            </div>
        </div>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Riwayat Kehamilan & Keluhan Utama</legend>
        <label>Gravida (G):<input type="number" name="gravida" min="0" id="anc_gravida" /></label>
        <label>Para (P):<input type="number" name="para" min="0" id="anc_para" /></label>
        <label>Abortus (A):<input type="number" name="abortus" min="0" id="anc_abortus" /></label>
        <label>HPHT:<input type="date" name="hpht" id="anc_hpht" /></label>
        <label>HPL:<input type="date" name="hpl" id="anc_hpl" /></label>
        <label>Keluhan utama:<textarea name="keluhan_utama" rows="2" id="anc_keluhan_utama" placeholder="Contoh: pusing, mual hebat, bengkak kaki, perdarahan, dsb."></textarea></label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Pemeriksaan Fisik</legend>
        <label>Tekanan darah (mmHg):<input type="text" name="td" placeholder="120/80" id="anc_td" /></label>
        <label>Nadi (x/menit):<input type="number" name="nadi" min="0" id="anc_nadi" /></label>
        <label>Suhu (°C):<input type="number" step="0.1" name="suhu" min="30" max="45" id="anc_suhu" /></label>
        <label>Berat badan (kg):<input type="number" step="0.1" name="bb" min="0" id="anc_bb" /></label>
        <label>Tinggi badan (cm):<input type="number" name="tb" min="0" id="anc_tb" /></label>
        <label>Edema:
          <select name="edema" id="anc_edema">
            <option value="">Pilih</option>
            <option value="tidak">Tidak</option>
            <option value="ringan">Ringan</option>
            <option value="berat">Berat</option>
          </select>
        </label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Pemeriksaan Janin</legend>
        <label>DJJ (x/menit):<input type="number" name="djj" min="0" id="anc_djj" /></label>
        <label>TFU (cm):<input type="number" step="0.1" name="tfu" min="0" id="anc_tfu" /></label>
        <label>Posisi janin:
          <select name="posisi_janin" id="anc_posisi">
            <option value="">Pilih</option>
            <option value="kepala">Kepala di bawah (letak kepala)</option>
            <option value="sungsang">Sungsang</option>
            <option value="lintang">Lintang</option>
            <option value="belum_jelas">Belum jelas</option>
          </select>
        </label>
        <label>Gerak janin:
          <select name="gerak_janin" id="anc_gerak">
            <option value="">Pilih</option>
            <option value="aktif">Aktif</option>
            <option value="berkurang">Berkurang</option>
            <option value="tidak_terasa">Tidak terasa</option>
          </select>
        </label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Tablet Fe & Konseling Gizi</legend>
        <label>Jumlah tablet Fe diberikan:<input type="number" name="fe_diberikan" min="0" id="anc_fe_diberikan" placeholder="Contoh: 30" /></label>
        <label>Saran Gizi:<textarea name="saran_gizi" rows="2" id="anc_saran_gizi" placeholder="Contoh: Perbanyak makanan kaya zat besi, hindari kopi/teh setelah makan, dsb."></textarea></label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Pemeriksaan Penunjang Sederhana</legend>
        <div class="form-row">
          <div class="col">
            <label>Hasil Hb (g/dL):<input type="number" step="0.1" name="hb" id="anc_hb" placeholder="Contoh: 11.5" /></label>
          </div>
          <div class="col">
            <label>Hasil Urin:<input type="text" name="urin" id="anc_urin" placeholder="Contoh: Negatif/Proteinuria" /></label>
          </div>
        </div>
        <label>Keterangan Pemeriksaan Penunjang Lain:<textarea name="penunjang_lain" rows="2" id="anc_penunjang_lain" placeholder="Contoh: Pemeriksaan gula darah sewaktu (GDS) 120 mg/dL"></textarea></label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Skrining Risiko & Rujukan</legend>
        <label>Faktor risiko ditemukan:<textarea name="faktor_risiko" rows="2" id="anc_risiko" placeholder="Contoh: Usia <20 tahun, preeklamsia, riwayat SC, perdarahan, anemia, TB, DM, hipertensi, kehamilan ganda, dsb."></textarea></label>
        <label>Klasifikasi risiko:
          <select name="klasifikasi_risiko" id="anc_klass">
            <option value="">Pilih</option>
            <option value="rendah">Risiko rendah</option>
            <option value="tinggi">Risiko tinggi</option>
            <option value="gawat_darurat">Gawat darurat obstetri</option>
          </select>
        </label>
        <label>Perlu rujukan:
          <select name="perlu_rujukan" id="anc_perlu_rujukan">
            <option value="">Pilih</option>
            <option value="tidak">Tidak</option>
            <option value="terencana">Ya, rujukan terencana</option>
            <option value="segera">Ya, rujukan segera</option>
          </select>
        </label>
        <label>Tujuan rujukan:<input type="text" name="tujuan_rujukan" id="anc_tujuan_rujukan" placeholder="Contoh: Puskesmas, RSUD, RS rujukan lain" /></label>
        <label>Alasan rujukan:<textarea name="alasan_rujukan" rows="2" id="anc_alasan_rujukan" placeholder="Contoh: preeklamsia berat, perdarahan, ketuban pecah dini, gawat janin, dsb."></textarea></label>
        <label>Tatalaksana awal di Poskesdes:<textarea name="tatalaksana_awal" rows="2" id="anc_tatalaksana_awal" placeholder="Contoh: stabilisasi umum, pemberian MgSO4 sesuai program, infus, edukasi keluarga, dsb."></textarea></label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Kesimpulan Kunjungan</legend>
        <label>Ringkasan:<textarea name="ringkasan_kunjungan" rows="3" id="anc_ringkasan_kunjungan" placeholder="Ringkasan hasil pemeriksaan, rencana kontrol, dan edukasi yang diberikan."></textarea></label>
        <label>Jadwal kontrol berikutnya:
          <input type="date" name="jadwal_kontrol_berikut" id="anc_jadwal_berikut" />
          <p class="danger-note" style="font-size:12px; margin-top:4px">Catatan: Tanggal ini akan otomatis ditambahkan ke halaman Jadwal.</p>
        </label>
      </fieldset>

      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="small-btn" data-link="pemeriksaan">Batal</button>
        <button type="submit" class="btn-primary">Simpan Rekam ANC</button>
      </div>
    </form>
  </div>
</section>

<section id="pelayanan-kb" class="page" data-page>
  <div class="card layanan-detail">
    <h2>Pelayanan Keluarga Berencana (KB) — Form Lengkap</h2>
    <p class="muted">Isi form berikut untuk merekam pelayanan dan jadwal kontrol KB. Data akan disimpan ke perangkat (localStorage).</p>

    <div class="card anc-block">
      <label>
        Pilih Pasien (Ibu):
        <select id="select-pasien-kb" data-info-target="info-pasien-kb">
          <option value="">-- Pilih Pasien Terdaftar --</option>
        </select>
      </label>
      <p id="info-pasien-kb" style="margin-top:8px; font-weight:bold; color:#444;"></p>
    </div>

    <form id="form-kb">
      <fieldset class="card anc-block">
        <legend>Data Pelayanan KB (Untuk Laporan Form 7)</legend>
        <label>Status Peserta:
          <select name="kb_status_peserta" id="kb_status_peserta" required>
            <option value="">Pilih Status</option>
            <option value="Baru">Peserta KB Baru</option>
            <option value="Lama">Peserta KB Lama (Mengganti)</option>
            <option value="Lama">Peserta KB Lama (Suntik Ulang/Pil Lanjut)</option>
            <option value="DropOut">Drop Out/Keluar dari KB</option>
            <option value="Gagal">Gagal/Hamil Saat KB</option>
            <option value="Komplikasi">Komplikasi</option>
          </select>
        </label>
        <label>Metode KB:
          <select name="metode_kb" id="kb_metode">
            <option value="">Pilih Metode</option>
            <option value="Pil">Pil KB</option>
            <option value="Suntik 1 Bln">Suntik 1 Bulan</option>
            <option value="Suntik 3 Bln">Suntik 3 Bulan</option>
            <option value="Implan">Implan (Susuk)</option>
            <option value="IUD">IUD</option>
            <option value="MOW">MOW (Steril)</option>
            <option value="MAL">MAL</option>
            <option value="Kondom">Kondom</option>
            <option value="MOP">MOP</option>
            <option value="Lain">Lainnya</option>
          </select>
        </label>
        <label>Tanggal Mulai/Pemasangan:
          <input type="date" name="tgl_mulai" id="kb_tgl_mulai" required />
        </label>
        <label>Keterangan:
          <textarea name="keterangan" rows="2" id="kb_keterangan" placeholder="Contoh: KB suntik 3 bulan ke-2, Keluhan: pusing ringan, dsb. Jika status Komplikasi/Drop Out/Gagal, jelaskan di sini."></textarea>
        </label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Jadwal Kontrol Berikutnya</legend>
        <label>Rencana Tindakan:
          <select name="rencana_tindakan" id="kb_rencana_tindakan">
            <option value="Kontrol Rutin">Kontrol Rutin</option>
            <option value="Ganti Metode">Ganti/Lepas Metode</option>
            <option value="Suntik Ulang">Suntik Ulang</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </label>
        <label>Jadwal Kontrol Berikutnya:
          <input type="date" name="jadwal_kontrol_kb" id="kb_jadwal_berikut" />
          <p class="danger-note" style="font-size:12px; margin-top:4px">Catatan: Tanggal ini akan otomatis ditambahkan ke halaman Jadwal.</p>
        </label>
      </fieldset>

      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="small-btn" data-link="home">Batal</button>
        <button type="submit" class="btn-primary">Simpan Rekam KB</button>
      </div>
    </form>
  </div>
</section>

<section id="lansia-detail" class="page" data-page>
  <div class="card layanan-detail">
    <h2>Pemeriksaan Pasien Lansia & Posbindu — Form Lengkap</h2>
    <p class="muted">Isi form berikut untuk merekam pemeriksaan Lansia (Usia 60+). Data akan disimpan ke perangkat (localStorage).</p>

    <div class="card anc-block">
      <label>
        Pilih Pasien:
        <select id="select-pasien-lansia" data-info-target="info-pasien-lansia">
          <option value="">-- Pilih Pasien Terdaftar --</option>
        </select>
      </label>
      <p id="info-pasien-lansia" style="margin-top:8px; font-weight:bold; color:#444;"></p>
    </div>

    <form id="form-lansia">
      <fieldset class="card anc-block">
        <legend>Data Kunjungan & Keluhan Utama</legend>
        <label>Keluhan Utama:
          <textarea name="keluhan_lansia" rows="2" id="lansia_keluhan" placeholder="Contoh: pusing, nyeri sendi, batuk lama, dsb." required></textarea>
        </label>
        <label>Diagnosa (Ringkas):
          <input type="text" name="diagnosa_lansia" id="lansia_diagnosa" required placeholder="Contoh: Hipertensi, Osteoarthritis, DM Tipe 2" />
        </label>
        <div class="form-row">
            <div class="col">
                <label>Berat Badan (kg):<input type="number" step="0.1" name="bb_lansia" min="0" id="lansia_bb" /></label>
            </div>
            <div class="col">
                <label>Tinggi Badan (cm):<input type="number" name="tb_lansia" min="0" id="lansia_tb" /></label>
            </div>
        </div>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Pemeriksaan Fisik & Penunjang</legend>
        <label>Tekanan darah (mmHg):<input type="text" name="td_lansia" id="lansia_td" placeholder="Contoh: 140/90" required /></label>
        <div class="form-row">
            <div class="col">
                <label>Gula Darah Sewaktu (GDS) (mg/dL):<input type="number" name="gds" min="0" id="lansia_gds" /></label>
            </div>
            <div class="col">
                <label>Asam Urat (mg/dL):<input type="number" step="0.1" name="asam_urat" id="lansia_asam_urat" /></label>
            </div>
            <div class="col">
                <label>Kolesterol Total (mg/dL):<input type="number" name="kolesterol" min="0" id="lansia_kolesterol" /></label>
            </div>
        </div>
        <label>Tindakan/Pengobatan:
          <textarea name="tindakan_lansia" rows="2" id="lansia_tindakan" placeholder="Contoh: Pemberian obat Hipertensi Amlodipin 5mg, Edukasi diet rendah garam, rujuk ke Puskesmas, dsb."></textarea>
        </label>
      </fieldset>

      <fieldset class="card anc-block">
        <legend>Jadwal Kontrol Berikutnya</legend>
        <label>Jadwal Kontrol Berikutnya:
          <input type="date" name="jadwal_kontrol_lansia" id="lansia_jadwal_berikut" />
          <p class="danger-note" style="font-size:12px; margin-top:4px">Catatan: Tanggal ini akan otomatis ditambahkan ke halaman Jadwal.</p>
        </label>
      </fieldset>

      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="small-btn" data-link="home">Batal</button>
        <button type="submit" class="btn-primary">Simpan Rekam Lansia</button>
      </div>
    </form>
  </div>
</section>

<section id="jadwal" class="page" data-page>
  <div class="card">
    <?php $logged = \App\Services\AuthService::isLoggedIn(); if ($logged) { ?>
      <h2 style="margin:0">Manajemen Jadwal & Pengingat Kontrol</h2>
      <p class="muted">Lihat semua jadwal kunjungan pasien (ANC/KB/Lansia) dan agenda Poskesdes (Imunisasi).</p>
      <div class="card" style="margin-bottom:12px;">
        <h3 style="margin:0 0 8px 0">Tambah Jadwal Baru (Manual)</h3>
        <form id="form-jadwal">
          <div class="form-row"><div class="col"><label>Nama Pasien/Kegiatan:<input type="text" id="j_nama" name="nama" required placeholder="Contoh: Ibu Siti / Imunisasi Polio" /></label></div><div class="col"><label>Tanggal Jadwal:<input type="date" id="j_tanggal" name="tanggal" required /></label></div></div>
          <div class="form-row" style="margin-top:10px"><div class="col"><label>Jenis Kegiatan:<select id="j_jenis" name="jenis" required><option value="">-- Pilih Jenis --</option><option value="ANC Kontrol">ANC Kontrol</option><option value="KB Kontrol">KB Kontrol</option><option value="Lansia Kontrol">Lansia Kontrol</option><option value="Imunisasi">Imunisasi</option><option value="Lainnya">Lainnya</option></select></label></div><div class="col"><label>Cara Kontak/Pengingat:<select id="j_cara" name="cara" required><option value="Telepon/WA">Telepon/WA</option><option value="Kunjungan">Kunjungan Bidan</option><option value="Poskesdes">Datang ke Poskesdes</option></select></label></div></div>
          <div style="text-align:right; margin-top:10px"><button type="submit" class="btn-primary">Simpan Jadwal</button></div>
        </form>
      </div>
    <?php } else { ?>
      
    <?php } ?>
    <h3 style="margin:0 0 8px 0">Daftar Semua Jadwal</h3>
    <div id="jadwal-kosong" class="muted" style="padding:10px;text-align:center;">Belum ada jadwal yang terdaftar.</div>
    <div id="jadwal-list" class="grid" style="gap:8px"></div>
  </div>
</section>

<section id="laporan" class="page" data-page>
  <div class="card">
    <h2 style="margin:0">Laporan & Statistik Ringkas</h2>
    <p class="muted">Statistik dasar pasien terdaftar dan jadwal kontrol aktif</p>
    <div style="display:flex;gap:10px;margin:12px 0">
      <div style="flex:1;background:#f9fafb;border-radius:10px;padding:10px"><div class="muted" style="font-size:12px">Total Pendaftar</div><div id="lap-total-pendaftar" style="font-size:20px;font-weight:700">0</div></div>
      <div style="flex:1;background:#f9fafb;border-radius:10px;padding:10px"><div class="muted" style="font-size:12px">Total Rekam ANC</div><div id="lap-total-anc" style="font-size:20px;font-weight:700">0</div></div>
      <div style="flex:1;background:#f9fafb;border-radius:10px;padding:10px"><div class="muted" style="font-size:12px">Total Rekam KB</div><div id="lap-total-kb" style="font-size:20px;font-weight:700">0</div></div>
      <div style="flex:1;background:#f9fafb;border-radius:10px;padding:10px"><div class="muted" style="font-size:12px">Total Rekam Lansia</div><div id="lap-total-lansia" style="font-size:20px;font-weight:700">0</div></div>
      <div style="flex:1;background:#f9fafb;border-radius:10px;padding:10px"><div class="muted" style="font-size:12px">Jadwal Kontrol Aktif</div><div id="lap-total-jadwal" style="font-size:20px;font-weight:700">0</div></div>
    </div>
    <h3 style="margin:0 0 4px 0">Daftar Jadwal (Ringkas)</h3>
    <div class="table-like" id="laporan-jadwal"><div class="header"><span>No</span><span>Pasien</span><span>Tanggal</span><span>Jenis</span></div></div>
  </div>
</section>

<section id="ekspor" class="page" data-page>
  <div class="card">
    <h2 style="margin:0">Ekspor Data Dasar & Laporan Bulanan</h2>
    <p class="muted" style="margin:4px 0 10px">Gunakan tombol di bawah untuk mengunduh data mentah pasien, jadwal, dan rekam medis. Atau unduh rangkuman bulanan untuk mempermudah rekap laporan.</p>
    <div>
      <button class="btn" id="btn-export-pendaftar">Unduh CSV Pendaftar</button>
      <button class="btn" id="btn-export-jadwal" style="margin-left:8px">Unduh CSV Jadwal</button>
      <button class="btn" id="btn-export-anc" style="margin-left:8px">Unduh CSV Rekam ANC</button>
      <button class="btn" id="btn-export-kb" style="margin-left:8px">Unduh CSV Rekam KB</button>
      <button class="btn" id="btn-export-lansia" style="margin-left:8px">Unduh CSV Rekam Lansia</button>
    </div>
    <div style="margin-top:14px; padding-top:10px; border-top:1px solid #eee;">
      <p class="muted" style="font-weight:700; margin-bottom:8px;">Laporan Bulanan (Ringkas)</p>
      <button class="btn-primary" id="btn-export-summary-bulan">Unduh CSV Laporan Bulanan (Ringkas)</button>
      <p class="muted" style="font-size:12px; margin-top:6px;">Laporan ini berisi total K1, K-Lanjut, KB Baru, dan Komplikasi per bulan.</p>
    </div>
  </div>
</section>

<section id="edukasi" class="page" data-page>
  <div class="card">
    <h2 style="margin:0">Edukasi Kesehatan</h2>
    <div style="height:10px"></div>
    <div style="display:grid;gap:10px">
      <div style="padding:12px;border-radius:10px;background:linear-gradient(180deg,#fff,#fbfbfb);border:1px solid #f1f7f7"><strong>Gizi Ibu Hamil</strong><p class="muted" style="margin:6px 0 0"> Contoh menu harian, makanan kaya zat besi, dan anjuran suplemen Fe. </p></div>
      <div style="padding:12px;border-radius:10px;background:linear-gradient(180deg,#fff,#fbfbfb);border:1px solid #f1f7f7"><strong>Tanda Bahaya Kehamilan</strong><p class="muted" style="margin:6px 0 0"> Perdarahan, bengkak wajah/tangan, pandangan kabur, atau nyeri kepala hebat. Segera lapor bidan. </p></div>
      <div style="padding:12px;border-radius:10px;background:linear-gradient(180deg,#fff,#fbfbfb);border:1px solid #f1f7f7"><strong>Metode Keluarga Berencana (KB)</strong><p class="muted" style="margin:6px 0 0"> Penjelasan lengkap metode KB jangka pendek (pil/suntik) dan jangka panjang (IUD/Implen). </p></div>
    </div>
  </div>
</section>

<section id="profil" class="page" data-page>
  <div class="card">
    <h2 style="margin:0">Profil Poskesdes & Bidan</h2>
    <p class="muted">Informasi kontak dan jam layanan Poskesdes Pagar Ruyung.</p>
    <div style="margin-top:12px">
      <p><strong>Nama Bidan Pengelola:</strong> Pertiwi Agustini (sesuai skripsi)</p>
      <p><strong>Alamat:</strong> Desa Pagar Ruyung, (Dekat Balai Desa)</p>
      <p><strong>Kontak:</strong> (0812) XXXX XXXX</p>
    </div>
    <?php $logged = \App\Services\AuthService::isLoggedIn(); if ($logged) { ?>
      <div style="height:12px"></div>
      <div class="card" style="padding:10px">
        <div class="muted" style="margin-bottom:6px">Akun masuk: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></div>
        <form method="post" class="vstack gap-2">
          <input type="hidden" name="action" value="logout" />
          <button class="btn" type="submit">Keluar</button>
        </form>
      </div>
    <?php } else { ?>
      <div style="height:12px"></div>
      <a class="btn" href="?page=login">Masuk</a>
    <?php } ?>
  </div>
</section>