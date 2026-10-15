-- ==============================================================
-- Database Schema for Japan Tourism Web Application (Webtravel)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `webtravel_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webtravel_db`;

DROP TABLE IF EXISTS `places`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(50) DEFAULT 'bi-geo-alt',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `places` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `name_jp` VARCHAR(200) DEFAULT NULL,
  `prefecture` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `highlight` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(500) DEFAULT NULL,
  `rating` DECIMAL(2,1) DEFAULT 5.0,
  `best_season` VARCHAR(100) DEFAULT 'เที่ยวได้ตลอดปี',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_places_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin: admin / admin123
INSERT INTO `admins` (`id`, `username`, `password`, `fullname`, `email`) VALUES
(1, 'admin', '$2y$10$wT6K26F1i7z/E6pE2tWcFeTjXkU.5Z/G4J5.yJqIbgfP0hY5W9Nre', 'ผู้ดูแลระบบสูงสุด (Admin)', 'admin@japan-travel.test');

INSERT INTO `categories` (`id`, `name`, `description`, `icon`) VALUES
(1, 'ภูเขาและธรรมชาติ', 'สัมผัสทิวทัศน์อันงดงาม ทะเลสาบ ยอดเขาฟูจิ และธรรมชาติแสนบริสุทธิ์', 'bi-tree-fill'),
(2, 'วัดและศาลเจ้าโบราณ', 'ย้อนรอยประวัติศาสตร์ ความศรัทธา และสถาปัตยกรรมไม้โบราณนับพันปี', 'bi-bank2'),
(3, 'เมืองและย่านช้อปปิ้ง', 'แสงสีเสียง แฟชั่น คาเฟ่สุดชิค และย่านการค้าที่มีชีวิตชีวา', 'bi-building'),
(4, 'มรดกโลกและประวัติศาสตร์', 'หมู่บ้านโบราณ ปราสาทเก่าแก่ และสถานที่สำคัญทางมรดกโลก UNESCO', 'bi-shield-fill-check'),
(5, 'ออนเซ็นและวัฒนธรรม', 'แช่น้ำแร่ธรรมชาติ พักผ่อนสไตล์เรียวกัง สวมชุดยูกาตะ', 'bi-water');

