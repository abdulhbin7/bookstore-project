<?php
require_once 'config/database.php';
if (!isLoggedIn()) { redirect('index.php'); }

$bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($bookId <= 0) { showAlert('معرف الكتاب غير صحيح', 'error'); redirect('home.php'); }

$result = mysqli_query($conn, "SELECT * FROM books WHERE book_id = $bookId");
if (mysqli_num_rows($result) === 0) { showAlert('الكتاب غير موجود', 'error'); redirect('home.php'); }

$book = mysqli_fetch_assoc($result);
$pageTitle = $book['title'];

$relatedResult = mysqli_query($conn, "SELECT * FROM books WHERE category = '" . sanitize($conn, $book['category']) . "' AND book_id != $bookId LIMIT 4");

include 'includes/header.php';
?>

<a href="home.php" class="btn btn-primary mb-4" style="box-shadow: var(--shadow-md);">→ العودة للكتب</a>

<div class="book-details" style="opacity: 1;">
    <div class="book-image-wrapper">
        <img src="/bookstore/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>"
              class="book-image-main" onerror="this.src='https://via.placeholder.com/400x550/2d2d2d/c41e3a?text=📚'">
    </div>

    <div class="book-info">
        <span class="book-category"><?php echo htmlspecialchars($book['category']); ?></span>
        <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>
        <p class="book-author">تأليف: <?php echo htmlspecialchars($book['author']); ?></p>
        <p class="book-price"><?php echo number_format($book['price'], 2); ?> ر.س</p>
        
        <div class="d-flex align-center gap-2 mb-4">
            <?php if ($book['stock_quantity'] > 0): ?>
                <span style="padding: 8px 16px; background: rgba(45, 90, 39, 0.1); color: #2d5a27; border-radius: var(--radius-sm);">
                    ✓ متوفر (<?php echo $book['stock_quantity']; ?> نسخة)
                </span>
            <?php else: ?>
                <span style="padding: 8px 16px; background: var(--red-pale); color: var(--red); border-radius: var(--radius-sm);">
                    ✗ غير متوفر
                </span>
            <?php endif; ?>
        </div>
        
<div class="book-description">
    <h3>الوصف</h3>
    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
</div>
        
        <div class="book-actions">
            <?php if ($book['stock_quantity'] > 0): ?>
                <button class="btn btn-primary btn-lg add-to-cart" data-book-id="<?php echo $book['book_id']; ?>">أضف للسلة</button>
            <?php endif; ?>
            <a href="cart.php" class="btn btn-secondary btn-lg">عرض السلة</a>
        </div>
    </div>
</div>

<?php if (mysqli_num_rows($relatedResult) > 0): ?>
<div style="margin-top: var(--space-xxl);">
    <h2 style="font-family: var(--font-arabic); font-size: var(--font-size-xl); margin-bottom: var(--space-lg);">كتب مشابهة</h2>
    <div class="grid grid-4">
        <?php while ($related = mysqli_fetch_assoc($relatedResult)): ?>
            <div class="card" style="opacity: 1;">
                <img src="uploads/<?php echo htmlspecialchars($related['image']); ?>" class="card-image" onerror="this.src='https://via.placeholder.com/300x200/2d2d2d/c41e3a?text=📚'">
                <div class="card-body">
                    <h3 class="card-title" style="font-size: var(--font-size-md);"><?php echo htmlspecialchars($related['title']); ?></h3>
                    <p class="card-author"><?php echo htmlspecialchars($related['author']); ?></p>
                    <div class="card-meta">
                        <span class="card-price"><?php echo number_format($related['price'], 2); ?> ر.س</span>
                        <a href="book.php?id=<?php echo $related['book_id']; ?>" class="btn btn-sm btn-secondary">عرض</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
