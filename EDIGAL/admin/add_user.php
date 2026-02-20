<?php
require "../config.php";

// Only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    // Prepare statement (MySQLi)
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sss", $username, $password, $role);

        // Execute
        if ($stmt->execute()) {
            $msg = "✅ User added successfully";
        } else {
            $msg = "❌ Username already exists";
        }

        $stmt->close();
    } else {
        $msg = "❌ Failed to prepare statement";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow col-md-6 mx-auto">
        <div class="card-header bg-dark text-white">
            <h5>Add New User</h5>
        </div>

        <div class="card-body">

            <?php if ($msg): ?>
                <div class="alert alert-info"><?= $msg ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="pharmacist">Pharmacist</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100">Add User</button>
            </form>

            <a href="../dashboard.php" class="btn btn-secondary w-100 mt-3">
                ⬅ Back to Dashboard
            </a>

        </div>
    </div>
</div>

</body>
</html>
