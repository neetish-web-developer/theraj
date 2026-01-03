<?php
/*************************************************
 * Admin Login
 * Session timeout: 5 minutes (300 seconds)
 *************************************************/

session_start();
require_once 'config.php';

// ================= SESSION TIMEOUT CONFIG =================
define('SESSION_TIMEOUT', 300); // 5 minutes

// If already logged in → check timeout
if (isset($_SESSION['admin_id'])) {

    if (isset($_SESSION['LAST_ACTIVITY']) &&
        (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {

        session_unset();
        session_destroy();
        header("Location: index.php?timeout=1");
        exit;
    }

    $_SESSION['LAST_ACTIVITY'] = time();
    header("Location: home.php");
    exit;
}

$error = "";

// ================= LOGIN HANDLER =================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT * FROM admin_users WHERE username=? LIMIT 1"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        if (password_verify($password, $admin['password'])) {

            session_regenerate_id(true); // security
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['LAST_ACTIVITY'] = time();

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Admin user not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login | The Raj Enterprises</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap + FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}
.login-box{
    background:#fff;
    padding:35px;
    border-radius:16px;
    width:100%;
    max-width:420px;
    box-shadow:0 12px 35px rgba(0,0,0,.3);
}
.login-box h3{
    text-align:center;
    margin-bottom:25px;
    font-weight:600;
}
.input-group-text{
    background:#fff;
    cursor:pointer;
}
footer{
    text-align:center;
    font-size:13px;
    color:#666;
    margin-top:20px;
}
</style>
</head>

<body>

<div class="login-box">
    <h3>🔐 Admin Login</h3>

    <?php if (isset($_GET['timeout'])): ?>
        <div class="alert alert-warning">
            Session expired due to inactivity. Please login again.
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text"
                   name="username"
                   class="form-control"
                   required
                   autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control"
                       required>
                <span class="input-group-text" id="togglePassword">
                    <i class="fa-solid fa-eye"></i>
                </span>
            </div>
        </div>

        <button class="btn btn-dark w-100 py-2">Login</button>
    </form>

    <footer>
        © <?= date('Y') ?> The Raj Enterprises
    </footer>
</div>

<script>
const toggle = document.getElementById("togglePassword");
const password = document.getElementById("password");
const icon = toggle.querySelector("i");

toggle.addEventListener("click", () => {
    password.type = password.type === "password" ? "text" : "password";
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");
});
</script>

</body>
</html>
