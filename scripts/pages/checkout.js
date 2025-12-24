// checkout.js - ПОЛНОСТЬЮ ПЕРЕПИСАННАЯ ВЕРСИЯ
class CheckoutManager {
    constructor() {
        this.isWidgetInitialized = false;
        this.isFormValid = false;
        this.messageHandler = null;
        this.init();
    }

    init() {
        console.log('🔄 CheckoutManager инициализирован');
        
        // Проверяем данные корзины
        if (!this.validateCartData()) {
            this.showEmptyCart();
            return;
        }

        // Инициализируем компоненты
        this.initCartDisplay();
        this.initPromocode();
        this.initFormValidation();
        this.setupPaymentForm();
        
        console.log('✅ Страница оформления заказа готова');
    }

    validateCartData() {
        console.log('📦 Проверка данных корзины:', window.initialCartData);
        
        if (!window.initialCartData || !window.initialCartData.items || window.initialCartData.items.length === 0) {
            console.warn('⚠️ Корзина пуста или данные не загружены');
            return false;
        }
        
        if (!window.initialCartData.total_price || window.initialCartData.total_price <= 0) {
            console.warn('⚠️ Некорректная сумма заказа');
            return false;
        }
        
        return true;
    }

    initCartDisplay() {
        console.log('🎨 Инициализация отображения корзины');
        
        try {
            this.renderCartItems(window.initialCartData.items);
            this.updateTotals(window.initialCartData.total_price);
        } catch (error) {
            console.error('❌ Ошибка при отображении корзины:', error);
        }
    }

    renderCartItems(items) {
        const productsContainer = document.querySelector('.checkout-products');
        if (!productsContainer) return;

        productsContainer.innerHTML = items.map(item => `
            <div class="checkout-product-item">
                <img src="${item.image}" alt="${item.name}" width="60">
                <div class="product-info">
                    <h4>${item.name}</h4>
                    <div class="product-price">${this.formatPrice(item.price)} × ${item.quantity}</div>
                </div>
                <div class="product-total">${this.formatPrice(item.total)}</div>
            </div>
        `).join('');
    }

    updateTotals(totalPrice) {
        const finalAmountElement = document.getElementById('final-amount');
        if (finalAmountElement) {
            finalAmountElement.textContent = this.formatPrice(totalPrice);
        }
    }

    formatPrice(price) {
        return new Intl.NumberFormat('ru-RU').format(price) + ' ₽';
    }

    initPromocode() {
        const applyPromocodeBtn = document.getElementById('apply-promocode');
        if (!applyPromocodeBtn) return;

        applyPromocodeBtn.addEventListener('click', () => {
            this.handlePromocodeApply();
        });
    }

