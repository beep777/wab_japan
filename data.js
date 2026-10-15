/**
 * ระบบจัดการข้อมูล 2 โหมดอัตโนมัติ (Dual Mode Database System)
 * 1. เมื่อเปิดบน XAMPP (http://localhost/...) -> จะเชื่อมต่อฐานข้อมูล MySQL จริงผ่าน api.php และ config.php
 * 2. เมื่อเปิดแบบดับเบิลคลิกไฟล์ HTML (file:///...) -> จะทำงานผ่าน LocalStorage ทันทีโดยไม่ต้องเปิดเซิร์ฟเวอร์
 */

const DEFAULT_CATEGORIES = [
    { id: 1, name: 'ภูเขาและธรรมชาติ', description: 'สัมผัสทิวทัศน์อันงดงาม ทะเลสาบ ยอดเขาฟูจิ และธรรมชาติแสนบริสุทธิ์', icon: 'bi-tree-fill' },
    { id: 2, name: 'วัดและศาลเจ้าโบราณ', description: 'ย้อนรอยประวัติศาสตร์ ความศรัทธา และสถาปัตยกรรมไม้โบราณนับพันปี', icon: 'bi-bank2' },
    { id: 3, name: 'เมืองและย่านช้อปปิ้ง', description: 'แสงสีเสียง แฟชั่น คาเฟ่สุดชิค และย่านการค้าที่มีชีวิตชีวา', icon: 'bi-building' },
    { id: 4, name: 'มรดกโลกและประวัติศาสตร์', description: 'หมู่บ้านโบราณ ปราสาทเก่าแก่ และสถานที่สำคัญทางมรดกโลก UNESCO', icon: 'bi-shield-fill-check' },
    { id: 5, name: 'ออนเซ็นและวัฒนธรรม', description: 'แช่น้ำแร่ธรรมชาติ พักผ่อนสไตล์เรียวกัง สวมชุดยูกาตะ', icon: 'bi-water' }
];

