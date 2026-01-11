-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 08:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default_book.jpg',
  `stock_quantity` int(11) DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `category`, `price`, `description`, `image`, `stock_quantity`, `created_at`) VALUES
(17, 'البؤساء', 'فيكتور هيغو', 'أدب كلاسيكي', 60.00, 'رواية تاريخية ملحمية من تأليف الكاتب الفرنسي فكتور هيغو نشرت سنة 1862، وتعد من أشهر روايات القرن التاسع عشر، إنه يصف وينتقد في هذا الكتاب الظلم الاجتماعي في فرنسا بين سقوط نابليون في 1815 والثورة الفاشلة ضد الملك لويس فيليب في 1832. إنه يكتب في مقدمته للكتاب: «تخلق العادات والقوانين في فرنسا ظرفًا اجتماعيًا هو نوع من جحيم بشري. فطالما توجد لا مبالاة وفقر على الأرض، كتب كهذا الكتاب ستكون ضرورية دائمًاً.', 'book_1765568885_693c7175a0e6f.jpg', 3, '2025-12-12 19:48:05'),
(18, 'مقدمة إبن خلدون', 'إبن خلدون', 'تاريخ وفلسفة', 100.00, 'كتابٌ ألفه المؤرخُ ابْنُ خلدون سنةَ 1377 حيث يقدم رؤية حول التاريخ العالمي. ويرى بعض المفكرين المعاصرين أنه أول عمل يتناول العلوم الاجتماعية مثل علم الاجتماع، والديموغرافيا، والتاريخ الثقافي. تتناول المقدِّمة أيضًا علم العقائد الإسلامية، وعلم كتابة التاريخ، وفلسفة التاريخ، والاقتصاد، والنظرية السياسية، وعلم البيئة. والعلوم الطبيعية وعلم الأحياء والكيمياء ويعتبر ابن خلدون مؤسس علم الاجتماع .', 'book_1765571494_6052.jpg', 2, '2025-12-12 19:52:39'),
(19, '48 قانوناً للسلطة', 'روبرت غرين', 'تطوير الذات', 100.00, '48 قانونا للسلطة أو كيف تمسك بزمام القوة هو أول كتاب للكاتب الأمريكي روبرت غرين، صدر عام 1998، بيعت منه أكثر من 1.2 مليون نسخة في الولايات المتحدة. وهو مشهور بين نزلاء السجون والمشاهير.\r\n\r\nفي العلوم السياسة والقوة هناك ثمانٍ وأربعين قانوناً نوعياً تحكمها، ويزعم الكاتب أنه جمعها من خلال التجارب البشرية المختلفة عبر ثلاثة آلاف سنة مستشهداً بمئات الأمثلة والقصص ومعتمداً على المخزون الثقافي من كل الشعوب. وقد عَرَض في كتابه جميع القوانين، كل على حدة، ومن ثم اختصر شرحه في أسطر قليلة ليذكر نموذجاً إنسانياً من التاريخ في معنى كل قانون وتطبيقه، ويذكر تبعات تطبيق القانون وآلية عمله كمصدر من مصادر القوة ومفاتيح الوصول للسلطة والنفوذ وقد بيع من الكتاب أكثر من 1.2 مليون نسخة في الولايات المتحدة وحدها.', 'book_1765569347_693c734327a4a.jpg', 5, '2025-12-12 19:55:47'),
(20, 'المحتوم', 'كيفن كيلي', 'تقنية', 40.00, 'الحتمي هو كتاب غير روائي صدر عام 2016 من تأليف كيفن كيلي وهذا يتنبأ بالقوى التكنولوجية الاثنتي عشرة التي ستشكل الثلاثين عامًا القادمة, ويُعد دليلاً لفهم التغيرات الكبرى مثل الذكاء الاصطناعي والواقع الافتراضي.', 'book_1765569712_693c74b093019.jpg', 10, '2025-12-12 20:01:52'),
(21, 'الرحيق المختوم', 'صفي الرحمن المباركفوري', 'سيرة نبوية', 60.00, 'الرحيق المختوم كتاب في سيرة النبي محمد ﷺ طُبع ونشر لأول مرة اعتمادًا على مبحث في السيرة للشيخ صفي الرحمن المباركفوري قدمه مشاركًا به في مسابقة رابطة العالم الإسلامي في السيرة النبوية الشريفة التي أُعلن عنها في مؤتمر للرابطة في باكستان عام 1396 هـ، وأُعلنت نتائجها في شعبان 1398 هـ حيث حاز البحث على المركز الأول من بين 171 بحثا جرى تقديمها، وسُلّمت الجوائز في مؤتمر الرابطة في مكة سنة 1399 هـ، وأعلنت الأمانة العامة للرابطة أنها ستقوم بطبع البحوث الفائزة ونشرها بعدّة لغات، فكان منها هذا الكتاب الذي توالت طبعاته فيما بعد.', 'book_1765570697_693c7889d21f6.jpg', 7, '2025-12-12 20:18:17'),
(22, 'كافكا والتصوير الفوتوغرافي', 'كارولين دوتلينغر', 'سيرة ذاتية', 2.00, 'هو دراسة تحليلية لكيفية استكشاف كافكا لمواضيع مثل الهوية والذاكرة والعلاقات عبر عدسة التصوير، حيث كان مفتونًا به، وظهر التصوير في أعماله الأدبية ورسائله بشكل بارز كأداة لفهم عوالم الواقع الخفية وتحفيز التفكير العميق، ويُظهر الكتاب كيف استخدم كافكا هذا الفن لاستكشاف اهتماماته الأساسية في الكتابة.', 'book_1765570870_693c79361cbf2.jpg', 10, '2025-12-12 20:21:10'),
(23, 'ديوان المتنبي', 'المتنبي', 'شعر وأدب', 30.00, 'ديوان المتنبي هو كتاب يضم مجموعة الشعر الكامل لأبي الطيب المتنبي، الشاعر العربي الشهير، ويُعد من أعظم الأعمال الأدبية العربية، حيث يحتوي على قصائد في المدح، الهجاء، الغزل، الفخر، والحكمة، ويعكس سيرته الذاتية وتطلعاته في القرن الرابع الهجري، ويتميز بجمال لغته وعمق معانيه، وغالباً ما يُنشر مشروحاً لشرح غوامض معانيه وتفسير سياقها التاريخي.', 'book_1765571064_693c79f8a921c.jpg', 10, '2025-12-12 20:24:24'),
(24, 'الإنمساخ', 'فرانز كافكا', 'فلسفة', 80.00, 'هي رواية قصيرة كتبها الروائي الألماني فرانز كافكا، نُشرت لأول مرة عام 1915. وهي من أشهر أعمال القرن العشرين وأكثرها تأثيرًا، حيث تتم دراستها في العديد من الجامعات والكليًات في العالم الغربي. وقد وصفها الكاتب البلغاري إلياس كانيتي بكونها «أحد الأعمال القليلة الرائعة، وأحد أفضل أعمال الخيال الشعري المكتوبة في هذا القرن.', 'book_1765571200_693c7a80ab453.jpg', 1, '2025-12-12 20:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `book_id`, `quantity`, `added_at`) VALUES
(1, 3, 24, 1, '2025-12-12 20:32:08'),
(2, 3, 23, 1, '2025-12-12 20:32:10'),
(3, 3, 22, 1, '2025-12-12 22:47:21');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `is_admin`, `created_at`) VALUES
(1, 'admin', 'admin@maktaba.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-12-12 18:57:40'),
(2, 'ahmed1', 'ahmed1@gmail.com', '$2y$10$xOjAQgUgrRn/6MGQ0L2rxun70B7UB2eCkh5QWkUhndeh0AXNeF4c2', 0, '2025-12-12 18:59:28'),
(3, 'moh', 'moh@gmail.com', '$2y$10$ePOMfUKn3DSE2zCn5etNruylKboO0ofcCPCVwoPYP31MmpuuTFZL2', 0, '2025-12-12 19:19:57'),
(4, 'ahmed11', 'ahmed1ff@gmail.com', '$2y$10$0Km1dEOIADYSl.SoHuAgj.DcmAa/.W.t76HVc6dowC0kgshtVqds6', 0, '2025-12-13 18:44:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
