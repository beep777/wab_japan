<?php
require_once __DIR__ . '/_auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    checkCsrf();
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        $pdo->prepare("DELETE FROM places WHERE id = :id")->execute([':id' => $id]);
        setFlash('success', 'ลบสถานที่เรียบร้อยแล้ว');
    }
    header('Location: places.php');
    exit;
}

$kw = trim($_GET['q'] ?? '');
$sql = "SELECT p.*, c.name AS cat FROM places p JOIN categories c ON p.category_id = c.id";
$params = [];
if ($kw !== '') { $sql .= " WHERE p.name LIKE :k OR p.prefecture LIKE :k"; $params[':k'] = "%$kw%"; }
$sql .= " ORDER BY p.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$places = $stmt->fetchAll();

$pageTitle = 'สถานที่ท่องเที่ยว'; $active = 'places';
require __DIR__ . '/_header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h4 class="fw-bold mb-0">จัดการสถานที่ท่องเที่ยว</h4>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="GET">
            <input name="q" class="form-control form-control-sm" placeholder="ค้นหา..." value="<?= htmlspecialchars($kw) ?>">
            <button class="btn btn-sm btn-outline-secondary">ค้นหา</button>
        </form>
        <a href="place_form.php" class="btn btn-danger btn-sm rounded-pill px-3"><i class="bi bi-plus-lg"></i> เพิ่มสถานที่</a>
    </div>
</div>
<div class="card border-0 shadow-sm table-responsive">
    <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>รูป</th><th>ชื่อ</th><th>จังหวัด</th><th>หมวดหมู่</th><th>คะแนน</th><th class="text-end">จัดการ</th></tr></thead>
        <tbody>
        <?php if (!$places): ?><tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีข้อมูล</td></tr><?php endif; ?>
        <?php foreach ($places as $p): ?>
            <tr>
                <td><img src="<?= getImageUrl($p['image'], '../') ?>" width="70" height="50" style="object-fit:cover;border-radius:8px"></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['prefecture']) ?></td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['cat']) ?></span></td>
                <td><i class="bi bi-star-fill text-warning"></i> <?= number_format($p['rating'], 1) ?></td>
                <td class="text-end text-nowrap">
                    <a href="../place_detail.html?id=<?= $p['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                    <a href="place_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบสถานที่นี้?')">
                        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/_footer.php'; ?>