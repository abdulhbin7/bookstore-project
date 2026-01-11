<?php
require_once '../config/database.php';
if (!isLoggedIn() || !isAdmin()) { redirect('../index.php'); }


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM books WHERE book_id = $id");
    header("Location: manage_books.php");
    exit;
}

$books = mysqli_query($conn, "SELECT * FROM books ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الكتب - اقْـرَأْ</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">📚 اقْـرَأْ</div>
            <ul class="admin-nav">
                <li><a href="admin_home.php" class="admin-nav-link">لوحة التحكم</a></li>
                <li><a href="manage_books.php" class="admin-nav-link active">إدارة الكتب</a></li>
                <li><a href="add_book.php" class="admin-nav-link">إضافة كتاب</a></li>
                <li><a href="view_users.php" class="admin-nav-link">المستخدمون</a></li>
                <li><a href="view_sales.php" class="admin-nav-link">المبيعات</a></li>
                <li style="margin-top: auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.2);">
                    <a href="../logout.php" class="admin-nav-link">خروج</a>
                </li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">إدارة الكتب</h1>
                <a href="add_book.php" class="btn btn-primary">+ إضافة كتاب</a>
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>الغلاف</th>
                            <th>العنوان</th>
                            <th>المؤلف</th>
                            <th>التصنيف</th>
                            <th>السعر</th>
                            <th>المخزون</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($books) > 0): ?>
                            <?php while ($book = mysqli_fetch_assoc($books)): ?>
                            <tr>
                                <td>
                                    <img src="/bookstore/uploads/<?php echo htmlspecialchars($book['image']); ?>" 
                                        class="data-table-image"
                                          onerror="this.src='../images/default_book.png'">
                                </td>
                                <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td>
                                    <span class="card-category"><?php echo htmlspecialchars($book['category']); ?></span>
                                </td>
                                <td style="color: var(--red); font-weight: 600;">
                                    <?php echo number_format($book['price'], 2); ?> ر.س
                                </td>
                                <td><?php echo $book['stock_quantity']; ?></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="edit_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-sm btn-secondary">تعديل</a>
                                        <a href="manage_books.php?delete=<?php echo $book['book_id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('هل أنت متأكد من حذف هذا الكتاب؟')">حذف</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 48px;">
                                    <p style="color: var(--ink-silver); margin-bottom: 16px;">لا توجد كتب بعد</p>
                                    <a href="add_book.php" class="btn btn-primary">إضافة أول كتاب</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
