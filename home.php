<?php
require_once 'config/database.php';
if (!isLoggedIn()) { redirect('index.php'); }
if (isAdmin()) { redirect('admin/admin_home.php'); }

$pageTitle = 'الرئيسية';

$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize($conn, $_GET['category']) : '';

$query = "SELECT * FROM books WHERE 1=1";
if (!empty($search)) { $query .= " AND (title LIKE '%$search%' OR author LIKE '%$search%')"; }
if (!empty($category)) { $query .= " AND category = '$category'"; }
$query .= " ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$categoriesResult = mysqli_query($conn, "SELECT DISTINCT category FROM books ORDER BY category");

include 'includes/header.php';
?>


<div style="margin-bottom: 32px;">
    <span style="
        background-color: var(--ink-dark); 
        color: var(--pure-white);       
        padding: 6px 12px;             
        border-radius: 6px;             
        
        /* تم التعديل: حجم أصغر للمربع العلوي */
        font-size: 24px; 
        font-weight: 700;
        display: inline-block;          
        margin-bottom: 8px;
    ">
        اهلاً وسهلاً <?php echo htmlspecialchars($_SESSION['username']); ?>
    </span>
    
    <span style="
        background-color: var(--ink-dark); 
        color: var(--pure-white);       
        padding: 6px 12px;             
        border-radius: 6px;             
        
        font-size: 16px; /* حجم النص العادي */
        font-weight: 500;
        display: block; /* لتبدأ العبارة في سطر جديد */
        max-width: fit-content; /* لتحديد عرض المربع على حجم النص فقط */
    ">
        العلم يرفع بيتاً لا عماد له - والجهل يهدم بيت العز والشرف
    </span>
</div>


<div style="display: flex; gap: 16px; margin-bottom: 32px; flex-wrap: wrap;">
    <form method="GET" style="display: flex; gap: 12px; flex: 1; min-width: 300px;">
        <div class="search-wrapper" style="flex: 1;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" name="search" class="form-control" placeholder="ابحث عن كتاب أو مؤلف..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        
        <select name="category" class="form-control" style="width: auto; min-width: 150px;">
            <option value="">جميع التصنيفات</option>
            <?php while ($cat = mysqli_fetch_assoc($categoriesResult)): ?>
                <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit" class="btn btn-primary">بحث</button>
        <?php if (!empty($search) || !empty($category)): ?>
            <a href="home.php" class="btn btn-secondary">مسح</a>
        <?php endif; ?>
    </form>
</div>


<div class="grid grid-4">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($book = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <div style="position: relative; overflow: hidden;">
                    <?php 
                    $systemPath = $_SERVER['DOCUMENT_ROOT'] . '/bookstore/uploads/' . $book['image']; 
                    $imageExists = file_exists($systemPath) && $book['image'] != 'default_book.jpg';
                    ?>
                    <?php if ($imageExists): ?>
                        <img src="/bookstore/uploads/<?php echo htmlspecialchars($book['image']); ?>" 
                          alt="<?php echo htmlspecialchars($book['title']); ?>"
                                class="card-image">
                    <?php else: ?>
                        <div class="card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e8e8e8, #d0d0d0);">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="1" style="width: 48px; height: 48px;">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <span class="card-category" style="position: absolute; top: 12px; right: 12px;">
                        <?php echo htmlspecialchars($book['category']); ?>
                    </span>
                </div>
                
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="card-author"><?php echo htmlspecialchars($book['author']); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars(mb_substr($book['description'], 0, 60, 'UTF-8')) . '...'; ?></p>
                    
                    <div class="card-meta">
                        <span class="card-price"><?php echo number_format($book['price'], 2); ?> ر.س</span>
                    </div>
                    
                    <div style="display: flex; gap: 8px; margin-top: 16px;">
                        <a href="book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-secondary" style="flex: 1;">التفاصيل</a>
                        <button class="btn btn-primary add-to-cart" data-book-id="<?php echo $book['book_id']; ?>" style="flex: 1;">
                            أضف للسلة
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 64px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="1.5" 
                 style="width: 64px; height: 64px; margin-bottom: 16px;">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </svg>
            <h3 style="color: var(--ink-gray);">لا توجد كتب</h3>
            <p style="color: var(--ink-silver);">جرب تعديل معايير البحث</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
