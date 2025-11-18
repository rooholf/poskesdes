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
  </aside>
  <section class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-bold">Ringkasan Halaman Publik</div><a class="btn btn-outline-secondary btn-sm" href="/home" target="_blank">Buka Halaman Publik</a></div>
    <div class="row g-2">
      <div class="col-12 col-md-4"><div class="admin-card"><div class="text-muted" style="font-size:12px">Total Pendaftar</div><div id="adm-stat-pendaftar" style="font-weight:700;font-size:22px">—</div></div></div>
      <div class="col-12 col-md-4"><div class="admin-card"><div class="text-muted" style="font-size:12px">Total Kunjungan</div><div id="adm-stat-kunjungan" style="font-weight:700;font-size:22px">—</div></div></div>
      <div class="col-12 col-md-4"><div class="admin-card"><div class="text-muted" style="font-size:12px">Agenda Aktif</div><div id="adm-stat-agenda" style="font-weight:700;font-size:22px">—</div></div></div>
    </div>
    <div class="row g-2 mt-2">
      <div class="col-12 col-md-7">
        <div class="admin-card">
          <div class="d-flex justify-content-between align-items-center"><div class="fw-bold">Agenda Terdekat</div><a class="btn btn-sm btn-outline-secondary" href="/admin/schedules">Kelola Jadwal</a></div>
          <div id="adm-agenda" class="agenda mt-2"></div>
        </div>
      </div>
      <div class="col-12 col-md-5">
        <div class="admin-card">
          <div class="fw-bold">Artikel Terbaru</div>
          <div id="adm-articles" class="mt-2"></div>
        </div>
      </div>
    </div>
    <script>
    (function(){
      const API='/api.php';
      const cache={};
      function getCached(query){ const now=Date.now(); const key=query; const it=cache[key]; if(it && (now - it.ts) < 30000){ return Promise.resolve(it.data); } return fetch(API+'?'+query).then(r=>r.ok?r.json():null).then(d=>{ if(d){ cache[key]={ ts:Date.now(), data:d }; } return d; }); }
      function fmt(t){ const d=new Date(t); return d.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}); }
      getCached('action=summary').then(s=>{ if(!s) return; const elP=document.getElementById('adm-stat-pendaftar'); const elK=document.getElementById('adm-stat-kunjungan'); const elA=document.getElementById('adm-stat-agenda'); if(elP) elP.textContent=s.pendaftar; if(elK) elK.textContent=(Number(s.anc||0)+Number(s.kb||0)+Number(s.lansia||0)); if(elA) elA.textContent=s.jadwal_aktif; });
      getCached('action=schedules_list').then(data=>{ const wrap=document.getElementById('adm-agenda'); if(!wrap) return; if(!data||data.length===0){ wrap.innerHTML='<div class="muted" style="padding:10px;text-align:center;">Belum ada agenda terdekat.</div>'; return; } const now=new Date(); const withDt=(Array.isArray(data)?data:[]).map(d=>{ const raw=String(d.time||''); const t=(raw&&raw.length===5? raw+':00' : (raw?raw:'23:59:59')); const dt=new Date(`${d.date}T${t}`); return Object.assign({},d,{__dt:dt}); }); const upcoming=withDt.filter(d=> d.__dt>=now).sort((a,b)=> a.__dt-b.__dt).slice(0,3); if(upcoming.length===0){ wrap.innerHTML='<div class="muted" style="padding:10px;text-align:center;">Belum ada agenda terdekat.</div>'; return; } wrap.innerHTML=upcoming.map(d=>`<div class="item"><div><div style="font-weight:700">${d.subject||d.service_type} — ${d.time||''}</div><div class="muted">${fmt(d.date)} — ${d.notes||''}</div></div></div>`).join(''); });
      fetch(API+'?action=articles_latest').then(r=>r.ok?r.json():null).then(rows=>{ const el=document.getElementById('adm-articles'); if(!el) return; if(!rows||rows.length===0){ el.innerHTML='<div class="text-muted">Belum ada artikel</div>'; return; } el.innerHTML=rows.map(r=>`<div style="padding:8px;border:1px solid #eee;border-radius:8px;margin-bottom:8px"><div class="d-flex justify-content-between"><div class="fw-bold">${r.title}</div><span class="badge text-bg-light">${r.category||'Tanpa Kategori'}</span></div><div class="text-muted mt-1">${r.snip}...</div><div class="mt-1"><a class="btn btn-outline-secondary btn-sm" href="/article?id=${r.id}">Baca</a></div></div>`).join(''); });
    })();
    </script>
  </section>
</div>