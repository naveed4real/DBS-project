<?php
// ─── Database Configuration ───────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mydb_');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die('<div style="font-family:sans-serif;padding:2rem;background:#fee2e2;color:#991b1b;border-radius:8px;">
        <h2>⚠️ Database Connection Failed</h2>
        <p>' . mysqli_connect_error() . '</p>
        <p>Make sure XAMPP MySQL is running and the database <strong>' . DB_NAME . '</strong> exists.</p>
    </div>');
}

mysqli_set_charset($conn, 'utf8mb4');

// ─── Session Start ─────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── Helper: Cart Item Count ───────────────────────────────────────────────────
function getCartCount() {
    if (!isset($_SESSION['cart'])) return 0;
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}

// ─── Helper: Format Currency ───────────────────────────────────────────────────
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

// ─── Helper: Sanitize Input ────────────────────────────────────────────────────
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, trim($input));
}
?>
