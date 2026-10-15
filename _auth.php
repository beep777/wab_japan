<?php
require_once __DIR__ . '/../config.php';

function requireLogin() {
    if (!isAdminLoggedIn()) { header('Location: login.php'); exit; }
}
function csrfToken() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function checkCsrf() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        die('Invalid CSRF token');
    }
}