<?php
include 'connect.php';
$uni_id = isset($_GET['id']) ? intval($_GET['id']) : die("ไอเหี้ย มึงลืมส่ง ID มหาลัยมาใน URL");

// --- 1. ส่วนประมวลผลข้อมูล (PHP Logic) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_uni'])) {
        $stmt = $conn->prepare("UPDATE universities SET uni_name=?, uni_img=? WHERE uni_id=?");
        $stmt->bind_param("ssi", $_POST['uni_name'], $_POST['uni_img'], $uni_id);
        $stmt->execute();
    } elseif (isset($_POST['save_fac'])) {
        $stmt = $conn->prepare("UPDATE faculties SET fac_name=? WHERE id=?");
        $stmt->bind_param("si", $_POST['fac_name'], $_POST['fac_id']);
        $stmt->execute();
    } elseif (isset($_POST['save_major'])) {
        $sql = "UPDATE majors SET major_name=?, major_description=?, round_open=?, seats=?, major_plans=?, gpax_min=?, condition_text=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiisssi", $_POST['m_name'], $_POST['m_desc'], $_POST['m_round'], $_POST['m_seats'], $_POST['m_plans'], $_POST['m_gpax'], $_POST['m_cond'], $_POST['m_id']);
        $stmt->execute();
    }
    header("Location: admin_edit_uni.php?id=$uni_id"); exit();
}

// ระบบลบ
if (isset($_GET['del_fac'])) {
    $conn->query("DELETE FROM faculties WHERE id = ".intval($_GET['del_fac']));
    header("Location: admin_edit_uni.php?id=$uni_id"); exit();
}
if (isset($_GET['del_maj'])) {
    $conn->query("DELETE FROM majors WHERE id = ".intval($_GET['del_maj']));
    header("Location: admin_edit_uni.php?id=$uni_id"); exit();
}

