<?php
require_once 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($password) < 6) {
        $message = "Password must be at least 6 characters";
    } else {
        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin user
        $stmt = $conn->prepare(
            "INSERT INTO admin_users (username, password) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $username, $hash);

        if ($stmt->execute()) {
            $message = "✅ Admin user created successfully. You can now login.";
        } else {
            $message = "❌ Username already exists.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Admin User</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f0f2f5;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}
.card{
    width:100%;
    max-width:420px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}
</style>
</head>

<body>

<div class="card">
    <div class="card-header fw-bold text-center">
        Create First Admin User
    </div>
    <div class="card-body">

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-success w-100">Create Admin</button>
        </form>

        <div class="alert alert-warning mt-3" style="font-size:13px;">
            ⚠️ After creating admin, <b>delete this file</b> for security.
        </div>
    </div>
</div>

</body>
</html>
