<?php
include 'connect.php';
$id = $_GET['id'];

// เปลี่ยนสถานะเป็น 1 เพื่อซ่อนจากหน้าหลัก
$sql = "UPDATE jobs SET is_deleted = 1 WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('ย้ายไปถังขยะแล้ว'); window.location='admin.php?mode=jobs';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>