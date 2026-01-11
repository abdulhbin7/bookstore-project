<?php

require_once 'config/database.php';

header('Content-Type: application/json');


if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user_id'];
$bookId = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

if ($bookId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
    exit;
}


$bookQuery = "SELECT book_id, stock_quantity FROM books WHERE book_id = $bookId";
$bookResult = mysqli_query($conn, $bookQuery);

if (mysqli_num_rows($bookResult) === 0) {
    echo json_encode(['success' => false, 'message' => 'Book not found']);
    exit;
}

$book = mysqli_fetch_assoc($bookResult);

if ($book['stock_quantity'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Book is out of stock']);
    exit;
}


$checkQuery = "SELECT cart_id, quantity FROM cart WHERE user_id = $userId AND book_id = $bookId";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    
    $cartItem = mysqli_fetch_assoc($checkResult);
    $newQuantity = $cartItem['quantity'] + 1;
    
    $updateQuery = "UPDATE cart SET quantity = $newQuantity WHERE cart_id = " . $cartItem['cart_id'];
    mysqli_query($conn, $updateQuery);
} else {
  
    $insertQuery = "INSERT INTO cart (user_id, book_id, quantity) VALUES ($userId, $bookId, 1)";
    mysqli_query($conn, $insertQuery);
}


$countQuery = "SELECT SUM(quantity) as count FROM cart WHERE user_id = $userId";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$cartCount = $countRow['count'] ?? 0;

echo json_encode([
    'success' => true, 
    'message' => 'Book added to cart',
    'cartCount' => $cartCount
]);
?>
