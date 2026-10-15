-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2026 at 10:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webtravel_db`
--
CREATE DATABASE IF NOT EXISTS `webtravel_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `webtravel_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `fullname`) VALUES
(1, 'admin', '$2y$10$.Lmui08/zk5I3OQJ1NUtc.E4itV.AsyJ5k.CJ8MGyTAPLsqZT6mHO', 'Japan Travel Admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'bi-geo-alt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`) VALUES
(1, 'ภูเขาและธรรมชาติ', 'ทิวทัศน์อันงดงาม ทะเลสาบ และยอดเขา', 'bi-tree-fill'),
(2, 'วัดและศาลเจ้าโบราณ', 'ประวัติศาสตร์และสถาปัตยกรรมไม้โบราณ', 'bi-bank2'),
(3, 'เมืองและย่านช้อปปิ้ง', 'แสงสีเสียง แฟชั่น และคาเฟ่', 'bi-building'),
(4, 'มรดกโลกและประวัติศาสตร์', 'หมู่บ้านโบราณและปราสาทเก่าแก่', 'bi-shield-fill-check'),
(5, 'ออนเซ็นและวัฒนธรรม', 'แช่น้ำแร่ธรรมชาติและเรียวกัง', 'bi-water');

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `name_jp` varchar(200) DEFAULT NULL,
  `prefecture` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `highlight` varchar(300) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 5.0,
  `best_season` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `category_id`, `name`, `name_jp`, `prefecture`, `description`, `highlight`, `image`, `rating`, `best_season`) VALUES
