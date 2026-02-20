<div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-0">
    <h5 class="text-white text-center py-3">EDIGAL</h5>

    <ul class="nav flex-column px-2">

        <li class="nav-item">
            <a class="nav-link text-white" href="../dashboard.php">🏠 Dashboard</a>
        </li>
        <li class="nav-item">
    <a class="nav-link text-white" href="admin/manage_users.php">👥 Manage Users</a>
</li>

        <li class="nav-item">
            <a class="nav-link text-white" href="../pharmacist/sell.php">💊 Sell Drugs</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="drugs.php">📦 Drug Inventory</a>
        </li>

        <?php if($_SESSION['user']['role']=='admin'): ?>
        <li class="nav-item">
            <a class="nav-link text-white" href="add_drug.php">➕ Add Drug</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="reports.php">📊 Reports</a>
        </li>
        <?php endif; ?>

        <li class="nav-item mt-3">
            <a class="nav-link text-danger" href="../logout.php">🚪 Logout</a>
        </li>
    </ul>
</div>
