<?php
/**
 * ==============================================================
 * API Bridge สำหรับเชื่อมต่อระหว่างหน้าเว็บ HTML และฐานข้อมูล MySQL (api.php)
 * รองรับการทำงานของหน้า HTML ทุกหน้าในการดึงข้อมูล บันทึก แก้ไข ลบ (CRUD)
 * ==============================================================
 */

header('Content-Type: application/json; charset=utf-8');

// นำเข้าไฟล์เชื่อมต่อฐานข้อมูล config.php
require_once __DIR__ . '/config.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

$response = [
    'success' => false,
    'message' => 'Action ไม่ถูกต้อง',
    'data' => null
];

try {
    switch ($action) {
        
        // 1. ดึงรายการหมวดหมู่ทั้งหมด
        case 'get_categories':
            $stmt = $pdo->query("
                SELECT c.*, COUNT(p.id) as total_places 
                FROM `categories` c 
                LEFT JOIN `places` p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.id ASC
            ");
            $response['success'] = true;
            $response['data'] = $stmt->fetchAll();
            $response['message'] = 'ดึงข้อมูลหมวดหมู่สำเร็จ';
            break;

        // 2. ดึงรายการสถานที่ท่องเที่ยวทั้งหมด (พร้อมตัวกรอง)
        case 'get_places':
            $catId = intval($_GET['cat_id'] ?? 0);
            $search = trim($_GET['search'] ?? '');

            $sql = "
                SELECT p.*, c.name AS category_name, c.icon AS category_icon 
                FROM `places` p 
                INNER JOIN `categories` c ON p.category_id = c.id 
                WHERE 1=1
            ";
            $params = [];

            if ($catId > 0) {
                $sql .= " AND p.category_id = :cat_id";
                $params[':cat_id'] = $catId;
            }
            if (!empty($search)) {
                $sql .= " AND (p.name LIKE :kw OR p.name_jp LIKE :kw OR p.prefecture LIKE :kw OR p.highlight LIKE :kw)";
                $params[':kw'] = "%{$search}%";
            }
            $sql .= " ORDER BY p.id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $response['success'] = true;
            $response['data'] = $stmt->fetchAll();
            $response['message'] = 'ดึงข้อมูลสถานที่สำเร็จ';
            break;

        // 3. ดึงข้อมูลสถานที่เดี่ยวตาม ID
        case 'get_place':
            $id = intval($_GET['id'] ?? 0);
            $stmt = $pdo->prepare("
                SELECT p.*, c.name AS category_name, c.icon AS category_icon, c.description AS category_desc
                FROM `places` p 
                INNER JOIN `categories` c ON p.category_id = c.id 
                WHERE p.id = :id LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $place = $stmt->fetch();

            if ($place) {
                $response['success'] = true;
                $response['data'] = $place;
                $response['message'] = 'พบข้อมูลสถานที่';
            } else {
                $response['message'] = 'ไม่พบสถานที่ท่องเที่ยวที่ระบุ';
            }
            break;

        // 4. ตรวจสอบสถานะการล็อกอิน (Session)
        case 'check_auth':
            $response['success'] = true;
            $response['data'] = [
                'logged_in' => isAdminLoggedIn(),
                'username' => $_SESSION['admin_username'] ?? null,
                'fullname' => $_SESSION['admin_fullname'] ?? null
            ];
            break;

        // 5. เข้าสู่ระบบแอดมิน (Login + Session)
        case 'login':
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $stmt = $pdo->prepare("SELECT * FROM `admins` WHERE `username` = :u LIMIT 1");
            $stmt->execute([':u' => $username]);
            $admin = $stmt->fetch();

            if ($admin && (
                password_verify($password, $admin['password']) || 
                $admin['password'] === md5($password) || 
                $admin['password'] === $password
            )) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_fullname'] = $admin['fullname'];

                $response['success'] = true;
                $response['message'] = 'เข้าสู่ระบบสำเร็จ';
                $response['data'] = [
                    'fullname' => $admin['fullname'],
                    'username' => $admin['username']
                ];
            } else {
                $response['message'] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            }
            break;

        // 6. ออกจากระบบ (Logout)
        case 'logout':
            unset($_SESSION['admin_logged_in']);
            unset($_SESSION['admin_id']);
            unset($_SESSION['admin_username']);
            unset($_SESSION['admin_fullname']);
            session_destroy();
            $response['success'] = true;
            $response['message'] = 'ออกจากระบบเรียบร้อยแล้ว';
            break;

        // 7. เพิ่มหรือแก้ไขสถานที่ท่องเที่ยว (Save Place)
        case 'save_place':
            if (!isAdminLoggedIn()) {
                $response['message'] = 'คุณไม่มีสิทธิ์ดำเนินการ กรุณาเข้าสู่ระบบก่อน';
                break;
            }

            $id = intval($_POST['id'] ?? 0);
            $catId = intval($_POST['category_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $nameJp = trim($_POST['name_jp'] ?? '');
            $prefecture = trim($_POST['prefecture'] ?? '');
            $rating = floatval($_POST['rating'] ?? 5.0);
            $bestSeason = trim($_POST['best_season'] ?? '');
            $highlight = trim($_POST['highlight'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $image = trim($_POST['image'] ?? '');

            if (empty($name) || $catId <= 0 || empty($prefecture) || empty($desc)) {
                $response['message'] = 'กรุณากรอกข้อมูลสำคัญให้ครบถ้วน';
                break;
            }

            if ($id > 0) {
                // แก้ไขข้อมูล (Update)
                $sql = "UPDATE `places` SET 
                        `category_id` = :cat_id,
                        `name` = :name,
                        `name_jp` = :name_jp,
                        `prefecture` = :prefecture,
                        `description` = :desc,
                        `highlight` = :highlight,
                        `image` = :image,
                        `rating` = :rating,
                        `best_season` = :season
                        WHERE `id` = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':cat_id' => $catId,
                    ':name' => $name,
                    ':name_jp' => $nameJp,
                    ':prefecture' => $prefecture,
                    ':desc' => $desc,
                    ':highlight' => $highlight,
                    ':image' => $image,
                    ':rating' => $rating,
                    ':season' => $bestSeason,
                    ':id' => $id
                ]);
                $response['success'] = true;
                $response['message'] = 'แก้ไขสถานที่ท่องเที่ยวเรียบร้อยแล้ว';
            } else {
                // เพิ่มข้อมูลใหม่ (Create)
                $sql = "INSERT INTO `places` 
                        (`category_id`, `name`, `name_jp`, `prefecture`, `description`, `highlight`, `image`, `rating`, `best_season`) 
                        VALUES 
                        (:cat_id, :name, :name_jp, :prefecture, :desc, :highlight, :image, :rating, :season)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':cat_id' => $catId,
                    ':name' => $name,
                    ':name_jp' => $nameJp,
                    ':prefecture' => $prefecture,
                    ':desc' => $desc,
                    ':highlight' => $highlight,
                    ':image' => $image,
                    ':rating' => $rating,
                    ':season' => $bestSeason
                ]);
                $response['success'] = true;
                $response['message'] = 'เพิ่มสถานที่ท่องเที่ยวใหม่เรียบร้อยแล้ว';
            }
            break;

        // 8. ลบสถานที่ท่องเที่ยว (Delete Place)
        case 'delete_place':
            if (!isAdminLoggedIn()) {
                $response['message'] = 'คุณไม่มีสิทธิ์ดำเนินการ';
                break;
            }
            $id = intval($_POST['id'] ?? ($_GET['id'] ?? 0));
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM `places` WHERE `id` = :id");
                $stmt->execute([':id' => $id]);
                $response['success'] = true;
                $response['message'] = 'ลบสถานที่ท่องเที่ยวเรียบร้อยแล้ว';
            }
            break;

        // 9. เพิ่มหมวดหมู่ใหม่ (Create Category)
        case 'save_category':
            if (!isAdminLoggedIn()) {
                $response['message'] = 'คุณไม่มีสิทธิ์ดำเนินการ';
                break;
            }
            $name = trim($_POST['name'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $icon = trim($_POST['icon'] ?? 'bi-geo-alt');

            if (!empty($name)) {
                $stmt = $pdo->prepare("INSERT INTO `categories` (`name`, `description`, `icon`) VALUES (:name, :desc, :icon)");
                $stmt->execute([':name' => $name, ':desc' => $desc, ':icon' => $icon]);
                $response['success'] = true;
                $response['message'] = 'เพิ่มหมวดหมู่สำเร็จ';
            }
            break;

        // 10. ลบหมวดหมู่ (Delete Category)
        case 'delete_category':
            if (!isAdminLoggedIn()) {
                $response['message'] = 'คุณไม่มีสิทธิ์ดำเนินการ';
                break;
            }
            $id = intval($_POST['id'] ?? ($_GET['id'] ?? 0));
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM `categories` WHERE `id` = :id");
                $stmt->execute([':id' => $id]);
                $response['success'] = true;
                $response['message'] = 'ลบหมวดหมู่สำเร็จ';
            }
            break;
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Database Error: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;

