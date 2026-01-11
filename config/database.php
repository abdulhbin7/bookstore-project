<?php


// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookstore_db');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize input to prevent SQL Injection
 */
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

/**
 * Redirect function
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Display alert message
 */
function showAlert($message, $type = 'info') {
    $_SESSION['alert'] = ['message' => $message, 'type' => $type];
}

/**
 * Get and clear alert
 */
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}
?>
