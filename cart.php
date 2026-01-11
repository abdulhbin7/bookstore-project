<?php
require_once 'config/database.php';
if (!isLoggedIn()) { redirect('index.php'); }

$pageTitle = 'سلة المشتريات';
$userId = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT c.*, b.title, b.author, b.price, b.image FROM cart c JOIN books b ON c.book_id = b.book_id WHERE c.user_id = $userId ORDER BY c.added_at DESC");

$subtotal = 0;
$cartItems = [];
while ($item = mysqli_fetch_assoc($result)) {
    $item['total'] = $item['price'] * $item['quantity'];
    $subtotal += $item['total'];
    $cartItems[] = $item;
}

include 'includes/header.php';
?>

<h1 style="font-family: var(--font-arabic); font-size: var(--font-size-xxl); margin-bottom: var(--space-xl);">
    🛒 سلة المشتريات
</h1>

<?php if (count($cartItems) > 0): ?>
<div style="display: grid; grid-template-columns: 1fr 350px; gap: var(--space-xl);">
    <div class="cart-items">
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item" data-price="<?php echo $item['price']; ?>" style="opacity: 1;">
                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" class="cart-item-image" onerror="this.src='https://via.placeholder.com/100x140/2d2d2d/c41e3a?text=📚'">
                <div class="cart-item-details">
                    <h3 class="cart-item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="cart-item-author"><?php echo htmlspecialchars($item['author']); ?></p>
                    <div class="d-flex align-center justify-between" style="margin-top: auto;">
                        <div class="d-flex align-center gap-3">
                            <div class="quantity-control">
                                <button class="quantity-decrease" data-cart-id="<?php echo $item['cart_id']; ?>">−</button>
                                <span><?php echo $item['quantity']; ?></span>
                                <button class="quantity-increase" data-cart-id="<?php echo $item['cart_id']; ?>">+</button>
                            </div>
                            <button class="btn btn-ghost btn-sm remove-from-cart" data-cart-id="<?php echo $item['cart_id']; ?>" style="color: var(--red);">حذف</button>
                        </div>
                        <p class="cart-item-price"><?php echo number_format($item['total'], 2); ?> ر.س</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="cart-summary">
        <h2 class="cart-summary-title">ملخص الطلب</h2>
        <div class="cart-summary-row"><span>المجموع الفرعي</span><span><?php echo number_format($subtotal, 2); ?> ر.س</span></div>
        <div class="cart-summary-row"><span>الشحن</span><span style="color: #4ade80;">مجاني</span></div>
        <div class="cart-summary-row"><span>الضريبة (15%)</span><span><?php echo number_format($subtotal * 0.15, 2); ?> ر.س</span></div>
        <div class="cart-summary-row cart-summary-total"><span>الإجمالي</span><span><?php echo number_format($subtotal * 1.15, 2); ?> ر.س</span></div>
        <button class="btn btn-primary btn-block btn-lg mt-3">إتمام الشراء</button>
        <a href="home.php" class="btn btn-secondary btn-block mt-2">متابعة التسوق</a>
    </div>
</div>
<?php else: ?>
<div class="text-center" style="padding: var(--space-xxl);">
    <div style="font-size: 64px; margin-bottom: var(--space-lg);">🛒</div>
    <h2 style="color: var(--ink-gray);">سلتك فارغة</h2>
    <p style="color: var(--ink-silver); margin-bottom: var(--space-xl);">ابدأ بإضافة بعض الكتب!</p>
    <a href="home.php" class="btn btn-primary btn-lg">تصفح الكتب</a>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
