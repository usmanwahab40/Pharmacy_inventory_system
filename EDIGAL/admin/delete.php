<?php
require "../config.php";

// Only admin can delete
if($_SESSION['user']['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM drugs WHERE id=$id");
}

header("Location: drugs.php");
exit;
?>