INSERT INTO `places` (`id`, `category_id`, `name`, `name_jp`, `prefecture`, `description`, `highlight`, `image`, `rating`, `best_season`) VALUES
(1, 1, 'ภูเขาไฟฟูจิ (Mount Fuji)', '富士山 (Fujisan)', 'ชิซูโอกะ / ยามานาชิ', 'ภูเขาไฟที่สูงที่สุดในประเทศญี่ปุ่น สูงถึง 3,776 เมตร เป็นสัญลักษณ์แห่งแดนอาทิตย์อุทัยและได้รับการขึ้นทะเบียนเป็นมรดกโลกทางวัฒนธรรม วิวรอบทะเลสาบคาวากุจิโกะสะท้อนภาพภูเขาไฟฟูจิอย่างงดงามตระการตาตลอดทั้งปี', 'สัญลักษณ์อันดับหนึ่งของญี่ปุ่น ทะเลสาบทั้งห้า และจุดชมวิวฟูจิสุดอลังการ', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=1200&q=80', 5.0, 'ฤดูใบไม้ผลิ และ ฤดูหนาว'),
(2, 2, 'ศาลเจ้าฟูชิมิ อินาริ (Fushimi Inari Shrine)', '伏見稲荷大社', 'เกียว托 (Kyoto)', 'ศาลเจ้าเสาโทริอิสีแดงหมื่นต้นอันเลื่องชื่อ ตั้งอยู่ทางตอนใต้ของเมืองเกียวโต สร้างขึ้นเพื่ออุทิศแด่เทพอินาริ เทพแห่งกสิกรรมและความเจริญรุ่งเรือง เสาโทริอิเรียงรายทอดยาวขึ้นไปบนยอดเขาอินาริเป็นระยะทางกว่า 4 กิโลเมตร', 'อุโมงค์เสาโทริอิสีแดงพันๆ ต้นและรูปปั้นสุนัขจิ้งจอกผู้ส่งสาส์น', 'https://images.unsplash.com/photo-1478436127897-769e00d0c715?auto=format&fit=crop&w=1200&q=80', 4.9, 'เที่ยวได้ตลอดทั้งปี'),
(3, 3, 'ย่านชิบูย่าและห้าแยกชิบูย่า (Shibuya Crossing)', '渋谷', 'โตเกียว (Tokyo)', 'ห้าแยกที่มีผู้คนข้ามถนนหนาแน่นที่สุดในโลก ใจกลางมหานครโตเกียว เต็มไปด้วยห้างสรรพสินค้า ป้ายไฟ LED ยักษ์ แฟชั่นทันสมัย คาเฟ่ และรูปปั้นสุนัขผู้ซื่อสัตย์ฮาจิโกะ', 'ห้าแยกคนข้ามที่คึกคักที่สุดในโลก และแลนด์มาร์กแฟชั่นโตเกียว', 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&w=1200&q=80', 4.8, 'เที่ยวได้ตลอดทั้งปี (ชมแสงไฟยามค่ำคืน)'),
(4, 2, 'วัดคิโยมิซุเดระ หรือ วัดน้ำใส (Kiyomizu-dera)', '清水寺', 'เกียวโต (Kyoto)', 'วัดโบราณสร้างขึ้นตั้งแต่ปี ค.ศ. 778 โดดเด่นด้วยระเบียงไม้ขนาดใหญ่ที่สร้างยื่นออกมาจากหน้าผาโดยไม่ใช้ตะปูแม้แต่ตัวเดียว สามารถมองเห็นทิวทัศน์เมืองเกียวโตได้กว้างไกล มีน้ำตกโอโตวะสามสายที่เชื่อว่าดื่มแล้วสมหวัง', 'ระเบียงไม้โบราณไร้ตะปู และน้ำตกศักดิ์สิทธิ์ 3 สาย', 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?auto=format&fit=crop&w=1200&q=80', 4.9, 'ฤดูใบไม้ผลิ (ซากุระ) และ ฤดูใบไม้ร่วง'),
(5, 4, 'หมู่บ้านมรดกโลก ชิราคาวาโกะ (Shirakawa-go)', '白川郷', 'กิฟุ (Gifu)', 'หมู่บ้านชาวนาโบราณกลางหุบเขา โดดเด่นด้วยบ้านเรือนสไตล์กัสโชซึคุริ (Gassho-zukuri) หลังคามุงหญ้าทรงจั่วสูงชันคล้ายคนพนมมือ สร้างขึ้นเพื่อรับมือกับหิมะที่ตกหนักในฤดูหนาว ได้รับการยกย่องเป็นมรดกโลก', 'บ้านโบราณทรงพนมมือท่ามกลางหิมะขาวโพลนและทุ่งนาเขียวขจี', 'https://images.unsplash.com/photo-1570459027562-4a916cc6113f?auto=format&fit=crop&w=1200&q=80', 4.9, 'ฤดูหนาว (ชมหิมะและเปิดไฟ Light-up)'),
(6, 3, 'ย่านโดทงโบริ (Dotonbori)', '道頓堀', 'โอซาก้า (Osaka)', 'ย่านบันเทิง อาหารการกิน และช้อปปิ้งริมคลองโดทงโบริ สัญลักษณ์สำคัญคือป้ายกูลิโกะแมน และป้ายปูดิ้นขนาดยักษ์ เต็มไปด้วยร้านอาหารริมทางชื่อดัง เช่น ทาโกยากิ โอโคโนมิยากิ และราเมน', 'ป้ายไฟกูลิโกะรันเนอร์ริมคลอง และสตรีทฟู้ดโอซาก้า', 'https://images.unsplash.com/photo-1590559899731-a382839e5549?auto=format&fit=crop&w=1200&q=80', 4.7, 'เที่ยวได้ตลอดปี (คึกคักสุดช่วงค่ำ)'),
(7, 2, 'วัดเซ็นโซจิ หรือ วัดอาซากุสะ (Senso-ji Temple)', '浅草寺', 'โตเกียว (Tokyo)', 'วัดพุทธที่เก่าแก่ที่สุดในโตเกียว ก่อตั้งขึ้นในปี ค.ศ. 628 ทางเข้ามีโคมแดงยักษ์ประตูคามินาริมงอันเป็นเอกลักษณ์ และถนนช้อปปิ้งนากามิเสะที่จำหน่ายขนมและของที่ระลึกโบราณ', 'โคมแดงยักษ์ประตูคามินาริมง และถนนคนเดินนากามิเสะ', 'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?auto=format&fit=crop&w=1200&q=80', 4.8, 'เที่ยวได้ตลอดทั้งปี'),
(8, 5, 'กินซัง ออนเซ็น (Ginzan Onsen)', '銀山温泉', 'ยามากาตะ (Yamagata)', 'เมืองน้ำพุร้อนเก่าแก่อายุกว่า 500 ปี ตั้งอยู่ริมสองฝั่งแม่น้ำกินซัง อาคารไม้สไตล์ไทโช 3-4 ชั้นเรียงราย ให้บรรยากาศย้อนยุคราวกับหลุดเข้าไปในโลกอนิเมชั่น Spirited Away', 'เรียวกังไม้โบราณริมแม่น้ำ แช่ออนเซ็น และสะพานหิมะแสนโรแมนติก', 'https://images.unsplash.com/photo-1528164344705-475426879c0d?auto=format&fit=crop&w=1200&q=80', 5.0, 'ฤดูหนาว (หิมะตก)');
