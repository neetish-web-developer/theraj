<?php
session_start();
require_once 'config.php';

// -------- SESSION & TIMEOUT --------
define('SESSION_TIMEOUT', 300);

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {

    session_unset();
    session_destroy();
    header("Location: index.php?timeout=1");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// -------- PAGE ROUTING --------
$page = $_GET['page'] ?? 'home';

$allowed_pages = [
    'home'    => 'home.php',
    'about'   => 'about.php',
    'gallery' => 'gallery.php'
];

$current_page = $allowed_pages[$page] ?? 'home.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | The Raj Enterprises</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    background: #f4f6f9;
}

/* Sidebar */
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    background: #1e293b;
    color: #fff;
    padding-top: 20px;
}
.sidebar h4 {
    text-align: center;
    margin-bottom: 30px;
}
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #cbd5e1;
    text-decoration: none;
}
.sidebar a:hover,
.sidebar a.active {
    background: #334155;
    color: #fff;
}

/* Content */
.content {
    margin-left: 240px;
    padding: 25px;
}
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
</style>
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <h4>🛠 Admin Panel</h4>

    <a href="dashboard.php?page=home"
       class="<?= $page=='home'?'active':'' ?>">
       <i class="fa fa-box"></i> Home
    </a>

    <a href="dashboard.php?page=about"
       class="<?= $page=='about'?'active':'' ?>">
       <i class="fa fa-info-circle"></i> About
    </a>

    <a href="dashboard.php?page=gallery"
       class="<?= $page=='gallery'?'active':'' ?>">
       <i class="fa fa-images"></i> Gallery
    </a>

    <a href="logout.php" class="text-danger">
       <i class="fa fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- ================= CONTENT ================= -->
<div class="content">

    <div class="top-bar">
        <h5 class="mb-0 text-capitalize"><?= htmlspecialchars($page) ?></h5>
        <span class="text-muted">
            Logged in as <?= htmlspecialchars($_SESSION['admin_username']) ?>
        </span>
    </div>

    <div class="card">
        <div class="card-body">
            <?php include $current_page; ?>
        </div>
    </div>

</div>

</body>
</html>
