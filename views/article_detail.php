<?php
  $aid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $article = null;
  if ($pdo && $aid>0) { $st=$pdo->prepare('SELECT id,title,category,body,created_at FROM articles WHERE id=? LIMIT 1'); $st->execute([$aid]); $article=$st->fetch(PDO::FETCH_ASSOC); }
?>
<div class="row g-3">
  <div class="col-12 col-lg-8">
    <div class="card"><div class="card-body">
      <?php if ($article) { ?>
        <div class="d-flex justify-content-between align-items-center">
          <div class="fw-bold h5 mb-0"><?php echo htmlspecialchars($article['title']); ?></div>
          <span class="badge text-bg-light"><?php echo htmlspecialchars($article['category'] ?? 'Tanpa Kategori'); ?></span>
        </div>
        <div class="mt-2">
          <?php echo nl2br(htmlspecialchars($article['body'])); ?>
        </div>
      <?php } else { ?>
        <div class="text-muted">Artikel tidak ditemukan</div>
      <?php } ?>
    </div></div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="card"><div class="card-body">
      <div class="fw-bold">Catatan dari Bidan</div>
      <div class="mt-2">
        <?php if ($pdo && $aid>0) { $rows=$pdo->prepare('SELECT note,created_at FROM article_notes WHERE article_id=? ORDER BY id DESC'); $rows->execute([$aid]); $list=$rows->fetchAll(); if(count($list)===0){ echo '<div class="text-muted">Belum ada catatan</div>'; } foreach($list as $n){ ?>
          <div style="padding:8px;border:1px solid #eee;border-radius:8px;margin-bottom:8px"><div><?php echo htmlspecialchars($n['note']); ?></div><div class="text-muted" style="font-size:12px"><?php echo htmlspecialchars($n['created_at']); ?></div></div>
        <?php } } ?>
      </div>
      <?php if (\App\Services\AuthService::isLoggedIn()) { ?>
      <form method="post" class="vstack gap-2 mt-2">
        <input type="hidden" name="action" value="add_article_note">
        <input type="hidden" name="article_id" value="<?php echo (int)$aid; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(\App\Services\CsrfService::token()); ?>">
        <textarea name="note" class="form-control" placeholder="Tambahkan catatan untuk artikel ini"></textarea>
        <button class="btn btn-primary">Simpan Catatan</button>
      </form>
      <?php } ?>
    </div></div>
  </div>
</div>