$uni = $conn->query("SELECT * FROM universities WHERE uni_id = $uni_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Sarabun', sans-serif; } </style>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto space-y-10">
        <h1 class="text-3xl font-black text-gray-800">จัดการข้อมูล: <?= $uni['uni_name'] ?></h1>

        <div class="bg-white p-8 rounded-3xl shadow border-l-8 border-red-500">
            <h2 class="text-xl font-bold mb-6 text-red-600 uppercase">1. ข้อมูลมหาวิทยาลัย</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="uni_name" value="<?= $uni['uni_name'] ?>" class="p-4 border rounded-2xl bg-gray-50 font-bold" placeholder="ชื่อมหาลัย">
                <input type="text" name="uni_img" value="<?= $uni['uni_img'] ?>" class="p-4 border rounded-2xl bg-gray-50" placeholder="URL รูป Logo">
                <button type="submit" name="save_uni" class="md:col-span-2 bg-red-500 text-white py-4 rounded-2xl font-black shadow-lg">บันทึกมหาลัย</button>
            </form>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow border-l-8 border-blue-500">
            <h2 class="text-xl font-bold mb-6 text-blue-600 uppercase">2. จัดการคณะ (กด MORE เพื่อแก้ชื่อ)</h2>
            <div class="space-y-3">
                <?php
                $facs = $conn->query("SELECT * FROM faculties WHERE uni_id = $uni_id");
                while($f = $facs->fetch_assoc()): ?>
                    <div class="border rounded-2xl p-4 bg-gray-50 flex flex-col">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-700"><?= $f['fac_name'] ?></span>
                            <div class="flex gap-2">
                                <button onclick="toggle('f-<?= $f['id'] ?>')" class="bg-white border-2 px-6 py-2 rounded-xl text-sm font-black hover:bg-blue-500 hover:text-white transition">MORE</button>
                                <a href="?id=<?= $uni_id ?>&del_fac=<?= $f['id'] ?>" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-black">ลบ</a>
                            </div>
                        </div>
                        <div id="f-<?= $f['id'] ?>" class="hidden mt-4 pt-4 border-t-2 border-dashed">
                            <form method="POST" class="flex gap-2">
                                <input type="hidden" name="fac_id" value="<?= $f['id'] ?>">
                                <input type="text" name="fac_name" value="<?= $f['fac_name'] ?>" class="flex-1 p-3 border rounded-xl shadow-sm outline-none">
                                <button type="submit" name="save_fac" class="bg-blue-600 text-white px-8 rounded-xl font-bold">อัปเดต</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow border-l-8 border-green-500">
            <h2 class="text-xl font-bold mb-6 text-green-600 uppercase">3. จัดการสาขา (กด MORE เพื่อแก้ละเอียดเหมือนตอน ADD)</h2>
            <div class="space-y-6">
                <?php
                $fac_res = $conn->query("SELECT * FROM faculties WHERE uni_id = $uni_id");
                while($fr = $fac_res->fetch_assoc()): ?>
                    <div class="p-4 bg-green-50 rounded-2xl border border-green-100">
                        <p class="font-black text-green-700 mb-4 border-b pb-2">📂 คณะ: <?= $fr['fac_name'] ?></p>
                        <?php
                        $majs = $conn->query("SELECT * FROM majors WHERE fac_id = {$fr['id']}");
                        while($m = $majs->fetch_assoc()): ?>
                            <div class="bg-white p-4 rounded-xl shadow-sm mb-3 border">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-800"><?= $m['major_name'] ?></span>
                                    <div class="flex gap-2">
                                        <button onclick="toggle('m-<?= $m['id'] ?>')" class="bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-black">MORE (แก้ละเอียด)</button>
                                        <a href="?id=<?= $uni_id ?>&del_maj=<?= $m['id'] ?>" class="text-red-500 text-xs font-bold px-2 self-center">ลบ</a>
                                    </div>
                                </div>
                                <div id="m-<?= $m['id'] ?>" class="hidden mt-4 pt-4 border-t-2 border-dashed space-y-4">
                                    <form method="POST" class="grid grid-cols-2 gap-4">
                                        <input type="hidden" name="m_id" value="<?= $m['id'] ?>">
                                        <div class="col-span-2">
                                            <label class="text-[10px] font-black text-gray-400">ชื่อสาขาวิชา</label>
                                            <input type="text" name="m_name" value="<?= $m['major_name'] ?>" class="w-full p-2 border rounded-xl bg-gray-50">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="text-[10px] font-black text-gray-400">รายละเอียด (DESCRIPTION)</label>
                                            <textarea name="m_desc" rows="2" class="w-full p-2 border rounded-xl bg-gray-50"><?= $m['major_description'] ?></textarea>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-gray-400">รอบที่เปิด</label>
                                            <input type="number" name="m_round" value="<?= $m['round_open'] ?>" class="w-full p-2 border rounded-xl bg-gray-50">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-gray-400">จำนวนที่รับ</label>
                                            <input type="number" name="m_seats" value="<?= $m['seats'] ?>" class="w-full p-2 border rounded-xl bg-gray-50">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-gray-400">แผนการเรียน</label>
                                            <input type="text" name="m_plans" value="<?= $m['major_plans'] ?>" class="w-full p-2 border rounded-xl bg-gray-50">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-gray-400">เกรดขั้นต่ำ (GPAX)</label>
                                            <input type="text" name="m_gpax" value="<?= $m['gpax_min'] ?>" class="w-full p-2 border rounded-xl bg-gray-50">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="text-[10px] font-black text-gray-400">เงื่อนไขเพิ่มเติม</label>
                                            <textarea name="m_cond" rows="2" class="w-full p-2 border rounded-xl bg-gray-50"><?= $m['condition_text'] ?></textarea>
                                        </div>
                                        <button type="submit" name="save_major" class="col-span-2 bg-green-600 text-white py-4 rounded-2xl font-black shadow-lg uppercase tracking-widest">อัปเดตสาขานี้</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>

    <script>
        function toggle(id) { document.getElementById(id).classList.toggle('hidden'); }
    </script>
</body>
</html>