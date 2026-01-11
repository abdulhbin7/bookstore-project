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
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($cartId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart ID']);
    exit;
}

if ($quantity < 1) $quantity = 1;
if ($quantity > 99) $quantity = 99;

// Update quantity (only if it belongs to this user)
$updateQuery = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cartId AND user_id = $userId";
$result = mysqli_query($conn, $updateQuery);

if (mysqli_affected_rows($conn) >= 0) {
    echo json_encode([
        'success' => true, 
        'message' => 'Cart updated',
        'quantity' => $quantity
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
}
?>
