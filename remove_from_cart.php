<?php

require_once 'config/database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user_id'];
$cartId = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;

if ($cartId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart ID']);
    exit;
}

// Delete cart item (only if it belongs to this user)
$deleteQuery = "DELETE FROM cart WHERE cart_id = $cartId AND user_id = $userId";
$result = mysqli_query($conn, $deleteQuery);

if (mysqli_affected_rows($conn) > 0) {
    // Get updated cart count
    $countQuery = "SELECT SUM(quantity) as count FROM cart WHERE user_id = $userId";
    $countResult = mysqli_query($conn, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $cartCount = $countRow['count'] ?? 0;

    echo json_encode([
        'success' => true, 
        'message' => 'Item removed from cart',
        'cartCount' => $cartCount
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
}
?>
