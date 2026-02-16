<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sal_jr = $_POST['sal_jr'];
    $sal_sr = $_POST['sal_sr'];
    $sal_exp = $_POST['sal_exp'];
    $h_skill = $_POST['h_skill'];
    $s_skill = $_POST['s_skill'];
    $future = $_POST['future'];
    $image_url = $_POST['image_url'];

    $sql = "INSERT INTO jobs (title, description, sal_jr, sal_sr, sal_exp, h_skill, s_skill, future, image_url, is_deleted) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $title, $description, $sal_jr, $sal_sr, $sal_exp, $h_skill, $s_skill, $future, $image_url);
    
    if ($stmt->execute()) {
        header("Location: admin.php?mode=jobs");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
$title = $_POST['title'];
$description = $_POST['description'];
$image_url = $_POST['image_url'];
// ... ค่าอื่นๆ (sal_jr, h_skill, ฯลฯ) ...

// แก้ SQL ให้รองรับช่อง description
$sql = "INSERT INTO jobs (title, description, image_url, sal_jr, sal_sr, sal_exp, h_skill, s_skill, future, is_deleted) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sssssssss", $title, $description, $image_url, $sal_jr, $sal_sr, $sal_exp, $h_skill, $s_skill, $future);
?>