<?php
require_once 'config/database.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/admin_home.php');
    } else {
        redirect('home.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'يرجى ملء جميع الحقول';
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
        $result = mysqli_query($conn, $query);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                if ($user['is_admin']) {
                    redirect('admin/admin_home.php');
                } else {
                    redirect('home.php');
                }
            } else {
                $error = 'كلمة المرور غير صحيحة';
            }
        } else {
            $error = 'اسم المستخدم غير موجود';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - مكتبة</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            
            <img src="images/logo.png" alt="اقْـرَأْ" class="auth-logo" onerror="this.style.display='none'">
            
            <h1 class="auth-title">اقْـرَأْ</h1>
            <p class="auth-subtitle"> حياك الله , سجل دخولك </p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text" name="username" class="form-control" placeholder="أدخل اسم المستخدم" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" placeholder="أدخل كلمة المرور" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">تسجيل الدخول</button>
            </form>
            
            <p style="text-align: center; margin-top: 24px; color: var(--ink-gray); font-size: var(--font-size-sm);">
                ماعندك حساب؟ <a href="register.php" style="color: var(--red); font-weight: 700;">سجّل الآن</a>
            </p>
        </div>
    </div>
</body>
</html>
