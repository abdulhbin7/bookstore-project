<?php
require_once '../config/database.php';
if (!isLoggedIn() || !isAdmin()) { redirect('../index.php'); }

$booksCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM books"))['count'] ?? 0;
$usersCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE is_admin = 0"))['count'] ?? 0;
$salesCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM sales"))['count'] ?? 0;
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_price), 0) as total FROM sales"))['total'] ?? 0;

$recentBooks = mysqli_query($conn, "SELECT * FROM books ORDER BY created_at DESC LIMIT 5");
$recentUsers = mysqli_query($conn, "SELECT * FROM users WHERE is_admin = 0 ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - مكتبة</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">📚 اقْـرَأْ</div>
            <ul class="admin-nav">
                <li><a href="admin_home.php" class="admin-nav-link active">لوحة التحكم</a></li>
                <li><a href="manage_books.php" class="admin-nav-link">إدارة الكتب</a></li>
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
    <h1 class="admin-title">لوحة التحكم</h1>
    <span style="
        background-color: var(--ink-dark); /* الخلفية السوداء */
        color: var(--pure-white);       /* لون النص الأبيض */
        padding: 6px 12px;              /* حشو داخلي لجعله مربعاً */
        border-radius: 6px;             /* زوايا مستديرة */
        font-weight: 700;               /* خط عريض */
        display: inline-block;          /* ضروري لتطبيق الحشو والخلفية بشكل صحيح */
    ">
        مرحباً، <?php echo htmlspecialchars($_SESSION['username']); ?>
    </span>
</div>

            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-icon red">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                    <div class="stat-card-value"><?php echo $booksCount; ?></div>
                    <div class="stat-card-label">الكتب</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-icon dark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="stat-card-value"><?php echo $usersCount; ?></div>
                    <div class="stat-card-label">المستخدمون</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-icon red">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                    </div>
                    <div class="stat-card-value"><?php echo $salesCount; ?></div>
                    <div class="stat-card-label">المبيعات</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-icon dark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <div class="stat-card-value"><?php echo number_format($totalRevenue, 2); ?> ر.س</div>
                    <div class="stat-card-label">الإيرادات</div>
                </div>
            </div>

            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                
                <div class="data-table">
                    <div style="padding: 16px; border-bottom: 1px solid var(--ink-pale);">
                        <h3>أحدث الكتب</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>الغلاف</th>
                                <th>العنوان</th>
                                <th>السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recentBooks && mysqli_num_rows($recentBooks) > 0): ?>
                                <?php while ($book = mysqli_fetch_assoc($recentBooks)): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/<?php echo htmlspecialchars($book['image']); ?>" 
                                             class="data-table-image" 
                                             onerror="this.src='https://via.placeholder.com/50x70/2d2d2d/c41e3a?text=📚'">
                                    </td>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td style="color: var(--red); font-weight: 600;">
                                        <?php echo number_format($book['price'], 2); ?> ر.س
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">لا توجد كتب</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="data-table">
                    <div style="padding: 16px; border-bottom: 1px solid var(--ink-pale);">
                        <h3>أحدث المستخدمين</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recentUsers && mysqli_num_rows($recentUsers) > 0): ?>
                                <?php while ($user = mysqli_fetch_assoc($recentUsers)): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('Y/m/d', strtotime($user['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">لا يوجد مستخدمون</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
