<?php $flash = getFlash(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'หลังบ้าน') ?> | Japan Travel Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body{font-family:'Prompt',sans-serif;background:#f8fafc}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="index.php">⛩️ หลังบ้าน</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <?php foreach (['dashboard'=>['index.php','แดชบอร์ด'], 'places'=>['places.php','สถานที่ท่องเที่ยว'], 'categories'=>['categories.php','หมวดหมู่']] as $k=>$m): ?>
                    <li class="nav-item"><a class="nav-link <?= ($active ?? '') === $k ? 'active fw-bold text-danger' : '' ?>" href="<?= $m[0] ?>"><?= $m[1] ?></a></li>
                <?php endforeach; ?>
            </ul>
            <a href="../index.html" class="btn btn-outline-secondary btn-sm rounded-pill me-2" target="_blank"><i class="bi bi-eye"></i> ดูหน้าเว็บ</a>
            <span class="small text-muted me-2"><?= htmlspecialchars($_SESSION['admin_fullname'] ?? '') ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm rounded-pill"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a>
        </div>
    </div>
</nav>
<div class="container pb-5">
<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>