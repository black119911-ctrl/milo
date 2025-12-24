<section class="cart-page">
    <div class="container">

        <div class="cart-page__header">
            <h1 class="cart-page__title">Корзина</h1>
            <div class="cart-page__count">
                Товаров в корзине: <span id="cart-items-count"><?= $cart_details['total_items'] ?></span>
            </div>
        </div>

        <div class="cart-page__content">
            <?php if (!empty($cart_details['items'])): ?>
                <div class="cart-items">
                    <?php foreach ($cart_details['items'] as $item): ?>
                    <div class="cart-item" data-id="<?= $item['id'] ?>">
                        <div class="cart-item__image">
                            <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                        </div>
                        
                        <div class="cart-item__info">
                            <h3 class="cart-item__name"><?= $item['name'] ?></h3>
                            <div class="cart-item__price"><?= $item['price'] ?> ₽</div>
                        </div>
                        
                        <div class="cart-item__controls">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="cartPageManager.changeQuantity(<?= $item['id'] ?>, -1)">-</button>
                                <span class="current-quantity"><?= $item['quantity'] ?></span>
                                <button class="quantity-btn" onclick="cartPageManager.changeQuantity(<?= $item['id'] ?>, 1)">+</button>
                            </div>
                            
                            <div class="cart-item__total">
                                <?= $item['total'] ?> ₽
                            </div>
                            
                            <button class="cart-item__remove" onclick="cartPageManager.removeItem(<?= $item['id'] ?>)">
                                🗑️
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <div class="cart-summary__total">
                        <div class="cart-summary__row">
                            <span>Товаров:</span>
                            <span id="summary-items-count"><?= $cart_details['total_items'] ?> шт.</span>
                        </div>
                        <div class="cart-summary__row">
                            <span>Общая стоимость:</span>
                            <span id="summary-total-price"><?= $cart_details['total_price'] ?> ₽</span>
                        </div>
                        <div class="cart-summary__row cart-summary__row--total">
                            <span>Итого к оплате:</span>
                            <span id="summary-final-price"><?= $cart_details['total_price'] ?> ₽</span>
                        </div>
                    </div>
                    
                    <div class="cart-summary__actions">
                        <button class="btn btn--primary btn--large" onclick="cartPageManager.checkout()">
                            Оформить заказ
                        </button>
                        <button class="btn btn--secondary" onclick="cartPageManager.clearCart()">
                            Очистить корзину
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="cart-empty">
                    <div class="cart-empty__icon">🛒</div>
                    <h2 class="cart-empty__title">Корзина пуста</h2>
                    <p class="cart-empty__text">Добавьте товары из каталога</p>
                    <a href="/catalog" class="btn btn--primary">Перейти в каталог</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Передаем данные корзины в JavaScript
window.cartPageData = {
    items: <?= json_encode($cart_details['items']) ?>,
    totalPrice: <?= $cart_details['total_price'] ?>,
    totalItems: <?= $cart_details['total_items'] ?>
};
</script>