<?php
require "config.php"; // ✅ REQUIRED

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Assign session user to variable
$u = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard | EDIGAL Pharmacy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
<div class="row">

<!-- LEFT SIDEBAR -->
<div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-0">
    <h5 class="text-white text-center py-3">EDIGAL PHARMACY</h5>

    <ul class="nav flex-column px-2">

        <li class="nav-item">
            <a class="nav-link text-white" href="dashboard.php">🏠 Dashboard</a>
        </li>
<li class="nav-item">
    <a class="nav-link text-white" href="admin/manage_users.php">👥 Manage Users</a>
</li>

        <li class="nav-item">
            <a class="nav-link text-white" href="admin/drugs.php">📦 Drug Inventory</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="pharmacist/sell.php">💊 Sell Drugs</a>
        </li>

        <!-- ADMIN ONLY -->
        <?php if ($u['role'] === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link text-white" href="admin/add_drug.php">➕ Add Drug</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="admin/reports.php">📊 Reports</a>
        </li>
        <?php endif; ?>

        <li class="nav-item mt-3">
            <a class="nav-link text-danger" href="logout.php">🚪 Logout</a>
        </li>
    </ul>
</div>

<!-- MAIN CONTENT -->
<div class="col-md-9 col-lg-10 p-4">

<h3>Welcome, <?= ucfirst($u['username']) ?></h3>
<p class="text-muted">Role: <?= strtoupper($u['role']) ?></p>

<div class="row g-4 mt-3">
<?php if ($u['role'] === 'admin'): ?>
<div class="col-md-4">
    <div class="card shadow text-center">
        <div class="card-body">
            <h5>Manage Users</h5>
            <p>Add or delete system users</p>
            <a href="admin/manage_users.php" class="btn btn-danger">Open</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="col-md-4">
    <div class="card shadow text-center">
        <div class="card-body">
            <h5>Drug Inventory</h5>
            <p>View and search drugs</p>
            <a href="admin/drugs.php" class="btn btn-primary">Open</a>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card shadow text-center">
        <div class="card-body">
            <h5>Sell Drugs</h5>
            <p>Process customer sales</p>
            <a href="pharmacist/sell.php" class="btn btn-warning">Sell</a>
        </div>
    </div>
</div>

<?php if ($u['role'] === 'admin'): ?>
<div class="col-md-4">
    <div class="card shadow text-center">
        <div class="card-body">
            <h5>Reports</h5>
            <p>Sales & inventory reports</p>
            <a href="admin/reports.php" class="btn btn-success">View</a>
        </div>
    </div>
</div>
<?php endif; ?>

</div>
</div>
</div>
</div>

</body>
</html>
