<?php
require "../config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'pharmacist') {
    header("Location: ../dashboard.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ../admin/drugs.php");
    exit;
}

$id = intval($_GET['id']);
$drug = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM drugs WHERE id=$id"));

if (!$drug) {
    header("Location: ../admin/drugs.php");
    exit;
}

if (isset($_POST['sell'])) {
    $qty = intval($_POST['quantity']);

    if ($qty <= 0) {
        $error = "Invalid quantity";
    } elseif ($qty > $drug['quantity']) {
        $error = "Not enough stock!";
    } else {
        $total = $qty * $drug['price'];
        $sold_by = $_SESSION['user']['username'];

        // Insert sale
        $stmt = $conn->prepare(
            "INSERT INTO sales (drug_id, quantity, total, sold_by, sale_date)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("iids", $id, $qty, $total, $sold_by);
        $stmt->execute();
        $stmt->close();

        // Update stock
        mysqli_query($conn, "UPDATE drugs SET quantity = quantity - $qty WHERE id=$id");

        header("Location: receipt.php?sale=success&id=$id&qty=$qty&total=$total");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sell Drug</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
<a href="../admin/drugs.php" class="btn btn-secondary btn-sm mb-3">⬅ Back to Inventory</a>

<div class="card shadow">
<div class="card-body">

<h4 class="mb-3">Sale</h4>

<?php if(isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post">
<div class="mb-2">
<strong>Drug:</strong> <?= $drug['name'] ?>
</div>

<div class="mb-2">
<strong>Price:</strong> GHS <?= number_format($drug['price'],2) ?>
</div>

<div class="mb-2">
<strong>Available Stock:</strong> <?= $drug['quantity'] ?>
</div>

<div class="mb-3">
<label>Quantity</label>
<input type="number" name="quantity" class="form-control" required min="1">
</div>

<button type="submit" name="sell" class="btn btn-success w-100">
Complete Sale
</button>
</form>

</div>
</div>
</div>

</body>
</html>
