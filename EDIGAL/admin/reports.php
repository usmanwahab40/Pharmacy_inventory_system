<?php 
require "../config.php";

// Session & admin check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Handle clearing the sales report
if (isset($_POST['clear_report'])) {
    mysqli_query($conn, "TRUNCATE TABLE sales");
    header("Location: reports.php");
    exit;
}

// Fetch all sales
$sales = mysqli_query($conn, "
    SELECT s.id, d.name AS drug_name, s.quantity, s.total, s.sold_by, s.sale_date
    FROM sales s
    JOIN drugs d ON s.drug_id = d.id
    ORDER BY s.sale_date DESC
");

// Total drugs sold
$total_qty_result = mysqli_query($conn, "SELECT SUM(quantity) AS total_drugs_sold FROM sales");
$total_qty_row = mysqli_fetch_assoc($total_qty_result);
$total_drugs_sold = $total_qty_row['total_drugs_sold'] ?? 0;

// --- Daily Sales (FIXED) ---
$daily_sales_result = mysqli_query($conn, "
    SELECT 
        DATE(sale_date) AS sale_day,
        DAYNAME(sale_date) AS day_name,
        SUM(total) AS total_amount,
        SUM(quantity) AS total_qty
    FROM sales
    GROUP BY DATE(sale_date)
    ORDER BY DATE(sale_date) DESC
");

// --- Weekly Sales ---
$weekly_sales_result = mysqli_query($conn, "
    SELECT 
        YEAR(sale_date) AS year, 
        WEEK(sale_date, 1) AS week, 
        SUM(total) AS total_amount, 
        SUM(quantity) AS total_qty
    FROM sales
    GROUP BY YEAR(sale_date), WEEK(sale_date, 1)
    ORDER BY year DESC, week DESC
");

// --- Monthly Sales ---
$monthly_sales_result = mysqli_query($conn, "
    SELECT 
        YEAR(sale_date) AS year, 
        MONTH(sale_date) AS month, 
        SUM(total) AS total_amount, 
        SUM(quantity) AS total_qty
    FROM sales
    GROUP BY YEAR(sale_date), MONTH(sale_date)
    ORDER BY year DESC, month DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Sales Report | EDIGAL</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; }
    .container { margin-top: 30px; }
    h2, h4 { margin-bottom: 15px; }
    table th, table td { vertical-align: middle; }
    .table-wrapper {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    @media print {
        .no-print { display: none; }
    }
</style>
</head>
<body>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h2>Sales Report</h2>
        <a href="../dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <strong>Total Drugs Sold: <?= $total_drugs_sold ?></strong>
        <div>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Clear all sales records?');">
                <button type="submit" name="clear_report" class="btn btn-danger">Clear Sales</button>
            </form>
            <button onclick="window.print()" class="btn btn-success">🖨 Print</button>
        </div>
    </div>

    <!-- Daily Sales -->
    <div class="table-wrapper table-responsive">
        <h4>Daily Sales</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Qty Sold</th>
                    <th>Total (GHS)</th>
                </tr>
            </thead>
            <tbody>
            <?php while($d = mysqli_fetch_assoc($daily_sales_result)): ?>
                <tr>
                    <td><?= $d['sale_day'] ?></td>
                    <td><?= $d['day_name'] ?></td>
                    <td><?= $d['total_qty'] ?></td>
                    <td><?= number_format($d['total_amount'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Weekly Sales -->
    <div class="table-wrapper table-responsive">
        <h4>Weekly Sales</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Year</th>
                    <th>Week</th>
                    <th>Qty Sold</th>
                    <th>Total (GHS)</th>
                </tr>
            </thead>
            <tbody>
            <?php while($w = mysqli_fetch_assoc($weekly_sales_result)): ?>
                <tr>
                    <td><?= $w['year'] ?></td>
                    <td><?= $w['week'] ?></td>
                    <td><?= $w['total_qty'] ?></td>
                    <td><?= number_format($w['total_amount'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Monthly Sales -->
    <div class="table-wrapper table-responsive">
        <h4>Monthly Sales</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Qty Sold</th>
                    <th>Total (GHS)</th>
                </tr>
            </thead>
            <tbody>
            <?php while($m = mysqli_fetch_assoc($monthly_sales_result)): ?>
                <tr>
                    <td><?= $m['year'] ?></td>
                    <td><?= date("F", mktime(0,0,0,$m['month'],1)) ?></td>
                    <td><?= $m['total_qty'] ?></td>
                    <td><?= number_format($m['total_amount'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- All Sales -->
    <div class="table-wrapper table-responsive">
        <h4>All Sales Records</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Drug</th>
                    <th>Qty</th>
                    <th>Amount (GHS)</th>
                    <th>Sold By</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php while($s = mysqli_fetch_assoc($sales)): ?>
                <tr>
                    <td><?= htmlspecialchars($s['drug_name']) ?></td>
                    <td><?= $s['quantity'] ?></td>
                    <td><?= number_format($s['total'], 2) ?></td>
                    <td><?= htmlspecialchars(ucfirst($s['sold_by'])) ?></td>
                    <td><?= $s['sale_date'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
