<?php
require "config.php";

$error = "";

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | EDIGAL PHARMACY</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    height: 100vh;
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.login-card {
    max-width: 420px;
    width: 100%;
    border-radius: 14px;
}

.eye-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.eye-btn svg {
    width: 22px;
    height: 22px;
    stroke: #6c757d;
}

.eye-btn:hover svg {
    stroke: #0d6efd;
}
</style>
</head>

<body>

<div class="card shadow-lg login-card">
    <div class="card-body p-4">

        <!-- Header -->
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">EDIGAL PHARMACY</h3>
            <p class="text-muted mb-0">Secure Pharmacy Login</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <!-- Username -->
            <div class="mb-3">
                <input type="text"
                       name="username"
                       class="form-control"
                       placeholder="Username"
                       required autofocus>
            </div>

            <!-- Password with Eye Toggle -->
            <div class="mb-3 position-relative">
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       placeholder="Password"
                       required>

                <button type="button"
                        class="eye-btn position-absolute top-50 end-0 translate-middle-y me-3"
                        onclick="togglePassword()">

                    <!-- Eye OPEN (password hidden) -->
                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                        <circle cx="12" cy="12" r="3.5"/>
                    </svg>

                    <!-- Eye CLOSED (password visible) -->
                    <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.8" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3l18 18M2.25 12s3.75-7.5 9.75-7.5a9.9 9.9 0 015.4 1.6M21.75 12s-3.75 7.5-9.75 7.5a9.9 9.9 0 01-5.4-1.6"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.12 14.12A3.5 3.5 0 019.88 9.88"/>
                    </svg>

                </button>
            </div>

            <button class="btn btn-primary w-100 fw-semibold">
                Login
            </button>
        </form>

        <p class="text-center text-muted mt-3 mb-0" style="font-size: 13px;">
            © <?= date("Y") ?> EDIGAL PHARMACY
        </p>

    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const eyeOpen = document.getElementById("eye-open");
    const eyeClosed = document.getElementById("eye-closed");

    if (password.type === "password") {
        password.type = "text";   // SHOW password
        eyeOpen.style.display = "none";
        eyeClosed.style.display = "block";
    } else {
        password.type = "password"; // HIDE password
        eyeOpen.style.display = "block";
        eyeClosed.style.display = "none";
    }
}
</script>

</body>
</html>
