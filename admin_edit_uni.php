<?php
include 'connect.php';

// --- 1. ส่วนประมวลผล (PHP Logic) ---
$uni_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// A. แก้ไขข้อมูลสาขาแบบละเอียด
// --- 1. ส่วนประมวลผล (PHP Logic) ---
if (isset($_POST['update_major_full'])) {
    $id = $_POST['major_id'];
    $name = $_POST['major_name'];
    $detail = $_POST['major_detail']; // รับจากฟอร์ม
    $round = $_POST['major_round'];
    $seats = $_POST['major_quota']; // จำนวนรับ
    $plans = $_POST['major_plans'];
    $gpax = $_POST['major_gpax'];
    $condition = $_POST['major_condition'];

    // แก้ชื่อคอลัมน์ให้ตรงกับ Screenshot 3 เป๊ะๆ
    $sql = "UPDATE majors SET 
            major_name=?, 
            major_description=?, 
            round_open=?, 
            seats=?, 
            major_plans=?, 
            gpax_min=?, 
            condition_text=? 
            WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    // เช็คประเภทตัวแปร: s=string, i=integer, d=decimal
    // gpax_min ใน DB มึงเป็น decimal(3,2) ให้ใช้ "d" หรือ "s" ก็ได้
    $stmt->bind_param("ssiisssi", 
        $name, $detail, $round, $seats, $plans, $gpax, $condition, $id
    );
    
    if ($stmt->execute()) {
        echo "<script>alert('บันทึกสำเร็จ!'); window.location='?id=$uni_id&tab=maj';</script>";
        exit();
    }
}

// B. แก้ไขมหาลัย
if (isset($_POST['update_university'])) {
    $name = $_POST['uni_name'];
    $img = $_POST['uni_img'];
    $stmt = $conn->prepare("UPDATE universities SET uni_name = ?, uni_img = ? WHERE uni_id = ?");
    $stmt->bind_param("ssi", $name, $img, $uni_id);
    if ($stmt->execute()) { header("Location: admin.php?mode=edu"); exit(); }
}

// C. ลบข้อมูล
if (isset($_GET['del_fac'])) {
    $id = intval($_GET['del_fac']);
    $conn->query("DELETE FROM majors WHERE fac_id = $id");
    $conn->query("DELETE FROM faculties WHERE id = $id");
    header("Location: ?id=$uni_id&tab=fac"); exit();
}
if (isset($_GET['del_maj'])) {
    $id = intval($_GET['del_maj']);
    $conn->query("DELETE FROM majors WHERE id = $id");
    header("Location: ?id=$uni_id&tab=maj"); exit();
}

