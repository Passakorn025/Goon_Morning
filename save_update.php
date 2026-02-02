<?php
include 'connect.php';

// 1. รับค่าจากฟอร์ม (กูทำความสะอาดตัวแปรให้กัน SQL Injection เบื้องต้น)
$id = $_POST['id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$salary = mysqli_real_escape_string($conn, $_POST['salary']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$salary_jr = mysqli_real_escape_string($conn, $_POST['salary_jr']);
$salary_sr = mysqli_real_escape_string($conn, $_POST['salary_sr']);
$salary_exp = mysqli_real_escape_string($conn, $_POST['salary_exp']);
$hard_skills = mysqli_real_escape_string($conn, $_POST['hard_skills']);
$soft_skills = mysqli_real_escape_string($conn, $_POST['soft_skills']);
$future_trend = mysqli_real_escape_string($conn, $_POST['future_trend']);

// 2. คำสั่ง SQL สำหรับอัปเดตตาราง jobs (ให้ตรงกับลิ้นชักที่มึงเพิ่มในฐานข้อมูล)
$sql = "UPDATE jobs SET 
        title = '$title', 
        category = '$category', 
        salary = '$salary', 
        description = '$description',
        salary_jr = '$salary_jr', 
        salary_sr = '$salary_sr', 
        salary_exp = '$salary_exp', 
        hard_skills = '$hard_skills', 
        soft_skills = '$soft_skills', 
        future_trend = '$future_trend' 
        WHERE id = $id";

// 3. ทำการรัน SQL และเช็กสถานะ
if ($conn->query($sql) === TRUE) {
    // ถ้าสำเร็จ ให้เด้งไปหน้าแสดงผลอาชีพนั้นเลย (P11.php หรือหน้าของมึง)
    header("Location: P11.php?id=$id&status=success");
    exit();
} else {
    echo "พังเพราะ: " . $conn->error;
}
?>