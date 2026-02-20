<?php
require "../config.php";

if (
    $_SESSION['user']['role'] != 'pharmacist' &&
    $_SESSION['user']['role'] != 'admin'
) {
    header("Location: ../dashboard.php");
    exit;
}
?>

<html>
<head>
<title>Sell Drugs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 col-md-6">
<div class="card shadow">
<div class="card-body">
<h4 class="text-center">Sell Drug</h4>

<form method="POST">
<select class="form-select mb-2" name="drug_id" required>
<option>Select Drug</option>
<?php
$q=mysqli_query($conn,"SELECT * FROM drugs WHERE quantity>0 AND expiry_date>CURDATE()");
while($d=mysqli_fetch_assoc($q)){
echo "<option value='{$d['id']}'>{$d['name']} (Stock {$d['quantity']})</option>";
}
?>
</select>

<input class="form-control mb-2" name="qty" type="number" placeholder="Quantity" required>
<button class="btn btn-success w-100">Complete Sale</button>
</form>

<?php
if($_POST){
$id=$_POST['drug_id']; $qty=$_POST['qty'];
$d=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM drugs WHERE id=$id"));

$total=$d['price']*$qty;
mysqli_query($conn,"INSERT INTO sales(drug_id,quantity,total,sold_by)
VALUES($id,$qty,$total,{$_SESSION['user']['id']})");
mysqli_query($conn,"UPDATE drugs SET quantity=quantity-$qty WHERE id=$id");

header("Location: receipt.php?total=$total");
}
?>
</div>
</div>
</div>
</body>
</html>
