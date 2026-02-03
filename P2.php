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
    </style>
</head>
<body>

    <header class="w-full bg-white border-b border-gray-200 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-12">
            <div class="w-10 h-10 bg-[#B1081C] rounded-lg flex items-center justify-center font-black text-white text-xl cursor-pointer">A</div>
            <nav class="flex space-x-8 text-[14px] font-bold text-gray-500">
                <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                <a href="P1.php" class="hover:text-[#B1081C] transition">หมวดหมู่การหางาน</a>
                <a href="P2.php" class="text-[#B1081C] border-b-2 border-[#B1081C] pb-1">หมวดหมู่มหาวิทยาลัยในไทย</a>
            </nav>
        </div>
        <input type="text" placeholder="ค้นหาชื่อมหาวิทยาลัย..." class="w-72 px-4 py-1.5 rounded-full border text-sm focus:outline-none">
    </header>

    <section class="hero-banner shadow-sm mb-16 screen-fade delay-1">
        <div class="w-1/2 flex flex-col justify-center pl-24 z-30">
            <h1 class="text-[70px] font-extrabold leading-[1.3] text-[#1a1a1a]">มหาวิทยาลัย <br> <span class="text-[#B1081C]">ชั้นนำทั่วไทย</span></h1>
        </div>
        <div class="red-design-part"></div>
    </section>

    <main class="max-w-[1400px] mx-auto px-10 pb-20 screen-fade delay-2">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="uni-card bg-white rounded-2xl shadow-md overflow-hidden flex flex-col">
                       <img src="uploads/<?php echo $row['uni_img']; ?>" class="w-full h-52 object-cover" onerror="this.src='https://via.placeholder.com/800x600?text=No+Image'">
                        <div class="p-8 flex flex-col flex-grow">
                            <span class="text-[#B1081C] font-extrabold text-xs uppercase"><?php echo $row['uni_location']; ?></span>
                            <h3 class="text-2xl font-extrabold mt-2 text-[#1a1a1a]"><?php echo $row['uni_name']; ?></h3>
                            <p class="text-sm text-gray-500">
    <?= isset($row['uni_location']) ? $row['uni_location'] : 'ไม่ระบุสถานที่' ?>
</p>

<p class="text-xs text-gray-400">
    <?= isset($row['uni_description']) ? $row['uni_description'] : '' ?>
</p>
                            <a href="P21.php?uni_id=<?php echo $row['uni_id']; ?>" class="btn-apply mt-auto w-full text-white py-4 rounded-xl font-bold">ดูรายละเอียดคณะ</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-20 text-gray-400">ยังไม่มีข้อมูลมหาวิทยาลัยในระบบ</div>
            <?php endif; ?>

        </div>
    </main>

    <footer class="ultra-footer">
        <div class="max-w-[1200px] mx-auto px-10">
            <div class="smooth-wrapper">
                <div class="smooth-panel"><div class="bg-number">01</div><div class="title-box"><h3 class="inner-title">JOBS</h3></div></div>
                <div class="smooth-panel"><div class="bg-number">02</div><div class="title-box"><h3 class="inner-title">CAMPUS</h3></div></div>
                <div class="smooth-panel"><div class="bg-number">03</div><div class="title-box"><h3 class="inner-title">TRENDS</h3></div></div>
                <div class="smooth-panel"><div class="bg-number">04</div><div class="title-box"><h3 class="inner-title">SUPPORT</h3></div></div>
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