const DEFAULT_PLACES = [
    {
        id: 1,
        category_id: 1,
        name: 'ภูเขาไฟฟูจิ (Mount Fuji)',
        name_jp: '富士山 (Fujisan)',
        prefecture: 'ชิซูโอกะ / ยามานาชิ',
        description: 'ภูเขาไฟที่สูงที่สุดในประเทศญี่ปุ่น สูงถึง 3,776 เมตร เป็นสัญลักษณ์แห่งแดนอาทิตย์อุทัยและได้รับการขึ้นทะเบียนเป็นมรดกโลกทางวัฒนธรรม วิวรอบทะเลสาบคาวากุจิโกะสะท้อนภาพภูเขาไฟฟูจิอย่างงดงามตระการตาตลอดทั้งปี มีจุดชมวิวและกิจกรรมปีนเขาในฤดูร้อน',
        highlight: 'สัญลักษณ์อันดับหนึ่งของญี่ปุ่น ทะเลสาบทั้งห้า และจุดชมวิวฟูจิสุดอลังการ',
        image: 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=1200&q=80',
        rating: 5.0,
        best_season: 'ฤดูใบไม้ผลิ และ ฤดูหนาว'
    },
    {
        id: 2,
        category_id: 2,
        name: 'ศาลเจ้าฟูชิมิ อินาริ (Fushimi Inari Shrine)',
        name_jp: '伏見稲荷大社',
        prefecture: 'เกียวโต (Kyoto)',
        description: 'ศาลเจ้าเสาโทริอิสีแดงหมื่นต้นอันเลื่องชื่อ ตั้งอยู่ทางตอนใต้ของเมืองเกียวโต สร้างขึ้นเพื่ออุทิศแด่เทพอินาริ เทพแห่งกสิกรรมและความเจริญรุ่งเรือง เสาโทริอิเรียงรายทอดยาวขึ้นไปบนยอดเขาอินาริเป็นระยะทางกว่า 4 กิโลเมตร',
        highlight: 'อุโมงค์เสาโทริอิสีแดงพันๆ ต้นและรูปปั้นสุนัขจิ้งจอกผู้ส่งสาส์น',
        image: 'Fushimi-Inari-Taisha-Kyoto.jpg',
        rating: 4.9,
        best_season: 'เที่ยวได้ตลอดทั้งปี'
    },
    {
        id: 3,
        category_id: 3,
        name: 'ย่านชิบูย่าและห้าแยกชิบูย่า (Shibuya Crossing)',
        name_jp: '渋谷',
        prefecture: 'โตเกียว (Tokyo)',
        description: 'ห้าแยกที่มีผู้คนข้ามถนนหนาแน่นที่สุดในโลก ใจกลางมหานครโตเกียว เต็มไปด้วยห้างสรรพสินค้า ป้ายไฟ LED ยักษ์ แฟชั่นทันสมัย คาเฟ่ และรูปปั้นสุนัขผู้ซื่อสัตย์ฮาจิโกะ',
        highlight: 'ห้าแยกคนข้ามที่คึกคักที่สุดในโลก และแลนด์มาร์กแฟชั่นโตเกียว',
        image: 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&w=1200&q=80',
        rating: 4.8,
        best_season: 'เที่ยวได้ตลอดทั้งปี (ชมแสงไฟยามค่ำคืน)'
    },
    {
        id: 4,
        category_id: 2,
        name: 'วัดคิโยมิซุเดระ หรือ วัดน้ำใส (Kiyomizu-dera)',
        name_jp: '清水寺',
        prefecture: 'เกียวโต (Kyoto)',
        description: 'วัดโบราณสร้างขึ้นตั้งแต่ปี ค.ศ. 778 โดดเด่นด้วยระเบียงไม้ขนาดใหญ่ที่สร้างยื่นออกมาจากหน้าผาโดยไม่ใช้ตะปูแม้แต่ตัวเดียว สามารถมองเห็นทิวทัศน์เมืองเกียวโตได้กว้างไกล',
        highlight: 'ระเบียงไม้โบราณไร้ตะปู และน้ำตกศักดิ์สิทธิ์ 3 สาย',
        image: 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?auto=format&fit=crop&w=1200&q=80',
        rating: 4.9,
        best_season: 'ฤดูใบไม้ผลิ (ซากุระ) และ ฤดูใบไม้ร่วง'
    },
    {
        id: 5,
        category_id: 4,
        name: 'หมู่บ้านมรดกโลก ชิราคาวาโกะ (Shirakawa-go)',
        name_jp: '白川郷',
        prefecture: 'กิฟุ (Gifu)',
        description: 'หมู่บ้านชาวนาโบราณกลางหุบเขา โดดเด่นด้วยบ้านเรือนสไตล์กัสโชซึคุริ หลังคามุงหญ้าทรงจั่วสูงชันคล้ายคนพนมมือ สร้างขึ้นเพื่อรับมือกับหิมะที่ตกหนักในฤดูหนาว ได้รับการยกย่องเป็นมรดกโลก',
        highlight: 'บ้านโบราณทรงพนมมือท่ามกลางหิมะขาวโพลนและทุ่งนาเขียวขจี',
        image: 'https://images.unsplash.com/photo-1570459027562-4a916cc6113f?auto=format&fit=crop&w=1200&q=80',
        rating: 4.9,
        best_season: 'ฤดูหนาว (ชมหิมะและเปิดไฟ Light-up)'
    },
    {
        id: 6,
        category_id: 3,
        name: 'ย่านโดทงโบริ (Dotonbori)',
        name_jp: '道頓堀',
        prefecture: 'โอซาก้า (Osaka)',
        description: 'ย่านบันเทิง อาหารการกิน และช้อปปิ้งริมคลองโดทงโบริ สัญลักษณ์สำคัญคือป้ายกูลิโกะแมน และป้ายปูดิ้นขนาดยักษ์ เต็มไปด้วยร้านอาหารริมทางชื่อดัง',
        highlight: 'ป้ายไฟกูลิโกะรันเนอร์ริมคลอง และสตรีทฟู้ดโอซาก้า',
        image: 'https://images.unsplash.com/photo-1590559899731-a382839e5549?auto=format&fit=crop&w=1200&q=80',
        rating: 4.7,
        best_season: 'เที่ยวได้ตลอดปี (คึกคักสุดช่วงค่ำ)'
    },
    {
        id: 7,
        category_id: 2,
        name: 'วัดเซ็นโซจิ หรือ วัดอาซากุสะ (Senso-ji Temple)',
        name_jp: '浅草寺',
        prefecture: 'โตเกียว (Tokyo)',
        description: 'วัดพุทธที่เก่าแก่ที่สุดในโตเกียว ก่อตั้งขึ้นในปี ค.ศ. 628 ทางเข้ามีโคมแดงยักษ์ประตูคามินาริมงอันเป็นเอกลักษณ์ และถนนช้อปปิ้งนากามิเสะ',
        highlight: 'โคมแดงยักษ์ประตูคามินาริมง และถนนคนเดินนากามิเสะ',
        image: 'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?auto=format&fit=crop&w=1200&q=80',
        rating: 4.8,
        best_season: 'เที่ยวได้ตลอดทั้งปี'
    },
    {
        id: 8,
        category_id: 5,
        name: 'กินซัง ออนเซ็น (Ginzan Onsen)',
        name_jp: '銀山温泉',
        prefecture: 'ยามากาตะ (Yamagata)',
        description: 'เมืองน้ำพุร้อนเก่าแก่อายุกว่า 500 ปี ตั้งอยู่ริมสองฝั่งแม่น้ำกินซัง อาคารไม้สไตล์ไทโช 3-4 ชั้นเรียงราย ให้บรรยากาศย้อนยุค',
        highlight: 'เรียวกังไม้โบราณริมแม่น้ำ แช่ออนเซ็น และสะพานหิมะแสนโรแมนติก',
        image: 'https://images.unsplash.com/photo-1528164344705-475426879c0d?auto=format&fit=crop&w=1200&q=80',
        rating: 5.0,
        best_season: 'ฤดูหนาว (หิมะตก)'
    }
];

