<?php
require "../config.php";

// Only admin can access this page
if($_SESSION['user']['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Handle form submission
if($_POST){
   $name = $_POST['name'];
$purchase_price = $_POST['purchase_price']; // NEW
$price = $_POST['price'];
$qty = $_POST['quantity'];
$expiry = $_POST['expiry_date'];

mysqli_query($conn, "INSERT INTO drugs(name, purchase_price, price, quantity, expiry_date)
VALUES('$name', $purchase_price, $price, $qty, '$expiry')");


    // Redirect to drugs list after adding
    header("Location: drugs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Drug - EDIGAL Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-6">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="mb-4 text-center">Add New Drug</h4>

          <form method="POST">
    <input class="form-control mb-3" 
           name="name" 
           placeholder="Drug Name" 
           required>

    <input class="form-control mb-3" 
           type="number" 
           step="0.01"
           name="purchase_price" 
           placeholder="Purchasing Price (GHS)" 
           required>

    <input class="form-control mb-3" 
           type="number" 
           step="0.01"
           name="price" 
           placeholder="Selling Price (GHS)" 
           required>

    <input class="form-control mb-3" 
           type="number" 
           name="quantity" 
           placeholder="Quantity" 
           required>

    <input class="form-control mb-3" 
           type="date" 
           name="expiry_date" 
           required>

    <button class="btn btn-success w-100">Save Drug</button>
</form>

            <!-- Return to Dashboard Button -->
            <a href="../dashboard.php" class="btn btn-secondary w-100 mt-3">Return to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
