<?php 
include 'connect.php'; 
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'jobs';

// ==========================================
// 1. LOGIC SYSTEM (จัดการข้อมูลหลังบ้าน)
// ==========================================

// --- ส่วนลบข้อมูล (Soft Delete) ---
if (isset($_GET['job_action']) && $_GET['job_action'] == 'delete') {
    $id = intval($_GET['id']);
    $conn->query("UPDATE jobs SET is_deleted = 1 WHERE id = $id");
    header("Location: admin.php?mode=jobs"); exit();
}

if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'uni_delete') {
    $id = intval($_GET['uni_id']);
    $conn->query("UPDATE universities SET is_deleted = 1 WHERE uni_id = $id");
    header("Location: admin.php?mode=edu"); exit();
}

// --- ส่วนเพิ่มข้อมูล (Insert) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update_type'])) {
    
    // เพิ่ม JOB
    if ($mode == 'insert_action') {
        $sql = "INSERT INTO jobs (title, description, sal_jr, sal_sr, sal_exp, h_skill, s_skill, image_url, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $_POST['title'], $_POST['description'], $_POST['sal_jr'], $_POST['sal_sr'], $_POST['sal_exp'], $_POST['h_skill'], $_POST['s_skill'], $_POST['image_url']);
        if ($stmt->execute()) { header("Location: admin.php?mode=jobs"); exit(); }
    }

    // เพิ่ม EDUCATION (UNI / FAC / MAJOR)
    if (isset($_POST['edu_type'])) {
        $type = $_POST['edu_type'];

        if ($type == 'uni_insert') {
            $stmt = $conn->prepare("INSERT INTO universities (uni_name, uni_img, is_deleted) VALUES (?, ?, 0)");
            $stmt->bind_param("ss", $_POST['uni_name'], $_POST['uni_img']);
            $stmt->execute(); header("Location: admin.php?mode=edu&status=success"); exit();
        }
        
        if ($type == 'fac_insert') {
            $stmt = $conn->prepare("INSERT INTO faculties (uni_id, fac_name, fac_img) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $_POST['uni_id'], $_POST['fac_name'], $_POST['fac_img']);
            $stmt->execute(); header("Location: admin.php?mode=edu&status=success"); exit();
        }

        if ($type == 'major_insert') {
            $u_id     = intval($_POST['uni_id']);
            $f_id     = intval($_POST['fac_id']);
            $m_name   = mysqli_real_escape_string($conn, $_POST['major_name']);
            $m_desc   = mysqli_real_escape_string($conn, $_POST['major_detail']);
            $round    = intval($_POST['round_open']);
            $seats    = intval($_POST['seats']);
            $gpax     = mysqli_real_escape_string($conn, $_POST['gpax_min']);
            $plan     = mysqli_real_escape_string($conn, $_POST['plan_accept']);
            $tgat     = intval($_POST['score_tgat']);
            $tpat3    = intval($_POST['score_tpat3']);
            $math1    = intval($_POST['score_math1']);
            $phy      = intval($_POST['score_phy']);
            $condition = mysqli_real_escape_string($conn, $_POST['condition_text']);
            $salary    = mysqli_real_escape_string($conn, $_POST['salary_start']);
            $rate      = mysqli_real_escape_string($conn, $_POST['job_rate']);
            $dem       = mysqli_real_escape_string($conn, $_POST['market_demand']);

            $sql = "INSERT INTO majors (fac_id, uni_id, major_name, major_description, round_open, seats, gpax_min, plan_accept, score_tgat, score_tpat3, score_alevel_math1, score_alevel_phy, condition_text, career_salary, career_job_rate, career_demand) 
                    VALUES ('$f_id', '$u_id', '$m_name', '$m_desc', '$round', '$seats', '$gpax', '$plan', '$tgat', '$tpat3', '$math1', '$phy', '$condition', '$salary', '$rate', '$dem')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('บันทึกสาขาสำเร็จ!'); window.location='admin.php?mode=edu';</script>";
                exit();
            } else { die("SQL พังเพราะ: " . mysqli_error($conn)); }
        }
    }
}

