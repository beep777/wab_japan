<?php
require_once __DIR__ . '/config.php';
$id = intval($_GET['id'] ?? 1);
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.icon AS category_icon FROM `places` p INNER JOIN `categories` c ON p.category_id = c.id WHERE p.id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$place = $stmt->fetch();
if (!$place) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($place['name']) ?> | <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<nav class="navbar bg-white shadow-sm py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="index.php">⛩️ JAPAN TRAVEL</a>
        <a href="index.php" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="bi bi-arrow-left"></i> กลับหน้าแรก</a>
    </div>
</nav>

<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <img src="<?= getImageUrl($place['image']) ?>" style="height: 400px; object-fit: cover;">
        <div class="card-body p-4 p-md-5">
            <span class="badge bg-danger rounded-pill mb-2"><?= htmlspecialchars($place['category_name']) ?></span>
            <h2 class="fw-bold mb-1"><?= htmlspecialchars($place['name']) ?></h2>
            <h5 class="text-muted mb-3"><?= htmlspecialchars($place['name_jp'] ?: '') ?></h5>
            <div class="d-flex gap-3 text-muted mb-4">
                <span><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($place['prefecture']) ?></span>
                <span><?= renderRatingStars($place['rating']) ?></span>
                <span><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($place['best_season'] ?: 'ตลอดปี') ?></span>
            </div>
            <?php if ($place['highlight']): ?>
                <div class="alert alert-danger py-2 mb-4"><strong>ไฮไลท์:</strong> <?= htmlspecialchars($place['highlight']) ?></div>
            <?php endif; ?>
            <p style="line-height: 1.8; white-space: pre-line;"><?= htmlspecialchars($place['description']) ?></p>
        </div>
    </div>
</div>
</body>
</html>
