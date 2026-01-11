<?php
require_once '../config/database.php';
if (!isLoggedIn() || !isAdmin()) { redirect('../index.php'); }

$users = mysqli_query($conn, "SELECT * FROM users WHERE is_admin = 0 ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المستخدمون - مكتبة</title>
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
                <li><a href="view_users.php" class="admin-nav-link active">المستخدمون</a></li>
                <li><a href="view_sales.php" class="admin-nav-link">المبيعات</a></li>
                <li style="margin-top: auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.2);">
                    <a href="../logout.php" class="admin-nav-link">خروج</a>
                </li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">المستخدمون</h1>
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المستخدم</th>
                            <th>البريد الإلكتروني</th>
                            <th>تاريخ التسجيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($users) > 0): ?>
                            <?php $i = 1; while ($user = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo date('Y/m/d H:i', strtotime($user['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center" style="padding: 48px; color: var(--ink-silver);">
                                    لا يوجد مستخدمون مسجلون بعد
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
