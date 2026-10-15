# 🌸 Japan Wonders - ระบบเว็บไซต์ท่องเที่ยวประเทศญี่ปุ่น

โครงงานเว็บไซต์นำเสนอแหล่งท่องเที่ยวประเทศญี่ปุ่น ออกแบบและพัฒนาครบทั้งฝั่ง **Front-end (หน้าบ้าน)** และ **Back-end (หลังบ้าน)**

## 📂 ไฟล์ในโฟลเดอร์นี้
- `index.html` : หน้าแรกหน้าบ้าน (ดับเบิลคลิกเปิดได้ทันที)
- `place_detail.html` : หน้ารายละเอียดสถานที่ (HTML)
- `data.js` : ฐานข้อมูลจำลอง LocalStorage + ฟังก์ชันเชื่อมโยง
- `admin/login.php` : หน้าเข้าสู่ระบบแอดมิน (HTML)
- `admin/index.html` : แดชบอร์ดสรุปสถิติ (HTML)
- `admin/places.html` : จัดการสถานที่ท่องเที่ยว CRUD (HTML)
- `admin/place_add.html` : เพิ่มสถานที่ท่องเที่ยว (HTML)
- `admin/place_edit.html` : แก้ไขสถานที่ท่องเที่ยว (HTML)
- `admin/categories.html` : จัดการหมวดหมู่ CRUD (HTML)

- `index.php` : หน้าแรกหน้าบ้าน (PHP + MySQL)
- `place_detail.php` : หน้ารายละเอียดสถานที่ (PHP + MySQL)
- `config.php` : เชื่อมต่อฐานข้อมูล PDO MySQL
- `database.sql` : ไฟล์สร้างฐานข้อมูล 3 ตารางสำหรับ XAMPP phpMyAdmin
- `api.php` : ตัวเชื่อมต่อ REST API ระหว่าง HTML และ MySQL
- `admin/*.php` : ระบบหลังบ้านฝั่ง PHP

## 🔑 บัญชีเข้าสู่ระบบแอดมิน
- **Username:** `admin`
- **Password:** `admin123`
