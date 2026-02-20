<?php
require "../config.php";

if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$id    = $_GET['id'];
$qty   = $_GET['qty'];
$total = $_GET['total'];

$drug = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM drugs WHERE id=$id")
);

$user = $_SESSION['user']['username'];
$date = date("Y-m-d H:i:s");

// --- Pharmacy Contact Info (Only Phone Number) ---
$pharmacy_name  = "EDIGAL PHARMACY";
$pharmacy_phone = "0530537324";
?>
<!DOCTYPE html>
<html>
<head>
<title>Sales Receipt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
@media print {
    .no-print { display: none; }
}
</style>
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="card shadow p-4">

<!-- HEADER -->
<div class="text-center mb-4">
    <h3><?= $pharmacy_name ?></h3>
    <p>Official Sales Receipt</p>
    <hr>
    <p>
        <strong>Phone:</strong> <?= $pharmacy_phone ?>
    </p>
</div>

<!-- RECEIPT INFO -->
<p><strong>Date:</strong> <?= $date ?></p>
<p><strong>Sold By:</strong> <?= strtoupper($user) ?></p>

<table class="table table-bordered mt-3">
<tr>
    <th>Drug Name</th>
    <td><?= $drug['name'] ?></td>
</tr>
<tr>
    <th>Unit Price (GHS)</th>
    <td><?= number_format($drug['price'], 2) ?></td>
</tr>
<tr>
    <th>Quantity</th>
    <td><?= $qty ?></td>
</tr>
<tr>
    <th>Total Amount (GHS)</th>
    <td><strong><?= number_format($total, 2) ?></strong></td>
</tr>
</table>

<!-- FOOTER -->
<p class="text-center mt-4"><strong>Thank you for your purchase!</strong></p>

<!-- ACTION BUTTONS -->
<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary">
        🖨 Print Receipt
    </button>

    <a href="../dashboard.php" class="btn btn-success">
        ⬅ Return to Dashboard
    </a>

    <a href="../dashboard.php" class="btn btn-danger">
        ✖ Cancel
    </a>
</div>

</div>
</div>

</body>
</html>
