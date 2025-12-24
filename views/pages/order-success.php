<style>
.order-success {
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px;
    text-align: center;
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 20px;
}

.order-success h1 {
    color: #1a1a2e;
    margin-bottom: 15px;
}

.order-success p {
    color: #495057;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.order-number {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
    font-size: 1.2rem;
    font-weight: bold;
    color: #1a1a2e;
}

.actions {
    margin-top: 30px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    margin: 0 10px;
    background: #4361ee;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #3a56d4;
}

.btn-secondary {
    background: #6c757d;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>

<div class="order-success">
    <div class="success-icon">✅</div>
    <h1>Заказ успешно оплачен!</h1>
    <p>Спасибо за ваш заказ!</p>
    
    <div class="order-number">
        Номер вашего заказа: #<?= htmlspecialchars($order_id ?? '') ?>
    </div>
    
    <p>Мы свяжемся с вами в ближайшее время для подтверждения заказа.</p>
    <p>На вашу почту отправлено письмо с деталями заказа.</p>
    
    <div class="actions">
        <a href="/catalog" class="btn">Продолжить покупки</a>
        <a href="/" class="btn btn-secondary">На главную</a>
    </div>
</div>