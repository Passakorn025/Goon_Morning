<?php
$conn = new mysqli("localhost", "root", "", "aplatform_db"); 
$conn->set_charset("utf8");
if ($conn->connect_error) { die("เชื่อมต่อล้มเหลว: " . $conn->connect_error); }
?>