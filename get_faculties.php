<?php
include 'connect.php';
header('Content-Type: application/json');

if (isset($_GET['uni_id'])) {
    $uni_id = mysqli_real_escape_string($conn, $_GET['uni_id']);
    
    // ดึง id และชื่อคณะ จากมหาลัยที่เลือก
    $sql = "SELECT id, fac_name FROM faculties WHERE uni_id = '$uni_id'";
    $result = $conn->query($sql);

    $faculties = [];

    if ($result) {
        while($row = $result->fetch_assoc()) { 
            // ยัดใส่ตัวแปร $faculties ให้ตรงกับตอน echo
            $faculties[] = array(
                "id" => $row['id'], 
                "fac_name" => $row['fac_name']
            ); 
        }
    }
    
    // ส่งข้อมูลออกไปเป็น JSON
    echo json_encode($faculties);
} else {
    // ถ้าไม่มี uni_id ส่งมา ก็ส่ง array ว่างกลับไป
    echo json_encode([]);
}
?>