// scripts/libs/alfa-payment-proxy.js
(function() {
    'use strict';

    function initAlfaPaymentWithProxy() {
        const paymentButtons = document.querySelectorAll('#alfa-payment-button');
        
        paymentButtons.forEach(function(button) {
            // Получаем URL прокси из data-атрибута или используем по умолчанию
            const proxyUrl = button.getAttribute('data-proxy-url') || '/proxy-payment.php';
            
            // Переопределяем метод регистрации виджета
            const originalRegister = window.AlfaPaymentButton?.register;
            
            if (window.AlfaPaymentButton && typeof originalRegister === 'function') {
                // Сохраняем оригинальную функцию
                const originalRegister = window.AlfaPaymentButton.register;
                
                // Переопределяем функцию регистрации
                window.AlfaPaymentButton.register = function(element, options) {
                    console.log('🔄 Используем прокси для Альфа-Банка');
                    
                    // Модифицируем options чтобы использовать прокси
                    const modifiedOptions = {
                        ...options,
                        // Переопределяем URL регистрации
                        registerUrl: proxyUrl,
                        // Добавляем обработчик ошибок
                        onError: function(error) {
                            console.error('Ошибка платежного виджета:', error);
                            if (typeof options.onError === 'function') {
                                options.onError(error);
                            }
                        },
                        onSuccess: function(data) {
                            console.log('✅ Платеж успешно зарегистрирован через прокси:', data);
                            if (typeof options.onSuccess === 'function') {
                                options.onSuccess(data);
                            }
                        }
                    };
                    
                    // Вызываем оригинальную функцию с модифицированными опциями
                    return originalRegister.call(this, element, modifiedOptions);
                };
            }
            
            // Инициализируем виджет как обычно
            if (window.AlfaPaymentButton) {
                window.AlfaPaymentButton.init(button);
            }
        });
    }

    // Запускаем когда DOM готов и виджет загружен
    function waitForAlfaWidget() {
        if (window.AlfaPaymentButton) {
            initAlfaPaymentWithProxy();
        } else {
            setTimeout(waitForAlfaWidget, 100);
        }
    }

    // Запускаем когда DOM полностью загружен
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', waitForAlfaWidget);
    } else {
        waitForAlfaWidget();
    }
})();