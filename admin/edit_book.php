<?php
require_once '../config/database.php';
if (!isLoggedIn() || !isAdmin()) { redirect('../index.php'); }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books WHERE book_id = $id"));

if (!$book) {
    header("Location: manage_books.php");
    exit;
}

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
        $imageName = $book['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $imageName = 'book_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $imageName);
            }
        }
        
        $query = "UPDATE books SET title='$title', author='$author', category='$category', price=$price, description='$description', image='$imageName', stock_quantity=$stock WHERE book_id=$id";
        if (mysqli_query($conn, $query)) {
            $success = 'تم تحديث الكتاب بنجاح!';
            $book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books WHERE book_id = $id"));
        } else { 
            $error = 'حدث خطأ في تحديث الكتاب'; 
        }
    }
}

$categories = mysqli_query($conn, "SELECT DISTINCT category FROM books ORDER BY category");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل كتاب - مكتبة</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">📚 مكتبة</div>
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
                <h1 class="admin-title">تعديل كتاب</h1>
                <a href="manage_books.php" class="btn btn-secondary">← العودة</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 200px 1fr; gap: 32px; max-width: 900px;">
           
                <div>
                    <img src="../uploads/<?php echo htmlspecialchars($book['image']); ?>" 
                         style="width: 100%; border: 2px solid var(--ink-pale);"
                         onerror="this.src='https://via.placeholder.com/200x280/2d2d2d/c41e3a?text=📚'">
                </div>
                
                
                <div style="background: var(--pure-white); border: 1px solid var(--ink-pale); padding: 32px;">
                    <form method="POST" enctype="multipart/form-data">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <div class="form-group">
                                <label class="form-label">عنوان الكتاب *</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?php echo htmlspecialchars($book['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">المؤلف *</label>
                                <input type="text" name="author" class="form-control"
                                       value="<?php echo htmlspecialchars($book['author']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">التصنيف *</label>
                                <input type="text" name="category" class="form-control" list="cats"
                                       value="<?php echo htmlspecialchars($book['category']); ?>" required>
                                <datalist id="cats">
                                    <?php while ($c = mysqli_fetch_assoc($categories)): ?>
                                        <option value="<?php echo htmlspecialchars($c['category']); ?>">
                                    <?php endwhile; ?>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="form-label">السعر (ر.س) *</label>
                                <input type="number" name="price" class="form-control" step="0.01" min="0.01"
                                       value="<?php echo $book['price']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">الكمية المتوفرة</label>
                                <input type="number" name="stock_quantity" class="form-control" min="0" 
                                       value="<?php echo $book['stock_quantity']; ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">تغيير صورة الغلاف</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($book['description']); ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">حفظ التغييرات</button>
                            <a href="manage_books.php" class="btn btn-secondary btn-lg">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
