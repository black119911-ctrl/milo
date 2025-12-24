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
    /* background: #ccc; */
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
            <div class="catalog__list-product" data-id="<?= $product['id'] ?>">
                <div class="catalog__list-product-image">
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                </div>
                <h3 class="catalog__list-product-name"><?= $product['name'] ?></h3>
                <div class="catalog__list-product-desc"><?= $product['subtitle'] ?></div>

                <?php if ($product['stock'] === 'true') : ?>
                <?php $addCartDisabled = '' ?>
                <div class="catalog__list-product-prices">
                    <?php if ($product['discount'] > 0) : ?>
                    <div class="catalog__list-product-price"><?= $product['discount'] ?> ₽</div>
                    <div class="catalog__list-product-price crossed-out"><?= $product['price'] ?> ₽</div>
                    <?php else : ?>
                    <div class="catalog__list-product-price"><?= $product['price'] ?> ₽</div>
                    <?php endif ?>
                </div>
                <?php elseif ($product['stock'] === 'false') : ?>
                <?php $addCartDisabled = 'disabled' ?>
                <div class="catalog__list-product-out-stock">Нет в наличии</div>
                <?php endif ?>

                <div class="catalog__list-product-btns">
                    <a href="/product/<?= $product['id'] ?>"
                        class="btn__white catalog__list-product-permalink">Подробнее</a>

                    <!-- Индивидуальные контролы для каждого товара -->
                    <div class="quantity-controls" style="display: none;">
                        <button class="quantity-btn">-</button>
                        <span class="current-quantity">0</span>
                        <button class="quantity-btn">+</button>
                    </div>

                    <button <?= $addCartDisabled ?> class="btn catalog__list-product-addcart">
                        Добавить в корзину
                    </button>
                </div>
            </div>
            <?php endforeach ?>
            <?php else: ?>
            <p>Нет товаров в наличии.</p>
            <?php endif; ?>
        </div>

        <!-- Общий счетчик корзины (должен быть один на странице) -->
        <!-- <div class="cart-info">
            🛒 Товаров в корзине: <span id="cart-count">0</span>
        </div> -->
    </div>
</section>

<script>
// Передаем данные корзины из PHP в JavaScript
window.initialCartData = <?= json_encode($cart) ?>;
console.log('Корзина из PHP сессии:', window.initialCartData);
</script>