// --- ส่วนแก้ไขข้อมูล (Update) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_type'])) {
    $utype = $_POST['update_type'];

    if ($utype == 'job_edit_action') {
        $id = intval($_POST['id']);
        $sql = "UPDATE jobs SET title=?, description=?, sal_jr=?, sal_sr=?, sal_exp=?, h_skill=?, s_skill=?, image_url=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $_POST['title'], $_POST['description'], $_POST['sal_jr'], $_POST['sal_sr'], $_POST['sal_exp'], $_POST['h_skill'], $_POST['s_skill'], $_POST['image_url'], $id);
        $stmt->execute(); header("Location: admin.php?mode=jobs"); exit();
    }

    if ($utype == 'uni_edit_action') {
        $uni_id = intval($_POST['uni_id']);
        $stmt = $conn->prepare("UPDATE universities SET uni_name=?, uni_img=? WHERE uni_id=?");
        $stmt->bind_param("ssi", $_POST['uni_name'], $_POST['uni_img'], $uni_id);
        $stmt->execute(); header("Location: admin.php?mode=edu"); exit();
    }

   // --- แก้ไข Logic บันทึกใน admin.php ---
if ($utype == 'major_edit_action') {
    $mid = intval($_POST['major_id']);
    // รวมคำสั่งอัปเดตทุกอย่างรวมถึง 2 ช่องใหม่
    $sql = "UPDATE majors SET 
            major_name=?, major_description=?, round_open=?, seats=?, 
            gpax_min=?, plan_accept=?, score_tgat=?, score_tpat3=?, 
            score_alevel_math1=?, score_alevel_phy=?, condition_text=?, 
            career_salary=?, career_job_rate=?, career_demand=?, 
            prep_math=?, prep_prog=? 
            WHERE id=?"; // หรือใช้ major_id ตามโครงสร้างเดิมมึง
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssiiiissssssi", 
        $_POST['major_name'], $_POST['major_detail'], $_POST['round_open'], $_POST['seats'], 
        $_POST['gpax_min'], $_POST['plan_accept'], $_POST['score_tgat'], $_POST['score_tpat3'], 
        $_POST['score_math1'], $_POST['score_phy'], $_POST['condition_text'], 
        $_POST['salary_start'], $_POST['job_rate'], $_POST['market_demand'],
        $_POST['prep_math'], $_POST['prep_prog'], $mid
    );
    $stmt->execute(); 
    header("Location: admin.php?mode=edu"); exit();
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
        .job-card { background: white; border-radius: 1rem; border: 2px solid #eee; display: flex; flex-direction: column; height: 100%; overflow: hidden; position: relative; transition: all 0.3s ease; }
        .job-card:hover { border-color: #B1081C; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .btn-delete { position: absolute; top: 15px; right: 15px; width: 32px; height: 32px; background: rgba(255, 255, 255, 0.9); border: 1px solid #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #999; font-size: 14px; font-weight: bold; transition: all 0.3s ease; z-index: 10; }
        .btn-delete:hover { background: #B1081C; color: white; border-color: #B1081C; transform: rotate(90deg); }
        .btn-p1-style { background: transparent; border: 1.5px solid #B1081C; color: #B1081C; padding: 0.75rem 0; border-radius: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; font-size: 12px; text-align: center; display: block; width: 100%; transition: all 0.2s ease; }
        .btn-p1-style:hover { background-color: #B1081C; color: white; }
    </style>
</head>
<body class="m-0">

    <nav class="w-full bg-white border-b border-gray-200 sticky top-0 z-50 px-10">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-16">
            <div class="flex items-center space-x-10">
                <div class="font-black text-2xl text-[#B1081C] tracking-tighter italic">A-PLATFORM</div>
                <div class="flex space-x-6 text-[14px] font-medium uppercase tracking-tight">
                    <a href="?mode=jobs" class="py-5 transition <?= ($mode == 'jobs' || $mode == 'add_job' || $mode == 'edit_job') ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Jobs</a>
                    <a href="?mode=edu" class="py-5 transition <?= ($mode == 'edu' || $mode == 'add_edu' || $mode == 'edit_major' || $mode == 'edit_uni') ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Education</a>
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
                            elseif($mode == 'edit_job') echo 'EDIT JOB';
                            elseif($mode == 'add_edu') echo 'ADD EDUCATION DATA';
                            elseif($mode == 'edit_major') echo 'EDIT MAJOR CRITERIA';
                            else echo strtoupper($mode);
                        ?>
                    </span>
                </h1>
                <div class="h-1 w-20 bg-[#B1081C] mt-4"></div>
            </div>
            <?php if($mode == 'jobs'): ?>
                <a href="?mode=add_job" class="bg-[#B1081C] text-white px-6 py-3 rounded-xl font-bold text-sm uppercase hover:bg-black transition-all shadow-md">+ Add New Job</a>
            <?php elseif($mode == 'edu'): ?>
                <a href="?mode=add_edu" class="bg-[#B1081C] text-white px-6 py-3 rounded-xl font-bold text-sm uppercase hover:bg-black transition-all shadow-md">+ Add Education</a>
            <?php endif; ?>
        </div>

        <?php if($mode == 'jobs'): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $result = $conn->query("SELECT * FROM jobs WHERE is_deleted = 0 ORDER BY id DESC");
                while($row = $result->fetch_assoc()):
                ?>
                <div class="job-card">
                    <a href="?job_action=delete&id=<?= $row['id'] ?>" onclick="return confirm('ย้ายไปที่ถังขยะ?')" class="btn-delete">✕</a>
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="<?= $row['image_url'] ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300'">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 mb-4"><?= $row['title'] ?></h3>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?= $row['description'] ?></p>
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <a href="?mode=edit_job&id=<?= $row['id'] ?>" class="btn-p1-style">Edit Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?> 
            </div>

        <?php elseif($mode == 'add_job'): ?>
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
                <form action="?mode=insert_action" method="POST" class="space-y-6">
                    <h2 class="text-2xl font-black text-gray-900 uppercase mb-8">เพิ่มข้อมูลอาชีพ</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">ชื่ออาชีพ:</label>
                            <input type="text" name="title" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">บทบาทหลัก:</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-4">เงินเดือน (JR/SR/EXP):</label>
                            <div class="grid grid-cols-3 gap-4">
                                <input type="text" name="sal_jr" placeholder="JR: 25k" class="px-4 py-3 border rounded-lg outline-none">
                                <input type="text" name="sal_sr" placeholder="SR: 50k" class="px-4 py-3 border rounded-lg outline-none">
                                <input type="text" name="sal_exp" placeholder="EXP: 100k+" class="px-4 py-3 border rounded-lg outline-none">
                            </div>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-2">Hard Skills:</label><textarea name="h_skill" rows="3" class="w-full px-4 py-3 border rounded-lg"></textarea></div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-2">Soft Skills:</label><textarea name="s_skill" rows="3" class="w-full px-4 py-3 border rounded-lg"></textarea></div>
                        <div class="md:col-span-2"><label class="block text-sm font-bold text-gray-700 mb-2">Image URL:</label><input type="text" name="image_url" class="w-full px-4 py-3 border rounded-lg"></div>
                    </div>
                    <div class="pt-8 flex space-x-4">
                        <button type="submit" class="flex-1 bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all">บันทึกข้อมูลอาชีพ</button>
                        <a href="?mode=jobs" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase text-center flex items-center justify-center">ยกเลิก</a>
                    </div>
                </form>
            </div>

        <?php elseif($mode == 'edit_job'): 
            $id = intval($_GET['id']);
            $res = $conn->query("SELECT * FROM jobs WHERE id = $id");
            $data = $res->fetch_assoc();
        ?>
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border-2 border-[#B1081C] p-10">
                <form action="admin.php?mode=jobs" method="POST" class="space-y-6">
                    <input type="hidden" name="update_type" value="job_edit_action">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <h2 class="text-2xl font-black text-gray-900 uppercase">แก้ไขข้อมูลอาชีพ</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2"><label class="text-sm font-bold">ชื่ออาชีพ:</label><input type="text" name="title" value="<?= $data['title'] ?>" class="w-full p-3 border rounded-lg"></div>
                        <div class="md:col-span-2"><label class="text-sm font-bold">บทบาทหลัก:</label><textarea name="description" rows="3" class="w-full p-3 border rounded-lg"><?= $data['description'] ?></textarea></div>
                        <div class="p-6 bg-gray-50 rounded-2xl md:col-span-2">
                            <label class="text-sm font-bold block mb-4">เงินเดือน (JR/SR/EXP):</label>
                            <div class="grid grid-cols-3 gap-4">
                                <input type="text" name="sal_jr" value="<?= $data['sal_jr'] ?>" class="p-3 border rounded-lg">
                                <input type="text" name="sal_sr" value="<?= $data['sal_sr'] ?>" class="p-3 border rounded-lg">
                                <input type="text" name="sal_exp" value="<?= $data['sal_exp'] ?>" class="p-3 border rounded-lg">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-bold uppercase">อัปเดตข้อมูลอาชีพ</button>
                </form>
            </div>

        <?php elseif($mode == 'edu'): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $result = $conn->query("SELECT * FROM universities WHERE is_deleted = 0 ORDER BY uni_id DESC");
                while($row = $result->fetch_assoc()):
                ?>
                <div class="job-card">
                    <a href="admin.php?mode=edu&edu_action=uni_delete&uni_id=<?= $row['uni_id'] ?>" onclick="return confirm('ย้ายลงถังขยะนะ?')" class="btn-delete">✕</a>
                    <div class="h-48 overflow-hidden bg-gray-100 flex items-center justify-center p-4">
                        <img src="<?= $row['uni_img'] ?>" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/150'">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 uppercase"><?= $row['uni_name'] ?></h3>
                        <p class="text-gray-400 text-[10px] mb-6 tracking-widest uppercase">ID: #<?= $row['uni_id'] ?></p>
                        <a href="?mode=edit_uni&id=<?= $row['uni_id'] ?>" class="btn-p1-style">Edit University</a>
                    </div>
                </div>
                <?php endwhile; ?> 
            </div>

            <div class="mt-16 bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-8 py-5 bg-gray-50 border-b font-bold text-xs tracking-widest text-gray-400 uppercase">Faculty Management (Manage Majors Here)</div>
                <div class="max-h-[500px] overflow-y-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $f_res = $conn->query("SELECT faculties.*, universities.uni_name FROM faculties INNER JOIN universities ON faculties.uni_id = universities.uni_id ORDER BY faculties.id DESC");
                            while($f = $f_res->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-4">
                                        <img src="<?= $f['fac_img'] ?>" class="w-12 h-12 rounded-lg object-cover" onerror="this.src='https://via.placeholder.com/50'">
                                        <div>
                                            <div class="text-sm font-bold text-gray-800"><?= $f['fac_name'] ?></div>
                                            <div class="text-[10px] text-gray-400 uppercase italic">สังกัด: <?= $f['uni_name'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right space-x-4">
                                    <a href="?mode=edit_major&fac_id=<?= $f['id'] ?>" class="text-[10px] font-bold text-blue-500 hover:text-blue-700 uppercase">Manage Majors</a>
                                    <a href="delete_fac.php?id=<?= $f['id'] ?>" onclick="return confirm('ลบคณะนี้?')" class="text-[10px] font-bold text-red-300 hover:text-red-600 uppercase">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif($mode == 'edit_uni'): 
            $uid = intval($_GET['id']);
            $uni_data = $conn->query("SELECT * FROM universities WHERE uni_id = $uid")->fetch_assoc();
        ?>
            <div class="max-w-xl mx-auto bg-white p-10 rounded-3xl border-2 border-black">
                <form action="admin.php?mode=edu" method="POST" class="space-y-6">
                    <input type="hidden" name="update_type" value="uni_edit_action">
                    <input type="hidden" name="uni_id" value="<?= $uni_data['uni_id'] ?>">
                    <h2 class="text-xl font-black italic uppercase text-[#B1081C]">แก้ไขข้อมูลมหาวิทยาลัย</h2>
                    <input type="text" name="uni_name" value="<?= $uni_data['uni_name'] ?>" class="w-full p-4 border rounded-xl">
                    <input type="text" name="uni_img" value="<?= $uni_data['uni_img'] ?>" class="w-full p-4 border rounded-xl">
                    <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-bold">SAVE CHANGES</button>
                </form>
            </div>

        <?php elseif($mode == 'edit_major'): 
            $fac_id = intval($_GET['fac_id']);
            $majors = $conn->query("SELECT * FROM majors WHERE fac_id = $fac_id");
        ?>
            <div class="mb-6"><a href="?mode=edu" class="text-xs font-bold text-gray-400 hover:text-black uppercase">← Back to Faculties</a></div>
            <div class="grid grid-cols-1 gap-8">
                <?php while($m = $majors->fetch_assoc()): ?>
                <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 hover:border-[#B1081C] transition-all shadow-sm">
                    <form action="admin.php?mode=edu" method="POST" class="space-y-6">
                        <input type="hidden" name="update_type" value="major_edit_action">
                        <input type="hidden" name="major_id" value="<?= $m['major_id'] ?>">
                        
                        <div class="flex justify-between items-center border-b pb-4">
                            <h3 class="text-xl font-black italic text-[#B1081C]"><?= $m['major_name'] ?></h3>
                            <a href="p1_detail.php?id=<?= $m['major_id'] ?>" target="_blank" class="text-[10px] font-bold bg-gray-100 px-4 py-2 rounded-full hover:bg-black hover:text-white transition-all uppercase">Check P1 Detail Site ↗</a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="text-xs font-bold text-gray-400">ชื่อสาขา</label><input type="text" name="major_name" value="<?= $m['major_name'] ?>" class="w-full p-3 bg-gray-50 border rounded-lg font-bold"></div>
                            <div><label class="text-xs font-bold text-gray-400">คำโปรย</label><input type="text" name="major_detail" value="<?= $m['major_description'] ?>" class="w-full p-3 bg-gray-50 border rounded-lg"></div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl">
                            <div><label class="text-[10px] font-bold text-gray-400">TGAT (%)</label><input type="number" name="score_tgat" value="<?= $m['score_tgat'] ?>" class="w-full p-2 border rounded-lg text-center"></div>
                            <div><label class="text-[10px] font-bold text-gray-400">TPAT 3 (%)</label><input type="number" name="score_tpat3" value="<?= $m['score_tpat3'] ?>" class="w-full p-2 border rounded-lg text-center"></div>
                            <div><label class="text-[10px] font-bold text-gray-400">คณิต 1 (%)</label><input type="number" name="score_math1" value="<?= $m['score_alevel_math1'] ?>" class="w-full p-2 border rounded-lg text-center"></div>
                            <div><label class="text-[10px] font-bold text-gray-400">ฟิสิกส์ (%)</label><input type="number" name="score_phy" value="<?= $m['score_alevel_phy'] ?>" class="w-full p-2 border rounded-lg text-center"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="salary_start" value="<?= $m['career_salary'] ?>" placeholder="Salary" class="p-3 border rounded-lg text-sm">
                            <input type="text" name="job_rate" value="<?= $m['career_job_rate'] ?>" placeholder="Job Rate" class="p-3 border rounded-lg text-sm">
                            <select name="market_demand" class="p-3 border rounded-lg text-sm">
                                <option value="สูง" <?= $m['career_demand'] == 'สูง' ? 'selected' : '' ?>>High Demand</option>
                                <option value="กลาง" <?= $m['career_demand'] == 'กลาง' ? 'selected' : '' ?>>Medium Demand</option>
                                <option value="ต่ำ" <?= $m['career_demand'] == 'ต่ำ' ? 'selected' : '' ?>>Low Demand</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <input type="number" name="round_open" value="<?= $m['round_open'] ?>" class="p-2 border rounded text-xs" placeholder="รอบ">
                            <input type="number" name="seats" value="<?= $m['seats'] ?>" class="p-2 border rounded text-xs" placeholder="ที่นั่ง">
                            <input type="text" name="gpax_min" value="<?= $m['gpax_min'] ?>" class="p-2 border rounded text-xs" placeholder="เกรด">
                            <input type="text" name="plan_accept" value="<?= $m['plan_accept'] ?>" class="p-2 border rounded text-xs" placeholder="แผนการเรียน">
                        </div>
                        <textarea name="condition_text" rows="2" class="w-full p-3 border rounded text-xs" placeholder="เงื่อนไขเพิ่มเติม"><?= $m['condition_text'] ?></textarea>

                        <button type="submit" class="w-full bg-[#B1081C] text-white py-3 rounded-xl font-black uppercase hover:bg-black transition-all">บันทึกแก้ไขสาขา</button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>

        <?php elseif($mode == 'add_edu'): 
            $sub_mode = isset($_GET['sub_mode']) ? $_GET['sub_mode'] : 'add_uni';
        ?>
            <div class="flex justify-center space-x-4 mb-10">
                <a href="?mode=add_edu&sub_mode=add_uni" class="px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all <?= $sub_mode == 'add_uni' ? 'bg-[#B1081C] text-white shadow-lg' : 'bg-white text-gray-400 border' ?>">1. มหาวิทยาลัย</a>
                <a href="?mode=add_edu&sub_mode=add_fac" class="px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all <?= $sub_mode == 'add_fac' ? 'bg-[#B1081C] text-white shadow-lg' : 'bg-white text-gray-400 border' ?>">2. คณะ</a>
                <a href="?mode=add_edu&sub_mode=add_major" class="px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all <?= $sub_mode == 'add_major' ? 'bg-[#B1081C] text-white shadow-lg' : 'bg-white text-gray-400 border' ?>">3. สาขา</a>
            </div>

            <div class="max-w-4xl mx-auto bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
                <?php if($sub_mode == 'add_uni'): ?>
                    <form action="?mode=edu" method="POST" class="space-y-6">
                        <input type="hidden" name="edu_type" value="uni_insert">
                        <h2 class="text-2xl font-black uppercase italic text-[#B1081C]">เพิ่มมหาวิทยาลัยใหม่</h2>
                        <input type="text" name="uni_name" placeholder="ชื่อมหาวิทยาลัย" required class="w-full p-4 bg-gray-50 border rounded-xl outline-none focus:border-[#B1081C]">
                        <input type="text" name="uni_img" placeholder="URL รูปโลโก้" required class="w-full p-4 bg-gray-50 border rounded-xl outline-none focus:border-[#B1081C]">
                        <button type="submit" class="w-full bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black">บันทึกมหาวิทยาลัย</button>
                    </form>
                <?php elseif($sub_mode == 'add_fac'): ?>
                    <form action="?mode=edu" method="POST" class="space-y-6">
                        <input type="hidden" name="edu_type" value="fac_insert">
                        <h2 class="text-2xl font-black uppercase italic text-[#B1081C]">เพิ่มคณะ</h2>
                        <select name="uni_id" required class="w-full p-4 bg-gray-50 border rounded-xl outline-none">
                            <option value="">เลือกมหาวิทยาลัยต้นสังกัด</option>
                            <?php 
                                $u_res = $conn->query("SELECT * FROM universities WHERE is_deleted = 0 ORDER BY uni_name ASC");
                                while($u = $u_res->fetch_assoc()) echo "<option value='{$u['uni_id']}'>{$u['uni_name']}</option>";
                            ?>
                        </select>
                        <input type="text" name="fac_name" placeholder="ชื่อคณะ" required class="w-full p-4 bg-gray-50 border rounded-xl">
                        <input type="text" name="fac_img" placeholder="URL รูปภาพคณะ" class="w-full p-4 bg-gray-50 border rounded-xl">
                        <button type="submit" class="w-full bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase">บันทึกคณะ</button>
                    </form>
               <?php elseif($sub_mode == 'add_major'): ?>
    <form action="admin.php?mode=edu" method="POST" class="space-y-8">
        <input type="hidden" name="edu_type" value="major_insert">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase mb-2">เลือกมหาวิทยาลัย</label>
                <select name="uni_id" required onchange="fetchFaculties(this.value)" class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-xl outline-none focus:border-black transition-all">
                    <option value="">-- เลือกมหาวิทยาลัย --</option>
                    <?php 
                        $u_res = $conn->query("SELECT * FROM universities WHERE is_deleted = 0 ORDER BY uni_name ASC");
                        while($u = $u_res->fetch_assoc()) echo "<option value='{$u['uni_id']}'>{$u['uni_name']}</option>";
                    ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase mb-2">เลือกคณะ</label>
                <select id="fac_select" name="fac_id" required class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-xl outline-none focus:border-black transition-all">
                    <option value="">-- กรุณาเลือกมหาวิทยาลัยก่อน --</option>
                </select>
            </div>
        </div>

        <div class="space-y-4">
            <input type="text" name="major_name" placeholder="ชื่อสาขาวิชา (เช่น วิศวกรรมคอมพิวเตอร์)" required class="w-full p-4 border-2 border-gray-100 rounded-xl font-bold text-lg">
            <textarea name="major_detail" rows="3" placeholder="รายละเอียดหรือคำอธิบายสาขา (Description)" class="w-full p-4 border-2 border-gray-100 rounded-xl"></textarea>
        </div>

       <div class="bg-gray-50 p-8 rounded-3xl space-y-6">
            <h3 class="text-sm font-black uppercase tracking-tighter border-l-4 border-[#B1081C] pl-3">Admission Criteria (เกณฑ์คะแนน)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">รอบที่เปิด (1-4)</label>
                    <input type="number" name="round_open" placeholder class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">จำนวนรับ (ที่นั่ง)</label>
                    <input type="number" name="seats" placeholder class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">GPAX ขั้นต่ำ</label>
                    <input type="text" name="gpax_min" placeholder class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">แผนการเรียน</label>
                    <input type="text" name="plan_accept" placeholder class="w-full p-3 border rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">TGAT (%)</label>
                    <input type="number" name="score_tgat" placeholder class="w-full p-3 border rounded-xl border-blue-100">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">TPAT 3 (%)</label>
                    <input type="number" name="score_tpat3" placeholder class="w-full p-3 border rounded-xl border-blue-100">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">A-Level คณิต 1 (%)</label>
                    <input type="number" name="score_math1" placeholder class="w-full p-3 border rounded-xl border-blue-100">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">A-Level ฟิสิกส์ (%)</label>
                    <input type="number" name="score_phy" placeholder class="w-full p-3 border rounded-xl border-blue-100">
                </div>
            </div>
            <textarea name="condition_text" placeholder="หมายเหตุเพิ่มเติม / เงื่อนไขเฉพาะ..." class="w-full p-4 border rounded-xl text-sm"></textarea>
        </div>

        <div style="margin: 20px 0; padding: 20px; border: 1px solid #ccc; background: #fff; border-radius: 15px;">
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">ข้อแนะนำที่ 1 :</label>
                <textarea name="prep_math_text" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 8px;" rows="3"><?= isset($row['prep_math_text']) ? $row['prep_math_text'] : '' ?></textarea>
            </div>

            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">ข้อแนะนำที่ 2 :</label>
                <textarea name="prep_prog_text" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 8px;" rows="3"><?= isset($row['prep_prog_text']) ? $row['prep_prog_text'] : '' ?></textarea>
            </div>
        </div>

        <div class="bg-black text-white p-8 rounded-3xl space-y-6">
            <h3 class="text-sm font-black uppercase tracking-tighter border-l-4 border-[#B1081C] pl-3">Career Projection (ข้อมูลตลาดงาน)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">เงินเดือนเริ่มต้น</label>
                    <input type="text" name="salary_start" placeholder="เช่น 25,000 - 35,000" class="w-full p-3 bg-zinc-900 border border-zinc-700 rounded-xl text-white">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">อัตราการจ้างงาน</label>
                    <input type="text" name="job_rate" placeholder="เช่น 95%" class="w-full p-3 bg-zinc-900 border border-zinc-700 rounded-xl text-white">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">ความต้องการตลาด</label>
                    <select name="market_demand" class="w-full p-3 bg-zinc-900 border border-zinc-700 rounded-xl text-white">
                        <option value="สูง">High Demand (สูง)</option>
                        <option value="กลาง">Medium Demand (กลาง)</option>
                        <option value="ต่ำ">Low Demand (ต่ำ)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-[#B1081C] text-white py-6 rounded-2xl font-black text-xl uppercase italic shadow-2xl hover:bg-white hover:text-black border-2 border-[#B1081C] transition-all">
            ยืนยันการเพิ่มข้อมูลสาขา
        </button>
    </form>
<?php endif; ?>
            </div>

        <?php elseif($mode == 'trash'): ?>
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b bg-gray-50 font-bold uppercase text-xs text-gray-400 tracking-widest">Combined Trash Bin</div>
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-[11px] uppercase text-gray-400">
                        <tr><th class="px-8 py-4">Type</th><th class="px-8 py-4">Title / Name</th><th class="px-8 py-4 text-right">Actions</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $sql = "(SELECT 'JOB' as type, id as main_id, title as name FROM jobs WHERE is_deleted = 1) UNION (SELECT 'UNI' as type, uni_id as main_id, uni_name as name FROM universities WHERE is_deleted = 1) ORDER BY name ASC";
                        $trash = $conn->query($sql);
                        if ($trash && $trash->num_rows > 0):
                            while($item = $trash->fetch_assoc()):
                                $restore_link = ($item['type'] == 'JOB') ? "restore_job.php?id=" : "restore_uni.php?uni_id=";
                                $badge = ($item['type'] == 'JOB') ? "bg-blue-100 text-blue-600" : "bg-purple-100 text-purple-600";
                        ?>
                        <tr>
                            <td class="px-8 py-5"><span class="px-2 py-1 rounded text-[9px] font-black <?= $badge ?>"><?= $item['type'] ?></span></td>
                            <td class="px-8 py-5 text-sm font-bold text-gray-600"><?= $item['name'] ?></td>
                            <td class="px-8 py-5 text-right space-x-4">
                                <a href="<?= $restore_link . $item['main_id'] ?>" class="text-[10px] font-bold text-green-500 uppercase hover:underline">Restore</a>
                                <a href="real_delete.php?type=<?= $item['type'] ?>&id=<?= $item['main_id'] ?>" class="text-[10px] font-bold text-gray-300 hover:text-red-500 uppercase">Permanent Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">ถังขยะว่างเปล่า</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </main>

    <footer class="mt-20 py-10 text-center text-gray-300 text-[10px] uppercase tracking-widest font-bold">
        A-PLATFORM &copy; 2026 Admin Management System
    </footer>

    <script>
    function fetchFaculties(uniId) {
        const facSelect = document.getElementById('fac_select');
        facSelect.innerHTML = '<option value="">กำลังโหลด...</option>';
        fetch('get_faculties.php?uni_id=' + uniId)
            .then(res => res.json())
            .then(data => {
                facSelect.innerHTML = '<option value="">-- เลือกคณะ --</option>';
                data.forEach(fac => { facSelect.innerHTML += `<option value="${fac.id}">${fac.fac_name}</option>`; });
            });
    }
    </script>
</body>
</html>