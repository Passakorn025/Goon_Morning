<?php
include 'connect.php';

// ตรวจสอบว่าส่ง id และ type มาไหม
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type'];

    if ($type == 'JOB') {
        // ลบ Job ถาวร
        $sql = "DELETE FROM jobs WHERE id = $id";
    } elseif ($type == 'UNI') {
        // ลบ University ถาวร (ระวัง: ถ้ามีคณะ/สาขาค้างอยู่ อาจจะลบไม่ได้ถ้าตั้ง Foreign Key ไว้)
        $sql = "DELETE FROM universities WHERE uni_id = $id";
    }

    if (isset($sql) && $conn->query($sql) === TRUE) {
        echo "<script>alert('ลบข้อมูลถาวรเรียบร้อย'); window.location='admin.php?mode=trash';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "<script>alert('ข้อมูลไม่ครบ'); window.location='admin.php?mode=trash';</script>";
}
?>