<?php
$order_id = $order_id ?? '';
$total_amount = $total_amount ?? 0;
$order_description = $order_description ?? '';
$order_items = $order_items ?? [];
?>

<style>
.payment-page {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.payment-header {
    text-align: center;
    margin-bottom: 30px;
}

.payment-header h1 {
    color: #1a1a2e;
    margin-bottom: 10px;
}

.order-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #4361ee;
}

.order-info h3 {
    margin-top: 0;
    color: #1a1a2e;
}

.order-items {
    margin: 15px 0;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.order-total {
    font-size: 1.2rem;
    font-weight: bold;
    color: #1a1a2e;
    text-align: right;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid #4361ee;
}

.payment-widget {
    text-align: center;
    margin: 30px 0;
}

.back-to-cart {
    text-align: center;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #5a6268;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

/* Стили для виджета банка */
#alfa-payment-button {
    margin: 20px 0;
}

/* Адаптивность */
@media (max-width: 768px) {
    .payment-page {
        padding: 15px;
    }
    
    .order-info {
        padding: 15px;
    }
}
</style>

<div class="payment-page">
    <div class="payment-header">
        <h1>Оплата заказа</h1>
        <p>Заказ №<?= htmlspecialchars($order_id) ?></p>
    </div>
    
    <div class="order-info">
        <h3>Детали заказа:</h3>
        
        <div class="order-items">
            <?php foreach($order_items as $item): ?>
            <div class="order-item">
                <span><?= htmlspecialchars($item['product']['name']) ?></span>
                <span><?= $item['quantity'] ?> × <?= number_format($item['product']['price'], 0, ',', ' ') ?> ₽</span>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="order-total">
            Итого: <?= number_format($total_amount, 0, ',', ' ') ?> ₽
        </div>
    </div>
    
    <div class="payment-widget">
        <h3>Оплата картой</h3>
        <p>Вы будете перенаправлены на безопасную страницу оплаты</p>
        
        <!-- Виджет Альфа-Банка -->
        <div id="alfa-payment-button" 
            data-token='YOUR_TOKEN_HERE' 
            data-order-number-selector='.ordernum'
            data-button-text='Оплатить <?= number_format($total_amount, 0, ',', ' ') ?> ₽' 
            data-amount='<?= $total_amount ?>' 
            data-stages="1" 
            data-version='1.0'
            data-description='<?= htmlspecialchars($order_description) ?>'>
        </div>
        
        <div class="loading" id="loading-message">
            Загрузка платежного виджета...
        </div>
    </div>
    
    <div class="back-to-cart">
        <a href="/cart" class="btn">← Вернуться в корзину</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingMessage = document.getElementById('loading-message');
    const paymentButton = document.getElementById('alfa-payment-button');
    
    // Скрываем сообщение о загрузке когда виджет загрузится
    setTimeout(() => {
        if (loadingMessage) {
            loadingMessage.style.display = 'none';
        }
    }, 3000);
    
    // Обработка успешной оплаты (если банк предоставляет callback)
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'payment_success') {
            window.location.href = '/order/success/' + <?= $order_id ?>;
        }
    });
});
</script>

<!-- Скрытый элемент с номером заказа для виджета -->
<div class="ordernum" style="display: none;"><?= htmlspecialchars($order_id) ?></div>