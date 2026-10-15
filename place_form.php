<?php
require_once __DIR__ . '/_auth.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$place = ['category_id'=>'', 'name'=>'', 'name_jp'=>'', 'prefecture'=>'', 'description'=>'',
          'highlight'=>'', 'image'=>'', 'rating'=>'5.0', 'best_season'=>''];

if ($id > 0) {
    $s = $pdo->prepare("SELECT * FROM places WHERE id = :id");
    $s->execute([':id' => $id]);
    $row = $s->fetch();
    if (!$row) { setFlash('danger', 'ไม่พบสถานที่ที่ต้องการแก้ไข'); header('Location: places.php'); exit; }
    $place = $row;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    foreach (['name','name_jp','prefecture','description','highlight','image','best_season'] as $f) {
        $place[$f] = trim($_POST[$f] ?? '');
    }
    $place['category_id'] = intval($_POST['category_id'] ?? 0);
    $place['rating'] = max(0, min(5, floatval($_POST['rating'] ?? 5)));

    if ($place['name'] === '')       $errors[] = 'กรุณากรอกชื่อสถานที่';
    if ($place['category_id'] <= 0)  $errors[] = 'กรุณาเลือกหมวดหมู่';
    if ($place['prefecture'] === '') $errors[] = 'กรุณากรอกจังหวัด';
    if ($place['description'] === '')$errors[] = 'กรุณากรอกรายละเอียด';

    // อัปโหลดรูป (ถ้าเลือกไฟล์ จะใช้แทนค่าในช่องลิงก์)
    if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp']) || !getimagesize($_FILES['image_file']['tmp_name'])) {
            $errors[] = 'ไฟล์รูปต้องเป็น jpg, png หรือ webp เท่านั้น';
        } else {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777, true);
            $fn = uniqid('place_') . '.' . $ext;
            move_uploaded_file($_FILES['image_file']['tmp_name'], UPLOAD_DIR . $fn);
            $place['image'] = $fn;
        }
    }

    if (!$errors) {
        $data = [
            ':cat'=>$place['category_id'], ':name'=>$place['name'], ':jp'=>$place['name_jp'],
            ':pref'=>$place['prefecture'], ':desc'=>$place['description'], ':hl'=>$place['highlight'],
            ':img'=>$place['image'], ':rate'=>$place['rating'], ':season'=>$place['best_season']
        ];
        if ($id > 0) {
            $data[':id'] = $id;
            $pdo->prepare("UPDATE places SET category_id=:cat, name=:name, name_jp=:jp, prefecture=:pref,
                description=:desc, highlight=:hl, image=:img, rating=:rate, best_season=:season WHERE id=:id")->execute($data);
            setFlash('success', 'แก้ไขสถานที่เรียบร้อยแล้ว');
        } else {
            $pdo->prepare("INSERT INTO places (category_id,name,name_jp,prefecture,description,highlight,image,rating,best_season)
                VALUES (:cat,:name,:jp,:pref,:desc,:hl,:img,:rate,:season)")->execute($data);
            setFlash('success', 'เพิ่มสถานที่เรียบร้อยแล้ว');
        }
        header('Location: places.php');
        exit;
    }
}

$pageTitle = $id ? 'แก้ไขสถานที่' : 'เพิ่มสถานที่'; $active = 'places';
require __DIR__ . '/_header.php';
?>
<h4 class="fw-bold mb-3"><?= $pageTitle ?></h4>
<?php foreach ($errors as $e): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($e) ?></div><?php endforeach; ?>

<form method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm p-4">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label small">ชื่อสถานที่ *</label>
            <input name="name" class="form-control" value="<?= htmlspecialchars($place['name']) ?>" required></div>
        <div class="col-md-6"><label class="form-label small">ชื่อภาษาญี่ปุ่น</label>
            <input name="name_jp" class="form-control" value="<?= htmlspecialchars($place['name_jp']) ?>"></div>
        <div class="col-md-4"><label class="form-label small">หมวดหมู่ *</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- เลือก --</option>
                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $place['category_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select></div>
        <div class="col-md-4"><label class="form-label small">จังหวัด *</label>
            <input name="prefecture" class="form-control" value="<?= htmlspecialchars($place['prefecture']) ?>" required></div>
        <div class="col-md-4"><label class="form-label small">คะแนน (0-5)</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" value="<?= htmlspecialchars($place['rating']) ?>"></div>
        <div class="col-md-6"><label class="form-label small">ฤดูกาลแนะนำ</label>
            <input name="best_season" class="form-control" value="<?= htmlspecialchars($place['best_season']) ?>"></div>
        <div class="col-md-6"><label class="form-label small">ไฮไลท์ (สั้นๆ)</label>
            <input name="highlight" class="form-control" value="<?= htmlspecialchars($place['highlight']) ?>"></div>
        <div class="col-12"><label class="form-label small">รายละเอียด *</label>
            <textarea name="description" rows="5" class="form-control" required><?= htmlspecialchars($place['description']) ?></textarea></div>
        <div class="col-md-6"><label class="form-label small">รูปภาพ (ลิงก์ URL หรือชื่อไฟล์ใน uploads/)</label>
            <input name="image" class="form-control" value="<?= htmlspecialchars($place['image']) ?>"></div>
        <div class="col-md-6"><label class="form-label small">หรืออัปโหลดรูปจากเครื่อง</label>
            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
        <?php if ($place['image']): ?>
            <div class="col-12"><img src="<?= getImageUrl($place['image'], '../') ?>" height="120" class="rounded"></div>
        <?php endif; ?>
    </div>
    <div class="mt-4">
        <button class="btn btn-danger rounded-pill px-4"><i class="bi bi-save"></i> บันทึก</button>
        <a href="places.php" class="btn btn-light rounded-pill px-4">ยกเลิก</a>
    </div>
</form>
<?php require __DIR__ . '/_footer.php'; ?>