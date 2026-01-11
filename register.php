<?php
require_once 'config/database.php';
if (isLoggedIn()) { redirect('home.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username']);
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'يرجى ملء جميع الحقول';
    } elseif (strlen($password) < 6) {
        $error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } elseif ($password !== $confirmPassword) {
        $error = 'كلمات المرور غير متطابقة';
    } else {
        $checkQuery = "SELECT user_id FROM users WHERE username = '$username' OR email = '$email'";
        if (mysqli_num_rows(mysqli_query($conn, $checkQuery)) > 0) {
            $error = 'اسم المستخدم أو البريد الإلكتروني موجود مسبقاً';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO users (username, email, password, is_admin) VALUES ('$username', '$email', '$hashedPassword', 0)";
            if (mysqli_query($conn, $insertQuery)) {
                showAlert('تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.', 'success');
                redirect('index.php');
            } else { $error = 'حدث خطأ، يرجى المحاولة مرة أخرى.'; }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب - مكتبة</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
           <img src="images/logo.png" alt="مكتبة" class="auth-logo" onerror="this.style.display='none'">
            
            <h1 class="auth-title">مكتبة</h1>
            <p class="auth-subtitle">أنشئ حسابك للبدء في رحلة القراءة</p>

            <div class="auth-body">
                <?php if ($error): ?>
                    <div class="alert alert-error"><span><?php echo $error; ?></span></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control" placeholder="اختر اسم مستخدم"
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" placeholder="أدخل بريدك الإلكتروني"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" placeholder="أنشئ كلمة مرور (6 أحرف على الأقل)" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="أعد إدخال كلمة المرور" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg">إنشاء الحساب</button>
                </form>
            </div>

            <div class="auth-footer">
                لديك حساب بالفعل؟ <a href="index.php">سجل دخولك</a>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
