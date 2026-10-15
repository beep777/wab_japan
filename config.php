<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webtravel_db');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'Japan Wonders | ท่องเที่ยวประเทศญี่ปุ่น');
define('ADMIN_NAME', 'Japan Travel Admin');
define('UPLOAD_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die('
        <div style="font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 25px; border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239;">
            <h2 style="color: #e11d48; margin-top:0;">⚠️ ไม่สามารถเชื่อมต่อฐานข้อมูล MySQL ได้</h2>
            <p><strong>ข้อผิดพลาด:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>
            <hr>
            <p><strong>วิธีแก้ไข:</strong></p>
            <ol>
                <li>เปิด XAMPP Control Panel และกด Start ที่ Apache และ MySQL</li>
                <li>ไปที่ <a href="http://localhost/phpmyadmin">http://localhost/phpmyadmin</a></li>
                <li>Import ไฟล์ <code>database.sql</code></li>
            </ol>
        </div>
    ');
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function getImageUrl($image, $basePrefix = '') {
    if (empty($image)) {
        return 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&w=800&q=80';
    }
    if (filter_var($image, FILTER_VALIDATE_URL)) {
        return $image;
    }
    return $basePrefix . 'uploads/' . htmlspecialchars($image);
}

function renderRatingStars($rating) {
    $rating = floatval($rating);
    $full = floor($rating);
    $half = ($rating - $full >= 0.5) ? 1 : 0;
    $empty = 5 - $full - $half;
    $html = '<span class="text-warning rating-stars">';
    for ($i = 0; $i < $full; $i++) $html .= '<i class="bi bi-star-fill"></i> ';
    if ($half) $html .= '<i class="bi bi-star-half"></i> ';
    for ($i = 0; $i < $empty; $i++) $html .= '<i class="bi bi-star"></i> ';
    $html .= '</span> <small class="fw-bold text-muted ms-1">' . number_format($rating, 1) . '</small>';
    return $html;
}