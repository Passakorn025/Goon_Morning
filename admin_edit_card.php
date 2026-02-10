<?php
include 'connect.php';

// 1. รับ ID จาก URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ไม่พบ ID!'); window.location='admin.php';</script>";
    exit;
}

$id = $_GET['id'];

// 2. ส่วนบันทึกข้อมูล (แก้ไขชื่อคอลัมน์ให้ตรงกับที่มึงแก้ใน DB)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE jobs SET 
            title='{$_POST['title']}', 
            description='{$_POST['description']}', 
            sal_jr='{$_POST['sal_jr']}', 
            sal_sr='{$_POST['sal_sr']}', 
            sal_exp='{$_POST['sal_exp']}', 
            h_skill='{$_POST['h_skill']}', 
            s_skill='{$_POST['s_skill']}', 
            future='{$_POST['future']}' 
            WHERE id=$id"; 
            
    if ($conn->query($sql)) {
        echo "<script>alert('อัปเดต ID: $id เรียบร้อย!'); window.location='admin.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// 3. ดึงข้อมูลมาโชว์
$res = $conn->query("SELECT * FROM jobs WHERE id=$id");
$row = $res->fetch_assoc();

if (!$row) {
    echo "ไม่พบข้อมูลในระบบ!";
    exit;
}
?>

<form method="POST" action="?id=<?= $id ?>" style="max-width: 600px; margin: 20px auto; font-family: sans-serif; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="border-left: 5px solid #B1081C; padding-left: 15px; margin-bottom: 20px;">แก้ไขข้อมูลอาชีพ (ID: <?= $id ?>)</h2>
    
    <label>ชื่ออาชีพ:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($row['title'] ?? '') ?>" style="width:100%; padding: 8px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;">

    <label>บทบาทหลัก:</label>
    <textarea name="description" style="width:100%; height:80px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>

    <label>เงินเดือน (JR/SR/EXP):</label>
    <div style="display: flex; gap: 10px; margin-bottom:15px;">
        <input type="text" name="sal_jr" value="<?= htmlspecialchars($row['sal_jr'] ?? '') ?>" placeholder="JR" style="width: 33%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
        <input type="text" name="sal_sr" value="<?= htmlspecialchars($row['sal_sr'] ?? '') ?>" placeholder="SR" style="width: 33%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
        <input type="text" name="sal_exp" value="<?= htmlspecialchars($row['sal_exp'] ?? '') ?>" placeholder="EXP" style="width: 33%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
    </div>

    <label>Hard Skills:</label>
    <textarea name="h_skill" style="width:100%; height:80px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;"><?= htmlspecialchars($row['h_skill'] ?? '') ?></textarea>

    <label>Soft Skills:</label>
    <textarea name="s_skill" style="width:100%; height:80px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;"><?= htmlspecialchars($row['s_skill'] ?? '') ?></textarea>

    <label>แนวโน้มอนาคต:</label>
    <textarea name="future" style="width:100%; height:80px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;"><?= htmlspecialchars($row['future'] ?? '') ?></textarea>

    <div style="display: flex; gap: 10px; margin-top: 20px;">
        <button type="submit" name="update_job" style="flex: 1; padding: 12px; background: #b00d23; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">บันทึกข้อมูล</button>
        <a href="admin.php" style="flex: 1; padding: 12px; background: #eee; color: #333; text-align: center; text-decoration: none; border-radius: 8px; font-weight: bold;">ยกเลิก</a>
    </div>
</form>