
<?php 
include 'connect.php'; 
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'jobs';
$sub = isset($_GET['sub']) ? $_GET['sub'] : 'uni_info';
$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// --- บรรทัดที่ประมาณ 4-5 ใน admin.php ---
// --- บรรทัดบนสุด หลังดึงข้อมูล settings ---
if (isset($_POST['update_site'])) {
    // 1. จัดการข้อมูล Text ทั่วไป
    foreach($_POST['settings'] as $key => $value) {
        $key = $conn->real_escape_string($key);
        $value = $conn->real_escape_string($value);
        $conn->query("INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE setting_value = '$value'");
    }

    // 2. จัดการไฟล์รูปภาพ (ถ้ามีการเลือกไฟล์)
    if (isset($_FILES['adviser_image_file']) && $_FILES['adviser_image_file']['error'] == 0) {
        $target_dir = "uploads/"; // อย่าลืมสร้างโฟลเดอร์ชื่อ uploads ในโปรเจกต์มึงด้วย!
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); } // สร้างโฟลเดอร์ถ้ายังไม่มี
        
        $file_ext = pathinfo($_FILES["adviser_image_file"]["name"], PATHINFO_EXTENSION);
        $new_filename = "uni_bg_" . time() . "." . $file_ext; // ตั้งชื่อใหม่กันชื่อซ้ำ
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["adviser_image_file"]["tmp_name"], $target_file)) {
            // บันทึก Path ของไฟล์ลง DB
            $conn->query("UPDATE site_settings SET setting_value = '$target_file' WHERE setting_key = 'adviser_image'");
        }
    }

    header("Location: admin.php?mode=site&status=success");
    exit();
}

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

if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'major_delete') {
    $mid = intval($_GET['major_id']);
    $uid = intval($_GET['uni_id']);
    $conn->query("UPDATE majors SET is_deleted = 1 WHERE id = $mid");
    header("Location: admin.php?mode=edit_uni&id=$uid&sub=maj_detail"); exit();
}

if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'fac_delete') {
    $fid = intval($_GET['fac_id']);
    $uid = intval($_GET['uni_id']);
    $conn->query("UPDATE faculties SET is_deleted = 1 WHERE id = $fid");
    header("Location: admin.php?mode=edit_uni&id=$uid&sub=fac_list"); exit();
}

if (isset($_GET['job_action']) && $_GET['job_action'] == 'restore') {
    $id = intval($_GET['id']);
    $conn->query("UPDATE jobs SET is_deleted = 0 WHERE id = $id");
    header("Location: admin.php?mode=trash"); exit();
}

if (isset($_GET['uni_action']) && $_GET['uni_action'] == 'restore') {
    $id = intval($_GET['id']);
    $conn->query("UPDATE universities SET is_deleted = 0 WHERE uni_id = $id");
    header("Location: admin.php?mode=trash"); exit();
}

if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'fac_restore') {
    $id = intval($_GET['fac_id']);
    $conn->query("UPDATE faculties SET is_deleted = 0 WHERE id = $id");
    header("Location: admin.php?mode=trash"); exit();
}

if (isset($_GET['edu_action']) && $_GET['edu_action'] == 'major_restore') {
    $id = intval($_GET['major_id']);
    $conn->query("UPDATE majors SET is_deleted = 0 WHERE id = $id");
    header("Location: admin.php?mode=trash"); exit();
}

// --- Permanent Delete ---
if (isset($_GET['perm_delete'])) {
    $type = $_GET['perm_delete'];
    $id   = intval($_GET['id']);
    if ($type === 'JOB') {
        $conn->query("DELETE FROM jobs WHERE id = $id AND is_deleted = 1");
    } elseif ($type === 'UNI') {
        $conn->query("DELETE FROM universities WHERE uni_id = $id AND is_deleted = 1");
    } elseif ($type === 'FAC') {
        $conn->query("DELETE FROM faculties WHERE id = $id AND is_deleted = 1");
    } elseif ($type === 'MAJOR') {
        $conn->query("DELETE FROM major_jobs WHERE major_id = $id");
        $conn->query("DELETE FROM majors WHERE id = $id AND is_deleted = 1");
    }
    header("Location: admin.php?mode=trash"); exit();
}

// --- Helper: อัปโหลดรูปภาพ ---
function uploadImage($field_name, $fallback = '') {
    if (!empty($_FILES[$field_name]['name'])) {
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif','svg'];
        if (!in_array($ext, $allowed)) die("ไฟล์ไม่รองรับ! ใช้ jpg, png, webp, gif, svg");
        $filename = uniqid('img_') . '.' . $ext;
        move_uploaded_file($_FILES[$field_name]['tmp_name'], $upload_dir . $filename);
        return 'uploads/' . $filename;
    }
    return $fallback;
}

