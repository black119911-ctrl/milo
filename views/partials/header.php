<?php
// header.php
session_start();
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milovan4ik.ru - магазин натуральной косметики</title>
    <meta name="description" content="Интернет-магазин качественной косметики">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/styles/common.css">
    <link rel="icon" href="https://milovan4ik.ru/images/favicon.png" type="image/png">
    <script id="alfa-payment-script" type="text/javascript" src="https://testpay.alfabank.ru/assets/alfa-payment.js">
    </script>
    
    <!-- Стили для корзины -->
    <link rel="stylesheet" href="/styles/components/cart.css">
    
    <?php if (isset($resources) && is_array($resources)) : ?>
    <?php foreach ($resources['styles'] as $style) : ?>
    <link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach ?>
    <?php endif ?>
    
    <script defer src="/scripts/components/header.js"></script>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <button class="header__inner-menu" id="menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    
    <nav role="navigation" class="menu hidden" id="menu">
        <button class="menu__close-btn" id="menu-close-btn">&times;</button>
        <ul>
        <?php foreach (get_menu_items() as $item): ?>
            <?php 
            if (!empty($item['target'])) {
                $target = 'target="' . $item['target'] . '"';
            } else {
                $target = '';
            }
            ?>
            <li><a href="<?= $item['href'] ?>" class="<?= is_active_class($item['href']) ?>" <?= $target ?> ><?= $item['title'] ?></a></li>
        <?php endforeach; ?>
        </ul>
    </nav>
    
    <?php 
        // Подключаем basket.php который содержит плавающую корзину
        include_once 'basket.php'
    ?>
    
    <!-- Передаем данные корзины в JavaScript -->
    <script>
        window.globalCartData = {
            count: <?= $cartCount ?>,
            items: <?= json_encode($_SESSION['cart'] ?? []) ?>
        };
    </script>
    
    <!-- Глобальный менеджер корзины -->
    <script src="/scripts/components/global-cart.js"></script>