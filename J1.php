<?php
include 'connect.php';

$major_id = isset($_GET['major_id']) ? intval($_GET['major_id']) : 0;

// ดึงชื่อสาขา
$major_res = $conn->query("SELECT major_name FROM majors WHERE id = $major_id");
$major = $major_res->fetch_assoc();

// ดึงอาชีพที่เกี่ยวข้อง
$sql = "SELECT jobs.* FROM jobs 
        JOIN major_jobs ON jobs.id = major_jobs.job_id 
        WHERE major_jobs.major_id = $major_id 
        AND jobs.is_deleted = 0";
$jobs_res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Pathways - A Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; margin: 0; background-color: #f5f5f5; }

        /* ====== การ์ดสไตล์ P1 ====== */
        .job-card { transition: all 0.2s ease-in-out; border: 1px solid rgba(0,0,0,0.05); }
        .job-card:hover { border-color: #B1081C; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); }
        .btn-apply { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background-color: #B1081C; }
        .btn-apply:hover { background-color: #8e0616; transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(177, 8, 28, 0.4); }

        /* ====== Footer ====== */
        .ultra-footer { background: #0a0a0a; padding: 100px 0; font-family: 'Sarabun', sans-serif; }
        .smooth-wrapper { display: flex; width: 100%; height: 480px; gap: 15px; align-items: stretch; }
        .smooth-panel {
            position: relative; flex: 1; background: #111; border-radius: 30px;
            overflow: hidden; cursor: default;
            transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
            border: 1px solid rgba(255, 255, 255, 0.03);
        }
        .smooth-wrapper .smooth-panel:hover {
            flex: 2.5; background: #161616;
            border-color: rgba(177, 8, 28, 0.3);
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }
        .bg-number {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            font-size: 140px; font-weight: 900;
            color: rgba(255, 255, 255, 0.02);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); z-index: 1;
        }
        .smooth-panel:hover .bg-number { color: rgba(177, 8, 28, 0.08); transform: translate(-50%, -50%) scale(1.1); }
        .title-box {
            position: absolute; top: 50%; left: -100%;
            transform: translateY(-50%);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 2; padding-left: 50px; width: 100%;
        }
        .smooth-panel:hover .title-box { left: 0; }
        .inner-title { font-size: 38px; font-weight: 800; color: #B1081C; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; }
        .footer-sub-links { list-style: none; padding: 0; margin: 0; opacity: 0; transform: translateX(-20px); transition: all 0.5s ease 0.3s; }
        .smooth-panel:hover .footer-sub-links { opacity: 1; transform: translateX(0); }
        .footer-sub-links li { margin-bottom: 10px; }
        .footer-sub-links a { color: #888; text-decoration: none; font-size: 16px; font-weight: 600; transition: all 0.3s ease; display: inline-block; }
        .footer-sub-links a:hover { color: #fff; transform: translateX(10px); }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header class="w-full bg-white border-b border-gray-200 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-12">
            <a href="index.php" class="w-10 h-10 bg-[#B1081C] rounded-lg flex items-center justify-center font-black text-white text-xl">A</a>
            <nav class="flex space-x-8 text-[14px] font-bold text-gray-500">
                <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                <a href="P1.php" class="hover:text-[#B1081C] transition">หมวดหมู่การหางาน</a>
                <a href="P2.php" class="hover:text-[#B1081C] transition">หมวดหมู่มหาวิทยาลัยในไทย</a>
            </nav>
        </div>
    </header>

    <!-- Hero Header -->
    <section class="bg-white border-b border-gray-100 px-10 py-16 mb-12">
        <div class="max-w-[1400px] mx-auto">
            <span class="text-[#B1081C] text-[10px] font-black uppercase tracking-[4px]">Career Pathways</span>
            <h1 class="text-5xl font-extrabold leading-tight text-[#1a1a1a] mt-3">
                อาชีพสำหรับสาย <span class="text-[#B1081C]"><?= isset($major['major_name']) ? htmlspecialchars($major['major_name']) : 'ทั้งหมด' ?></span>
            </h1>
            <p class="text-gray-500 mt-4 text-sm">รวบรวมอาชีพที่ตรงสายและตลาดงานต้องการมากที่สุด</p>
        </div>
    </section>

    <!-- Job Cards Grid (สไตล์ P1) -->
    <main class="max-w-[1400px] mx-auto px-10 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <?php if ($jobs_res && $jobs_res->num_rows > 0): ?>
                <?php while($row = $jobs_res->fetch_assoc()): ?>

                <div class="job-card bg-white rounded-2xl shadow-md overflow-hidden flex flex-col relative">

                    <!-- Badge เงินเดือน -->
                    <div class="absolute top-3 right-3 z-10">
                        <div class="bg-white/60 backdrop-blur-sm border border-white/40 px-3 py-1.5 rounded-xl text-right shadow-sm">
                            <p class="text-[7px] font-black text-gray-500 uppercase tracking-[1px] mb-0 leading-none">Experienced</p>
                            <div class="font-black text-[#B1081C] text-base leading-tight">
                                <?= htmlspecialchars($row['sal_sr'] ?? 'N/A') ?>
                            </div>
                        </div>
                    </div>

                    <!-- รูปภาพ -->
                    <div class="w-full h-52 bg-gray-100 overflow-hidden">
                        <img src="<?= htmlspecialchars($row['image_url'] ?? '') ?>"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                             onerror="this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop';">
                    </div>

                    <!-- ข้อมูล -->
                    <div class="p-8 flex flex-col flex-grow">
                        <span class="text-[#B1081C] font-extrabold text-xs tracking-widest uppercase mb-2">
                            <?= htmlspecialchars($row['category'] ?? 'Career') ?>
                        </span>
                        <h3 class="text-2xl font-extrabold text-[#1a1a1a] mb-2"><?= htmlspecialchars($row['title'] ?? '') ?></h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed mb-8 h-12 overflow-hidden">
                            <?= mb_strimwidth(htmlspecialchars($row['description'] ?? ''), 0, 85, "...") ?>
                        </p>
                        <!-- ลิงก์ไป P13 เพื่อดูรายละเอียดอาชีพ -->
                        <a href="P13.php?id=<?= $row['id'] ?>" class="btn-apply mt-auto w-full text-white py-4 rounded-xl font-bold uppercase text-sm text-center">
                            ดูรายละเอียดอาชีพนี้
                        </a>
                    </div>
                </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 py-20 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-400 italic font-bold">ยังไม่มีข้อมูลอาชีพที่เชื่อมโยงกับสาขานี้</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer (สไตล์ P1/P13) -->
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

</body>
</html>