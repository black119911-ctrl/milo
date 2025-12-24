<?php
// views/admin/services.php
$service = $service ?? null;
?>

<style>
/* Стили для страницы услуг */
.services-page {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Заголовок страницы */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding-bottom: 25px;
    border-bottom: 2px solid var(--border-color);
}

.page-header__title {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary-green);
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.page-header__subtitle {
    color: var(--text-light);
    font-size: 16px;
}

/* Контейнер формы */
.service-form-container {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 40px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    max-width: 800px;
    margin: 0 auto;
}

.section-title {
    color: var(--primary-green);
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(16, 94, 52, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title::before {
    content: "💼";
    font-size: 28px;
}

/* Форма */
.form-group {
    margin-bottom: 30px;
}

.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    border-bottom: 1px solid var(--border-color);
}

.form-row--column {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 16px;
    min-width: 200px;
}

.form-control {
    flex: 1;
    width: 100%;
}

.form-control input[type="text"],
.form-control input[type="number"] {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s;
    background: var(--light-bg);
    color: var(--text-dark);
}

.form-control input:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(16, 94, 52, 0.1);
}

/* Кастомный чекбокс */
.checkbox-toggle {
    display: flex !important;
    align-items: center;
    gap: 15px;
    cursor: pointer;
    user-select: none;
}

.checkbox-toggle__input {
    display: none;
}

.checkbox-toggle__switch {
    display: flex;
    width: 60px;
    height: 32px;
    background: #e0e0e0;
    border-radius: 20px;
    position: relative;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.checkbox-toggle__switch::before {
    content: '';
    position: absolute;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: white;
    top: 2px;
    left: 2px;
    transition: transform 0.3s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.checkbox-toggle__input:checked + .checkbox-toggle__switch {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
    border-color: rgba(16, 94, 52, 0.2);
}

.checkbox-toggle__input:checked + .checkbox-toggle__switch::before {
    transform: translateX(28px);
    background: white;
}

.checkbox-toggle__label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 16px;
}

.checkbox-toggle__status {
    font-size: 14px;
    color: var(--text-light);
    margin-left: 8px;
}

/* Кнопка сохранения */
.form-submit {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--border-color);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 32px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    min-height: 56px;
    width: 100%;
}

.btn--primary {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
    color: white;
    border: 2px solid transparent;
}

.btn--primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, var(--primary-dark) 0%, #0a3c1f 100%);
}

.btn--secondary {
    background: var(--light-bg);
    color: var(--text-dark);
    border: 2px solid var(--border-color);
    margin-top: 15px;
}

.btn--secondary:hover {
    border-color: var(--primary-green);
    color: var(--primary-green);
}

/* Информационные подсказки */
.form-hint {
    display: block;
    margin-top: 8px;
    color: var(--text-light);
    font-size: 14px;
    line-height: 1.5;
    padding-left: 8px;
    border-left: 3px solid rgba(16, 94, 52, 0.3);
}

/* Адаптивность */
@media (max-width: 768px) {
    .services-page {
        padding: 15px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }
    
    .service-form-container {
        padding: 25px;
    }
    
    .form-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .form-label {
        min-width: auto;
    }
    
    .form-control {
        width: 100%;
    }
    
    .btn {
        padding: 14px 24px;
    }
}

/* Статус услуги */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin-left: 15px;
}

