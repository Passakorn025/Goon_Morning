<?php 
include 'connect.php'; 

// 1. รับ ID มหาลัยจาก URL (ถ้าไม่มีให้เป็น 1)
$uni_id = isset($_GET['uni_id']) ? intval($_GET['uni_id']) : 1;

// 2. ดึงข้อมูลมหาลัย
$uni_query = $conn->query("SELECT * FROM universities WHERE uni_id = $uni_id");
$university = $uni_query->fetch_assoc();

// 3. ดึงคณะทั้งหมดของมหาลัยนี้ (เช็คชื่อคอลัมน์ uni_id ให้ตรงกับ DB)
$fac_query = $conn->query("SELECT * FROM faculties WHERE uni_id = $uni_id");

// ถ้าไม่เจอมหาลัยให้เด้งกลับ หรือโชว์ Error
if (!$university) {
    die("ไม่พบข้อมูลมหาวิทยาลัย");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $university['uni_name'] ?> - A-Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8f8f8; color: #333; margin: 0; }
        .hero-banner { display: flex; height: 380px; width: 100%; background: white; position: relative; overflow: hidden; }
        .red-design-part { position: absolute; right: 0; top: 0; height: 100%; width: 60%; clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%); background: linear-gradient(115deg, #B1081C 4.5%, #B1081C 100%); }
        .faculty-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; padding: 20px 0; }
        .faculty-card { position: relative; height: 480px; background: #1a1a1a; border-radius: 24px; overflow: hidden; transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); border: 1px solid rgba(0,0,0,0.05); }
        .faculty-card:hover { transform: translateY(-10px); box-shadow: 0 25px 50px rgba(0,0,0,0.3); border-color: #B1081C; }
        .card-bg-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.55; transition: 0.8s ease; z-index: 1; }
        .card-inner { position: relative; z-index: 10; padding: 40px; height: 100%; display: flex; flex-direction: column; background: linear-gradient(to top, rgba(0,0,0,0.95) 15%, transparent 85%); }
        .faculty-title { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 25px; border-left: 6px solid #B1081C; padding-left: 15px; line-height: 1.2; }
        .major-container { margin-top: auto; opacity: 0; transform: translateY(20px); transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); }
        .faculty-card:hover .major-container { opacity: 1; transform: translateY(0); }
        .major-link { display: flex; align-items: center; font-size: 15px; color: rgba(255, 255, 255, 0.75); margin-bottom: 14px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; }
        .major-link:hover { color: #fff; transform: translateX(12px); }
        .major-link svg { margin-right: 12px; color: #B1081C; flex-shrink: 0; }
        .nav-active { color: #B1081C !important; border-bottom: 2px solid #B1081C; padding-bottom: 4px; }
    </style>
</head>
<body>

    <header class="w-full bg-white border-b border-gray-200 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-12">
          <a href="index.php" class="w-10 h-10 bg-[#B1081C] rounded-lg flex items-center justify-center font-black text-white text-xl">A</a>
            <nav class="flex space-x-8 text-[14px] font-bold text-gray-500">
                <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                <a href="P1.php" class="hover:text-[#B1081C] transition">หมวดหมู่การหางาน</a>
                <a href="P2.php" class="nav-active">หมวดหมู่มหาวิทยาลัยในไทย</a>
            </nav>
        </div>
        <input type="text" placeholder="ค้นหาคณะหรือสาขา..." class="w-72 px-4 py-1.5 rounded-full border text-sm focus:outline-none focus:border-[#B1081C]">
    </header>

    <section class="hero-banner shadow-sm mb-16">
        <div class="w-1/2 flex flex-col justify-center pl-24 z-10">
            <h1 class="text-[44px] font-extrabold leading-tight text-[#1a1a1a]">
                คณะที่เปิดรับสมัคร<br><span class="text-[#B1081C]"><?= $university['uni_name'] ?></span>
            </h1>
            <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-xs">
                <?= strtoupper($university['uni_name']) ?> PROGRAMS
            </p>
        </div>
        <div class="red-design-part"></div>
    </section>

    <main class="max-w-[1300px] mx-auto px-10 pb-20">
        <div class="faculty-grid">
            <?php 
            if($fac_query->num_rows > 0):
                while($fac = $fac_query->fetch_assoc()): 
                    // กุแก้ตรงนี้ให้: ใช้ 'id' ให้ตรงกับตาราง faculties ของมึง
                    $f_id = $fac['id']; 
            ?>
                <div class="faculty-card">
                    <img src="<?= $fac['fac_img'] ?>" class="card-bg-img" onerror="this.src='https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=800'">
                    <div class="card-inner">
                        <h3 class="faculty-title"><?= $fac['fac_name'] ?></h3>
                        
                        <div class="major-container">
                            <?php
                            // ดึงสาขา: ใช้ fac_id เชื่อม และดึง major_detail มาด้วย (เผื่อมึงเอาไปใช้)
                            $major_sql = "SELECT id, major_name FROM majors WHERE fac_id = $f_id LIMIT 6";
                            $major_res = $conn->query($major_sql);
                            if($major_res && $major_res->num_rows > 0):
                                while($major = $major_res->fetch_assoc()):
                            ?>
                                <a href="P21_detail.php?id=<?= $major['id'] ?>" class="major-link">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> 
                                    <?= $major['major_name'] ?>
                                </a>
                            <?php 
                                endwhile; 
                            else:
                                echo "<p class='text-gray-500 text-xs italic'>ยังไม่มีข้อมูลสาขา</p>";
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile; 
            else:
                echo "<p class='col-span-3 text-center text-gray-400'>ยังไม่มีข้อมูลคณะในขณะนี้</p>";
            endif;
            ?>
        </div>
    </main>

    <footer class="py-12 bg-white border-t border-gray-100 text-center">
        <p class="text-gray-400 text-[10px] uppercase tracking-[5px] font-bold">A-PLATFORM / NAVIGATE YOUR FUTURE</p>
    </footer>

</body>
</html>