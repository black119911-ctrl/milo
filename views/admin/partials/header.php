<?php
$current_page = $currentPage ?? ($_SESSION['admin_current_page'] ?? 'dashboard');
$username = $_SESSION['admin_username'] ?? 'Администратор';
?>
<header class="admin-header">
    <!-- Верхняя панель с меню-бургер и лого -->
    <div class="admin-topbar">
        <button class="menu-toggle" id="menuToggle" aria-label="Открыть меню" type="button">
            <span class="hamburger"></span>
            <span class="hamburger"></span>
            <span class="hamburger"></span>
        </button>

        <div class="admin-logo">
            <div class="logo-icon">
                <i class="fas fa-cube"></i>
            </div>
            <span class="logo-text">MILOVAN4IK</span>
        </div>

        <div class="admin-user">
            <div class="user-avatar">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </div>
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
                <span class="user-role">Администратор</span>
            </div>
        </div>
    </div>

    <!-- Мобильное меню (скрытое по умолчанию) -->
    <nav class="admin-nav-mobile" id="mobileNav">
        <div class="nav-header">
            <h3>Навигация</h3>
            <button class="nav-close" id="navClose" type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <ul class="nav-menu">
            <li>
                <a href="/admin" class="<?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Дашборд</span>
                </a>
            </li>
            <li>
                <a href="/admin/orders" class="<?php echo ($current_page === 'orders') ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Заказы</span>
                    <span class="badge">5</span>
                </a>
            </li>
            <li>
                <a href="/admin/products" class="<?php echo ($current_page === 'products') ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i>
                    <span>Товары</span>
                </a>
            </li>
            <!-- Замени эти два li в mobile menu: -->
            <li>
                <a href="/admin/reviews" class="<?php echo ($current_page === 'reviews') ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i>
                    <span>Отзывы</span>
                    <?php if (isset($reviews_count) && $reviews_count > 0): ?>
                    <span class="badge"><?php echo $reviews_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="/admin/product-reviews"
                    class="<?php echo ($current_page === 'product-reviews') ? 'active' : ''; ?>">
                    <i class="fas fa-comment"></i>
                    <span>Отзывы к товарам</span>
                    <?php if (isset($product_reviews_count) && $product_reviews_count > 0): ?>
                    <span class="badge"><?php echo $product_reviews_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="/admin/services" class="<?php echo ($current_page === 'services') ? 'active' : ''; ?>">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Услуги</span>
                </a>
            </li>
            <li>
                <a href="/admin/promocodes" class="<?php echo ($current_page === 'promocodes') ? 'active' : ''; ?>">
                    <i class="fas fa-tag"></i>
                    <span>Промокоды</span>
                </a>
            </li>
            <li class="nav-divider"></li>
            <li>
                <a href="/" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>На сайт</span>
                </a>
            </li>
            <li>
                <a href="/admin/logout" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Выйти</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Затемнение фона при открытом меню -->
    <div class="nav-overlay" id="navOverlay"></div>
</header>