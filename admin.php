<?php 
include 'connect.php'; 
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'jobs';

// --- จุดที่ 1: แทรกโค้ดลบตรงนี้ (จัดการการลบผ่าน Link) ---
if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'uni_delete') {
    $id = $_GET['uni_id'];
    $conn->query("UPDATE universities SET is_deleted = 1 WHERE uni_id = $id");
    header("Location: admin.php?mode=edu"); // ลบเสร็จรีเฟรชหน้าตัวเอง
    exit();
}

// --- อันนี้ของเดิมมึง ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... โค้ดเก่ามึง ...
    
    // 1. เพิ่ม Job
    if ($mode == 'insert_action') {
        $sql = "INSERT INTO jobs (title, description, sal_jr, sal_sr, sal_exp, h_skill, s_skill, future, image_url, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $_POST['title'], $_POST['description'], $_POST['sal_jr'], $_POST['sal_sr'], $_POST['sal_exp'], $_POST['h_skill'], $_POST['s_skill'], $_POST['future'], $_POST['image_url']);
        if ($stmt->execute()) { header("Location: admin.php?mode=jobs"); exit(); }
    }

    // 2. เพิ่มมหาวิทยาลัย (แก้ Action ให้ตรงกับฟอร์ม)
    if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'uni_insert') {
        $stmt = $conn->prepare("INSERT INTO universities (uni_name, uni_img) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['uni_name'], $_POST['uni_img']);
        if ($stmt->execute()) { header("Location: admin.php?mode=edu&status=success"); exit(); }
    }

    // 3. เพิ่มคณะ
    if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'fac_insert') {
        $stmt = $conn->prepare("INSERT INTO faculties (uni_id, fac_name, fac_img) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_POST['uni_id'], $_POST['fac_name'], $_POST['fac_img']);
        if ($stmt->execute()) { header("Location: admin.php?mode=edu&status=success"); exit(); }
    }
}
?>
    
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>A-PLATFORM | Admin System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Noto Sans Thai', sans-serif; background-color: #f8f9fa; color: #333; }
        .nav-active { border-bottom: 3px solid #B1081C; color: #B1081C !important; }
        .job-card {
            background: white; border-radius: 1rem; border: 2px solid #eee; 
            display: flex; flex-direction: column; height: 100%; overflow: hidden;
            position: relative; transition: all 0.3s ease; 
        }
        .job-card:hover { border-color: #B1081C; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .btn-delete {
            position: absolute; top: 15px; right: 15px; width: 32px; height: 32px;
            background: rgba(255, 255, 255, 0.9); border: 1px solid #eee; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #999; font-size: 14px; font-weight: bold; transition: all 0.3s ease; z-index: 10;
        }
        .btn-delete:hover { background: #B1081C; color: white; border-color: #B1081C; transform: rotate(90deg); }
        .btn-p1-style {
            background: transparent; border: 1.5px solid #B1081C; color: #B1081C;
            padding: 0.75rem 0; border-radius: 0.5rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em; font-size: 12px;
            text-align: center; display: block; width: 100%; transition: all 0.2s ease;
        }
        .btn-p1-style:hover { background-color: #B1081C; color: white; }
    </style>
</head>
<body>
<body class="m-0">

    <nav class="w-full bg-white border-b border-gray-200 sticky top-0 z-50 px-10">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-16">
            <div class="flex items-center space-x-10">
                <div class="font-black text-2xl text-[#B1081C] tracking-tighter italic">A-PLATFORM</div>
                <div class="flex space-x-6 text-[14px] font-medium uppercase tracking-tight">
                    <a href="?mode=jobs" class="py-5 transition <?= $mode == 'jobs' || $mode == 'add_job' ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Jobs</a>
                    <a href="?mode=edu" class="py-5 transition <?= $mode == 'edu' ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Education</a>
                    <a href="?mode=trash" class="py-5 transition <?= $mode == 'trash' ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Trash Bin</a>
                </div>
            </div>
            <div class="text-xs text-gray-400 font-medium uppercase italic">Admin System</div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-10 py-12">
        <div class="mb-10 flex justify-between items-end">
            <div>
               <h1 class="text-3xl text-gray-900 uppercase">
    Dashboard / <span class="text-gray-400">
        <?php 
            if($mode == 'edu') echo 'EDUCATION';
            elseif($mode == 'add_job') echo 'ADD NEW JOB';
            else echo strtoupper($mode);
        ?>
    </span>
</h1>
                <div class="h-1 w-20 bg-[#B1081C] mt-4"></div>
            </div>
         <?php if($mode == 'jobs'): ?>
    <a href="?mode=add_job" class="bg-[#B1081C] text-white px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-black transition-all shadow-md">+ Add New Job</a>
<?php elseif($mode == 'edu'): ?>
    <a href="?mode=add_uni" class="bg-[#B1081C] text-white px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-black transition-all shadow-md">+ Add New University</a>
<?php endif; ?>
        </div>

        <?php if($mode == 'jobs'): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $result = $conn->query("SELECT * FROM jobs WHERE is_deleted = 0 ORDER BY id DESC");
                while($row = $result->fetch_assoc()):
                ?>
                <div class="job-card">
                    <a href="delete_job.php?id=<?= $row['id'] ?>" onclick="return confirm('ย้ายไปที่ถังขยะ?')" class="btn-delete">✕</a>
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="<?= $row['image_url'] ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl text-gray-900 mb-4"><?= $row['title'] ?></h3>
                        <div class="mt-auto pt-4">
                            <a href="admin_edit_card.php?id=<?= $row['id'] ?>" class="btn-p1-style">Edit Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?> 
            </div>

        <?php elseif($mode == 'add_job'): ?>
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
                <form action="?mode=insert_action" method="POST" class="space-y-6">
                    <div>
    <label class="block text-sm font-bold text-gray-700 mb-2">ชื่ออาชีพ :</label>
    <input type="text" name="title" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
</div>

<div>
    <label class="block text-sm font-bold text-gray-700 mb-2">คำอธิบายสั้นๆ:</label>
    <input type="text" name="description" placeholder class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
</div>
 <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Image URL:</label>
                        <input type="text" name="image_url" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 uppercase mb-8">เพิ่มข้อมูลอาชีพ</h2>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">ชื่ออาชีพ:</label>
                        <input type="text" name="title" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">บทบาทหลัก:</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                    </div>

                    <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-4">เงินเดือน (JR/SR/EXP):</label>
                        <div class="grid grid-cols-3 gap-4">
                            <input type="text" name="sal_jr" placeholder="JR: 25k" class="px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                            <input type="text" name="sal_sr" placeholder="SR: 50k" class="px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                            <input type="text" name="sal_exp" placeholder="EXP: 100k+" class="px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Hard Skills:</label>
                            <textarea name="h_skill" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Soft Skills:</label>
                            <textarea name="s_skill" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">แนวโน้มอนาคต:</label>
                        <textarea name="future" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                    </div>

                   

                    <div class="pt-8 flex space-x-4">
                        <button type="submit" class="flex-1 bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all">บันทึกข้อมูล</button>
                        <a href="?mode=jobs" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase text-center">ยกเลิก</a>
                    </div>
                </form>
            </div>
            <?php elseif($mode == 'edu'): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php
      // ต้องมี WHERE is_deleted = 0 ด้วย
$result = $conn->query("SELECT * FROM universities WHERE is_deleted = 0 ORDER BY uni_id DESC");
        while($row = $result->fetch_assoc()):
        ?>
        <div class="job-card">
           <a href="admin.php?mode=edu&edu_action=uni_delete&uni_id=<?= $row['uni_id'] ?>" 
   onclick="return confirm('ย้ายลงถังขยะนะ?')" 
   class="btn-delete">✕</a>
            
            <div class="h-48 overflow-hidden bg-gray-100 flex items-center justify-center p-4">
                <img src="<?= $row['uni_img'] ?>" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/400x300?text=No+Logo'">
            </div>
            
            <div class="p-6 flex flex-col flex-grow text-center">
                <h3 class="text-xl font-bold text-gray-900 mb-2 uppercase"><?= $row['uni_name'] ?></h3>
                <p class="text-gray-400 text-[10px] mb-6 tracking-widest uppercase">ID: #<?= $row['uni_id'] ?></p>
                <div class="mt-auto">
                    <a href="admin_edit_uni.php?id=<?= $row['uni_id'] ?>" class="btn-p1-style">Edit University</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?> 
    </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <span class="text-xs font-black uppercase text-gray-400 tracking-widest">Faculty List</span>
                </div>
                <div class="max-h-[600px] overflow-y-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $sql = "SELECT faculties.*, universities.uni_name 
                                    FROM faculties 
                                    INNER JOIN universities ON faculties.uni_id = universities.uni_id 
                                    ORDER BY faculties.fac_id DESC";
                            $f_res = $conn->query($sql);
                            if($f_res->num_rows > 0):
                                while($f = $f_res->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <img src="<?= $f['fac_img'] ?>" class="w-12 h-12 rounded-lg object-cover bg-gray-100" onerror="this.src='https://via.placeholder.com/80'">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900"><?= $f['fac_name'] ?></div>
                                            <div class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">สังกัด: <?= $f['uni_name'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="delete_fac.php?id=<?= $f['fac_id'] ?>" onclick="return confirm('ลบคณะนี้?')" class="text-[10px] font-bold text-gray-300 hover:text-red-600 uppercase transition-colors">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                                <tr><td class="px-6 py-10 text-center text-gray-400 italic text-sm">ยังไม่มีข้อมูลคณะในระบบ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php elseif($mode == 'edu'): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <?php
        $result = $conn->query("SELECT * FROM universities WHERE is_deleted = 0 ORDER BY uni_id DESC");
        while($row = $result->fetch_assoc()):
        ?>
        <div class="job-card">
            <a href="delete_uni.php?uni_id=<?= $row['uni_id'] ?>" onclick="return confirm('ย้ายลงถังขยะ?')" class="btn-delete">✕</a>
            <div class="h-48 overflow-hidden bg-gray-100 flex items-center justify-center p-4">
                <img src="<?= $row['uni_img'] ?>" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/400x300?text=No+Logo'">
            </div>
            <div class="p-6 flex flex-col flex-grow text-center">
                <h3 class="text-xl font-bold text-gray-900 mb-2 uppercase"><?= $row['uni_name'] ?></h3>
                <p class="text-gray-400 text-[10px] mb-6 tracking-widest uppercase">ID: #<?= $row['uni_id'] ?></p>
                <div class="mt-auto">
                    <a href="admin_edit_uni.php?id=<?= $row['uni_id'] ?>" class="btn-p1-style">Edit University</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?> 
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm h-fit">
            <h2 class="text-xl font-black mb-6 uppercase italic text-[#B1081C]">Add New Faculty</h2>
            <form action="?mode=edu&edu_action=fac_insert" method="POST" class="space-y-4">
                <select name="uni_id" required class="w-full px-4 py-3 bg-gray-50 border rounded-xl outline-none focus:border-[#B1081C]">
                    <option value="">เลือกมหาวิทยาลัย</option>
                    <?php 
                    $u_res = $conn->query("SELECT * FROM universities WHERE is_deleted = 0 ORDER BY uni_name ASC");
                    while($u = $u_res->fetch_assoc()) echo "<option value='{$u['uni_id']}'>{$u['uni_name']}</option>";
                    ?>
                </select>
                <input type="text" name="fac_name" placeholder="ชื่อคณะ" required class="w-full px-4 py-3 bg-gray-50 border rounded-xl outline-none focus:border-[#B1081C]">
                <input type="text" name="fac_img" placeholder="URL รูปหน้าคณะ" class="w-full px-4 py-3 bg-gray-50 border rounded-xl outline-none focus:border-[#B1081C]">
                <button type="submit" class="w-full bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all">Save Faculty</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 font-bold uppercase text-xs tracking-widest text-gray-400">Faculty List</div>
            <div class="max-h-[500px] overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $sql = "SELECT faculties.*, universities.uni_name FROM faculties 
                                INNER JOIN universities ON faculties.uni_id = universities.uni_id 
                                ORDER BY faculties.fac_id DESC";
                        $f_res = $conn->query($sql);
                        while($f = $f_res->fetch_assoc()):
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-4">
                                    <img src="<?= $f['fac_img'] ?>" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                    <div>
                                        <div class="text-sm font-bold text-gray-800"><?= $f['fac_name'] ?></div>
                                        <div class="text-[10px] text-gray-400 uppercase"><?= $f['uni_name'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="delete_fac.php?id=<?= $f['fac_id'] ?>" onclick="return confirm('ลบคณะนี้?')" class="text-[10px] font-bold text-gray-300 hover:text-red-600 uppercase">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
            <?php elseif($mode == 'add_uni'): ?>
    <div class="max-w-xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
        <h2 class="text-2xl font-black text-gray-900 uppercase mb-8">Add New University</h2>
        <form action="?mode=edu&edu_action=uni_insert" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">University Name:</label>
                <input type="text" name="uni_name" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Logo Image URL:</label>
                <input type="text" name="uni_img" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
            </div>
            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all">Save University</button>
                <a href="?mode=edu" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase text-center">Cancel</a>
            </div>
        </form>
    </div>
    
     <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-bold mb-4 flex items-center text-gray-800">
                    <span class="w-1.5 h-5 bg-blue-600 mr-2 rounded-full"></span> เพิ่มคณะ
                </h3>
                <form action="?mode=edu&edu_action=fac_insert" method="POST" class="space-y-3">
                    <select name="uni_id" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg outline-none focus:border-blue-600 text-sm">
                        <option value="">เลือกมหาวิทยาลัย</option>
                        <?php 
                        $u_res = $conn->query("SELECT * FROM universities ORDER BY uni_id DESC");
                        while($u = $u_res->fetch_assoc()) echo "<option value='{$u['uni_id']}'>{$u['uni_name']}</option>";
                        ?>
                    </select>
                    <input type="text" name="fac_name" placeholder="ชื่อคณะ" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg outline-none focus:border-blue-600 text-sm">
                    <input type="text" name="fac_img" placeholder="URL รูปหน้าคณะ" class="w-full px-4 py-2 bg-gray-50 border rounded-lg outline-none focus:border-blue-600 text-sm">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold text-xs uppercase hover:bg-black transition-all">บันทึกคณะ</button>
                </form>
            </div>
        </div>
       <?php elseif($mode == 'trash'): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-700 uppercase tracking-tight">Combined Trash Bin (Jobs & Universities)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] uppercase tracking-widest text-gray-400 border-b border-gray-50">
                        <th class="px-8 py-4 font-semibold">Type</th>
                        <th class="px-8 py-4 font-semibold">Details</th>
                        <th class="px-8 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php
                    // ใช้ UNION เพื่อดึงข้อมูลจาก 2 ตารางมารวมกัน
                    // เราต้องตั้งชื่อเล่น (Alias) ให้คอลัมน์มันตรงกัน
                    $sql = "(SELECT 'JOB' as type, id as main_id, title as name, image_url as img FROM jobs WHERE is_deleted = 1)
                            UNION
                            (SELECT 'UNI' as type, uni_id as main_id, uni_name as name, uni_img as img FROM universities WHERE is_deleted = 1)
                            ORDER BY name ASC";
                            
                    $combined_trash = $conn->query($sql);

                    if ($combined_trash->num_rows > 0):
                        while($item = $combined_trash->fetch_assoc()):
                            // เช็คว่าเป็นประเภทไหน เพื่อกำหนดไฟล์ที่จะส่งไปจัดการ
                            $restore_link = ($item['type'] == 'JOB') ? "restore_job.php?id=" : "restore_uni.php?uni_id=";
                            $delete_link = ($item['type'] == 'JOB') ? "real_delete.php?id=" : "real_delete_uni.php?uni_id=";
                            $badge_color = ($item['type'] == 'JOB') ? "bg-blue-100 text-blue-600" : "bg-purple-100 text-purple-600";
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <span class="px-2 py-1 rounded text-[9px] font-black <?= $badge_color ?>"><?= $item['type'] ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg overflow-hidden grayscale opacity-50 bg-gray-100">
                                    <img src="<?= $item['img'] ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/50'">
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-600"><?= $item['name'] ?></div>
                                    <div class="text-[9px] text-gray-400 uppercase">ID: #<?= $item['main_id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center space-x-6">
                                <a href="<?= $restore_link . $item['main_id'] ?>" class="text-[11px] font-black text-emerald-600 hover:text-emerald-700 uppercase">↺ Restore</a>
                                <a href="<?= $delete_link . $item['main_id'] ?>" onclick="return confirm('จะลบถาวร')" class="text-[11px] font-black text-gray-300 hover:text-red-600 uppercase">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="3" class="px-8 py-20 text-center text-gray-300 font-bold uppercase italic">Trash Bin is Empty</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
    </main>
    
</body>
</html>