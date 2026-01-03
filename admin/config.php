<?php
/*************************************************
 * config.php
 * Central configuration file
 * Project: The Raj Enterprises
 *************************************************/

// =====================
// DATABASE SETTINGS
// =====================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'theraj');

// =====================
// DATABASE CONNECTION
// =====================
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// =====================
// SET CHARSET
// =====================
$conn->set_charset("utf8mb4");

// =====================
// SITE SETTINGS
// =====================
define('SITE_NAME', 'The Raj Enterprises');
define('BASE_URL', 'http://localhost/theraj/'); // change when live

// =====================
// PATH SETTINGS
// =====================
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', 'uploads/');

// =====================
// SECURITY (FOR FUTURE)
// =====================
// session_start(); // enable in admin/login pages only
// ini_set('session.cookie_httponly', 1);
// ini_set('session.use_only_cookies', 1);

?>