(1, 1, 'ภูเขาไฟฟูจิ (Mount Fuji)', '富士山 (Fujisan)', 'ชิซูโอกะ / ยามานาชิ', 'ภูเขาไฟที่สูงที่สุดในประเทศญี่ปุ่น สูงถึง 3,776 เมตร เป็นสัญลักษณ์แห่งแดนอาทิตย์อุทัยและได้รับการขึ้นทะเบียนเป็นมรดกโลกทางวัฒนธรรม วิวรอบทะเลสาบคาวากุจิโกะสะท้อนภาพภูเขาไฟฟูจิอย่างงดงามตระการตาตลอดทั้งปี มีจุดชมวิวและกิจกรรมปีนเขาในฤดูร้อน', 'สัญลักษณ์อันดับหนึ่งของญี่ปุ่น ทะเลสาบทั้งห้า และจุดชมวิวฟูจิสุดอลังการ', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=1200&q=80', 5.0, 'ฤดูใบไม้ผลิ และ ฤดูหนาว'),
(2, 2, 'ศาลเจ้าฟูชิมิ อินาริ (Fushimi Inari Shrine)', '伏見稲荷大社', 'เกียวโต (Kyoto)', 'ศาลเจ้าเสาโทริอิสีแดงหมื่นต้นอันเลื่องชื่อ ตั้งอยู่ทางตอนใต้ของเมืองเกียวโต สร้างขึ้นเพื่ออุทิศแด่เทพอินาริ เทพแห่งกสิกรรมและความเจริญรุ่งเรือง เสาโทริอิเรียงรายทอดยาวขึ้นไปบนยอดเขาอินาริเป็นระยะทางกว่า 4 กิโลเมตร', 'อุโมงค์เสาโทริอิสีแดงพันๆ ต้นและรูปปั้นสุนัขจิ้งจอกผู้ส่งสาส์น', 'jpan06.jpg', 4.9, 'เที่ยวได้ตลอดทั้งปี'),
(3, 3, 'ย่านชิบูย่าและห้าแยกชิบูย่า (Shibuya Crossing)', '渋谷', 'โตเกียว (Tokyo)', 'ห้าแยกที่มีผู้คนข้ามถนนหนาแน่นที่สุดในโลก ใจกลางมหานครโตเกียว เต็มไปด้วยห้างสรรพสินค้า ป้ายไฟ LED ยักษ์ แฟชั่นทันสมัย คาเฟ่ และรูปปั้นสุนัขผู้ซื่อสัตย์ฮาจิโกะ', 'ห้าแยกคนข้ามที่คึกคักที่สุดในโลก และแลนด์มาร์กแฟชั่นโตเกียว', 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&w=1200&q=80', 4.8, 'เที่ยวได้ตลอดทั้งปี (ชมแสงไฟยามค่ำคืน)'),
(4, 2, 'วัดคิโยมิซุเดระ หรือ วัดน้ำใส (Kiyomizu-dera)', '清水寺', 'เกียวโต (Kyoto)', 'วัดโบราณสร้างขึ้นตั้งแต่ปี ค.ศ. 778 โดดเด่นด้วยระเบียงไม้ขนาดใหญ่ที่สร้างยื่นออกมาจากหน้าผาโดยไม่ใช้ตะปูแม้แต่ตัวเดียว สามารถมองเห็นทิวทัศน์เมืองเกียวโตได้กว้างไกล', 'ระเบียงไม้โบราณไร้ตะปู และน้ำตกศักดิ์สิทธิ์ 3 สาย', 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?auto=format&fit=crop&w=1200&q=80', 4.9, 'ฤดูใบไม้ผลิ (ซากุระ) และ ฤดูใบไม้ร่วง'),
(5, 4, 'หมู่บ้านมรดกโลก ชิราคาวาโกะ (Shirakawa-go)', '白川郷', 'กิฟุ (Gifu)', 'หมู่บ้านชาวนาโบราณกลางหุบเขา โดดเด่นด้วยบ้านเรือนสไตล์กัสโชซึคุริ หลังคามุงหญ้าทรงจั่วสูงชันคล้ายคนพนมมือ สร้างขึ้นเพื่อรับมือกับหิมะที่ตกหนักในฤดูหนาว ได้รับการยกย่องเป็นมรดกโลก', 'บ้านโบราณทรงพนมมือท่ามกลางหิมะขาวโพลนและทุ่งนาเขียวขจี', 'https://images.unsplash.com/photo-1570459027562-4a916cc6113f?auto=format&fit=crop&w=1200&q=80', 4.9, 'ฤดูหนาว (ชมหิมะและเปิดไฟ Light-up)'),
(6, 3, 'ย่านโดทงโบริ (Dotonbori)', '道頓堀', 'โอซาก้า (Osaka)', 'ย่านบันเทิง อาหารการกิน และช้อปปิ้งริมคลองโดทงโบริ สัญลักษณ์สำคัญคือป้ายกูลิโกะแมน และป้ายปูดิ้นขนาดยักษ์ เต็มไปด้วยร้านอาหารริมทางชื่อดัง', 'ป้ายไฟกูลิโกะรันเนอร์ริมคลอง และสตรีทฟู้ดโอซาก้า', 'https://images.unsplash.com/photo-1590559899731-a382839e5549?auto=format&fit=crop&w=1200&q=80', 4.7, 'เที่ยวได้ตลอดปี (คึกคักสุดช่วงค่ำ)'),
(7, 2, 'วัดเซ็นโซจิ หรือ วัดอาซากุสะ (Senso-ji Temple)', '浅草寺', 'โตเกียว (Tokyo)', 'วัดพุทธที่เก่าแก่ที่สุดในโตเกียว ก่อตั้งขึ้นในปี ค.ศ. 628 ทางเข้ามีโคมแดงยักษ์ประตูคามินาริมงอันเป็นเอกลักษณ์ และถนนช้อปปิ้งนากามิเสะ', 'โคมแดงยักษ์ประตูคามินาริมง และถนนคนเดินนากามิเสะ', 'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?auto=format&fit=crop&w=1200&q=80', 4.8, 'เที่ยวได้ตลอดทั้งปี'),
(8, 5, 'กินซัง ออนเซ็น (Ginzan Onsen)', '銀山温泉', 'ยามากาตะ (Yamagata)', 'เมืองน้ำพุร้อนเก่าแก่อายุกว่า 500 ปี ตั้งอยู่ริมสองฝั่งแม่น้ำกินซัง อาคารไม้สไตล์ไทโช 3-4 ชั้นเรียงราย ให้บรรยากาศย้อนยุค', 'เรียวกังไม้โบราณริมแม่น้ำ แช่ออนเซ็น และสะพานหิมะแสนโรแมนติก', 'https://images.unsplash.com/photo-1528164344705-475426879c0d?auto=format&fit=crop&w=1200&q=80', 5.0, 'ฤดูหนาว (หิมะตก)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
