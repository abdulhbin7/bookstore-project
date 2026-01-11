<?php

require_once __DIR__ . '/../config/database.php';

$cartCount = 0;
if (isLoggedIn()) {
    $userId = $_SESSION['user_id'];
    $cartQuery = mysqli_query($conn, "SELECT SUM(quantity) as count FROM cart WHERE user_id = $userId");
    $cartResult = mysqli_fetch_assoc($cartQuery);
    $cartCount = $cartResult['count'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>اقْـرَأْ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
           <div class="page-wrapper">
        
           <nav class="navbar">
             <div class="container">
                
         <a href="home.php" class="navbar-brand">
                    📚 اقْـرَأْ
                </a>

                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li>
                                <a href="admin/admin_home.php" class="nav-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                                    </svg>
                                    لوحة التحكم
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="home.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                    </svg>
                                    الرئيسية
                                </a>
                            </li>
                            <li>
                                <a href="cart.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                    </svg>
                                    السلة
                                    <?php if ($cartCount > 0): ?>
                                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="logout.php" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                خروج
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                                </svg>
                                دخول
                            </a>
                        </li>
                        <li>
                            <a href="register.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="8.5" cy="7" r="4"/>
                                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                                </svg>
                                تسجيل
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        
        <main class="main-content">
            <div class="container">
                <?php
                $alert = getAlert();
                if ($alert):
                ?>
                <div class="alert alert-<?php echo $alert['type']; ?>">
                    <span><?php echo $alert['message']; ?></span>
                </div>
                <?php endif; ?>
