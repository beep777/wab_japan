<?php
require_once __DIR__ . '/_auth.php';
requireLogin();

$totalPlaces = $pdo->query("SELECT COUNT(*) FROM places")->fetchColumn();
$totalCats   = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$latest = $pdo->query("SELECT p.id, p.name, p.prefecture, c.name AS cat FROM places p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 5")->fetchAll();

$pageTitle = 'แดชบอร์ด'; $active = 'dashboard';
require __DIR__ . '/_header.php';
?>
<h4 class="fw-bold mb-4">ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['admin_fullname']) ?></h4>
<div class="row g-3 mb-4">
    <div class="col-md-6"><a href="places.php" class="text-decoration-none">
        <div class="card border-0 shadow-sm p-4"><div class="text-muted">สถานที่ท่องเที่ยว</div><div class="display-5 fw-bold text-danger"><?= $totalPlaces ?></div></div></a></div>
    <div class="col-md-6"><a href="categories.php" class="text-decoration-none">
        <div class="card border-0 shadow-sm p-4"><div class="text-muted">หมวดหมู่</div><div class="display-5 fw-bold text-primary"><?= $totalCats ?></div></div></a></div>
</div>
<div class="card border-0 shadow-sm p-4">
    <h6 class="fw-bold mb-3">สถานที่ที่เพิ่มล่าสุด</h6>
    <ul class="list-group list-group-flush">
        <?php foreach ($latest as $r): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span><?= htmlspecialchars($r['name']) ?> <small class="text-muted">(<?= htmlspecialchars($r['prefecture']) ?>)</small></span>
                <span class="badge bg-light text-dark border"><?= htmlspecialchars($r['cat']) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php require __DIR__ . '/_footer.php'; ?>