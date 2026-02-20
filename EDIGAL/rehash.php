<?php
require "config.php";

$adminPass = password_hash("admin123", PASSWORD_DEFAULT);
$pharmaPass = password_hash("pharma123", PASSWORD_DEFAULT);

mysqli_query($conn, "UPDATE users SET password='$adminPass' WHERE username='admin'");
mysqli_query($conn, "UPDATE users SET password='$pharmaPass' WHERE username='pharmacist'");

echo "Passwords updated successfully";
