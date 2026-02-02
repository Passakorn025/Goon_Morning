<?php
include 'connect.php';
$id = $_GET['id'];

// ลบออกจาก Database ไปเลย
$sql = "DELETE FROM jobs WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('ลบข้อมูลถาวรเรียบร้อย'); window.location='admin.php?mode=trash';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>