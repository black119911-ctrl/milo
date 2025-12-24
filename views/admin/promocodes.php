<?php
// views/admin/promocodes.php
$promocode = $promocode ?? null;
?>

<style>
/* Стили для страницы промокодов */
.promocodes-page {
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

.page-header__actions {
    display: flex;
    gap: 15px;
}

/* Контейнер формы */
.promocode-form-container {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 40px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    max-width: 600px;
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

/* .section-title::before {
    content: "🎫";
    font-size: 28px;
} */

/* Форма промокода */
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

.form-label__icon {
    color: var(--primary-green);
    margin-right: 8px;
}

.form-control {
    flex: 1;
    width: 100%;
}

.form-control input[type="text"] {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 16px;
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
    transition: all 0.3s;
    background: var(--light-bg);
    color: var(--text-dark);
    text-transform: uppercase;
}

.form-control input:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(16, 94, 52, 0.1);
    transform: translateY(-2px);
}

.form-hint {
    display: block;
    margin-top: 8px;
    color: var(--text-light);
    font-size: 14px;
    line-height: 1.5;
    padding: 10px 15px;
    background: rgba(16, 94, 52, 0.05);
    border-radius: 8px;
    border-left: 3px solid var(--primary-green);
}

/* Бейдж промокода */
.promo-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, rgba(16, 94, 52, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
    border: 2px solid rgba(16, 94, 52, 0.2);
    border-radius: 12px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: var(--primary-green);
    font-size: 18px;
    letter-spacing: 1px;
    margin: 10px 0;
}

.promo-badge__icon {
    font-size: 20px;
}

/* Информационная карточка */
.info-card {
    background: rgba(16, 94, 52, 0.05);
    border-radius: 16px;
    padding: 20px;
    margin: 25px 0;
    border: 1px solid rgba(16, 94, 52, 0.1);
}

.info-card__title {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--primary-green);
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 16px;
}

.info-card__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-card__item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: var(--text-light);
    font-size: 14px;
}

.info-card__item::before {
    content: "✓";
    color: var(--primary-green);
    font-weight: bold;
}

/* Кнопки */
.form-actions {
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

.btn--danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: 2px solid transparent;
    margin-top: 15px;
}

.btn--danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
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

