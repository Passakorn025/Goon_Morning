<?php
include 'connect.php'; 

if(isset($_GET['uni_id'])) {
    $id = $_GET['uni_id'];
    
   
    $sql = "UPDATE universities SET is_deleted = 1 WHERE uni_id = $id";
    
    if($conn->query($sql) === TRUE) {
       
        header("Location: admin.php?mode=edu&status=deleted");
        exit();
    }
}
?>