<?php
include 'connect.php';
if(isset($_GET['uni_id'])) {
    $id = $_GET['uni_id'];
    $conn->query("UPDATE universities SET is_deleted = 0 WHERE uni_id = $id");
    header("Location: admin.php?mode=trash&status=restored");
}
?>