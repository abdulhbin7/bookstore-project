<?php
require_once '../config/database.php';
if (!isLoggedIn() || !isAdmin()) { redirect('../index.php'); }

$sales = mysqli_query($conn, "
    SELECT s.*, u.username, b.title 
    FROM sales s 
    LEFT JOIN users u ON s.user_id = u.user_id 
    LEFT JOIN books b ON s.book_id = b.book_id 
    ORDER BY s.sale_date DESC
");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المبيعات - مكتبة</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">📚 اقْـرَأْ</div>
            <ul class="admin-nav">
                <li><a href="admin_home.php" class="admin-nav-link">لوحة التحكم</a></li>
                <li><a href="manage_books.php" class="admin-nav-link">إدارة الكتب</a></li>
                <li><a href="add_book.php" class="admin-nav-link">إضافة كتاب</a></li>
                <li><a href="view_users.php" class="admin-nav-link">المستخدمون</a></li>
                <li><a href="view_sales.php" class="admin-nav-link active">المبيعات</a></li>
                <li style="margin-top: auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.2);">
                    <a href="../logout.php" class="admin-nav-link">خروج</a>
                </li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">المبيعات</h1>
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>الكتاب</th>
                            <th>الكمية</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($sales && mysqli_num_rows($sales) > 0): ?>
                            <?php $i = 1; while ($sale = mysqli_fetch_assoc($sales)): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><strong><?php echo htmlspecialchars($sale['username'] ?? 'غير معروف'); ?></strong></td>
                                <td><?php echo htmlspecialchars($sale['title'] ?? 'كتاب محذوف'); ?></td>
                                <td><?php echo $sale['quantity']; ?></td>
                                <td style="color: var(--red); font-weight: 600;">
                                    <?php echo number_format($sale['total_price'], 2); ?> ر.س
                                </td>
                                <td><?php echo date('Y/m/d H:i', strtotime($sale['sale_date'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 48px; color: var(--ink-silver);">
                                    لا توجد مبيعات بعد
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
