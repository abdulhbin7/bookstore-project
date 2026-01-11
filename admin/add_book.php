
<?php
require_once '../config/database.php';
if (!isLoggedIn() || !isAdmin()) { redirect('../index.php'); }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($conn, $_POST['title']);
    $author = sanitize($conn, $_POST['author']);
    $category = sanitize($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock_quantity']);
    $description = sanitize($conn, $_POST['description']);
    
    if (empty($title) || empty($author) || empty($category) || $price <= 0) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } else {
        $imageName = 'default_book.jpg';
        
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            // تحديد المسار  
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/bookstore/uploads/';
            
            // إنشاء المجلد إذا لم يكن موجود
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                chmod($uploadDir, 0777);
            }
            
            
            $fileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
           
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($fileType, $allowedTypes)) {
                $error = 'نوع الملف غير مسموح. يرجى رفع صورة (jpg, jpeg, png, gif, webp)';
            } elseif ($_FILES['image']['size'] > $maxSize) {
                $error = 'حجم الصورة كبير جداً. الحد الأقصى 5MB';
            } else {
               
                $imageName = 'book_' . time() . '_' . uniqid() . '.' . $fileType;
                $uploadPath = $uploadDir . $imageName;
                
              
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    chmod($uploadPath, 0644);
                } else {
                    $error = 'فشل رفع الصورة. تحقق من صلاحيات المجلد uploads';
                    $imageName = 'default_book.jpg';
                }
            }
        }
        
       
        if (empty($error)) {
            $query = "INSERT INTO books (title, author, category, price, description, image, stock_quantity) 
                      VALUES ('$title', '$author', '$category', $price, '$description', '$imageName', $stock)";
            
            if (mysqli_query($conn, $query)) {
                $success = 'تمت إضافة الكتاب بنجاح!';
                $_POST = array();
            } else { 
                $error = 'حدث خطأ في إضافة الكتاب: ' . mysqli_error($conn); 
            }
        }
    }
}

$categories = ['رواية', 'أدب كلاسيكي', 'شعر وأدب', 'تاريخ وفلسفة', 'فلسفة', 'سيرة ذاتية', 'سيرة نبوية', 'تطوير الذات', 'علوم', 'تقنية'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كتاب - مكتبة</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">📚 اقْـرَأْ</div>
            <ul class="admin-nav">
                <li><a href="admin_home.php" class="admin-nav-link">لوحة التحكم</a></li>
                <li><a href="manage_books.php" class="admin-nav-link">إدارة الكتب</a></li>
                <li><a href="add_book.php" class="admin-nav-link active">إضافة كتاب</a></li>
                <li><a href="view_users.php" class="admin-nav-link">المستخدمون</a></li>
                <li><a href="view_sales.php" class="admin-nav-link">المبيعات</a></li>
                <li style="margin-top: auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.2);">
                    <a href="../logout.php" class="admin-nav-link">خروج</a>
                </li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">إضافة كتاب جديد</h1>
                <a href="manage_books.php" class="btn btn-secondary">← العودة</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div style="background: var(--pure-white); border: 1px solid var(--ink-pale); padding: 32px; max-width: 800px;">
                <form method="POST" enctype="multipart/form-data">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div class="form-group">
                            <label class="form-label">عنوان الكتاب *</label>
                            <input type="text" name="title" class="form-control" placeholder="أدخل عنوان الكتاب" 
                                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">المؤلف *</label>
                            <input type="text" name="author" class="form-control" placeholder="أدخل اسم المؤلف"
                                   value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">التصنيف *</label>
                            <select name="category" class="form-control" required>
                                <option value="">اختر التصنيف</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat; ?>" <?php echo (isset($_POST['category']) && $_POST['category'] == $cat) ? 'selected' : ''; ?>>
                                        <?php echo $cat; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">السعر (ر.س) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0.01" placeholder="0.00"
                                   value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">الكمية المتوفرة</label>
                            <input type="number" name="stock_quantity" class="form-control" min="0" 
                                   value="<?php echo isset($_POST['stock_quantity']) ? htmlspecialchars($_POST['stock_quantity']) : '10'; ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">صورة الغلاف (JPG, PNG, GIF - حد أقصى 5MB)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="أدخل وصف الكتاب..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">إضافة الكتاب</button>
                        <a href="manage_books.php" class="btn btn-secondary btn-lg">إلغاء</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>