    async handlePromocodeApply() {
        const promocodeInput = document.getElementById('promocode-input');
        const applyPromocodeBtn = document.getElementById('apply-promocode');
        const promocodeMessage = document.getElementById('promocode-message');

        const code = promocodeInput.value.trim();
        if (!code) {
            this.showPromocodeMessage('Введите промокод', 'error');
            return;
        }

        applyPromocodeBtn.textContent = 'Проверка...';
        applyPromocodeBtn.disabled = true;

        try {
            const response = await fetch('/checkout/apply-promocode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ promocode: code })
            });

            const data = await response.json();

            if (data.success) {
                this.updateUIAfterPromoApplied(data, code);
                this.showPromocodeMessage(data.message || 'Промокод успешно применен!', 'success');
            } else {
                this.showPromocodeMessage(data.message || 'Неверный промокод', 'error');
            }
        } catch (error) {
            console.error('❌ Ошибка при применении промокода:', error);
            this.showPromocodeMessage('Ошибка при применении промокода', 'error');
        } finally {
            applyPromocodeBtn.textContent = 'Применить';
            applyPromocodeBtn.disabled = false;
        }
    }

    showPromocodeMessage(message, type) {
        const promocodeMessage = document.getElementById('promocode-message');
        if (promocodeMessage) {
            promocodeMessage.textContent = message;
            promocodeMessage.className = `promocode-message ${type}`;
            promocodeMessage.style.color = type === 'success' ? '#27ae60' : '#e74c3c';
        }
    }

    updateUIAfterPromoApplied(data, promocode) {
        const promocodeContainer = document.querySelector('.promocode__form');
        if (!promocodeContainer) return;

        promocodeContainer.innerHTML = `
            <div class="applied-promocode">
                <div class="promocode-success">
                    ✅ Промокод "${promocode}" применен
                    <button type="button" onclick="checkoutManager.removePromocode()" class="promocode-remove">✕</button>
                </div>
                <div class="discount-info">
                    Скидка: ${data.discount}%
                </div>
            </div>
        `;

        if (data.cartData) {
            this.updateCartTotals(data.cartData);
            this.updatePaymentFormData(data.cartData);
        }
    }

    async removePromocode() {
        try {
            const response = await fetch('/checkout/remove-promocode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (data.success) {
                this.updateUIAfterPromoRemoved(data.cartData);
            }
        } catch (error) {
            console.error('❌ Ошибка при удалении промокода:', error);
        }
    }

    updateUIAfterPromoRemoved(cartData) {
        const promocodeContainer = document.querySelector('.promocode');
        if (!promocodeContainer) return;

        promocodeContainer.innerHTML = `
            <div class="promocode__form">
                <input type="text" id="promocode-input" class="promocode__input" placeholder="Введите промокод">
                <button type="button" id="apply-promocode" class="promocode__btn">Применить</button>
            </div>
            <div id="promocode-message" class="promocode__message"></div>
        `;

        if (cartData) {
            this.updateCartTotals(cartData);
            this.updatePaymentFormData(cartData);
        }

        this.initPromocode();
    }

    updateCartTotals(cartData) {
        const finalAmount = document.getElementById('final-amount');
        if (finalAmount && cartData.final_total !== undefined) {
            finalAmount.textContent = this.formatPrice(cartData.final_total);
        }

        const cartTotalResult = document.querySelector('.cart-total__result');
        if (cartTotalResult && cartData.discount > 0) {
            cartTotalResult.innerHTML = `
                <div class="cart-total__result-row">
                    <span>Сумма:</span>
                    <span>${this.formatPrice(cartData.total_price)}</span>
                </div>
                <div class="cart-total__result-row discount">
                    <span>Скидка (${cartData.discount}%):</span>
                    <span>-${this.formatPrice(cartData.discount_amount)}</span>
                </div>
                <div class="cart-total__result-text">
                    Итого: <span id="final-amount">${this.formatPrice(cartData.final_total)}</span>
                </div>
                <div class="cart-total__result-back-wrap">
                    <a href="/cart" class="cart-total__result-back">← Вернуться в корзину</a>
                </div>
            `;
        }
    }

    initFormValidation() {
        console.log('🔄 Инициализация валидации формы...');

        this.form = document.querySelector('.order__form');
        this.paymentContainer = document.getElementById('alfa-payment-button');
        this.alfaMessage = document.getElementById('alfa-message');
        this.agreementCheckbox = document.getElementById('agreement-checkbox');
        this.agreementError = document.getElementById('agreement-error');

        if (!this.form || !this.paymentContainer) {
            console.error('❌ Форма или платежный контейнер не найдены');
            return;
        }

        // Скрываем платежный блок изначально
        this.paymentContainer.classList.add('hidden');
        if (this.alfaMessage) {
            this.alfaMessage.style.display = 'block';
        }

        // Назначаем обработчики
        this.setupFormEventListeners();
        
        // Первоначальная проверка
        setTimeout(() => this.checkFormValidity(), 100);
    }

    setupFormEventListeners() {
        // Обработчик для галочки согласия
        if (this.agreementCheckbox) {
            this.agreementCheckbox.addEventListener('change', () => {
                const label = this.agreementCheckbox.closest('.checkbox-label');
                if (this.agreementCheckbox.checked) {
                    label.classList.add('checked');
                } else {
                    label.classList.remove('checked');
                }
                this.checkFormValidity();
            });
        }

        // Обработчики для всех полей формы
        const allFields = this.form.querySelectorAll('input, textarea');
        allFields.forEach(field => {
            field.addEventListener('input', () => this.checkFormValidity());
            field.addEventListener('change', () => this.checkFormValidity());
            field.addEventListener('blur', () => this.validateField(field));
        });

        // Специальная валидация для email и телефона
        const emailField = this.form.querySelector('input[type="email"]');
        const phoneField = this.form.querySelector('input[type="tel"]');

        if (emailField) {
            emailField.addEventListener('blur', () => this.validateEmail(emailField));
        }
        if (phoneField) {
            phoneField.addEventListener('blur', () => this.validatePhone(phoneField));
        }
    }

    checkFormValidity() {
        const wasValid = this.isFormValid;
        this.isFormValid = this.validateForm();
        
        // Если статус формы изменился
        if (wasValid !== this.isFormValid) {
            this.handleFormValidityChange();
        }
        
        return this.isFormValid;
    }

    validateForm() {
        const requiredFields = this.form.querySelectorAll('input[required]');
        
        // Проверяем обязательные поля
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                return false;
            }
        }

        // Проверяем email
        const emailField = this.form.querySelector('input[type="email"]');
        if (emailField && emailField.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value.trim())) {
                return false;
            }
        }

        // Проверяем телефон
        const phoneField = this.form.querySelector('input[type="tel"]');
        if (phoneField && phoneField.value.trim()) {
            const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
            const cleanPhone = phoneField.value.trim().replace(/\s/g, '');
            if (!phoneRegex.test(cleanPhone) || cleanPhone.length < 10) {
                return false;
            }
        }

        // Проверяем галочку согласия
        if (!this.agreementCheckbox || !this.agreementCheckbox.checked) {
            if (this.agreementError) {
                this.agreementError.classList.add('show');
            }
            return false;
        } else {
            if (this.agreementError) {
                this.agreementError.classList.remove('show');
            }
        }

        return true;
    }

    handleFormValidityChange() {
        console.log('📊 Статус формы изменился:', this.isFormValid ? 'валидна' : 'невалидна');

        if (this.isFormValid) {
            // Показываем платежный блок
            this.paymentContainer.classList.remove('hidden');
            if (this.alfaMessage) {
                this.alfaMessage.style.display = 'none';
            }
            
            // Инициализируем виджет если еще не инициализирован
            if (!this.isWidgetInitialized) {
                this.initializePaymentWidget();
            }
        } else {
            // Скрываем платежный блок
            this.paymentContainer.classList.add('hidden');
            if (this.alfaMessage) {
                this.alfaMessage.style.display = 'block';
            }
        }
    }

    validateField(field) {
        if (field.value.trim()) {
            field.style.borderColor = '#27ae60';
            this.clearFieldError(field);
        } else {
            field.style.borderColor = '#e74c3c';
        }
    }

    validateEmail(emailField) {
        const value = emailField.value.trim();
        if (value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                emailField.style.borderColor = '#e74c3c';
                this.showFieldError(emailField, 'Введите корректный email');
            } else {
                emailField.style.borderColor = '#27ae60';
                this.clearFieldError(emailField);
            }
        }
    }

    validatePhone(phoneField) {
        const value = phoneField.value.trim();
        if (value) {
            const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
            const cleanPhone = value.replace(/\s/g, '');
            if (!phoneRegex.test(cleanPhone) || cleanPhone.length < 10) {
                phoneField.style.borderColor = '#e74c3c';
                this.showFieldError(phoneField, 'Введите корректный номер телефона');
            } else {
                phoneField.style.borderColor = '#27ae60';
                this.clearFieldError(phoneField);
            }
        }
    }

    showFieldError(field, message) {
        this.clearFieldError(field);

        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.style.cssText = `
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            padding: 3px 0;
        `;
        errorElement.textContent = message;

        field.parentNode.appendChild(errorElement);
    }

    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }

    setupPaymentForm() {
        console.log('🎯 Настройка платежной формы...');
        
        // Ждем загрузки скрипта Альфа-Банка
        this.waitForAlfaScript();
    }

    waitForAlfaScript(attempt = 0) {
        const maxAttempts = 20; // 10 секунд максимум
        
        if (window.AlfaPaymentButton) {
            console.log('✅ Скрипт Альфа-Банка загружен');
            return;
        }
        
        if (attempt < maxAttempts) {
            setTimeout(() => {
                console.log(`⏳ Ожидание скрипта Альфа-Банка... (${attempt + 1}/${maxAttempts})`);
                this.waitForAlfaScript(attempt + 1);
            }, 500);
        } else {
            console.error('❌ Скрипт Альфа-Банка не загрузился');
        }
    }

    initializePaymentWidget() {
        console.log('🎯 Инициализация платежного виджета...');
        
        if (!window.AlfaPaymentButton || !window.AlfaPaymentButton.init) {
            console.error('❌ AlfaPaymentButton не доступен');
            return;
        }

        try {
            // Получаем данные клиента
            const clientData = this.getClientData();
            console.log('📦 Данные клиента для виджета:', clientData);

            // Настраиваем обработчик сообщений
            this.setupMessageHandler();

            // Инициализируем виджет
            window.AlfaPaymentButton.init(this.paymentContainer, {
                clientName: clientData.clientName,
                clientPhone: clientData.clientPhone,
                clientEmail: clientData.clientEmail,
                onSuccess: (data) => {
                    console.log('✅ Платеж успешно завершен:', data);
                    this.handleSuccessfulPayment(data);
                },
                onError: (error) => {
                    console.error('❌ Ошибка платежа:', error);
                    this.showNotification('Ошибка при обработке платежа', 'error');
                },
                onClose: () => {
                    console.log('🔒 Платежный виджет закрыт');
                }
            });

            this.isWidgetInitialized = true;
            console.log('✅ Платежный виджет инициализирован');

        } catch (error) {
            console.error('💥 Ошибка при инициализации виджета:', error);
        }
    }

    getClientData() {
        return {
            clientName: this.form.querySelector('input[name="name"]')?.value.trim() || '',
            clientPhone: this.form.querySelector('input[name="phone"]')?.value.trim() || '',
            clientEmail: this.form.querySelector('input[name="email"]')?.value.trim() || ''
        };
    }

    setupMessageHandler() {
        // Удаляем старый обработчик если есть
        if (this.messageHandler) {
            window.removeEventListener('message', this.messageHandler);
        }

        this.messageHandler = (event) => {
            // Фильтруем сообщения только от Альфа-Банка
            const allowedOrigins = [
                'https://testpay.alfabank.ru',
                'https://pay.alfabank.ru'
            ];
            
            if (!allowedOrigins.includes(event.origin)) {
                return;
            }

            // Обрабатываем только важные сообщения
            if (event.data && typeof event.data === 'object') {
                switch (event.data.type) {
                    case 'payment_success':
                        console.log('✅ Получено подтверждение платежа');
                        this.handleSuccessfulPayment(event.data);
                        break;
                    case 'payment_error':
                        console.error('❌ Ошибка платежа:', event.data);
                        break;
                    default:
                        // Игнорируем служебные сообщения
                        break;
                }
            }
        };

        window.addEventListener('message', this.messageHandler);
    }

    updatePaymentFormData(cartData) {
        if (!this.paymentContainer) return;

        // Обновляем сумму в виджете
        this.paymentContainer.setAttribute('data-amount', cartData.final_total);

        // Обновляем описание
        let description = '';
        if (cartData.items && cartData.items.length > 0) {
            cartData.items.forEach((item, index) => {
                if (index > 0) description += ', ';

                const productName = item.alt_name || item.name;
                const cleanName = productName
                    .replace(/<[^>]*>/g, '')
                    .replace(/&quot;|&#34;|&#39;|"/g, '')
                    .replace(/&[^;]+;/g, '')
                    .trim();

                description += cleanName + ' - ' + item.quantity + ' шт.';
            });
        }

        if (cartData.discount > 0) {
            description += ` | Скидка: ${cartData.discount}%`;
        }

        this.paymentContainer.setAttribute('data-description', description);

        // Обновляем виджет если он уже инициализирован
        if (this.isWidgetInitialized && window.AlfaPaymentButton && window.AlfaPaymentButton.refresh) {
            window.AlfaPaymentButton.refresh();
        }
    }

    handleSuccessfulPayment(paymentData = null) {
        console.log('✅ Платеж успешно завершен, очищаем корзину...');

        // Очищаем корзину на клиенте
        this.clearClientCart();

        // Отправляем запрос на сервер для очистки сессии
        this.clearServerCart()
            .then(() => {
                // Перенаправляем на страницу успеха
                setTimeout(() => {
                    window.location.href = '/order/success/' + (paymentData?.orderId || this.generateOrderId());
                }, 2000);
            })
            .catch(error => {
                console.error('Ошибка при очистке корзины:', error);
                window.location.href = '/order/success';
            });
    }

    clearClientCart() {
        localStorage.removeItem('cart');
        localStorage.removeItem('promocode');

        const cartCounters = document.querySelectorAll('.basket__product-qty, .cart-count');
        cartCounters.forEach(counter => {
            counter.textContent = '0';
            counter.style.display = 'none';
        });
    }

    async clearServerCart() {
        const response = await fetch('/cart/clear-after-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ clear: true })
        });
        return response.json();
    }

    generateOrderId() {
        return 'ORDER_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    showEmptyCart() {
        const cartContent = document.querySelector('.cart-page__content') || document.querySelector('.cart');
        if (cartContent) {
            cartContent.innerHTML = `
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h2>Корзина пуста</h2>
                    <p>Добавьте товары из каталога</p>
                    <a href="/catalog" class="btn btn--primary">Перейти в каталог</a>
                </div>
            `;
        }
    }

    showNotification(message, type = 'success') {
        // Простая реализация уведомлений
        const notification = document.createElement('div');
        notification.className = `checkout-notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'error' ? '#e74c3c' : '#27ae60'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Очистка ресурсов
    cleanup() {
        if (this.messageHandler) {
            window.removeEventListener('message', this.messageHandler);
            this.messageHandler = null;
        }
        console.log('🧹 Ресурсы CheckoutManager очищены');
    }
}

// Глобальная инициализация
document.addEventListener('DOMContentLoaded', function() {
    window.checkoutManager = new CheckoutManager();
});

// Очистка при уходе со страницы
window.addEventListener('beforeunload', function() {
    if (window.checkoutManager) {
        window.checkoutManager.cleanup();
    }
});