// --- ส่วนเพิ่มข้อมูลใหม่ (Insert) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update_type'])) {
    
    // เพิ่ม EDUCATION (UNI / FAC / MAJOR)
    if (isset($_POST['edu_type'])) {
        $type = $_POST['edu_type'];

        if ($type == 'uni_insert') {
            $uni_img = uploadImage('uni_img');
            $stmt = $conn->prepare("INSERT INTO universities (uni_name, uni_img, is_deleted) VALUES (?, ?, 0)");
            $stmt->bind_param("ss", $_POST['uni_name'], $uni_img);
            $stmt->execute(); header("Location: admin.php?mode=edu&status=success"); exit();
        }
        
        if ($type == 'fac_insert') {
            $fac_img = uploadImage('fac_img');
            $stmt = $conn->prepare("INSERT INTO faculties (uni_id, fac_name, fac_img) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $_POST['uni_id'], $_POST['fac_name'], $fac_img);
            $stmt->execute(); header("Location: admin.php?mode=edu&status=success"); exit();
        }

        if ($type == 'major_insert') {
            $u_id      = intval($_POST['uni_id']);
            $f_id      = intval($_POST['fac_id']);
            $m_name    = mysqli_real_escape_string($conn, $_POST['major_name']);
            $m_desc    = mysqli_real_escape_string($conn, $_POST['major_detail']);
            $round     = mysqli_real_escape_string($conn, $_POST['round_open']);
            $seats     = intval($_POST['seats']);
            $gpax      = mysqli_real_escape_string($conn, $_POST['gpax_min']);
            $plan      = mysqli_real_escape_string($conn, $_POST['plan_accept']);
            $tgat      = floatval($_POST['score_tgat']);
            $tpat3     = floatval($_POST['score_tpat3']);
            $tgat2     = floatval($_POST['score_tgat2']);
            $tpat1     = floatval($_POST['score_tpat1']);
            $condition = mysqli_real_escape_string($conn, $_POST['condition_text']);
            $salary    = mysqli_real_escape_string($conn, $_POST['salary_start']);
            $rate      = mysqli_real_escape_string($conn, $_POST['job_rate']);
            $dem       = mysqli_real_escape_string($conn, $_POST['market_demand']);
            $prep_math = mysqli_real_escape_string($conn, $_POST['prep_math'] ?? '');
            $prep_prog = mysqli_real_escape_string($conn, $_POST['prep_prog'] ?? '');

            $sql = "INSERT INTO majors (uni_id, fac_id, major_name, major_description, round_open, seats, plan_accept, gpax_min, score_tgat, score_tpat3, score_tgat2, score_tpat1, condition_text, career_salary, career_job_rate, career_demand, prep_math, prep_prog) 
                    VALUES ($u_id, $f_id, '$m_name', '$m_desc', '$round', $seats, '$plan', '$gpax', $tgat, $tpat3, $tgat2, $tpat1, '$condition', '$salary', '$rate', '$dem', '$prep_math', '$prep_prog')";
              
            if (mysqli_query($conn, $sql)) {
                $new_major_id = mysqli_insert_id($conn);
                if (isset($_POST['related_jobs']) && is_array($_POST['related_jobs'])) {
                    foreach ($_POST['related_jobs'] as $job_id) {
                        $jid = intval($job_id);
                        $conn->query("INSERT INTO major_jobs (major_id, job_id) VALUES ($new_major_id, $jid)");
                    }
                }
                echo "<script>alert('บันทึกสาขาสำเร็จ!'); window.location='admin.php?mode=edu';</script>";
                exit();
            } else { die("SQL พังเพราะ: " . mysqli_error($conn)); }
        }
    }
}

