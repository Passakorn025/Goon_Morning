<?php
include 'connect.php';

$type = $_GET['type'] ?? 'faculty'; // faculty หรือ major
$uni_id = intval($_GET['uni_id'] ?? 0);
$edit_id = intval($_GET['edit_id'] ?? 0);

// --- 1. Logic การลบ ---
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    if ($type == 'faculty') {
        $conn->query("DELETE FROM majors WHERE fac_id = $del_id"); // ลบลูกก่อน
        $conn->query("DELETE FROM faculties WHERE id = $del_id");
    } else {
        $conn->query("DELETE FROM majors WHERE id = $del_id");
    }
    header("Location: manage_structure.php?type=$type&uni_id=$uni_id");
    exit();
}

// --- 2. Logic การอัปเดต (ฟอร์มเดียวกับตอนเพิ่ม) ---
if (isset($_POST['update_data'])) {
    if ($type == 'faculty') {
        $name = $_POST['fac_name'];
        $stmt = $conn->prepare("UPDATE faculties SET fac_name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $edit_id);
    } else {
        $name = $_POST['major_name'];
        $stmt = $conn->prepare("UPDATE majors SET major_name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $edit_id);
    }
    $stmt->execute();
    header("Location: manage_structure.php?type=$type&uni_id=$uni_id");
    exit();
}

// ดึงข้อมูลมหาลัยมาโชว์หัวข้อ
$uni = $conn->query("SELECT uni_name FROM universities WHERE uni_id = $uni_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Manage Structure - <?= $uni['uni_name'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-5">
        
        <div class="mb-10 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-800"><?= $uni['uni_name'] ?></h1>
                <p class="text-gray-500 text-sm">จัดการโครงสร้างคณะและสาขาวิชา</p>
            </div>
            <a href="edit_uni.php?id=<?= $uni_id ?>" class="bg-gray-200 px-6 py-2 rounded-full font-bold text-gray-600 hover:bg-gray-300 transition">กลับหน้าแก้ไขหลัก</a>
        </div>

        <div class="flex border-b mb-8">
            <a href="?type=faculty&uni_id=<?= $uni_id ?>" class="px-8 py-4 font-bold <?= $type == 'faculty' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-400' ?>">จัดการคณะ</a>
            <a href="?type=major&uni_id=<?= $uni_id ?>" class="px-8 py-4 font-bold <?= $type == 'major' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-400' ?>">จัดการสาขา</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border">
                <h3 class="font-bold text-gray-400 text-xs uppercase mb-4 tracking-widest">List of <?= $type ?></h3>
                <div class="space-y-3">
                    <?php 
                    $sql = ($type == 'faculty') 
                           ? "SELECT * FROM faculties WHERE uni_id = $uni_id" 
                           : "SELECT majors.*, faculties.fac_name FROM majors 
                              JOIN faculties ON majors.fac_id = faculties.id 
                              WHERE faculties.uni_id = $uni_id ORDER BY faculties.id";
                    $list = $conn->query($sql);
                    while($row = $list->fetch_assoc()):
                    ?>
                    <div class="group flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-red-200 hover:bg-red-50 transition-all">
                        <div>
                            <?php if($type == 'major'): ?>
                                <p class="text-[10px] font-bold text-red-400 uppercase"><?= $row['fac_name'] ?></p>
                            <?php endif; ?>
                            <p class="font-bold text-gray-700"><?= $row[$type.'_name'] ?></p>
                        </div>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                            <a href="?type=<?= $type ?>&uni_id=<?= $uni_id ?>&edit_id=<?= $row['id'] ?>" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white">📝</a>
                            <a href="?type=<?= $type ?>&uni_id=<?= $uni_id ?>&delete_id=<?= $row['id'] ?>" onclick="return confirm('ลบแล้วกู้ไม่ได้นะไอ้สัส เอาจริงไหม?')" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white">🗑️</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl border-2 <?= $edit_id ? 'border-blue-500' : 'border-dashed border-gray-200' ?>">
                <?php if ($edit_id): 
                    $target_table = ($type == 'faculty') ? 'faculties' : 'majors';
                    $edit_data = $conn->query("SELECT * FROM $target_table WHERE id = $edit_id")->fetch_assoc();
                ?>
                    <h3 class="text-xl font-black mb-6 text-gray-800">แก้ไขข้อมูล<?= $type == 'faculty' ? 'คณะ' : 'สาขา' ?></h3>
                    <form action="" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 italic">Name of <?= $type ?></label>
                            <input type="text" name="<?= $type ?>_name" value="<?= $edit_data[$type.'_name'] ?>" class="w-full px-4 py-3 bg-gray-50 border rounded-xl outline-none focus:border-blue-500 font-bold text-lg">
                        </div>
                        <button type="submit" name="update_data" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-black transition-all">SAVE CHANGES</button>
                        <a href="?type=<?= $type ?>&uni_id=<?= $uni_id ?>" class="block text-center text-sm text-gray-400 font-bold uppercase">Cancel Edit</a>
                    </form>
                <?php else: ?>
                    <div class="h-full flex flex-col items-center justify-center text-center py-20">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-2xl mb-4">👈</div>
                        <p class="text-gray-400 font-bold">เลือกรายการด้านซ้าย<br>เพื่อทำการแก้ไขหรือลบ</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</body>
</html>