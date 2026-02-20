<?php
require "../config.php";

// Admin only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Delete user
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Prevent admin from deleting themselves
    if ($id != $_SESSION['user']['id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    }

    header("Location: manage_users.php");
    exit;
}

// Fetch users
$result = mysqli_query($conn, "SELECT id, username, role FROM users");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5>Manage Users</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= ucfirst($row['username']) ?></td>
                        <td><?= strtoupper($row['role']) ?></td>
                        <td>
                            <?php if ($row['id'] != $_SESSION['user']['id']): ?>
                                <a href="?delete=<?= $row['id'] ?>"
                                   onclick="return confirm('Delete this user?')"
                                   class="btn btn-danger btn-sm">
                                   🗑 Delete
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="add_user.php" class="btn btn-primary">➕ Add User</a>
            <a href="../dashboard.php" class="btn btn-secondary float-end">Back</a>
        </div>
    </div>
</div>
</body>
</html>
