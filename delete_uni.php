<?php
include 'connect.php'; // เช็คดูว่าไฟล์นี้อยู่โฟลเดอร์เดียวกันมั้ย

if (isset($_GET['uni_id'])) {
    $id = intval($_GET['uni_id']); // กัน SQL Injection ไปในตัว
    
    // คำสั่ง Update ให้เป็น 1 เพื่อลงถังขยะ
    $sql = "UPDATE universities SET is_deleted = 1 WHERE uni_id = $id";
    
    if ($conn->query($sql)) {
        // ลบเสร็จ ดีดกลับหน้า admin ตรงโหมด edu
        header("Location: admin.php?mode=edu");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    // ถ้าไม่มี ID ส่งมา ให้ดีดกลับหน้าหลัก
    header("Location: admin.php?mode=edu");
    exit();
}
?>