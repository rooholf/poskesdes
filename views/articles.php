<?php
?><div class="mb-3 d-flex justify-content-between align-items-center">
  <h1 class="h5">Informasi Kesehatan</h1>
  <?php if (\App\Services\AuthService::isLoggedIn()) { ?><a class="btn btn-primary btn-sm" href="/admin">Kelola</a><?php } ?>
</div>
<form class="mb-3" method="get">
  <input type="hidden" name="page" value="articles">
  <div class="input-group">
    <input name="q" class="form-control" placeholder="Cari artikel">
    <button class="btn btn-outline-secondary">Cari</button>
  </div>
</form>
<?php
  $cat = isset($_GET['cat']) ? trim($_GET['cat']) : '';
  $cats = [];
  if ($pdo) { $cats = $pdo->query("SELECT DISTINCT COALESCE(category,'Tanpa Kategori') AS c FROM articles ORDER BY c ASC")->fetchAll(PDO::FETCH_COLUMN); }
?>
<div class="mb-2">
  <div class="btn-group" role="group">
    <a href="/articles" class="btn btn-outline-secondary">Semua</a>
    <?php foreach ($cats as $c) { $active = ($cat === $c); ?>
      <a href="/articles?cat=<?php echo urlencode($c); ?>" class="btn btn-outline-secondary<?php echo ($active? ' active':''); ?>"><?php echo htmlspecialchars($c); ?></a>
    <?php } ?>
  </div>
  <a class="btn btn-warning btn-sm ms-2" href="?page=articles">Reset</a>
  </div>
<div class="row g-2">
  <?php
  if ($pdo) {
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $sql = "SELECT id, title, category, SUBSTRING(body,1,140) AS snippet, created_at FROM articles";
    $params = [];
    $clauses = [];
    if ($q !== '') { $clauses[] = "(title LIKE ? OR body LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
    if ($cat !== '') { $clauses[] = "(COALESCE(category,'Tanpa Kategori') = ?)"; $params[] = $cat; }
    if (!empty($clauses)) { $sql .= " WHERE ".implode(" AND ", $clauses); }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) { ?>
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="fw-bold"><?php echo htmlspecialchars($r['title']); ?></div>
              <span class="badge text-bg-light"><?php echo htmlspecialchars($r['category']); ?></span>
            </div>
            <div class="text-muted mt-1"><?php echo htmlspecialchars($r['snippet']); ?>...</div>
            <div class="mt-2"><a class="btn btn-outline-secondary btn-sm" href="/article?id=<?php echo (int)$r['id']; ?>">Baca selengkapnya</a></div>
          </div>
        </div>
      </div>
    <?php }
  } else { ?>
    <div class="col-12"><div class="alert alert-warning">Artikel memerlukan database</div></div>
  <?php } ?>
</div>