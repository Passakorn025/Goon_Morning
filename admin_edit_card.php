<?php
include 'connect.php';

// 1. รับ ID จาก URL (ถ้าไม่มีให้เด้งกลับหน้า admin)
if (!isset($_GET['id'])) {
    echo "<script>alert('ไม่พบ ID!'); window.location='admin.php';</script>";
    exit;
}

$id = $_GET['id'];

// 2. ส่วนบันทึกข้อมูล (เมื่อกดปุ่ม Submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE jobs SET 
            title='{$_POST['title']}', 
            description='{$_POST['description']}', 
            salary_jr='{$_POST['salary_jr']}', 
            salary_sr='{$_POST['salary_sr']}', 
            salary_exp='{$_POST['salary_exp']}', 
            hard_skills='{$_POST['hard_skills']}', 
            soft_skills='{$_POST['soft_skills']}', 
            future_trend='{$_POST['future_trend']}' 
            WHERE id=$id"; 
            
    if ($conn->query($sql)) {
        echo "<script>alert('อัปเดต ID: $id เรียบร้อย!'); window.location='admin.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// 3. ดึงข้อมูลมาโชว์ในฟอร์ม
$res = $conn->query("SELECT * FROM jobs WHERE id=$id");
$row = $res->fetch_assoc();

if (!$row) {
    echo "ไม่พบข้อมูลในระบบ!";
    exit;
}
?>

<form method="POST" action="?id=<?= $id ?>" style="max-width: 600px; margin: 20px auto; font-family: sans-serif; background: white; padding: 30px; border-radius: 20px; shadow: lg;">
    <h2 style="border-left: 5px solid #B1081C; padding-left: 15px; margin-bottom: 20px;">แก้ไขข้อมูลอาชีพ (ID: <?= $id ?>)</h2>
    
    ชื่ออาชีพ: <input type="text" name="title" value="<?= $row['title'] ?>" style="width:100%; padding: 8px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;"><br>
    
    บทบาทหลัก: <textarea name="description" style="width:100%; height:100px; padding: 8px; margin-bottom:15px; border: 1px solid #ddd; border-radius: 5px;"><?= $row['description'] ?></textarea><br>
    
    <div style="background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
        <strong>เงินเดือน (JR/SR/EXP):</strong> <br>
        <input type="text" name="salary_jr" value="<?= $row['salary_jr'] ?>" placeholder="JR" style="width: 30%; padding: 5px;">
        <input type="text" name="salary_sr" value="<?= $row['salary_sr'] ?>" placeholder="SR" style="width: 30%; padding: 5px;">
        <input type="text" name="salary_exp" value="<?= $row['salary_exp'] ?>" placeholder="EXP" style="width: 30%; padding: 5px;">
    </div>

    Hard Skills: <textarea name="hard_skills" style="width:100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;"><?= $row['hard_skills'] ?></textarea><br><br>
    Soft Skills: <textarea name="soft_skills" style="width:100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;"><?= $row['soft_skills'] ?></textarea><br><br>
    แนวโน้มอนาคต: <textarea name="future_trend" style="width:100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;"><?= $row['future_trend'] ?></textarea><br><br>
    
    <div style="display: flex; gap: 10px;">
        <button type="submit" style="background:#B1081C; color:white; padding:12px 25px; border:none; border-radius: 10px; cursor:pointer; font-weight: bold; flex: 1;">บันทึกข้อมูล</button>
        <a href="admin.php" style="background:#eee; color:#333; padding:12px 25px; text-decoration: none; border-radius: 10px; text-align: center; flex: 1;">ยกเลิก</a>
    </div>
</form>