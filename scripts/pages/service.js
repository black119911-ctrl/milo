// scripts/pages/service.js
// scripts/pages/service.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Загрузка страницы услуги...');
    initServiceFormValidation();
});

function initServiceFormValidation() {
    const form = document.getElementById('service-order-form');
    const paymentContainer = document.getElementById('alfa-payment-button');
    const alfaMessage = document.getElementById('alfa-message');
    const agreeCheckbox = document.getElementById('agree-checkbox');

    if (!form || !paymentContainer) {
        console.error('❌ Элементы формы не найдены');
        return;
    }

    console.log('✅ Все элементы формы найдены');

    // ✅ УБЕРИ СТАРУЮ ОБРАБОТКУ SUBMIT - она создает вторую кнопку
    // form.addEventListener('submit', ...) // ← ЗАКОММЕНТИРУЙ или УДАЛИ

    // Сразу скрываем виджет
    paymentContainer.classList.add('hidden');
    if (alfaMessage) {
        alfaMessage.style.display = 'block';
    }

    const requiredFields = form.querySelectorAll('input[required]');

    function checkFormValidity() {
        let allValid = true;

        // Проверяем обязательные поля
        requiredFields.forEach(field => {
            if (field.type !== 'checkbox' && !field.value.trim()) {
                allValid = false;
                field.classList.add('error');
                field.classList.remove('valid');
            } else if (field.type !== 'checkbox') {
                field.classList.remove('error');
                field.classList.add('valid');
            }
        });

        // Проверяем email
        const emailField = form.querySelector('input[type="email"]');
        if (emailField) {
            const emailValue = emailField.value.trim();
            if (!emailValue) {
                allValid = false;
                emailField.classList.add('error');
                emailField.classList.remove('valid');
            } else {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailValue)) {
                    allValid = false;
                    emailField.classList.add('error');
                    emailField.classList.remove('valid');
                } else {
                    emailField.classList.remove('error');
                    emailField.classList.add('valid');
                }
            }
        }

        // Проверяем телефон
        const phoneField = form.querySelector('input[type="tel"]');
        if (phoneField) {
            const phoneValue = phoneField.value.trim();
            if (!phoneValue) {
                allValid = false;
                phoneField.classList.add('error');
                phoneField.classList.remove('valid');
            } else {
                const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
                const cleanPhone = phoneValue.replace(/\s/g, '');
                if (!phoneRegex.test(cleanPhone) || cleanPhone.length < 10) {
                    allValid = false;
                    phoneField.classList.add('error');
                    phoneField.classList.remove('valid');
                } else {
                    phoneField.classList.remove('error');
                    phoneField.classList.add('valid');
                }
            }
        }

        // Проверяем галочку согласия
        if (!agreeCheckbox || !agreeCheckbox.checked) {
            allValid = false;
            if (agreeCheckbox) {
                agreeCheckbox.parentElement.classList.add('error');
            }
        } else if (agreeCheckbox) {
            agreeCheckbox.parentElement.classList.remove('error');
        }

        console.log('📊 Форма валидна:', allValid);

        // Управление виджетом банка
        if (allValid) {
            paymentContainer.classList.remove('hidden');
            if (alfaMessage) {
                alfaMessage.style.display = 'none';
            }
            console.log('✅ Виджет банка ПОКАЗАН');
            
            // ✅ ОБНОВЛЯЕМ ДАННЫЕ В ВИДЖЕТЕ
            updatePaymentWidgetData();
        } else {
            paymentContainer.classList.add('hidden');
            if (alfaMessage) {
                alfaMessage.style.display = 'block';
            }
            console.log('❌ Виджет банка СКРЫТ');
        }

        return allValid;
    }

    // ✅ ФУНКЦИЯ ОБНОВЛЕНИЯ ДАННЫХ В ВИДЖЕТЕ
    function updatePaymentWidgetData() {
        const nameField = form.querySelector('input[name="client_name"]');
        const phoneField = form.querySelector('input[name="client_phone"]');
        const emailField = form.querySelector('input[name="client_email"]');
        
        if (nameField && phoneField && emailField) {
            const clientName = nameField.value.trim();
            const clientPhone = phoneField.value.trim();
            const clientEmail = emailField.value.trim();
            
            // Обновляем data-атрибуты виджета
            paymentContainer.setAttribute('data-client-name', clientName);
            paymentContainer.setAttribute('data-client-phone', clientPhone);
            paymentContainer.setAttribute('data-client-email', clientEmail);
            
            console.log('📦 Данные клиента обновлены в виджете');
        }
    }

    // Слушатели событий
    const allFields = form.querySelectorAll('input');
    allFields.forEach(field => {
        field.addEventListener('input', checkFormValidity);
        field.addEventListener('change', checkFormValidity);
        field.addEventListener('blur', checkFormValidity);
    });

    if (agreeCheckbox) {
        agreeCheckbox.addEventListener('change', checkFormValidity);
    }

    console.log('✅ Валидация формы услуги запущена');
}