.status-badge--active {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status-badge--inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* Тёмная тема */
@media (prefers-color-scheme: dark) {
    .service-form-container {
        background: #1e293b;
        border-color: #334155;
    }
    
    .section-title {
        color: #10b981;
        border-bottom-color: rgba(16, 185, 129, 0.2);
    }
    
    .form-control input {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }
    
    .checkbox-toggle__switch {
        background: #334155;
    }
    
    .btn--secondary {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }
}
</style>

<div class="services-page">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div>
            <h1 class="page-header__title">💼 Управление услугой</h1>
            <p class="page-header__subtitle">
                <?php if ($service): ?>
                Редактирование услуги "<?= htmlspecialchars($service['name']) ?>"
                <?php else: ?>
                Создание новой услуги
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Форма услуги -->
    <div class="service-form-container">
        <h2 class="section-title">Услуга</h2>
        
        <form id="service-form" method="POST" action="/admin/services/save">
            <div class="form-group">
                <!-- Название услуги -->
                <div class="form-row form-row--column">
                    <label class="form-label">Название услуги *</label>
                    <div class="form-control">
                        <input type="text" name="name" 
                               value="<?= htmlspecialchars($service['name'] ?? '') ?>"
                               placeholder="Например: Уход за кожей - Консультация" 
                               required>
                        <!-- <span class="form-hint">
                            Укажите понятное название услуги, которое будет отображаться клиентам
                        </span> -->
                    </div>
                </div>

                <!-- Статус наличия -->
                <div class="form-row">
                    <label class="form-label">Доступность услуги</label>
                    <div class="form-control">
                        <label class="checkbox-toggle">
                            <input type="checkbox" 
                                   name="is_stock" 
                                   value="1"
                                   class="checkbox-toggle__input"
                                   <?= (($service['is_stock'] ?? '1') == '1') ? 'checked' : '' ?>>
                            <span class="checkbox-toggle__switch"></span>
                            <span class="checkbox-toggle__label">
                                Услуга доступна
                                <span class="checkbox-toggle__status">
                                    <?= (($service['is_stock'] ?? '1') == '1') ? '(Активна)' : '(Недоступна)' ?>
                                </span>
                            </span>
                        </label>
                        <!-- <span class="form-hint">
                            При отключении услуга не будет отображаться на сайте для заказа
                        </span> -->
                    </div>
                </div>

                <!-- Цена услуги -->
                <div class="form-row">
                    <label class="form-label">Стоимость услуги *</label>
                    <div class="form-control">
                        <input type="number" 
                               name="price" 
                               value="<?= $service['price'] ?? '' ?>" 
                               placeholder="1990" 
                               min="0" 
                               required
                               oninput="formatPrice(this)">
                        <!-- <span class="form-hint">
                            Укажите стоимость услуги в рублях. Клиенты увидят эту цену при заказе
                        </span> -->
                    </div>
                </div>
            </div>

            <!-- Кнопки формы -->
            <div class="form-submit">
                <button type="submit" class="btn btn--primary">
                    <?php if ($service): ?>
                    <span style="font-size: 20px;">💾</span> Обновить услугу
                    <?php else: ?>
                    <span style="font-size: 20px;">➕</span> Создать услугу
                    <?php endif; ?>
                </button>
                
                <a href="/admin/" class="btn btn--secondary">
                    <span style="font-size: 20px;">←</span> Назад в панель управления
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Обработка формы
document.getElementById('service-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span style="font-size: 20px;">⏳</span> Сохранение...';
    submitBtn.disabled = true;

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message || 'Услуга успешно сохранена', 'success');
            setTimeout(() => {
                window.location.href = '/admin/';
            }, 1500);
        } else {
            showNotification('Ошибка: ' + (result.message || 'неизвестная ошибка'), 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Ошибка при сохранении услуги', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        console.error('Form submit error:', error);
    }
});

// Форматирование цены при вводе
function formatPrice(input) {
    const value = input.value.replace(/\D/g, '');
    if (value) {
        // Можно добавить форматирование с пробелами тысяч
        // input.value = parseInt(value).toLocaleString('ru-RU');
    }
}

// Обновление статуса чекбокса
document.querySelectorAll('.checkbox-toggle__input').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const statusElement = this.closest('.checkbox-toggle').querySelector('.checkbox-toggle__status');
        if (statusElement) {
            statusElement.textContent = this.checked ? '(Активна)' : '(Недоступна)';
        }
    });
});

// Функция для отображения уведомлений
function showNotification(message, type = 'info') {
    // Используйте вашу существующую систему уведомлений
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        // Fallback уведомление
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 12px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            font-weight: 600;
            z-index: 10000;
            box-shadow: var(--shadow-lg);
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Добавляем стили для fallback уведомления
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>