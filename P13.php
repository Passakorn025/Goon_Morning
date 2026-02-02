<?php
// 1. เชื่อมต่อ Database (เช็คชื่อ DB มึงด้วยว่าชื่ออะไร ถ้าชื่อ 'a_platform' ก็เปลี่ยนตามนั้น)
$conn = new mysqli("localhost", "root", "", "aplatform_db");
mysqli_set_charset($conn, "utf8");

// 2. รับค่า ID จาก URL (ถ้าไม่มี ID ส่งมา ให้เป็นอาชีพที่ 1 โดยอัตโนมัติ)
$id = isset($_GET['id']) ? intval($_GET['id']) : 1; 

// 3. ดึงข้อมูลจากตาราง jobs ตาม ID
$sql = "SELECT * FROM jobs WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// 4. ถ้าไม่พบข้อมูล (เช่น ใส่ ID มั่ว) ให้เด้งกลับหน้าหลัก
if(!$row) {
    header("Location: P1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Insight - <?php echo $row['title']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Sarabun', sans-serif; margin: 0; background-color: #fcfcfc; color: #333; }
        .header-accent { height: 8px; background: #B1081C; width: 100%; }
        .content-card {
            background: white;
            border-left: 12px solid #B1081C; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border-radius: 0 1rem 1rem 0;
        }
        .btn-back {
            transition: all 0.3s ease;
            border: 2px solid #B1081C;
            color: #B1081C;
        }
        .btn-back:hover {
            background-color: #B1081C;
            color: white;
            transform: translateX(-5px);
        }
        .screen-fade { 
            opacity: 0; 
            transform: translateX(-20px); 
            transition: opacity 0.6s ease-out, transform 0.6s ease-out; 
        }
        .screen-fade.active { opacity: 1; transform: translateX(0); }

        /* Footer Styles (มึงทำมาดีมาก กูกองไว้ให้ข้างล่าง) */
        .ultra-footer { background: #0a0a0a; padding: 100px 0; font-family: 'Sarabun', sans-serif; }
        .smooth-wrapper { display: flex; width: 100%; height: 480px; gap: 15px; align-items: stretch; }
        .smooth-panel { position: relative; flex: 1; background: #111; border-radius: 30px; overflow: hidden; transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1); border: 1px solid rgba(255, 255, 255, 0.03); }
        .smooth-wrapper .smooth-panel:hover { flex: 2.5; background: #161616; border-color: rgba(177, 8, 28, 0.3); }
        .bg-number { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 140px; font-weight: 900; color: rgba(255, 255, 255, 0.02); transition: all 0.6s; z-index: 1; }
        .smooth-panel:hover .bg-number { color: rgba(177, 8, 28, 0.08); }
        .title-box { position: absolute; top: 50%; left: -100%; transform: translateY(-50%); transition: all 0.6s; z-index: 2; padding-left: 50px; width: 100%; }
        .smooth-panel:hover .title-box { left: 0; }
        .inner-title { font-size: 38px; font-weight: 800; color: #B1081C; text-transform: uppercase; margin-bottom: 20px; }
        .footer-sub-links { list-style: none; padding: 0; opacity: 0; transition: 0.5s 0.3s; }
        .smooth-panel:hover .footer-sub-links { opacity: 1; }
        .footer-sub-links a { color: #888; text-decoration: none; font-size: 16px; transition: 0.3s; }
        .footer-sub-links a:hover { color: #fff; transform: translateX(10px); display: inline-block; }
    </style>
</head>
<body>

    <div class="header-accent"></div>
    <header class="w-full bg-white border-b border-gray-100 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-12">
            <div class="w-10 h-10 bg-[#B1081C] rounded flex items-center justify-center font-black text-white text-xl">A</div>
            <nav class="flex space-x-8 text-[14px] font-bold text-gray-400">
                <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                <a href="P1.php" class="text-[#B1081C]">หมวดหมู่การหางาน</a>
                <a href="P2.php" class="hover:text-[#B1081C] transition">มหาวิทยาลัยในไทย</a>
            </nav>
        </div>
    </header>

    <main class="max-w-[1000px] mx-auto px-10 py-16 text-left">
        <div class="mb-10 screen-fade">
            <h1 class="text-5xl font-extrabold text-[#1a1a1a] mt-6 tracking-tighter uppercase"><?php echo $row['title']; ?></h1>
            <p class="text-[#B1081C] font-bold mt-2 tracking-[2px] text-sm uppercase">ข้อมูลเจาะลึกสายอาชีพ (<?php echo $row['category']; ?>)</p>
        </div>

        <div class="content-card p-12 screen-fade">
            <div class="space-y-12">
                <section>
                    <div class="flex items-center mb-6">
                        <span class="text-2xl font-black text-[#B1081C]/20 mr-4">01</span>
                        <h2 class="text-xl font-extrabold text-[#1a1a1a] uppercase tracking-wide">บทบาทและความสำคัญ</h2>
                    </div>
                    <div class="text-gray-600 leading-[1.9] pl-12">
                        <p><?php echo nl2br($row['description']); ?></p>
                    </div>
                </section>

                <section>
                    <div class="flex items-center mb-6">
                        <span class="text-2xl font-black text-[#B1081C]/20 mr-4">02</span>
                        <h2 class="text-xl font-extrabold text-[#1a1a1a] uppercase tracking-wide">ค่าตอบแทนโดยประมาณ</h2>
                    </div>
                    <div class="pl-12">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-gray-200">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Entry Level</p>
                                <p class="text-lg font-bold"><?php echo $row['salary_jr']; ?> ฿</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-[#B1081C]">
                                <p class="text-[10px] text-[#B1081C] font-bold uppercase">Experience</p>
                                <p class="text-lg font-bold"><?php echo $row['salary_sr']; ?> ฿</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-gray-200">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Expert Level</p>
                                <p class="text-lg font-bold"><?php echo $row['salary_exp']; ?> ฿</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="flex items-center mb-6">
                        <span class="text-2xl font-black text-[#B1081C]/20 mr-4">03</span>
                        <h2 class="text-xl font-extrabold text-[#1a1a1a] uppercase tracking-wide">Essential Skills</h2>
                    </div>
                    <div class="pl-12 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm leading-relaxed text-gray-600">
                        <div class="space-y-3">
                            <p class="font-bold text-[#1a1a1a]">Hard Skills:</p>
                            <p><?php echo nl2br($row['hard_skills']); ?></p>
                        </div>
                        <div class="space-y-3">
                            <p class="font-bold text-[#1a1a1a]">Soft Skills:</p>
                            <p><?php echo nl2br($row['soft_skills']); ?></p>
                        </div>
                    </div>
                </section>

                <section class="pt-10 border-t border-gray-100 pl-12">
                    <h2 class="text-sm font-bold text-[#B1081C] mb-3 uppercase tracking-widest">Future Outlook</h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                        <?php echo nl2br($row['future_trend']); ?>
                    </p>
                   <a href="P1.php?all=true#job-<?php echo $id; ?>" 
   class="btn-back px-8 py-3 rounded font-bold text-xs uppercase tracking-widest">
   ย้อนกลับ
</a>
                </section>
            </div>
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
            elements.forEach((el, index) => {
                setTimeout(() => { el.classList.add('active'); }, index * 150);
            });
        });
    </script>
</body>
</html>