// --- ส่วนแก้ไขข้อมูล (Update) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_type'])) {
    $utype = $_POST['update_type'];

    // 1. แก้ไขข้อมูล JOB
    if ($utype == 'job_edit_action') {
        $id = intval($_POST['job_id']);
        $image_url = uploadImage('image_url', $_POST['image_url_current'] ?? '');
        $sql = "UPDATE jobs SET title=?, description=?, sal_jr=?, sal_sr=?, sal_exp=?, h_skill=?, s_skill=?, image_url=?, future=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", 
            $_POST['title'], $_POST['description'], $_POST['sal_jr'], $_POST['sal_sr'], 
            $_POST['sal_exp'], $_POST['h_skill'], $_POST['s_skill'], $image_url, 
            $_POST['future'], $id
        );
        if($stmt->execute()){ header("Location: admin.php?mode=jobs"); exit(); }
    }

    // 2. แก้ไขข้อมูลมหาวิทยาลัย
    if ($utype == 'uni_edit_action') {
        $uni_id = intval($_POST['uni_id']);
        $uni_img = uploadImage('uni_img', $_POST['uni_img_current'] ?? '');
        $stmt = $conn->prepare("UPDATE universities SET uni_name=?, uni_img=? WHERE uni_id=?");
        $stmt->bind_param("ssi", $_POST['uni_name'], $uni_img, $uni_id);
        $stmt->execute(); header("Location: admin.php?mode=edu"); exit();
    }

    // 3. แก้ไขข้อมูลคณะ
    if ($utype == 'fac_edit_action') {
        $fac_id = intval($_POST['fac_id']);
        $uni_id = intval($_POST['uni_id']);
        $fac_img = uploadImage('fac_img', $_POST['fac_img_current'] ?? '');
        $stmt = $conn->prepare("UPDATE faculties SET fac_name=?, fac_img=? WHERE id=?");
        $stmt->bind_param("ssi", $_POST['fac_name'], $fac_img, $fac_id);
        if($stmt->execute()){
            header("Location: admin.php?mode=edit_uni&id=$uni_id&sub=fac_list"); 
            exit();
        }
    }

    // 4. แก้ไขข้อมูลสาขา
    if ($utype == 'major_edit_action') {
        $mid = intval($_POST['major_id']);
        $u_id = intval($_POST['uni_id']);
        $f_id = intval($_POST['fac_id']);
        
        $sql = "UPDATE majors SET 
                major_name=?, major_description=?, round_open=?, seats=?, 
                gpax_min=?, plan_accept=?, score_tgat=?, score_tpat3=?, 
                score_tgat2=?, score_tpat1=?, condition_text=?, 
                career_salary=?, career_job_rate=?, career_demand=?, 
                prep_math=?, prep_prog=? 
                WHERE id=?"; 
                
        $stmt = $conn->prepare($sql);
        
        // ดึงค่าจาก $_POST มาเช็คก่อนบันทึก
        $m_name    = $_POST['major_name'];
        $m_detail  = $_POST['major_detail'];
        $r_open    = $_POST['round_open'];
        $seats     = intval($_POST['seats']);
        $gpax_min  = $_POST['gpax_min'];
        $plan_acc  = $_POST['plan_accept'];
        $s_tgat    = floatval($_POST['score_tgat']);
        $s_tpat3   = floatval($_POST['score_tpat3']);
        $s_tgat2   = floatval($_POST['score_tgat2']);
        $s_tpat1   = floatval($_POST['score_tpat1']);
        $cond_text = $_POST['condition_text'];
        $c_salary  = $_POST['salary_start'];
        $c_rate    = $_POST['job_rate'];
        $c_demand  = $_POST['market_demand'];
        $p_math    = $_POST['prep_math'];
        $p_prog    = $_POST['prep_prog'];

        // bind_param: ลำดับต้องตรงกับ SQL เป๊ะๆ
        // s = string, i = integer, d = double (ทศนิยม)
        $stmt->bind_param("ssssssddddssssssi", 
            $m_name, $m_detail, $r_open, $seats, $gpax_min, $plan_acc,
            $s_tgat, $s_tpat3, $s_tgat2, $s_tpat1, 
            $cond_text, $c_salary, $c_rate, $c_demand, 
            $p_math, $p_prog, $mid
        );

        if($stmt->execute()){
            // จัดการอาชีพที่เกี่ยวข้อง
          $conn->query("DELETE FROM major_jobs WHERE major_id = $mid"); // ลบเก่า
if (isset($_POST['related_jobs']) && is_array($_POST['related_jobs'])) {
    foreach ($_POST['related_jobs'] as $job_id) {
        $jid = intval($job_id);
        $conn->query("INSERT INTO major_jobs (major_id, job_id) VALUES ($mid, $jid)"); // บันทึกใหม่ "ทุกอัน" ที่เลือก
    }
}
            header("Location: admin.php?mode=edit_uni&id=$u_id&sub=maj_detail&fac_id=$f_id&success=1"); 
            exit();
        } else {
            die("Error ในการอัปเดต: " . $stmt->error);
        }
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
        <form action="?mode=insert_action" method="POST" enctype="multipart/form-data" class="space-y-6">
            <h2 class="text-2xl font-black text-gray-900 uppercase mb-8">เพิ่มข้อมูลอาชีพใหม่</h2>
            
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
                        <input type="text" name="sal_jr" placeholder="JR: 25,000" class="px-4 py-3 border border-gray-200 rounded-lg outline-none">
                        <input type="text" name="sal_sr" placeholder="SR: 50,000" class="px-4 py-3 border border-gray-200 rounded-lg outline-none">
                        <input type="text" name="sal_exp" placeholder="EXP: 100,000+" class="px-4 py-3 border border-gray-200 rounded-lg outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hard Skills:</label>
                    <textarea name="h_skill" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Soft Skills:</label>
                    <textarea name="s_skill" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">แนวโน้มอนาคต :</label>
                    <textarea name="future" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none" placeholder></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพอาชีพ:</label>
                    <input type="file" name="image_url" accept="image/*" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
                </div>
            </div>

            <div class="pt-8 flex space-x-4">
                <button type="submit" class="flex-1 bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all">บันทึกข้อมูลอาชีพ</button>
                <a href="?mode=jobs" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase text-center flex items-center justify-center">ยกเลิก</a>
            </div>
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

            <?php elseif($mode == 'edit_job'): 
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM jobs WHERE id = $id");
    $job = $res->fetch_assoc();
    if(!$job) { echo "ไม่พบข้อมูลอาชีพ"; }
    else {
?>
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
        <form action="admin.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="update_type" value="job_edit_action">
            <input type="hidden" name="job_id" value="<?= $job['id'] ?>">

            <h2 class="text-2xl font-black text-gray-900 uppercase mb-8">แก้ไขข้อมูลอาชีพ: <?= $job['title'] ?></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">ชื่ออาชีพ:</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($job['title']) ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">บทบาทหลัก:</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"><?= htmlspecialchars($job['description']) ?></textarea>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-4">เงินเดือน (JR/SR/EXP):</label>
                    <div class="grid grid-cols-3 gap-4">
                        <input type="text" name="sal_jr" value="<?= htmlspecialchars($job['sal_jr']) ?>" placeholder="JR" class="px-4 py-3 border border-gray-200 rounded-lg outline-none">
                        <input type="text" name="sal_sr" value="<?= htmlspecialchars($job['sal_sr']) ?>" placeholder="SR" class="px-4 py-3 border border-gray-200 rounded-lg outline-none">
                        <input type="text" name="sal_exp" value="<?= htmlspecialchars($job['sal_exp']) ?>" placeholder="EXP" class="px-4 py-3 border border-gray-200 rounded-lg outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hard Skills:</label>
                    <textarea name="h_skill" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"><?= htmlspecialchars($job['h_skill']) ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Soft Skills:</label>
                    <textarea name="s_skill" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"><?= htmlspecialchars($job['s_skill']) ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">แนวโน้มอนาคต (Future):</label>
                    <textarea name="future" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none"><?= htmlspecialchars($job['future'] ?? '') ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพอาชีพ (เลือกใหม่ หรือปล่อยว่างเพื่อใช้รูปเดิม):</label>
                    <?php if (!empty($job['image_url'])): ?><img src="<?= htmlspecialchars($job['image_url']) ?>" class="h-16 w-16 object-cover rounded-lg mb-2 border"><?php endif; ?>
                    <input type="hidden" name="image_url_current" value="<?= htmlspecialchars($job['image_url']) ?>">
                    <input type="file" name="image_url" accept="image/*" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#B1081C] outline-none file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
                </div>
            </div>

            <div class="pt-8 flex space-x-4">
                <button type="submit" class="flex-1 bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all shadow-lg">อัปเดตข้อมูล</button>
                <a href="?mode=jobs" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase text-center flex items-center justify-center">ยกเลิก</a>
            </div>
        </form>
    </div>
    
<?php } ?>

   <?php elseif($mode == 'edit_uni'): 
    $uid = intval($_GET['id']);
    // ดึงข้อมูลมหาลัยมาล่วงหน้าเลย จะได้มีค่าไปโชว์ใน Input
    $uni_res = $conn->query("SELECT * FROM universities WHERE uni_id = $uid");
    $uni_data = $uni_res->fetch_assoc();

    // จุดตาย: ถ้าใน URL ไม่มี sub ให้มันเป็น uni_info อัตโนมัติ หน้าจะได้ไม่ว่างไอ้สัส!
    $sub = isset($_GET['sub']) ? $_GET['sub'] : 'uni_info'; 
?>
    <div class="flex justify-center space-x-4 mb-10">
        <a href="?mode=edit_uni&id=<?= $uid ?>&sub=uni_info" class="px-8 py-3 rounded-2xl font-bold text-sm transition-all <?= $sub == 'uni_info' ? 'bg-[#B1081C] text-white shadow-lg' : 'bg-white text-gray-400 border' ?>">1. มหาวิทยาลัย</a>
        <a href="?mode=edit_uni&id=<?= $uid ?>&sub=fac_list" class="px-8 py-3 rounded-2xl font-bold text-sm transition-all <?= $sub == 'fac_list' ? 'bg-[#B1081C] text-white shadow-lg' : 'bg-white text-gray-400 border' ?>">2. คณะ</a>
        <a href="?mode=edit_uni&id=<?= $uid ?>&sub=maj_detail" class="px-8 py-3 rounded-2xl font-bold text-sm transition-all <?= $sub == 'maj_detail' ? 'bg-[#B1081C] text-white shadow-lg' : 'bg-white text-gray-400 border' ?>">3. สาขา</a>
    </div>

    <div class="max-w-4xl mx-auto">
        <?php if($sub == 'uni_info'): ?>
            <div class="bg-white p-10 rounded-3xl border-2 border-gray-100 shadow-sm">
                <h2 class="text-2xl font-black text-gray-900 uppercase mb-8">แก้ไขข้อมูลมหาวิทยาลัย</h2>
                
                <form action="admin.php?mode=edit_uni&id=<?= $uid ?>&sub=uni_info" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="update_type" value="uni_edit_action">
                    <input type="hidden" name="uni_id" value="<?= $uid ?>">

                    <div class="space-y-2 text-left">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">ชื่อมหาวิทยาลัย</label>
                        <input type="text" name="uni_name" value="<?= htmlspecialchars($uni_data['uni_name'] ?? '') ?>" placeholder="ชื่อมหาวิทยาลัย" required 
                               class="w-full p-4 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:border-[#B1081C] font-bold">
                    </div>
                    
                    <div class="space-y-2 text-left">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">รูปโลโก้ (เลือกไฟล์)</label>
                        <?php if (!empty($uni_data['uni_img'])): ?><img src="<?= htmlspecialchars($uni_data['uni_img']) ?>" class="h-12 w-12 object-contain rounded-lg border mb-1"><?php endif; ?>
                        <input type="hidden" name="uni_img_current" value="<?= htmlspecialchars($uni_data['uni_img'] ?? '') ?>">
                        <input type="file" name="uni_img" accept="image/*" class="w-full p-2 bg-gray-50 border rounded-xl file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
                    </div>

                    <button type="submit" class="w-full bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all shadow-lg mt-4">
                        บันทึกการแก้ไขมหาวิทยาลัย
                    </button
                </form>
            </div>
        


       <?php elseif($sub == 'fac_list'): ?>
    <div class="space-y-4">
        <?php 
        $fac_res = $conn->query("SELECT * FROM faculties WHERE uni_id = $uid AND is_deleted = 0");
        if($fac_res && $fac_res->num_rows > 0):
            while($f = $fac_res->fetch_assoc()): ?>
                <div class="bg-white rounded-[2rem] border-2 border-gray-100 overflow-hidden shadow-sm mb-4 text-left">
                    <div class="w-full p-6 flex justify-between items-center bg-white border-b-2 border-gray-50">
                        <button type="button" onclick="toggle('form-f-<?= $f['id'] ?>')" class="flex-1 text-left font-black uppercase italic group">
                            <span class="text-xl text-gray-800 group-hover:text-[#B1081C]"><?= $f['fac_name'] ?></span>
                            <span class="ml-4 text-[10px] bg-gray-100 px-3 py-1 rounded-full text-gray-400 font-bold uppercase">Click to Edit</span>
                        </button>
                        
                        <a href="admin.php?edu_action=fac_delete&fac_id=<?= $f['id'] ?>&uni_id=<?= $uid ?>" 
                           onclick="return confirm('จะลบคณะนี้ใช่ไหม?')" 
                           class="ml-4 p-3 bg-red-50 text-red-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>
                    </div>

                    <div id="form-f-<?= $f['id'] ?>" class="hidden p-10 bg-gray-50/30 border-t-2 border-dashed border-gray-100">
                        <form action="admin.php?mode=edit_uni&id=<?= $uid ?>&sub=fac_list" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <input type="hidden" name="update_type" value="fac_edit_action">
                            <input type="hidden" name="fac_id" value="<?= $f['id'] ?>">
                            <input type="hidden" name="uni_id" value="<?= $uid ?>">

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">ชื่อคณะ (Faculty Name)</label>
                                <input type="text" name="fac_name" value="<?= htmlspecialchars($f['fac_name']) ?>" 
                                       class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl font-bold focus:border-[#B1081C] outline-none" required>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">รูปภาพคณะ (เลือกไฟล์)</label>
                                <?php if (!empty($f['fac_img'])): ?><img src="<?= htmlspecialchars($f['fac_img']) ?>" class="h-12 w-12 object-contain rounded-lg border mb-1"><?php endif; ?>
                                <input type="hidden" name="fac_img_current" value="<?= htmlspecialchars($f['fac_img']) ?>">
                                <input type="file" name="fac_img" accept="image/*" class="w-full p-2 bg-white border-2 border-gray-100 rounded-2xl file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
                            </div>

                            <button type="submit" class="md:col-span-2 bg-black text-white py-5 rounded-2xl font-black uppercase shadow-xl hover:bg-[#B1081C] transition-all">
                                Update Faculty Information
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; 
        else: ?>
            <div class="py-20 text-center bg-gray-50 rounded-[3rem] text-gray-400 font-bold border-2 border-dashed uppercase italic">No Faculty Found</div>
        <?php endif; ?>
    </div>
<?php elseif($sub == 'fac_detail'): ?>
    <div class="space-y-4">
        <?php 
        // ดึงเฉพาะคณะที่ยังไม่ถูกลบของมหาวิทยาลัยนี้
        $fac_res = $conn->query("SELECT * FROM faculties WHERE uni_id = $uid AND is_deleted = 0");
        while($f = $fac_res->fetch_assoc()): ?>
            <div class="bg-white rounded-[2rem] border-2 border-gray-100 mb-4 overflow-hidden shadow-sm">
                <div class="w-full p-6 flex justify-between items-center bg-white border-b-2 border-gray-50">
                    <button onclick="toggle('form-f-<?= $f['id'] ?>')" class="flex-1 text-left font-black uppercase italic hover:opacity-70 transition-all">
                        <span class="text-xl text-gray-800"><?= $f['fac_name'] ?></span>
                        <span class="ml-4 text-[10px] bg-gray-100 px-3 py-1 rounded-full text-gray-400">CLICK TO EDIT</span>
                    </button>

                    <a href="admin.php?edu_action=fac_delete&fac_id=<?= $f['id'] ?>&uni_id=<?= $uid ?>" 
                       onclick="return confirm('จะลบคณะนี้จริงดิ?')"
                       class="ml-4 p-3 bg-red-50 text-red-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </div>

                <div id="form-f-<?= $f['id'] ?>" class="hidden p-10 bg-gray-50/20 border-t-2 border-dashed border-gray-100 text-left">
                    <form action="admin.php?mode=edit_uni&id=<?= $uid ?>&sub=fac_detail" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <input type="hidden" name="update_type" value="fac_edit_action">
                        <input type="hidden" name="fac_id" value="<?= $f['id'] ?>">
                        <input type="hidden" name="uni_id" value="<?= $uid ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase">Faculty Name</label>
                                <input type="text" name="fac_name" value="<?= htmlspecialchars($f['fac_name']) ?>" 
                                       class="w-full p-4 border-2 rounded-2xl font-bold focus:border-black outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 uppercase">รูปภาพคณะ (เลือกไฟล์)</label>
                                <?php if (!empty($f['fac_img'])): ?><img src="<?= htmlspecialchars($f['fac_img']) ?>" class="h-12 w-12 object-contain rounded-lg border mb-1"><?php endif; ?>
                                <input type="hidden" name="fac_img_current" value="<?= htmlspecialchars($f['fac_img']) ?>">
                                <input type="file" name="fac_img" accept="image/*" class="w-full p-2 bg-white border-2 border-gray-100 rounded-2xl file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 bg-black text-white py-5 rounded-[1.5rem] font-black text-lg shadow-xl hover:bg-gray-800 transition-all active:scale-[0.98]">
                                UPDATE FACULTY INFO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
       <?php elseif($sub == 'maj_detail'): ?>
    <div class="bg-white p-6 rounded-3xl border-2 border-gray-100 shadow-sm mb-6 flex flex-wrap justify-center gap-2">
        <?php
        $fac_res = $conn->query("SELECT * FROM faculties WHERE uni_id = $uid");
        $selected_fac = isset($_GET['fac_id']) ? intval($_GET['fac_id']) : 0;
        while($fb = $fac_res->fetch_assoc()): ?>
            <a href="?mode=edit_uni&id=<?= $uid ?>&sub=maj_detail&fac_id=<?= $fb['id'] ?>" 
               class="px-5 py-2 rounded-xl font-bold text-xs border-2 <?= $selected_fac == $fb['id'] ? 'bg-black text-white border-black' : 'bg-white text-gray-400 border-gray-100' ?>">
                <?= $fb['fac_name'] ?>
            </a>
        <?php endwhile; ?>
    </div>

  <?php if($selected_fac > 0): ?>
    <div class="space-y-4">
        <?php 
        // --- บรรทัดนี้แหละที่มึงขาดไป! คือการดึงข้อมูลมาใส่ตัวแปร $majors ---
        $majors = $conn->query("SELECT * FROM majors WHERE fac_id = $selected_fac AND is_deleted = 0");
        
        // เช็คเผื่อไว้หน่อยว่า Query ผ่านไหมและมีข้อมูลไหม
        if($majors && $majors->num_rows > 0):
            while($m = $majors->fetch_assoc()): 
                // กำหนด ID ให้ชัวร์ (ใช้ $m['id'] ตามโครงสร้างตารางหลัก)
                $curr_mid = $m['id']; 
        ?>
            <div class="bg-white rounded-[2rem] border-2 border-gray-100 mb-8 overflow-hidden shadow-sm text-left">
                <div class="w-full p-6 flex justify-between items-center bg-white border-b-2 border-gray-50">
                    <button type="button" onclick="toggle('form-m-<?= $curr_mid ?>')" class="flex-1 text-left font-black uppercase italic hover:opacity-70 transition-all">
                        <span class="text-xl text-gray-800"><?= $m['major_name'] ?></span>
                        <span class="ml-4 text-[10px] bg-gray-100 px-3 py-1 rounded-full text-gray-400">CLICK TO EDIT</span>
                    </button>

                    <a href="admin.php?edu_action=major_delete&major_id=<?= $curr_mid ?>&uni_id=<?= $uid ?>" 
                       onclick="return confirm('จะลบจริงดิ?')"
                       class="ml-4 p-3 bg-red-50 text-red-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </div>

                <div id="form-m-<?= $curr_mid ?>" class="hidden p-8 space-y-6 bg-gray-50/30">
                    
                    <form action="admin.php?mode=edit_uni&id=<?= $uid ?>&sub=maj_detail&fac_id=<?= $selected_fac ?>" method="POST" class="space-y-6">
                        <input type="hidden" name="update_type" value="major_edit_action">
                        <input type="hidden" name="major_id" value="<?= $curr_mid ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">ชื่อสาขา</label>
                                <input type="text" name="major_name" value="<?= htmlspecialchars($m['major_name']) ?>" class="w-full p-4 border-2 rounded-xl font-bold focus:border-black outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">รายละเอียดสาขา</label>
                                <textarea name="major_detail" class="w-full p-4 border-2 rounded-xl outline-none focus:border-black"><?= htmlspecialchars($m['major_description']) ?></textarea>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 space-y-6 shadow-sm">
                            <h3 class="text-sm font-black uppercase tracking-tighter border-l-4 border-[#B1081C] pl-3 text-gray-600">Admission Criteria</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div><label class="text-[10px] font-bold text-gray-400 uppercase">รอบที่เปิด</label>
                                <input type="number" name="round_open" value="<?= $m['round_open'] ?>" class="w-full p-3 border rounded-xl"></div>
                                <div><label class="text-[10px] font-bold text-gray-400 uppercase">จำนวนที่รับ</label>
                                <input type="number" name="seats" value="<?= $m['seats'] ?>" class="w-full p-3 border rounded-xl"></div>
                                <div><label class="text-[10px] font-bold text-gray-400 uppercase">GPAX ขั้นต่ำ</label>
                                <input type="text" name="gpax_min" value="<?= $m['gpax_min'] ?>" class="w-full p-3 border rounded-xl"></div>
                                <div><label class="text-[10px] font-bold text-gray-400 uppercase">แผนการเรียน</label>
                                <input type="text" name="plan_accept" value="<?= $m['plan_accept'] ?>" class="w-full p-3 border rounded-xl"></div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">TGAT (%)</label>
                                    <input type="number" step="0.01" name="score_tgat" value="<?= $m['score_tgat'] ?>" placeholder="TGAT %" class="w-full p-3 border rounded-xl">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">TPAT 3 (%)</label>
                                    <input type="number" step="0.01" name="score_tpat3" value="<?= $m['score_tpat3'] ?>" placeholder="TPAT3 %" class="w-full p-3 border rounded-xl">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">TGAT2 (%)</label>
                                    <input type="number" step="0.01" name="score_tgat2" value="<?= $m['score_tgat2'] ?? 0 ?>" placeholder="TGAT2 %" class="w-full p-3 border rounded-xl">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">TPAT1 (%)</label>
                                    <input type="number" step="0.01" name="score_tpat1" value="<?= $m['score_tpat1'] ?? 0 ?>" placeholder="TPAT1 %" class="w-full p-3 border rounded-xl">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">หมายเหตุ / เงื่อนไขเพิ่มเติม</label>
                                <textarea name="condition_text" rows="3" class="w-full p-3 border rounded-xl text-xs outline-none focus:border-black" placeholder="กรอกหมายเหตุที่นี่..."><?= htmlspecialchars($m['condition_text'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-6 bg-[#B1081C]/5 rounded-3xl border border-[#B1081C]/10 space-y-2">
                                <label class="font-bold text-sm block text-[#B1081C] uppercase tracking-wider">การเตรียมตัว (Mathematics)</label>
                                <textarea name="prep_math" class="w-full p-4 bg-white rounded-xl border-2 border-gray-100 outline-none focus:border-[#B1081C]" rows="4"><?= htmlspecialchars($m['prep_math'] ?? '') ?></textarea>
                            </div>
                            <div class="p-6 bg-black/5 rounded-3xl border border-black/10 space-y-2">
                                <label class="font-bold text-sm block text-gray-700 uppercase tracking-wider">การเตรียมตัว (Programming)</label>
                                <textarea name="prep_prog" class="w-full p-4 bg-white rounded-xl border-2 border-gray-100 outline-none focus:border-black" rows="4"><?= htmlspecialchars($m['prep_prog'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="bg-black text-white p-8 rounded-3xl space-y-6 mt-6">
    <h3 class="text-sm font-black uppercase tracking-tighter border-l-4 border-[#B1081C] pl-3">Career Projection (ข้อมูลตลาดงาน)</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase">เงินเดือนเริ่มต้น</label>
            <input type="text" name="salary_start" value="<?= $m['career_salary'] ?>" placeholder="เช่น 25,000 - 35,000" class="w-full p-3 bg-zinc-900 border border-zinc-700 rounded-xl text-white">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase">อัตราการจ้างงาน</label>
            <input type="text" name="job_rate" value="<?= $m['career_job_rate'] ?>" placeholder="เช่น 95%" class="w-full p-3 bg-zinc-900 border border-zinc-700 rounded-xl text-white">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase">ความต้องการตลาด</label>
            <select name="market_demand" class="w-full p-3 bg-zinc-900 border border-zinc-700 rounded-xl text-white">
                <option value="สูง" <?= $m['career_demand'] == 'สูง' ? 'selected' : '' ?>>High Demand (สูง)</option>
                <option value="กลาง" <?= $m['career_demand'] == 'กลาง' ? 'selected' : '' ?>>Medium Demand (กลาง)</option>
                <option value="ต่ำ" <?= $m['career_demand'] == 'ต่ำ' ? 'selected' : '' ?>>Low Demand (ต่ำ)</option>
            </select>
        </div>
    </div>
</div>

                        <div class="bg-gray-50 p-6 rounded-2xl border-2 border-dashed border-gray-100 mt-4 text-left">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Select Careers (J1 System)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <?php 
                                $all_jobs = $conn->query("SELECT id, title FROM jobs WHERE is_deleted = 0");
                                $selected_jobs = [];
                                // ดึงอาชีพที่เคยเลือกไว้แล้วมาโชว์ (ใช้ ID สาขาปัจจุบัน)
                                $get_saved = $conn->query("SELECT job_id FROM major_jobs WHERE major_id = " . $curr_mid);
                                while($sj = $get_saved->fetch_assoc()) { $selected_jobs[] = $sj['job_id']; }

                                while($job = $all_jobs->fetch_assoc()): 
                                ?>
                                    <label class="flex items-center space-x-2 p-2 bg-white rounded-lg border cursor-pointer hover:border-[#B1081C]">
                                        <input type="checkbox" name="related_jobs[]" value="<?= $job['id'] ?>" 
                                               <?= in_array($job['id'], $selected_jobs) ? 'checked' : '' ?>
                                               class="accent-[#B1081C]">
                                        <span class="text-[10px] font-bold truncate"><?= $job['title'] ?></span>
                                    </label>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#B1081C] text-white py-6 rounded-[2rem] font-black text-xl shadow-xl hover:bg-black transition-all active:scale-[0.98]">
                            UPDATE MAJOR INFORMATION
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-20 text-center rounded-[3rem] border-2 border-dashed border-gray-100">
                <p class="text-gray-400 font-bold uppercase tracking-widest">ยังไม่มีข้อมูลสาขาในคณะนี้</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php endif; ?>



     <?php elseif($mode == 'edit_major'): 
    $fac_id = intval($_GET['fac_id']);
    $fac_info = $conn->query("SELECT uni_id FROM faculties WHERE id = $fac_id")->fetch_assoc();
    $uni_id = $fac_info['uni_id'];
    $majors = $conn->query("SELECT * FROM majors WHERE fac_id = $fac_id AND is_deleted = 0");
?>
    <div class="mb-6">
        <a href="?mode=edit_uni&id=<?= $uni_id ?>&sub=maj_detail&fac_id=<?= $fac_id ?>" class="text-xs font-bold text-gray-400 hover:text-black uppercase">← Back to Faculty Detail</a>
    </div>

    <div class="grid grid-cols-1 gap-8">
        <?php while($m = $majors->fetch_assoc()): $mid = $m['id']; ?>
        <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 hover:border-[#B1081C] transition-all shadow-sm">
            <form action="admin.php" method="POST" class="space-y-6">
                <input type="hidden" name="update_type" value="major_edit_action">
                <input type="hidden" name="major_id" value="<?= $mid ?>">
                <input type="hidden" name="uni_id" value="<?= $uni_id ?>">
                <input type="hidden" name="fac_id" value="<?= $fac_id ?>">
                
                <h3 class="text-xl font-black italic text-[#B1081C] border-b pb-4"><?= $m['major_name'] ?></h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">ชื่อสาขา</label>
                        <input type="text" name="major_name" value="<?= $m['major_name'] ?>" class="w-full p-3 bg-gray-50 border rounded-lg font-bold">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">คำโปรย (major_detail)</label>
                        <input type="text" name="major_detail" value="<?= $m['major_description'] ?>" class="w-full p-3 bg-gray-50 border rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-red-50 p-4 rounded-xl border border-red-100">
                    <div>
                        <label class="text-[10px] font-black text-[#B1081C] uppercase block mb-1">TGAT (%)</label>
                        <input type="number" step="0.01" name="score_tgat" value="<?= $m['score_tgat'] ?>" class="w-full p-2 border rounded-lg text-center font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#B1081C] uppercase block mb-1">TPAT 3 (%)</label>
                        <input type="number" step="0.01" name="score_tpat3" value="<?= $m['score_tpat3'] ?>" class="w-full p-2 border rounded-lg text-center font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#B1081C] uppercase block mb-1">TGAT 2 (%)</label>
                        <input type="number" step="0.01" name="score_tgat2" value="<?= $m['score_tgat2'] ?>" class="w-full p-2 border rounded-lg text-center font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#B1081C] uppercase block mb-1">TPAT 1 (%)</label>
                        <input type="number" step="0.01" name="score_tpat1" value="<?= $m['score_tpat1'] ?>" class="w-full p-2 border rounded-lg text-center font-bold">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">หมายเหตุ / เงื่อนไขเพิ่มเติม (condition_text)</label>
                    <textarea name="condition_text" rows="3" class="w-full p-3 border rounded-lg text-xs" placeholder="กรอกหมายเหตุที่นี่..."><?= $m['condition_text'] ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">เตรียมตัว 1 (prep_math)</label>
                        <textarea name="prep_math" rows="2" class="w-full p-3 bg-white border rounded-lg text-xs"><?= $m['prep_math'] ?></textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">เตรียมตัว 2 (prep_prog)</label>
                        <textarea name="prep_prog" rows="2" class="w-full p-3 bg-white border rounded-lg text-xs"><?= $m['prep_prog'] ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">เงินเดือน (salary_start)</label>
                    <input type="text" name="salary_start" value="<?= $m['career_salary'] ?>" class="w-full p-3 border rounded-lg text-sm font-bold"></div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">อัตราได้งาน (job_rate)</label>
                    <input type="text" name="job_rate" value="<?= $m['career_job_rate'] ?>" class="w-full p-3 border rounded-lg text-sm font-bold"></div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">ความต้องการ (market_demand)</label>
                    <input type="text" name="market_demand" value="<?= $m['career_demand'] ?>" class="w-full p-3 border rounded-lg text-sm font-bold"></div>
                </div>

                <button type="submit" class="w-full bg-[#B1081C] text-white py-4 rounded-xl font-black uppercase hover:bg-black transition-all shadow-lg">บันทึกข้อมูลสาขานี้</button>
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
                    <form action="?mode=edu" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="edu_type" value="uni_insert">
                        <h2 class="text-2xl font-black uppercase italic text-[#B1081C]">เพิ่มมหาวิทยาลัยใหม่</h2>
                        <input type="text" name="uni_name" placeholder="ชื่อมหาวิทยาลัย" required class="w-full p-4 bg-gray-50 border rounded-xl outline-none focus:border-[#B1081C]">
                        <label class="text-xs font-bold text-gray-400 uppercase">รูปโลโก้</label>
                        <input type="file" name="uni_img" accept="image/*" class="w-full p-2 bg-gray-50 border rounded-xl file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
                        <button type="submit" class="w-full bg-[#B1081C] text-white py-4 rounded-xl font-bold uppercase hover:bg-black">บันทึกมหาวิทยาลัย</button>
                    </form>
                <?php elseif($sub_mode == 'add_fac'): ?>
                    <form action="?mode=edu" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        <label class="text-xs font-bold text-gray-400 uppercase">รูปภาพคณะ</label>
                        <input type="file" name="fac_img" accept="image/*" class="w-full p-2 bg-gray-50 border rounded-xl file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-red-50 file:text-[#B1081C] hover:file:bg-red-100">
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
                    <label class="text-[10px] font-black text-gray-400 uppercase">TGAT2 (%)</label>
                    <input type="number" name="score_tgat2" placeholder class="w-full p-3 border rounded-xl border-blue-100">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase">TPAT1 (%)</label>
                    <input type="number" name="score_tpat1" placeholder class="w-full p-3 border rounded-xl border-blue-100">
                </div>
            </div>
            <textarea name="condition_text" placeholder="หมายเหตุเพิ่มเติม / เงื่อนไขเฉพาะ..." class="w-full p-4 border rounded-xl text-sm"></textarea>
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
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[10px] font-bold text-[#B1081C] uppercase">เตรียมตัว 1</label>
            <textarea name="prep_math" rows="3" class="w-full p-3 bg-white border rounded-lg text-xs" placeholder="ใส่คำแนะนำ..."></textarea>
        </div>
        <div>
            <label class="text-[10px] font-bold text-black uppercase">เตรียมตัว 2</label>
            <textarea name="prep_prog" rows="3" class="w-full p-3 bg-white border rounded-lg text-xs" placeholder="ใส่คำแนะนำ..."></textarea>
        </div>
    </div>

    <div class="bg-gray-50 p-6 rounded-2xl border-2 border-dashed border-gray-100">
        <h4 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Select Careers (J1 System)</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <?php 
            $all_jobs = $conn->query("SELECT id, title FROM jobs WHERE is_deleted = 0");
            while($job = $all_jobs->fetch_assoc()): 
            ?>
                <label class="flex items-center space-x-2 p-2 bg-white rounded-lg border text-left cursor-pointer hover:border-[#B1081C]">
                    <input type="checkbox" name="related_jobs[]" value="<?= $job['id'] ?>" class="accent-[#B1081C]">
                    <span class="text-[10px] font-bold truncate"><?= $job['title'] ?></span>
                </label>
            <?php endwhile; ?>
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
    <div class="bg-white rounded-[2rem] border-2 border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b-2 border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <th class="px-8 py-4">Type</th>
                    <th class="px-8 py-4">Name</th>
                    <th class="px-8 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php 
                $trash_res = $conn->query("
                    SELECT 'UNI' as type, uni_id as main_id, uni_name as name FROM universities WHERE is_deleted = 1
                    UNION
                    SELECT 'FAC' as type, id as main_id, fac_name as name FROM faculties WHERE is_deleted = 1
                    UNION
                    SELECT 'MAJOR' as type, id as main_id, major_name as name FROM majors WHERE is_deleted = 1
                    UNION
                    SELECT 'JOB' as type, id as main_id, title as name FROM jobs WHERE is_deleted = 1
                ");
                if($trash_res->num_rows > 0):
                    while($item = $trash_res->fetch_assoc()): 
                        // แก้ไข Logic การสร้าง Link กู้คืนตรงนี้
                        if ($item['type'] == 'MAJOR') {
                            $badge = "bg-purple-100 text-purple-600";
                            $restore_link = "admin.php?edu_action=major_restore&major_id=";
                        } elseif ($item['type'] == 'FAC') {
                            $badge = "bg-orange-100 text-orange-600";
                            $restore_link = "admin.php?edu_action=fac_restore&fac_id=";
                        } elseif ($item['type'] == 'UNI') {
                            $badge = "bg-blue-100 text-blue-600";
                            $restore_link = "admin.php?uni_action=restore&id=";
                        } else {
                            $badge = "bg-green-100 text-green-600";
                            $restore_link = "admin.php?job_action=restore&id=";
                        }
                ?>
                <tr>
                    <td class="px-8 py-5">
                        <span class="px-2 py-1 rounded text-[9px] font-black <?= $badge ?>"><?= $item['type'] ?></span>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-gray-600"><?= $item['name'] ?></td>
                    <td class="px-8 py-5 text-right space-x-4">
                        <a href="<?= $restore_link . $item['main_id'] ?>" class="text-[10px] font-bold text-green-500 uppercase hover:underline">Restore</a>
                        <a href="admin.php?perm_delete=<?= $item['type'] ?>&id=<?= $item['main_id'] ?>" onclick="return confirm('ลบถาวรเลยนะ?')" class="text-[10px] font-bold text-gray-300 hover:text-red-500 uppercase">Permanent Delete</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="3" class="px-8 py-20 text-center text-gray-400 italic font-bold">ถังขยะว่างเปล่า</td></tr>
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
    function toggle(id) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('hidden');
}
    </script>
    
</body>
</html>