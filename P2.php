<?php
// --- 1. ส่วนเชื่อมต่อ Database (บรรทัดนี้ต้องอยู่บนสุด!) ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aplatform_db"; // <--- มึงต้องเปลี่ยนตรงนี้ให้ตรงกับชื่อ DB มึง

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

// เช็กการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query ดึงข้อมูลมหาลัย
$sql = "SELECT * FROM universities ORDER BY uni_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html> 
<html lang="th"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thai University Board - Explore Your Future</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; margin: 0; background-color: #f5f5f5; }
        .hero-banner { display: flex; height: 380px; width: 100%; background: white; position: relative; overflow: hidden; }
        .red-design-part {
            position: absolute;
            right: 0; top: 0; height: 100%; width: 60%;
            clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
            background: linear-gradient(115deg, #B1081C 4.5%, #B1081C 100%);
        }
        .uni-card { transition: all 0.2s ease-in-out; border: 1px solid rgba(0,0,0,0.05); }
        .uni-card:hover { border-color: #B1081C; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); }
        .btn-apply { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); background-color: #B1081C; display: block; text-align: center; }
        .btn-apply:hover { background-color: #8e0616; transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(177, 8, 28, 0.4); letter-spacing: 1.5px; }
        .screen-fade { opacity: 0; transform: translateY(30px); transition: opacity 1s ease, transform 1s ease; }
        .screen-fade.active { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.4s; }
        
        /* สไตล์ Footer แบบเลื่อนที่มึงอยากได้ */
        .ultra-footer { background: #0a0a0a; padding: 100px 0; }
        .smooth-wrapper { display: flex; width: 100%; height: 480px; gap: 15px; }
        .smooth-panel { position: relative; flex: 1; background: #111; border-radius: 30px; overflow: hidden; transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1); border: 1px solid rgba(255, 255, 255, 0.03); }
        .smooth-panel:hover { flex: 2.5; background: #161616; border-color: rgba(177, 8, 28, 0.3); }
        .bg-number { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 140px; font-weight: 900; color: rgba(255, 255, 255, 0.02); z-index: 1; }
        .title-box { position: absolute; top: 50%; left: -100%; transform: translateY(-50%); transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); z-index: 2; padding-left: 50px; width: 100%; }
        .smooth-panel:hover .title-box { left: 0; }
        .inner-title { font-size: 38px; font-weight: 800; color: #B1081C; margin-bottom: 20px; }
        .footer-sub-links a { color: #888; font-size: 16px; transition: 0.3s; }
        .footer-sub-links a:hover { color: #fff; transform: translateX(10px); display: inline-block; }
        
         
        .job-card { transition: all 0.2s ease-in-out; border: 1px solid rgba(0,0,0,0.05); }
        .job-card:hover { border-color: #B1081C; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); }
        
        /* สไตล์สำหรับปุ่มเพิ่มเติมที่มึงขอ */
        .job-card.hidden-card { display: none; } /* ซ่อนการ์ดที่ยังไม่ถึงคิว */
        
        .btn-apply { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background-color: #B1081C; }
        .btn-apply:hover {
            background-color: #8e0616;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(177, 8, 28, 0.4);
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <header class="w-full bg-white border-b border-gray-200 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
            <div class="flex items-center space-x-12">
              <a href="index.php" class="w-10 h-10 bg-[#B1081C] rounded-lg flex items-center justify-center font-black text-white text-xl">A</a>
                <nav class="flex space-x-8 text-[14px] font-bold text-gray-500">
                    <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                    <a href="P1.php" class="hover:text-[#B1081C] transition">หมวดหมู่การหางาน</a>
                    <a href="P2.php" class="text-[#B1081C] border-b-2 border-[#B1081C] pb-1">หมวดหมู่มหาวิทยาลัยในไทย</a>
                </nav>
            </div>
            <input type="text" placeholder="ค้นหาชื่อมหาวิทยาลัย..." class="w-72 px-4 py-1.5 rounded-full border text-sm bg-white focus:outline-none focus:border-[#B1081C]">
        </header>

     <section class="hero-banner shadow-sm mb-16 screen-fade delay-1 bg-white relative overflow-hidden h-[420px]">
        <div class="w-1/2 flex flex-col justify-center pl-24 z-30">
            <div class="flex items-center space-x-3 mb-5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#B1081C] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#B1081C]"></span>
                </span>
                <span class="text-[#B1081C] text-[10px] font-black uppercase tracking-[4px]">Verified Platform 2026</span>
            </div>
            
        <h1 class="text-[70px] font-extrabold leading-[1.3] text-[#1a1a1a]">
            มหาวิทยาลัย <br> 
            <span class="text-[#B1081C] relative inline-block">
                ชั้นนำทั่วไทย
                <div class="absolute -bottom-1 left-0 w-full h-[6px] bg-[#B1081C]/10 -z-10"></div>
            </span>
        </h1>
            <p class="text-gray-400 mt-6 font-bold uppercase tracking-[3px] text-xs flex items-center">
                <span class="w-10 h-[1px] bg-[#B1081C] mr-4"></span>
                Thai University & Education Hub
            </p>
        </div>

        <div class="red-design-part">
            <div class="absolute inset-0 z-0 opacity-40 mix-blend-overlay scale-110">
            <img src="https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1200&auto=format&fit=crop" class="w-full h-full object-cover grayscale" alt="Campus">
                    class="w-full h-full object-cover grayscale" alt="University Campus">
            </div>

            <div class="absolute inset-0 opacity-[0.1] z-10" 
                style="background-image: linear-gradient(rgba(255,255,255,0.4) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 35px 35px;">
            </div>

            <div class="absolute inset-0 z-20">
                <div class="absolute top-1/4 left-1/4 w-[1px] h-40 bg-gradient-to-b from-white/0 via-white/40 to-white/0 -rotate-45"></div>
                <div class="absolute top-1/3 left-1/3 w-2 h-2 bg-white rounded-full blur-[2px] animate-pulse"></div>
                
                <div class="absolute bottom-1/4 right-1/4 w-[1px] h-32 bg-gradient-to-t from-white/0 via-white/30 to-white/0 rotate-12"></div>
                <div class="absolute bottom-1/4 right-[28%] w-1 h-1 bg-white/60 rounded-full"></div>
            </div>

            <div class="absolute bottom-8 right-12 z-20">
                <div class="text-white/10 font-black text-7xl italic select-none">EDU</div>
            </div>
        </div>

        <div class="absolute top-0 right-[60%] h-full w-[1px] bg-gradient-to-b from-transparent via-gray-100 to-transparent z-10"></div>
    </section>

   <main class="max-w-[1400px] mx-auto px-10 pb-20 screen-fade delay-2">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="uni-card bg-white rounded-2xl shadow-md overflow-hidden flex flex-col">
                    
                    <img src="uploads/<?php echo $row['uni_img']; ?>" 
                         class="w-full h-52 object-cover" 
                         onerror="this.src='https://images.unsplash.com/photo-1541339907198-e08756ebafe3?w=800'">
                    
                    <div class="p-8 flex flex-col flex-grow">
                        <span class="text-[#B1081C] font-extrabold text-xs uppercase tracking-widest">
                            📍 <?php echo $row['uni_location']; ?>
                        </span>
                        
                        <h3 class="text-2xl font-extrabold mt-2 text-[#1a1a1a]">
                            <?php echo $row['uni_name']; ?>
                        </h3>
                        
                        <p class="text-sm text-gray-500 mt-4 mb-8 leading-relaxed">
                            <?php echo mb_strimwidth($row['uni_description'], 0, 120, "..."); ?>
                        </p>

                        <a href="P21.php?uni_id=<?php echo $row['uni_id']; ?>" 
                           class="btn-apply mt-auto w-full text-white py-4 rounded-xl font-bold block text-center uppercase text-sm">
                            ดูรายละเอียดคณะ
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-20 text-gray-400">ยังไม่มีข้อมูลมหาวิทยาลัยในระบบ</div>
        <?php endif; ?>

    </div>
</main>
   <style>
    /* คอนเทนเนอร์หลัก */
    .ultra-footer {
        background: #0a0a0a;
        padding: 80px 0;
        font-family: 'Sarabun', sans-serif;
    }

    /* ตัวหุ้มการ์ดแบบกำหนดความสูงตายตัว */
    .smooth-wrapper {
        display: flex;
        width: 100%;
        height: 450px;
        gap: 15px;
        /* สำคัญ: ให้ฝั่งลูกขยายเต็มพื้นที่ */
        align-items: stretch; 
    }

    /* การ์ดแต่ละใบ */
    .smooth-panel {
        position: relative;
        /* เริ่มต้นที่ความกว้างเท่าๆ กัน */
        width: 25%; 
        background: #161616;
        border-radius: 24px;
        overflow: hidden;
        cursor: pointer;
        /* ใช้ Cubic Bezier แบบนุ่มนวลพิเศษ */
        transition: width 0.7s cubic-bezier(0.23, 1, 0.32, 1), 
                    background 0.5s ease, 
                    transform 0.5s ease;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* เมื่อ Hover: การ์ดที่เลือกจะกางออก */
    .smooth-wrapper:hover .smooth-panel {
        width: 15%; /* ใบอื่นหดลงเหลือ 15% */
        filter: grayscale(0.5);
    }

    .smooth-wrapper .smooth-panel:hover {
        width: 55%; /* ใบที่เลือกกางออกไป 55% */
        background: #1c1c1c;
        filter: grayscale(0);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    /* พื้นหลังรูปภาพหรือ Gradient เพิ่มความลึก */
    .panel-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(177, 8, 28, 0) 0%, rgba(177, 8, 28, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .smooth-panel:hover .panel-bg {
        opacity: 1;
    }

    /* ตัวเลข (Background Number) */
    /* ตัวเลข: ปรับให้อยู่กึ่งกลางแผ่นการ์ดตลอดเวลา */
.bg-number {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* ดึงเข้าจุดศูนย์กลางเป๊ะ */
    font-size: 160px; /* ขนาดใหญ่สะใจ */
    font-weight: 900;
    color: rgba(255, 255, 255, 0.02); /* จางๆ ตอนปกติ */
    transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    z-index: 1;
    pointer-events: none; /* ป้องกันเลขไปบังการคลิกลิงก์ */
    white-space: nowrap; /* กันเลขขึ้นบรรทัดใหม่จนเพี้ยน */
}

/* เมื่อ Hover: ให้เลขชัดขึ้นและขยายขนาดเล็กน้อย */
.smooth-panel:hover .bg-number {
    color: rgba(177, 8, 28, 0.08); /* สีแดงจางๆ */
    transform: translate(-50%, -50%) scale(1.1); /* ขยายจากจุดศูนย์กลาง */
}

/* ปรับ Title Box ให้มาอยู่เหนือเลขเมื่อกางออก */
.title-box {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    z-index: 2; /* ให้อยู่เหนือเลข */
    padding: 0 40px;
    opacity: 0; /* ซ่อนไว้ก่อน */
    visibility: hidden;
    transition: all 0.5s ease;
}

/* เมื่อ Hover: ให้เนื้อหาแสดงออกมา */
.smooth-panel:hover .title-box {
    opacity: 1;
    visibility: visible;
}

    /* คำบรรยาย: ค่อยๆ Fade in และ Slide up */
    .inner-desc {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease 0.2s; /* มี Delay  */
        color: #a0a0a0;
        max-width: 400px;
        line-height: 1.6;
    }

    .smooth-panel:hover .inner-desc {
        opacity: 1;
        transform: translateY(0);
    }

    /* ปุ่ม Link */
    .inner-link {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 25px;
        background: #B1081C;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        text-decoration: none;
        font-size: 14px;
        transform: scale(0.9);
        transition: 0.3s;
    }
    .inner-link:hover {
        transform: scale(1);
        background: #d10a22;
    }
</style>

<style>
    /* คอนเทนเนอร์หลัก */
    .ultra-footer {
        background: #0a0a0a;
        padding: 100px 0;
        font-family: 'Sarabun', sans-serif;
    }

    .smooth-wrapper {
        display: flex;
        width: 100%;
        height: 400px;
        gap: 15px;
        align-items: stretch; 
    }

    /* การ์ดแต่ละใบ */
    .smooth-panel {
        position: relative;
        flex: 1; /* ใช้ Flex แทน Width เพื่อความสมูทในการกระจายตัว */
        background: #111;
        border-radius: 30px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* เมื่อ Hover การ์ดใบนั้นจะเด่นขึ้น */
    .smooth-wrapper .smooth-panel:hover {
        flex: 2; /* ขยายการ์ดนิดหน่อยให้มีพื้นที่โชว์ตัวหนังสือ */
        background: #161616;
        border-color: rgba(177, 8, 28, 0.3);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    /* ตัวเลข: อยู่กลางการ์ดตลอดเวลา */
    .bg-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); /* จัดให้อยู่กึ่งกลางเป๊ะ */
        font-size: 120px;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.03); /* จางๆ ตอนปกติ */
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 1;
    }

    .smooth-panel:hover .bg-number {
        color: rgba(177, 8, 28, 0.1); /* เข้มขึ้นตอน Hover */
        transform: translate(-50%, -50%) scale(1.1);
    }

    /* ส่วนหุ้มหัวเรื่อง: สำหรับทำเอฟเฟกต์สไลด์ */
    .title-box {
        position: absolute;
        top: 50%;
        left: -100%; /* ซ่อนไว้ด้านซ้ายนอกการ์ด */
        transform: translateY(-50%);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 2;
        padding-left: 40px;
        width: 100%;
    }

    /* เมื่อ Hover: ให้หัวเรื่องวิ่งออกมา */
    .smooth-panel:hover .title-box {
        left: 0; /* กลับเข้ามาในตำแหน่งปกติ */
    }

    .inner-title {
        font-size: 42px;
        font-weight: 800;
        color: #B1081C;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }

    /* คำบรรยาย: ค่อยๆ Fade ตามมา */
    .inner-desc {
        color: #888;
        font-size: 14px;
        margin-top: 5px;
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease 0.2s; /* Delay ให้ Title ออกมาก่อน */
    }

    .smooth-panel:hover .inner-desc {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<style>
    .site-footer {
        background: #0a0a0a;
        padding: 80px 0 40px 0;
        font-family: 'Sarabun', sans-serif;
        color: #fff;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* มือถือโชว์ 2 คอลัมน์ */
        gap: 40px;
    }

    @media (min-width: 1024px) {
        .footer-grid { grid-template-columns: repeat(4, 1fr); } /* จอคอมโชว์ 4 คอลัมน์ */
    }

    /* สไตล์หัวข้อหลัก */
    .footer-title {
        font-size: 18px;
        font-weight: 800;
        color: #B1081C; 
        margin-bottom: 25px;
        letter-spacing: 1px;
        text-transform: uppercase;
        position: relative;
    }
    
    /* ขีดเส้นใต้หัวข้อจางๆ */
    .footer-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 30px;
        height: 2px;
        background: #B1081C;
    }

    /* สไตล์รายการลิงก์ */
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: #888;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block; /* เพื่อให้ transform ทำงาน */
    }

    /* Hover Effect: สไลด์ออกไปทางขวานิดนึงและสีสว่างขึ้น */
    .footer-links a:hover {
        color: #fff;
        transform: translateX(8px);
    }

    .footer-bottom {
        margin-top: 60px;
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        text-align: center;
    }

    .footer-bottom p {
        color: #444;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
    }
</style>

<style>
    /* คอนเทนเนอร์หลัก */
    .ultra-footer {
        background: #0a0a0a;
        padding: 100px 0;
        font-family: 'Sarabun', sans-serif;
    }

    .smooth-wrapper {
        display: flex;
        width: 100%;
        height: 480px; /* เพิ่มความสูงนิดนึงให้พอดีกับลิสต์ลิงก์ */
        gap: 15px;
        align-items: stretch; 
    }

    /* การ์ดแต่ละใบ */
    .smooth-panel {
        position: relative;
        flex: 1;
        background: #111;
        border-radius: 30px;
        overflow: hidden;
        cursor: default; /* เปลี่ยนเป็น default เพราะเราจะคลิกที่ลิงก์ข้างในแทน */
        transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* เมื่อ Hover การ์ดจะขยาย */
    .smooth-wrapper .smooth-panel:hover {
        flex: 2.5; 
        background: #161616;
        border-color: rgba(177, 8, 28, 0.3);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    /* ตัวเลข: อยู่กลางการ์ด */
    .bg-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 140px;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.02);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 1;
    }

    .smooth-panel:hover .bg-number {
        color: rgba(177, 8, 28, 0.08);
        transform: translate(-50%, -50%) scale(1.1);
    }

    /* กล่องเนื้อหาที่สไลด์ออกมา */
    .title-box {
        position: absolute;
        top: 50%;
        left: -100%; /* ซ่อนทางซ้าย */
        transform: translateY(-50%);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 2;
        padding-left: 50px;
        width: 100%;
    }

    .smooth-panel:hover .title-box {
        left: 0; /* สไลด์เข้ามา */
    }

    .inner-title {
        font-size: 38px;
        font-weight: 800;
        color: #B1081C;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    /* รายการลิงก์ย่อย */
    .footer-sub-links {
        list-style: none;
        padding: 0;
        margin: 0;
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease 0.3s; /* Delay ให้ Title มาก่อน */
    }

    .smooth-panel:hover .footer-sub-links {
        opacity: 1;
        transform: translateX(0);
    }

    .footer-sub-links li {
        margin-bottom: 10px;
    }

    .footer-sub-links a {
        color: #888;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-sub-links a:hover {
        color: #fff;
        transform: translateX(10px); /* สไลด์ลิงก์ตอนจี้เมาส์ */
    }
</style>

<footer class="ultra-footer">
    <div class="max-w-[1200px] mx-auto px-10">
        
        <div class="smooth-wrapper">
            
            <div class="smooth-panel">
                <div class="bg-number">01</div>
                <div class="title-box">
                    <h3 class="inner-title">JOBS</h3>
                    <ul class="footer-sub-links">
                        <li><a href="P1.php">Software Developer</a></li>
                        <li><a href="P1.php">Data Science</a></li>
                        <li><a href="P1.php">Digital Marketing</a></li>
                        <li><a href="P1.php">Engineering</a></li>
                    </ul>
                </div>
            </div>

            <div class="smooth-panel">
                <div class="bg-number">02</div>
                <div class="title-box">
                    <h3 class="inner-title">CAMPUS</h3>
                    <ul class="footer-sub-links">
                        <li><a href="P2.php">Chulalongkorn</a></li>
                        <li><a href="P2.php">Thammasat</a></li>
                        <li><a href="P2.php">Kasetsart</a></li>
                        <li><a href="P2.php">Mahidol</a></li>
                    </ul>
                </div>
            </div>

            <div class="smooth-panel">
                <div class="bg-number">03</div>
                <div class="title-box">
                    <h3 class="inner-title">TRENDS</h3>
                    <ul class="footer-sub-links">
                        <li><a href="#">Salary Guide 2026</a></li>
                        <li><a href="#">Future Skills</a></li>
                        <li><a href="#">AI Roadmap</a></li>
                        <li><a href="#">Interview Tips</a></li>
                    </ul>
                </div>
            </div>

            <div class="smooth-panel">
                <div class="bg-number">04</div>
                <div class="title-box">
                    <h3 class="inner-title">SUPPORT</h3>
                    <ul class="footer-sub-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Support</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Join Community</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="mt-16 text-center">
            <p class="text-gray-700 text-[10px] uppercase tracking-[5px] font-bold">A-PLATFORM / NAVIGATE YOUR FUTURE</p>
        </div>
    </div>
</footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll('.screen-fade');
            setTimeout(() => { elements.forEach(el => el.classList.add('active')); }, 100);
        });
    </script>
</body>
</html>