<?php
// views/admin/dashboard.php
$stats = $stats ?? [];
?>

<!-- Навигация по разделам -->
<section class="admin-navigation">
    <div class="navigation-grid">
        <a href="/admin/products" class="nav-card">
            <div class="nav-card__icon">📦</div>
            <div class="nav-card__content">
                <div class="nav-card__number"><?= $stats['products'] ?? 0 ?></div>
                <div class="nav-card__label">Товары</div>
                <p>Управление товарами и их описаниями</p>
            </div>
        </a>
        <a href="/admin/services" class="nav-card">
            <div class="nav-card__icon">🔧</div>
            <div class="nav-card__content">
                <div class="nav-card__label">Услуга</div>
                <p>Управление услугой и ценой</p>
            </div>
        </a>
        <a href="/admin/promocodes" class="nav-card">
            <div class="nav-card__icon">🎫</div>
            <div class="nav-card__content">
                <div class="nav-card__label">Промокод</div>
                <p>Создание и управление промокодом</p>
            </div>
        </a>
        <a href="/admin/reviews" class="nav-card">
            <div class="nav-card__icon">⭐</div>
            <div class="nav-card__content">
                <div class="nav-card__number"><?= $stats['reviews'] ?? 0 ?></div>
                <div class="nav-card__label">Отзывы</div>
                <p>Загрузка и управление отзывами</p>
            </div>
        </a>
    </div>
</section>

<!-- Быстрые действия -->
<section class="quick-actions">
    <h2 class="quick-actions__title">Быстрые действия</h2>
    <div class="action-buttons">
        <a href="/admin/products?action=create" class="btn btn--primary">
            <span class="icon icon-plus"></span>
            Добавить товар
        </a>
        <a href="/admin/services?action=create" class="btn btn--primary">
            <span class="icon icon-plus"></span>
            Добавить услугу
        </a>
        <a href="/admin/reviews" class="btn btn--secondary">
            <span class="icon icon-upload"></span>
            Загрузить отзыв
        </a>
    </div>
</section>