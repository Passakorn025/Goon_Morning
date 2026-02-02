<?php
include 'connect.php';
$id = $_GET['id'];

// เปลี่ยนสถานะกลับเป็น 0 เพื่อให้โชว์หน้าหลักเหมือนเดิม
$sql = "UPDATE jobs SET is_deleted = 0 WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('กู้คืนข้อมูลสำเร็จ'); window.location='admin.php?mode=trash';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>