<?php
require_once __DIR__ . '/_auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);

    if ($action === 'save') {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '') ?: 'bi-geo-alt';
        if ($name === '') {
            setFlash('danger', 'กรุณากรอกชื่อหมวดหมู่');
        } elseif ($id > 0) {
            $pdo->prepare("UPDATE categories SET name=:n, description=:d, icon=:i WHERE id=:id")
                ->execute([':n'=>$name, ':d'=>$desc, ':i'=>$icon, ':id'=>$id]);
            setFlash('success', 'แก้ไขหมวดหมู่เรียบร้อยแล้ว');
        } else {
            $pdo->prepare("INSERT INTO categories (name, description, icon) VALUES (:n,:d,:i)")
                ->execute([':n'=>$name, ':d'=>$desc, ':i'=>$icon]);
            setFlash('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
        }
    } elseif ($action === 'delete' && $id > 0) {
        $cnt = $pdo->prepare("SELECT COUNT(*) FROM places WHERE category_id = :id");
        $cnt->execute([':id' => $id]);
        if ($cnt->fetchColumn() > 0) {
            setFlash('danger', 'ลบไม่ได้ เพราะยังมีสถานที่ในหมวดหมู่นี้ กรุณาย้ายหรือลบสถานที่ก่อน');
        } else {
            $pdo->prepare("DELETE FROM categories WHERE id = :id")->execute([':id' => $id]);
            setFlash('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
        }
    }
    header('Location: categories.php');
    exit;
}

$editId = intval($_GET['edit'] ?? 0);
$edit = ['id'=>0, 'name'=>'', 'description'=>'', 'icon'=>'bi-geo-alt'];
if ($editId) {
    $s = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $s->execute([':id' => $editId]);
    $edit = $s->fetch() ?: $edit;
}
$cats = $pdo->query("SELECT c.*, COUNT(p.id) AS total FROM categories c LEFT JOIN places p ON c.id = p.category_id GROUP BY c.id ORDER BY c.id")->fetchAll();

$pageTitle = 'หมวดหมู่'; $active = 'categories';
require __DIR__ . '/_header.php';
?>
<h4 class="fw-bold mb-3">จัดการหมวดหมู่</h4>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-3"><?= $edit['id'] ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่' ?></h6>
            <form method="POST">
                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <div class="mb-3"><label class="form-label small">ชื่อหมวดหมู่ *</label>
                    <input name="name" class="form-control" value="<?= htmlspecialchars($edit['name']) ?>" required></div>
                <div class="mb-3"><label class="form-label small">คำอธิบาย</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($edit['description']) ?></textarea></div>
                <div class="mb-3"><label class="form-label small">ไอคอน (Bootstrap Icons เช่น bi-tree-fill)</label>
                    <input name="icon" class="form-control" value="<?= htmlspecialchars($edit['icon']) ?>"></div>
                <button class="btn btn-danger rounded-pill px-4"><?= $edit['id'] ? 'บันทึกการแก้ไข' : 'เพิ่มหมวดหมู่' ?></button>
                <?php if ($edit['id']): ?><a href="categories.php" class="btn btn-light rounded-pill">ยกเลิก</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>#</th><th>ชื่อ</th><th>ไอคอน</th><th>สถานที่</th><th class="text-end">จัดการ</th></tr></thead>
                <tbody>
                <?php foreach ($cats as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><i class="bi <?= htmlspecialchars($c['icon']) ?>"></i></td>
                        <td><?= $c['total'] ?></td>
                        <td class="text-end">
                            <a href="categories.php?edit=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้?')">
                                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require __DIR__ . '/_footer.php'; ?>