$tab = $_GET['tab'] ?? 'fac'; 
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Edit System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 py-12 px-5">

    <div class="max-w-4xl mx-auto space-y-10">
        
        <?php if ($uni_id > 0): 
            $res_uni = $conn->query("SELECT * FROM universities WHERE uni_id = $uni_id");
            $data = $res_uni->fetch_assoc();
        ?>
        <div class="bg-white p-10 rounded-3xl shadow-sm border">
            <h2 class="text-2xl font-black mb-8 uppercase text-gray-800 border-l-4 border-red-600 pl-4">Edit University Info</h2>
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2 uppercase">University Name</label>
                    <input type="text" name="uni_name" value="<?= $data['uni_name'] ?>" class="w-full px-4 py-3 border rounded-xl outline-none focus:border-red-600">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2 uppercase">Logo URL</label>
                    <input type="text" name="uni_img" value="<?= $data['uni_img'] ?>" class="w-full px-4 py-3 border rounded-xl outline-none focus:border-red-600">
                </div>
                <div class="flex space-x-4">
                    <button type="submit" name="update_university" class="flex-1 bg-red-700 text-white py-4 rounded-xl font-bold uppercase hover:bg-black transition-all shadow-lg">Update</button>
                    <a href="admin.php?mode=edu" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase text-center flex items-center justify-center">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <?php 
        if (isset($_GET['edit_major'])): 
            $edit_id = intval($_GET['edit_major']);
            $res_m = $conn->query("SELECT * FROM majors WHERE id = $edit_id");
            $edit_data = $res_m->fetch_assoc();
            if($edit_data):
        ?>
        <div id="major-edit-form" class="bg-white p-10 rounded-3xl shadow-2xl border-2 border-blue-500 scroll-mt-10">
            <h2 class="text-2xl font-black mb-8 text-blue-600 uppercase border-b pb-4">📝 Edit Major Details</h2>
            <form action="" method="POST" class="space-y-8">
                <input type="hidden" name="major_id" value="<?= $edit_data['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-800 border-b pb-2">1. Basic Info</h3>
                        <label class="block text-sm font-bold">ชื่อสาขา: 
                            <input type="text" name="major_name" value="<?= $edit_data['major_name'] ?? '' ?>" class="w-full border p-3 rounded-xl mt-1">
                        </label>
                        <label class="block text-sm font-bold">รายละเอียด: 
                            <textarea name="major_detail" rows="3" class="w-full border p-3 rounded-xl mt-1"><?= $edit_data['major_detail'] ?? $edit_data['detail'] ?? '' ?></textarea>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="block text-sm font-bold">รอบที่รับ: 
                                <input type="number" name="major_round" value="<?= $edit_data['major_round'] ?? $edit_data['round'] ?? 0 ?>" class="w-full border p-3 rounded-xl mt-1">
                            </label>
                            <label class="block text-sm font-bold">จำนวนรับ: 
                                <input type="number" name="major_quota" value="<?= $edit_data['major_quota'] ?? $edit_data['quota'] ?? 0 ?>" class="w-full border p-3 rounded-xl mt-1">
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-800 border-b pb-2">2. Requirements</h3>
                        <label class="block text-sm font-bold">แผนการเรียน: 
                            <input type="text" name="major_plans" value="<?= $edit_data['major_plans'] ?? '' ?>" class="w-full border p-3 rounded-xl mt-1">
                        </label>
                        <label class="block text-sm font-bold">เกรดเฉลี่ย (GPAX): 
                            <input type="text" name="major_gpax" value="<?= $edit_data['major_gpax'] ?? '' ?>" class="w-full border p-3 rounded-xl mt-1">
                        </label>
                        <label class="block text-sm font-bold">เงื่อนไขเพิ่มเติม: 
                            <textarea name="major_condition" rows="3" class="w-full border p-3 rounded-xl mt-1"><?= $edit_data['major_condition'] ?? '' ?></textarea>
                        </label>
                    </div>
                </div>

                <div class="pt-6 flex gap-4 border-t">
                    <button type="submit" name="update_major_full" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold uppercase hover:bg-black transition-all shadow-lg">Save All Changes</button>
                    <a href="?id=<?= $uni_id ?>&tab=maj" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold uppercase text-center flex items-center justify-center border">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; endif; ?>

        <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
            <div class="flex border-b bg-gray-50">
                <a href="?id=<?= $uni_id ?>&tab=fac" class="flex-1 py-5 text-center font-bold <?= $tab == 'fac' ? 'bg-white text-red-600 border-t-4 border-red-600' : 'text-gray-400' ?>">1. จัดการคณะ</a>
                <a href="?id=<?= $uni_id ?>&tab=maj" class="flex-1 py-5 text-center font-bold <?= $tab == 'maj' ? 'bg-white text-red-600 border-t-4 border-red-600' : 'text-gray-400' ?>">2. จัดการสาขา</a>
            </div>

            <div class="p-8">
                <?php if ($tab == 'fac'): ?>
                    <div class="grid gap-3">
                        <?php 
                        $facs = $conn->query("SELECT * FROM faculties WHERE uni_id = $uni_id");
                        while($f = $facs->fetch_assoc()): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border hover:border-red-500 transition">
                                <span class="font-bold text-gray-700"><?= $f['fac_name'] ?></span>
                                <div class="flex items-center space-x-3">
                                    <a href="?id=<?= $uni_id ?>&tab=fac&del_fac=<?= $f['id'] ?>" onclick="return confirm('ลบแล้วข้อมูลสาขาข้างในจะหายหมด ยืนยันไหม?')" class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-full hover:bg-red-600 hover:text-white transition">✕</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="grid gap-3">
                        <?php 
                        $majs = $conn->query("SELECT majors.*, faculties.fac_name FROM majors JOIN faculties ON majors.fac_id = faculties.id WHERE faculties.uni_id = $uni_id");
                        while($m = $majs->fetch_assoc()): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border hover:border-pink-500 transition">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase"><?= $m['fac_name'] ?></p>
                                    <span class="font-bold text-gray-700"><?= $m['major_name'] ?></span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="?id=<?= $uni_id ?>&edit_major=<?= $m['id'] ?>&tab=maj#major-edit-form" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition shadow-sm">แก้ไขละเอียด</a>
                                    <a href="?id=<?= $uni_id ?>&tab=maj&del_maj=<?= $m['id'] ?>" onclick="return confirm('ลบสาขานี้จริงไหม?')" class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-full hover:bg-red-600 hover:text-white transition">✕</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>