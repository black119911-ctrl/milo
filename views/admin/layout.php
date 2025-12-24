<?php
// Проверка авторизации
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}



include __DIR__ . "/$templateName.php";

$page_title = $title ?? 'Панель управления';
$currentPage = $currentPage ?? 'dashboard';
$resources = $resources ?? ['styles' => [], 'scripts' => []];


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | MILOVAN4IK.RU</title>
    
    <!-- Основные стили -->
    <link rel="stylesheet" href="/styles/common.css">
    <link rel="stylesheet" href="/styles/admin/admin.css">
    <link rel="stylesheet" href="/styles/admin/mobile-header.css">
    
    <!-- Дополнительные стили страницы -->
    <?php foreach ($resources['styles'] ?? [] as $style): ?>
        <link rel="stylesheet" href="<?php echo $style; ?>">
    <?php endforeach; ?>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js для графиков -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
// В layout.php, перед include header
$reviews_count = $reviews_count ?? $_SESSION['reviews_count'] ?? 0;
$product_reviews_count = $product_reviews_count ?? $_SESSION['product_reviews_count'] ?? 0;

?>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <main class="admin-main-content">
        <?php echo $content ?? ''; ?>
    </main>
    
    <!-- Основные скрипты -->
    <script src="/scripts/admin/mobile-menu.js"></script>
    
    <!-- Дополнительные скрипты страницы -->
    <?php foreach ($resources['scripts'] ?? [] as $script): ?>
        <script src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
</body>
</html>