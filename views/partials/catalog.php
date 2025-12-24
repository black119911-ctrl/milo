<?php
// session_destroy();
// echo "<pre>";
// var_dump($_SESSION);
// echo "</pre>";
?>

<style>
.quantity-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin: 10px 0;
}

.quantity-btn {
    background: #fff;
    color: black;
    border: 1px solid green;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.quantity-btn:disabled {
    cursor: not-allowed;
}

.current-quantity {
    font-weight: bold;
    font-size: 18px;
    min-width: 20px;
    text-align: center;
}

.catalog__list-product.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Стили для статуса товара */
.catalog__list-product-status {
    display: flex;
    justify-content: center;
    margin: 8px 0 12px 0;
}

.stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.stock-badge--in {
    background: rgba(16, 94, 52, 0.1);
    color: #105E34;
}

.stock-badge--out {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    padding: 10px 16px;
    border-radius: 8px;
}

.stock-badge__subtitle {
    font-size: 13px;
    font-weight: normal;
    margin-top: 4px;
    opacity: 0.8;
    display: block;
}

.stock-badge svg {
    flex-shrink: 0;
}

/* Стили для кнопок */
.catalog__list-product-btns {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 15px;
}

.catalog__list-product-addcart:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Для товаров не в наличии */
.catalog__list-product:not([data-in-stock="true"]) {
    opacity: 0.85;
    position: relative;
}

.catalog__list-product:not([data-in-stock="true"])::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.3);
    pointer-events: none;
    border-radius: inherit;
}

.catalog__list-product:not([data-in-stock="true"]) .catalog__list-product-image {
    filter: grayscale(0.3);
}

.catalog__list-product:not([data-in-stock="true"]) .catalog__list-product-prices {
    opacity: 0.6;
}

/* Для мобильных устройств */
@media (max-width: 768px) {
    .stock-badge {
        padding: 5px 10px;
        font-size: 12px;
    }
    
    .stock-badge--out {
        padding: 8px 12px;
    }
}
</style>

<section class="section">
    <div class="container">
        <div class="section__title">
            <h2>Каталог</h2>
        </div>
        
        <div class="catalog__links">
            <div class="catalog__links-inner">
                <a href="https://www.wildberries.ru/brands/311407959-made-by-milovan4ik" target="_blank" class="btn catalog__links-btn">Наша косметика на Wildberries</a>
                <a href="https://www.ozon.ru/seller/narodnaya-lyubov-2748023/products/" target="_blank" class="btn catalog__links-btn">Наша косметика на Ozon</a>
                <a href="https://t.me/MadeByMilovan4ik" target="_blank" class="btn catalog__links-btn">Телеграм-канал</a>
            </div>
        </div>
        
        <div class="catalog__list">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    // Определяем статус товара
                    $isInStock = ($product['stock'] == 1 || $product['stock'] === 'true' || $product['stock'] === true);
                    $addCartDisabled = $isInStock ? '' : 'disabled';
                    ?>
                    
                    <div class="catalog__list-product" 
                         data-id="<?= $product['id'] ?>" 
                         data-in-stock="<?= $isInStock ? 'true' : 'false' ?>">
                        
                        <!-- Изображение товара -->
                        <div class="catalog__list-product-image">
                            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        
                        <!-- Название товара -->
                        <h3 class="catalog__list-product-name"><?= htmlspecialchars($product['name']) ?></h3>
                        
                        <!-- Подзаголовок -->
                        <div class="catalog__list-product-desc"><?= htmlspecialchars($product['subtitle']) ?></div>
                        
                        <!-- Статус товара -->
                        <div class="catalog__list-product-status">
                            <?php if ($isInStock): ?>
                                <span class="stock-badge stock-badge--in">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                        <path d="M11.6667 3.5L5.25 9.91667L2.33333 7" stroke="#105E34" stroke-width="2" 
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    В наличии
                                </span>
                            <?php else: ?>
                                <span class="stock-badge stock-badge--out">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15Z" 
                                              stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10 6L6 10" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6 6L10 10" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Нет в наличии
                                    <span class="stock-badge__subtitle">Товар временно недоступен</span>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Цены -->
                        <div class="catalog__list-product-prices">
                            <?php if ($product['discount'] > 0) : ?>
                                <div class="catalog__list-product-price"><?= number_format($product['discount'], 0, '', ' ') ?> ₽</div>
                                <div class="catalog__list-product-price crossed-out"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
                            <?php else : ?>
                                <div class="catalog__list-product-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
                            <?php endif ?>
                        </div>
                        
                        <!-- Кнопки действий -->
                        <div class="catalog__list-product-btns">
                            <a href="/product/<?= $product['id'] ?>"
                               class="btn__white catalog__list-product-permalink">
                               Подробнее
                            </a>
                            
                            <!-- Индивидуальные контролы для каждого товара -->
                            <div class="quantity-controls" style="display: none;">
                                <button class="quantity-btn" type="button">-</button>
                                <span class="current-quantity">0</span>
                                <button class="quantity-btn" type="button">+</button>
                            </div>
                            
                            <button <?= $addCartDisabled ?> 
                                    class="btn catalog__list-product-addcart"
                                    <?php if (!$isInStock): ?>title="Товар временно недоступен"<?php endif; ?>>
                                Добавить в корзину
                            </button>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <!-- Состояние пустого каталога -->
                <div class="empty-catalog">
                    <div style="text-align: center; padding: 40px 20px;">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" style="margin-bottom: 20px; opacity: 0.5;">
                            <path d="M52 20L24 20M52 20L44 44M52 20L40 8M24 20L12 8M24 20L16 44M16 44H44M16 44L12 56M44 44L48 56" 
                                  stroke="#105E34" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="24" cy="32" r="2" fill="#105E34"/>
                            <circle cx="40" cy="32" r="2" fill="#105E34"/>
                        </svg>
                        <h3 style="color: var(--text-dark); margin-bottom: 10px; font-size: 20px;">
                            Нет товаров в наличии
                        </h3>
                        <p style="color: var(--text-light); max-width: 400px; margin: 0 auto;">
                            В данный момент все товары временно отсутствуют на складе.
                            Пожалуйста, зайдите позже или свяжитесь с нами для уточнения информации.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Передаем данные корзины из PHP в JavaScript
window.initialCartData = <?= json_encode($cart ?? []) ?>;
console.log('Корзина из PHP сессии:', window.initialCartData);

// JavaScript для управления товарами не в наличии
document.addEventListener('DOMContentLoaded', function() {
    const outOfStockProducts = document.querySelectorAll('.catalog__list-product:not([data-in-stock="true"])');
    
    outOfStockProducts.forEach(product => {
        // Делаем кнопку "Подробнее" основной для товаров не в наличии
        const detailBtn = product.querySelector('.catalog__list-product-permalink');
        if (detailBtn) {
            detailBtn.classList.remove('btn__white');
            detailBtn.classList.add('btn');
            detailBtn.style.marginTop = '0';
            detailBtn.style.width = '100%';
        }
        
        // Скрываем кнопку добавления в корзину для товаров не в наличии
        const addCartBtn = product.querySelector('.catalog__list-product-addcart');
        if (addCartBtn) {
            addCartBtn.style.display = 'none';
        }
    });
});
</script>