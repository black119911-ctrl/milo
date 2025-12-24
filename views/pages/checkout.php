<?php
// views/pages/checkout.php
$cart = $cart ?? [];
$cart_details = $cart_details ?? [];
$resources = $resources ?? [];
?>

<?php
function generateOrderDescription($items) {
    $description = '';
    foreach ($items as $index => $item) {
        if ($index > 0) $description .= ', ';
        
        $productName = !empty($item['alt_name']) ? $item['alt_name'] : $item['name'];
        
        $cleanName = html_entity_decode($productName, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleanName = strip_tags($cleanName);
        $cleanName = preg_replace('/[^\w\s\-\.\(\)]/u', '', $cleanName);
        $cleanName = trim($cleanName);
        
        $description .= $cleanName . ' - ' . $item['quantity'] . ' шт.';
    }
    return $description;
}
?>

<section class="checkout-page">
    <div class="container">
        <div class="checkout-title">
            <h2>Оформление заказа</h2>
        </div>

        <div class="checkout">
            <!-- Левая колонка - форма заказа -->
            <div class="order">
                <form class="order__form" id="checkout-form">
                    <input type="hidden" name="promocode" id="applied-promocode" value="">

                    <div class="order__form-field">
                        <label class="order__form-label">ФИО *</label>
                        <input type="text" name="name" class="input order__form-input" required>
                    </div>

                    <div class="order__form-field">
                        <label class="order__form-label">Телефон *</label>
                        <input type="tel" name="phone" class="input order__form-input" required>
                    </div>

                    <div class="order__form-field">
                        <label class="order__form-label">Email *</label>
                        <input type="email" name="email" class="input order__form-input" required>
                    </div>

                    <div class="order__form-field">
                        <label class="checkbox-label">
                            <input type="checkbox" name="agreement" id="agreement-checkbox">
                            <span>
                                Я соглашаюсь с 
                                <a href="/privacy-policy" target="_blank" class="order__form-oferta-link">политикой обработки персональных данных</a> 
                                и 
                                <a href="/oferta" target="_blank" class="order__form-oferta-link">договором оферты</a>
                                *
                            </span>
                        </label>
                        <div id="agreement-error" class="field-error">
                            Необходимо согласие с условиями
                        </div>
                    </div>

                    <div class="order__form-submit">
                        <!-- Скрытый номер заказа для виджета -->
                        <div class="ordernum" style="display: none;"><?= uniqid() ?></div>

                        <div id="alfa-payment-button" class="hidden" 
                            data-token="rvvghshk064ql82vu53aah43or"
                            data-order-number-selector=".ordernum" 
                            data-button-text="Оформить заказ"
                            data-amount="<?= $cart_details['final_total'] ?? 0 ?>" 
                            data-stages="1" 
                            data-version="1.0"
                            data-description="<?= htmlspecialchars(generateOrderDescription($cart_details['items'] ?? [])) ?>">
                        </div>

                        <div id="alfa-message">Заполните данные формы и нажмите галочку о согласии</div>
                    </div>
                </form>
            </div>

            <!-- Правая колонка - товары и промокод -->
            <div class="cart-total">
                <h3 class="cart-total__title">Ваш заказ</h3>

                <?php if (empty($cart_details['items'])): ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h2>Корзина пуста</h2>
                    <p>Добавьте товары из каталога</p>
                    <a href="/catalog" class="btn btn--primary">Перейти в каталог</a>
                </div>
                <?php else: ?>
                <div class="cart-total__list">
                    <?php foreach ($cart_details['items'] as $item): ?>
                    <div class="cart-total__list-item">
                        <div class="cart-total__list-item-img">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="cart-total__list-item-info">
                            <div class="cart-total__list-item-title">
                                <?= htmlspecialchars_decode($item['name'], ENT_QUOTES) ?>
                            </div>
                            <div class="cart-total__list-item-calcs">
                                <?php if (isset($item['original_price'])): ?>
                                <div class="cart-total__list-item-price">
                                    <span class="original-price"><?= number_format($item['original_price'], 0, '', ' ') ?> ₽</span>
                                    <span class="discount-price"><?= number_format($item['price'], 0, '', ' ') ?> ₽</span>
                                </div>
                                <?php else: ?>
                                <div class="cart-total__list-item-price">
                                    <?= number_format($item['price'], 0, '', ' ') ?> ₽
                                </div>
                                <?php endif; ?>
                                <div class="cart-total__list-item-quant"><?= $item['quantity'] ?> шт.</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="promocode">
                    <?php if ($cart_details['promocode']): ?>
                    <div class="applied-promocode">
                        <div class="promocode-success">
                            ✅ Промокод применен
                            <button type="button" onclick="checkoutManager.removePromocode()" class="promocode-remove">✕</button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="promocode__form">
                        <input type="text" id="promocode-input" class="promocode__input" placeholder="Введите промокод">
                        <button type="button" id="apply-promocode" class="promocode__btn">Применить</button>
                    </div>
                    <div id="promocode-message" class="promocode__message"></div>
                    <?php endif; ?>
                </div>

                <div class="cart-total__result">
                    <?php if ($cart_details['discount'] > 0): ?>
                    <div class="cart-total__result-row">
                        <span>Сумма:</span>
                        <span><?= number_format($cart_details['total_price'], 0, '', ' ') ?> ₽</span>
                    </div>
                    <div class="cart-total__result-row discount">
                        <span>Скидка (<?= $cart_details['discount'] ?>%):</span>
                        <span>-<?= number_format($cart_details['discount_amount'], 0, '', ' ') ?> ₽</span>
                    </div>
                    <?php endif; ?>
                    <div class="cart-total__result-text">
                        <span>Итого:</span>
                        <span id="final-amount"><?= number_format($cart_details['final_total'], 0, '', ' ') ?> ₽</span>
                    </div>
                    <div class="cart-total__result-back-wrap">
                        <a href="/cart" class="cart-total__result-back">← Вернуться в корзину</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Передаем данные корзины в JavaScript
window.initialCartData = <?= json_encode($cart_data ?? [], JSON_UNESCAPED_UNICODE) ?>;
</script>