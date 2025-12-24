<?php
// basket.php
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!-- Плавающая кнопка корзины -->
<a href="/cart" class="basket" id="global-basket">
    <div class="basket__image">
        <img src="/images/svg/basket-green.svg" alt="Корзина">
    </div>
    <div class="basket__product-qty" id="global-cart-count"><?= $cartCount ?></div>
</a>