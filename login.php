<?php
require_once __DIR__ . '/_auth.php';
if (isAdminLoggedIn()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $u]);
    $admin = $stmt->fetch();

    $ok = false;
    if ($admin) {
        if (password_verify($p, $admin['password'])) {
            $ok = true;
        } elseif ($admin['password'] === $p) {       // รหัสผ่านที่ยังไม่ถูกเข้ารหัส -> เข้ารหัสให้ทันที
            $ok = true;
            $pdo->prepare("UPDATE admins SET password = :h WHERE id = :id")
                ->execute([':h' => password_hash($p, PASSWORD_DEFAULT), ':id' => $admin['id']]);
        }
    }

    if ($ok) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_fullname'] = $admin['fullname'];
        header('Location: ../index.html');
        exit;
    }
    $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบหลังบ้าน | Japan Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{font-family:'Prompt',sans-serif;min-height:100vh;display:flex;align-items:center;background:linear-gradient(180deg,rgba(29,53,87,.9),rgba(201,24,74,.8))}
        .login-card{max-width:420px;width:100%;border:0;border-radius:24px}
    </style>
</head>
<body>
<div class="container">
    <div class="card login-card shadow-lg mx-auto p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="fs-1">⛩️</div>
            <h4 class="fw-bold mb-1">เข้าสู่ระบบหลังบ้าน</h4>
            <div class="text-muted small">Japan Travel Admin</div>
        </div>
        <?php if ($error): ?><div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label small fw-semibold">ชื่อผู้ใช้</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="username" class="form-control" required autofocus></div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">รหัสผ่าน</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" required></div>
            </div>
            <button class="btn btn-danger w-100 rounded-pill py-2 fw-semibold">เข้าสู่ระบบ</button>
        </form>
        <div class="text-center mt-4"><a href="../index.html" class="small text-decoration-none text-secondary"><i class="bi bi-arrow-left"></i> กลับหน้าแรก</a></div>
    </div>
</div>
</body>
</html>