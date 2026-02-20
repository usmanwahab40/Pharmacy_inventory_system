<?php
require "../config.php";

// Only admin can access
if($_SESSION['user']['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Get drug ID from URL
if(!isset($_GET['id'])) {
    header("Location: drugs.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch the drug details
$result = mysqli_query($conn, "SELECT * FROM drugs WHERE id=$id");
if(mysqli_num_rows($result) == 0){
    header("Location: drugs.php");
    exit;
}

$drug = mysqli_fetch_assoc($result);
$error = '';
$success = '';

// Handle form submission
if(isset($_POST['update'])){
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $expiry = $_POST['expiry_date'];

    if(empty($name) || $price <= 0 || $quantity < 0 || empty($expiry)){
        $error = "Please fill all fields correctly!";
    } else {
        $stmt = $conn->prepare("UPDATE drugs SET name=?, price=?, quantity=?, expiry_date=? WHERE id=?");
        $stmt->bind_param("sdisi", $name, $price, $quantity, $expiry, $id);
        $stmt->execute();
        $stmt->close();
        $success = "Drug updated successfully!";
        // Refresh the data
        $drug = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM drugs WHERE id=$id"));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Drug | EDIGAL</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.container { margin-top: 30px; max-width: 600px; }
.card { padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Edit Drug</h2>
        <a href="drugs.php" class="btn btn-secondary">⬅ Back to Inventory</a>
    </div>

    <div class="card">
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Drug Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($drug['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (GHS)</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $drug['price'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?= $drug['quantity'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="expiry_date" class="form-label">Expiry Date</label>
                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?= $drug['expiry_date'] ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" name="update" class="btn btn-primary">Update Drug</button>
                <a href="drugs.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