function isHttp() {
    return window.location.protocol.startsWith('http');
}

// ดึงข้อมูลจาก api.php (MySQL) แบบรอผลก่อน เพื่อให้ index.html เดิมใช้ได้โดยไม่ต้องแก้
function apiGet(action) {
    try {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'api.php?action=' + action + '&_=' + Date.now(), false);
        xhr.send();
        if (xhr.status !== 200) return null;
        const json = JSON.parse(xhr.responseText);
        return json.success ? json.data : null;
    } catch (e) {
        return null;
    }
}

// ถ้าเป็นชื่อไฟล์ เช่น jpan06.jpg ให้ชี้ไปที่โฟลเดอร์ uploads/
function fixImage(img) {
    if (!img) return '';
    if (/^(https?:)?\/\//.test(img) || img.indexOf('/') !== -1) return img;
    return 'uploads/' + img;
}

function getCategories() {
    if (isHttp()) {
        const dbData = apiGet('get_categories');
        if (dbData) return dbData;
    }
    const data = localStorage.getItem('webtravel_categories');
    if (!data) {
        localStorage.setItem('webtravel_categories', JSON.stringify(DEFAULT_CATEGORIES));
        return DEFAULT_CATEGORIES;
    }
    return JSON.parse(data);
}

function getPlaces() {
    if (isHttp()) {
        const dbData = apiGet('get_places');
        if (dbData) return dbData.map(p => Object.assign({}, p, { image: fixImage(p.image) }));
    }
    const data = localStorage.getItem('webtravel_places');
    let list = DEFAULT_PLACES;
    if (!data) {
        localStorage.setItem('webtravel_places', JSON.stringify(DEFAULT_PLACES));
    } else {
        try {
            list = JSON.parse(data);
        } catch (e) {
            list = DEFAULT_PLACES;
        }
    }
    return list.map(p => Object.assign({}, p, { image: fixImage(p.image) }));
}



function saveCategories(cats) {
    localStorage.setItem('webtravel_categories', JSON.stringify(cats));
}

function savePlaces(pls) {
    localStorage.setItem('webtravel_places', JSON.stringify(pls));
}

function renderStarsHtml(rating) {
    const r = parseFloat(rating) || 5;
    const full = Math.floor(r);
    const half = (r - full >= 0.5) ? 1 : 0;
    const empty = 5 - full - half;
    let html = '<span class="text-warning rating-stars">';
    for (let i = 0; i < full; i++) html += '<i class="bi bi-star-fill"></i> ';
    if (half) html += '<i class="bi bi-star-half"></i> ';
    for (let i = 0; i < empty; i++) html += '<i class="bi bi-star"></i> ';
    html += `</span> <small class="fw-bold text-muted ms-1">${r.toFixed(1)}</small>`;
    return html;
}

