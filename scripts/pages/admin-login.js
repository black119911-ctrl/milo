// scripts/pages/admin-login.js
document.addEventListener('DOMContentLoaded', function() {
    // Элементы DOM
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('rememberCheckbox');
    const rememberCheckboxInput = document.getElementById('rememberCheckboxInput');
    const rememberHiddenInput = document.getElementById('rememberInput');
    const usernameInput = document.getElementById('username'); // ИЗМЕНИЛ emailInput на usernameInput
    
    // Проверка существования элементов
    if (!loginForm || !togglePassword || !passwordInput) {
        console.error('Не найдены необходимые элементы формы');
        return;
    }
    
    // ========== ПЕРЕКЛЮЧЕНИЕ ВИДИМОСТИ ПАРОЛЯ ==========
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                this.setAttribute('aria-label', 'Скрыть пароль');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                this.setAttribute('aria-label', 'Показать пароль');
            }
            
            // Фокусируемся обратно на поле пароля
            passwordInput.focus();
        });
    }
    
    // ========== КАСТОМНЫЙ ЧЕКБОКС "ЗАПОМНИТЬ МЕНЯ" ==========
    if (rememberCheckbox && rememberCheckboxInput && rememberHiddenInput) {
        rememberCheckbox.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (rememberCheckboxInput.classList.contains('checked')) {
                rememberCheckboxInput.classList.remove('checked');
                rememberHiddenInput.value = '0';
            } else {
                rememberCheckboxInput.classList.add('checked');
                rememberHiddenInput.value = '1';
            }
            
            // Сохраняем в localStorage
            try {
                if (rememberHiddenInput.value === '1') {
                    localStorage.setItem('admin_remember_me', 'true');
                } else {
                    localStorage.removeItem('admin_remember_me');
                }
            } catch (e) {
                console.log('LocalStorage не доступен');
            }
        });
        
        // Восстанавливаем состояние чекбокса из localStorage
        try {
            if (localStorage.getItem('admin_remember_me') === 'true') {
                rememberCheckboxInput.classList.add('checked');
                rememberHiddenInput.value = '1';
            }
        } catch (e) {
            console.log('Не удалось восстановить состояние чекбокса');
        }
    }
    
    // ========== ВАЛИДАЦИЯ ФОРМЫ ==========
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Удаляем предыдущие ошибки
            clearErrors();
            
            // Проверяем логин и пароль
            const username = usernameInput ? usernameInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value.trim() : '';
            
            let hasError = false;
            
            // Валидация логина
            if (!username) {
                showError('Введите логин', usernameInput);
                hasError = true;
            }
            
            // Валидация пароля
            if (!password) {
                showError('Введите пароль', passwordInput);
                hasError = true;
            } else if (password.length < 6) {
                showError('Пароль должен содержать минимум 6 символов', passwordInput);
                hasError = true;
            }
            
            // Если есть ошибки - отменяем отправку
            if (hasError) {
                e.preventDefault();
                return false;
            }
            
            // Показываем состояние загрузки
            const submitButton = loginForm.querySelector('.login-button');
            if (submitButton) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Вход...';
                submitButton.disabled = true;
                loginForm.classList.add('loading');
            }
            
            // Форма будет отправлена
            return true;
        });
    }
    
    // ========== ВАЛИДАЦИЯ В РЕАЛЬНОМ ВРЕМЕНИ ==========
    if (usernameInput) {
        usernameInput.addEventListener('blur', function() {
            const username = this.value.trim();
            if (!username) {
                showError('Введите логин', this);
            } else {
                clearFieldError(this);
            }
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('blur', function() {
            const password = this.value.trim();
            if (password && password.length < 6) {
                showError('Минимум 6 символов', this);
            } else {
                clearFieldError(this);
            }
        });
    }
    
    // ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==========
    
    // Показать ошибку под полем
    function showError(message, inputElement) {
        // Удаляем старую ошибку
        const existingError = inputElement.parentElement.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Создаем элемент ошибки
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errorDiv.style.cssText = `
            color: var(--error-text);
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: fadeIn 0.3s;
        `;
        
        // Добавляем стили для иконки
        errorDiv.querySelector('i').style.cssText = `
            font-size: 12px;
            flex-shrink: 0;
        `;
        
        // Добавляем после поля ввода
        inputElement.parentElement.appendChild(errorDiv);
        
        // Подсвечиваем поле
        inputElement.style.borderColor = 'var(--error-border)';
        inputElement.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
        
        // Фокусируемся на поле с ошибкой
        inputElement.focus();
    }
    
    // Очистить ошибку поля
    function clearFieldError(inputElement) {
        const error = inputElement.parentElement.querySelector('.field-error');
        if (error) {
            error.remove();
        }
        inputElement.style.borderColor = '';
        inputElement.style.boxShadow = '';
    }
    
    // Очистить все ошибки
    function clearErrors() {
        document.querySelectorAll('.field-error').forEach(el => el.remove());
        document.querySelectorAll('.form-input').forEach(input => {
            input.style.borderColor = '';
            input.style.boxShadow = '';
        });
        
        // Удаляем общую ошибку, если она есть
        const generalError = document.getElementById('errorMessage');
        if (generalError) {
            generalError.style.display = 'none';
        }
    }
    
    // Автофокус на поле логина
    if (usernameInput) {
        setTimeout(() => {
            usernameInput.focus();
        }, 100);
    }
    
    // Обработка клавиши Enter
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.target.matches('button, a, [contenteditable]')) {
            const focused = document.activeElement;
            if (focused && focused.matches('.form-input')) {
                e.preventDefault();
                loginForm.dispatchEvent(new Event('submit'));
            }
        }
    });
    
    // Анимация появления
    setTimeout(() => {
        document.querySelector('.login-container').style.opacity = '1';
    }, 50);
});