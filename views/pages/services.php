<?php
// views/pages/services.php
$service = $service ?? null;
$resources = $resources ?? [];
?>

<style>
/* Стили для кнопки банка на странице услуги */
#alfa-payment-button {
    width: 100% !important;
    margin: 20px 0 !important;
}

#alfa-payment__button {
    background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
    border: none !important;
    border-radius: 8px !important;
    color: white !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    padding: 15px 30px !important;
    width: 100% !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3) !important;
}

#alfa-payment__button:hover {
    background: linear-gradient(135deg, #219653, #27ae60) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4) !important;
}

#alfa-payment__button:disabled {
    background: #bdc3c7 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Скрытие платежного блока */
#alfa-payment-button.hidden {
    display: none !important;
}

#alfa-message {
    text-align: center;
    color: #7f8c8d;
    font-size: 14px;
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

/* Стили для полей формы */
.order__form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.order__form-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.order__form-input.error {
    border-color: #e74c3c;
}

.order__form-input.valid {
    border-color: #27ae60;
}

.field-error {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 5px;
}
</style>

<?php
// views/pages/services.php
$service = $service ?? null;
$resources = $resources ?? [];
?>

<section class="section-main cart-section">
    <div class="container">
        <div class="consult">
            <?php if ($service && $service['is_stock'] == '1'): ?>

            <!-- ✅ ТОЛЬКО ФОРМА С ПОЛЯМИ И ВИДЖЕТОМ -->
            <form class="order__form" id="service-order-form" method="POST">
                <div class="consult__row">
                    <div class="consult__title">Услуга</div>
                    <div class="consult__value"><?= htmlspecialchars($service['name']) ?></div>
                </div>
                <div class="consult__row">
                    <div class="consult__title">Стоимость</div>
                    <div class="consult__value"><?= number_format($service['price'], 0, '', ' ') ?> ₽</div>
                </div>

                <div class="order__form-field">
                    <label class="order__form-label">ФИО*</label>
                    <input class="input order__form-input" name="client_name" type="text" required>
                </div>
                <div class="order__form-field">
                    <label class="order__form-label">Телефон*</label>
                    <input class="input order__form-input" name="client_phone" type="tel" required>
                </div>
                <div class="order__form-field">
                    <label class="order__form-label">Email*</label>
                    <input class="input order__form-input" name="client_email" type="email" required>
                </div>

                <!-- ✅ БЛОК С ВИДЖЕТОМ БАНКА -->
                <div class="order__form-submit">
                    <div class="ordernum hide"><?= uniqid() ?></div>

                    <div id="alfa-payment-button" class="hidden" data-token="rvvghshk064ql82vu53aah43or"
                        data-order-number-selector=".ordernum"
                        data-proxy-url="/proxy-payment.php"
                        data-button-text="Оплатить <?= number_format($service['price'], 0, '', ' ') ?> ₽"
                        data-amount="<?= $service['price'] ?>" data-stages="1" data-version="1.0"
                        data-description="Услуга: <?= htmlspecialchars($service['name']) ?>">
                    </div>

                    <div id="alfa-message">Заполните данные формы и поставьте галочку о согласии</div>
                </div>

                <small style="display:flex;margin: 10px 0">*Обязательные поля</small>

                <div class="agree-checkbox">
                    <input type="checkbox" id="agree-checkbox" name="agree" required>
                    <label for="agree-checkbox">
                        Я соглашаюсь с <a target="_blank" class="order__form-oferta-link" href="/privacy-policy">политикой
                            обработки персональных данных</a> и <a href="/assets/docs/oferta.pdf" target="_blank"
                            class="order__form-oferta-link">договором оферты</a>
                    </label>
                </div>
            </form>

            <?php else: ?>
            <div class="consult__unavailable">
                <h2>Консультация</h2>
                <p>В данный момент запись на консультации временно приостановлена.</p>
                <p>Следите за обновлениями в наших социальных сетях.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>