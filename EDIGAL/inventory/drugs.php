<?php
require "../config.php";

/* =========================
   LOGIN CHECK
========================= */
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$role = $_SESSION['user']['role'];

/* =========================
   FETCH DRUGS + TOTALS
========================= */
$drugs = mysqli_query($conn, "SELECT * FROM drugs ORDER BY name");

$total_qty = 0;
$total_purchase_value = 0;

// Calculate totals
$totals_query = mysqli_query($conn, "SELECT quantity, purchase_price FROM drugs");
while ($row = mysqli_fetch_assoc($totals_query)) {
    $total_qty += $row['quantity'];
    $total_purchase_value += ($row['quantity'] * $row['purchase_price']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Drug Inventory</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<?php include "../includes/sidebar.php"; ?>

<!-- MAIN CONTENT -->
<div class="col-md-9 col-lg-10 p-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Drug Inventory</h4>
    <a href="../dashboard.php" class="btn btn-secondary btn-sm">
        ⬅ Back to Dashboard
    </a>
</div>

<input type="text" id="search" class="form-control mb-3" placeholder="Search drug...">

<!-- SUMMARY CARDS -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Products in Stock</h6>
                <h3 class="text-primary"><?= $total_qty ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Purchasing Value (GHS)</h6>
                <h3 class="text-success">
                    <?= number_format($total_purchase_value, 2) ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-hover align-middle">
<thead class="table-dark">
<tr>
    <th>Name</th>
    <th>Purchase Price (GHS)</th>
    <th>Selling Price (GHS)</th>
    <th>Stock</th>
    <th>Expiry</th>
    <th>Sell</th>
    <?php if ($role === 'admin'): ?>
        <th>Admin Actions</th>
    <?php endif; ?>
</tr>
</thead>

<tbody id="drugTable">
<?php while ($d = mysqli_fetch_assoc($drugs)): ?>
<tr>
    <td><?= htmlspecialchars($d['name']) ?></td>

    <td><?= number_format($d['purchase_price'], 2) ?></td>

    <td><?= number_format($d['price'], 2) ?></td>

    <td class="<?= $d['quantity'] < 10 ? 'text-danger fw-bold' : '' ?>">
        <?= $d['quantity'] ?>
    </td>

    <td><?= htmlspecialchars($d['expiry_date']) ?></td>

    <!-- SELL BUTTON -->
    <td>
        <?php if ($d['quantity'] > 0): ?>
            <a href="../pharmacist/sell.php?id=<?= $d['id'] ?>"
               class="btn btn-success btn-sm">
               Sell
            </a>
        <?php else: ?>
            <span class="badge bg-secondary">Out of stock</span>
        <?php endif; ?>
    </td>

    <!-- ADMIN ONLY -->
    <?php if ($role === 'admin'): ?>
    <td>
        <a href="../admin/edit_drug.php?id=<?= $d['id'] ?>"
           class="btn btn-primary btn-sm">
           Edit
        </a>

        <a href="../admin/delete_drug.php?id=<?= $d['id'] ?>"
           onclick="return confirm('Delete this drug?')"
           class="btn btn-danger btn-sm">
           Delete
        </a>
    </td>
    <?php endif; ?>

</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</div>
</div>

<script>
document.getElementById("search").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    document.querySelectorAll("#drugTable tr").forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>

</body>
</html>