/* Адаптивность */
@media (max-width: 768px) {
    .promocodes-page {
        padding: 15px;
    }

    .page-header {
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }

    .promocode-form-container {
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

/* Генератор промокода */
.promo-generator {
    margin: 20px 0;
    text-align: center;
}

.generate-btn {
    background: rgba(16, 94, 52, 0.1);
    border: 2px solid var(--primary-green);
    color: var(--primary-green);
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-family: 'Courier New', monospace;
    font-size: 14px;
}

.generate-btn:hover {
    background: var(--primary-green);
    color: white;
    transform: translateY(-2px);
}

/* Тёмная тема */
@media (prefers-color-scheme: dark) {
    .promocode-form-container {
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

    .form-hint {
        background: rgba(16, 185, 129, 0.05);
        border-left-color: #10b981;
    }

    .promo-badge {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
        color: #10b981;
    }

    .info-card {
        background: rgba(16, 185, 129, 0.05);
        border-color: rgba(16, 185, 129, 0.1);
    }

    .btn--secondary {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }
}

/* В конец стилей добавить */
.form-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.copy-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
}

.copy-btn:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
}

/* Добавить эти стили в конец <style> */

/* Компактные стили для всей страницы */
.promocodes-page {
    padding: 15px; /* Было 20px */
    max-width: 600px; /* Уменьшаем максимальную ширину */
}

.page-header {
    margin-bottom: 25px; /* Было 40px */
    padding-bottom: 15px; /* Было 25px */
}

.promocode-form-container {
    padding: 25px; /* Было 40px */
}

.form-row {
    padding: 15px 0; /* Было 20px 0 */
}

.section-title {
    margin-bottom: 20px; /* Было 30px */
    padding-bottom: 10px; /* Было 15px */
}

.form-hint {
    margin-top: 6px; /* Было 8px */
    padding: 8px 12px; /* Было 10px 15px */
    font-size: 13px; /* Было 14px */
}

/* Инпут с кнопкой копирования */
.input-with-button {
    display: flex;
    gap: 10px;
    align-items: center;
}

.input-with-button input {
    flex: 1;
}

.copy-btn-input {
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-dark) 0%, #0a3c1f 100%);
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.copy-btn-input:hover {
    background: linear-gradient(135deg, #0a3c1f 0%, #083018 100%);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.copy-btn-input:active {
    transform: translateY(0);
}

/* Уменьшаем генератор */
.promo-generator {
    margin: 12px 0; /* Было 20px 0 */
}

.generate-btn {
    padding: 10px 20px; /* Было 12px 24px */
    font-size: 13px; /* Было 14px */
}

/* Кнопки формы компактнее */
.form-actions {
    margin-top: 25px; /* Было 40px */
    padding-top: 20px; /* Было 30px */
}

.btn {
    padding: 14px 24px; /* Было 16px 32px */
    min-height: 50px; /* Было 56px */
    font-size: 15px; /* Было 16px */
}

/* Уменьшаем подсказку внизу формы */
.info-card {
    margin: 15px 0; /* Было 25px 0 */
    padding: 15px; /* Было 20px */
}

/* Уменьшаем отступы на мобильных */
@media (max-width: 768px) {
    .promocodes-page {
        padding: 10px; /* Было 15px */
    }
    
    .promocode-form-container {
        padding: 20px; /* Было 25px */
    }
    
    .form-control input[type="text"] {
        padding: 14px 16px; /* Было 16px 20px */
    }
    
    .copy-btn-input {
        width: 46px;
        height: 46px;
    }
}
</style>

<div class="promocodes-page">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div>
            <h1 class="page-header__title">🎫 Управление промокодами</h1>
            <p class="page-header__subtitle">
                <?php if ($promocode): ?>
                Редактирование промокода
                <?php else: ?>
                Создание нового промокода
                <?php endif; ?>
            </p>
        </div>
        <div class="page-header__actions">
            <a href="/admin/" class="btn btn--secondary">
                ← Назад
            </a>
        </div>
    </div>

    <!-- Форма промокода -->
    <div class="promocode-form-container">
        <h2 class="section-title">Промокоды и скидки</h2>

        <form id="promocode-form" method="POST" action="/admin/promocodes/save">
            <div class="form-group">
               
                <!-- Поле для ввода промокода с кнопкой копирования -->
                <div class="form-row form-row--column">
                    <label class="form-label">
                        <span class="form-label__icon">🔑</span> Код промокода *
                    </label>
                    <div class="form-control">
                        <div class="input-with-button">
                            <input type="text" name="promocode"
                                value="<?= htmlspecialchars($promocode['promocode'] ?? '') ?>" placeholder="SUMMER2024"
                                autocomplete="off" required pattern="[A-Za-z0-9\-_]+"
                                title="Только латинские буквы, цифры, дефисы и подчёркивания" maxlength="50"
                                id="promocode-input">

                            <?php if ($promocode): ?>
                            <button type="button" onclick="copyPromocode()" class="copy-btn-input"
                                title="Копировать промокод">
                                📋
                            </button>
                            <?php endif; ?>
                        </div>

                        <?php if (!$promocode): ?>
                        <div class="promo-generator">
                            <button type="button" onclick="generatePromocode()" class="generate-btn">
                                🔄 Сгенерировать код
                            </button>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Кнопки формы -->
                <div class="form-actions">
                    <button type="submit" class="btn btn--primary">
                        <?php if ($promocode): ?>
                        <span style="font-size: 20px;">💾</span> Обновить промокод
                        <?php else: ?>
                        <span style="font-size: 20px;">➕</span> Добавить промокод
                        <?php endif; ?>
                    </button>

                    <?php if ($promocode): ?>
                    <button type="button" onclick="deletePromocode()" class="btn btn--danger">
                        <span style="font-size: 20px;">🗑️</span> Удалить промокод
                    </button>
                    <?php endif; ?>
                </div>
            </div>
           
        </form>
    </div>
</div>

<script>
// Генерация промокода
function generatePromocode() {
    const prefixes = ['SUMMER', 'WINTER', 'SPRING', 'AUTUMN', 'SALE', 'DISCOUNT', 'VIP', 'CLUB', 'BONUS', 'GIFT'];
    const suffix = Math.floor(1000 + Math.random() * 9000);
    const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
    const promoCode = `${prefix}${suffix}`;

    const input = document.querySelector('input[name="promocode"]');
    if (input) {
        input.value = promoCode;

        // Показать уведомление
        showNotification(`Сгенерирован промокод: ${promoCode}`, 'success');

        // Плавно скроллить к полю
        input.focus();
        input.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

// Обработка формы промокода
document.getElementById('promocode-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span style="font-size: 20px;">⏳</span> Сохранение...';
    submitBtn.disabled = true;

    // Автоматическое приведение к верхнему регистру
    const promoInput = this.querySelector('input[name="promocode"]');
    if (promoInput) {
        promoInput.value = promoInput.value.toUpperCase().trim();
    }

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message || 'Промокод успешно сохранён', 'success');
            setTimeout(() => {
                window.location.href = '/admin/promocodes';
            }, 1500);
        } else {
            showNotification('Ошибка: ' + (result.message || 'неизвестная ошибка'), 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Ошибка при сохранении промокода', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        console.error('Form submit error:', error);
    }
});

// Удаление промокода
async function deletePromocode() {
    if (!confirm(
            'Вы уверены, что хотите удалить этот промокод?\n\nПосле удаления он станет недоступен для использования.'
        )) return;

    const deleteBtn = document.querySelector('.btn--danger');
    const originalText = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<span style="font-size: 20px;">⏳</span> Удаление...';
    deleteBtn.disabled = true;

    try {
        const response = await fetch('/admin/promocodes/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $promocode['id'] ?? 'null' ?>
            })
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Промокод успешно удалён', 'success');
            setTimeout(() => {
                window.location.href = '/admin/promocodes';
            }, 1500);
        } else {
            showNotification('Ошибка: ' + (result.message || 'неизвестная ошибка'), 'error');
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Ошибка при удалении промокода', 'error');
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
        console.error('Delete error:', error);
    }
}

// Валидация промокода
document.querySelector('input[name="promocode"]')?.addEventListener('input', function(e) {
    const value = e.target.value;
    // Удаляем недопустимые символы
    const cleaned = value.replace(/[^A-Za-z0-9\-_]/g, '');
    if (cleaned !== value) {
        e.target.value = cleaned;
        showNotification('Используйте только латинские буквы, цифры, дефисы и подчёркивания', 'warning');
    }

    // Автоматически делаем верхний регистр
    if (value !== value.toUpperCase()) {
        e.target.value = value.toUpperCase();
    }
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
            background: ${type === 'success' ? '#10b981' : 
                         type === 'error' ? '#ef4444' : 
                         type === 'warning' ? '#f59e0b' : '#3b82f6'};
            color: white;
            font-weight: 600;
            z-index: 10000;
            box-shadow: var(--shadow-lg);
            animation: slideIn 0.3s ease;
            max-width: 400px;
            word-break: break-word;
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
if (!document.querySelector('style[data-notifications]')) {
    const style = document.createElement('style');
    style.setAttribute('data-notifications', 'true');
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
}


// Функция копирования промокода в буфер обмена
function copyPromocode() {
    const promoInput = document.getElementById('promocode-input');
    const promoCode = promoInput ? promoInput.value : "<?= htmlspecialchars($promocode['promocode'] ?? '') ?>";
    
    if (!promoCode || promoCode.trim() === '') {
        showNotification('Промокод не найден', 'error');
        return;
    }
    
    navigator.clipboard.writeText(promoCode).then(() => {
        showNotification(`Промокод "${promoCode}" скопирован!`, 'success');
        
        // Анимация кнопки
        const copyBtn = document.querySelector('.copy-btn-input');
        if (copyBtn) {
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '✅';
            copyBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
                copyBtn.style.background = 'linear-gradient(135deg, var(--primary-dark) 0%, #0a3c1f 100%)';
            }, 1500);
        }
    }).catch(err => {
        console.error('Ошибка копирования:', err);
        showNotification('Не удалось скопировать промокод', 'error');
    });
}
</script>