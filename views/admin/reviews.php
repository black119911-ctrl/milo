<?php
// views/admin/reviews.php
$reviews = $reviews ?? [];
?>

<style>
/* Стили для страницы отзывов */
.reviews-page {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Заголовок страницы */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
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

.page-header__stats {
    background: rgba(16, 94, 52, 0.1);
    padding: 12px 20px;
    border-radius: 12px;
    color: var(--primary-green);
    font-weight: 600;
    font-size: 16px;
    border: 1px solid rgba(16, 94, 52, 0.2);
}

/* Форма загрузки */
.upload-container {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 30px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    margin-bottom: 40px;
}

.upload-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(16, 94, 52, 0.1);
}

.upload-header h2 {
    color: var(--primary-green);
    font-size: 24px;
    font-weight: 700;
}

.upload-icon {
    font-size: 28px;
}

/* Форма загрузки */
.upload-form {
    margin-top: 20px;
}

.upload-area {
    border: 3px dashed var(--border-color);
    border-radius: 16px;
    padding: 40px 20px;
    text-align: center;
    background: var(--light-bg);
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
    position: relative;
}

.upload-area:hover {
    border-color: var(--primary-green);
    background: rgba(16, 94, 52, 0.05);
}

.upload-area input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-area__content {
    pointer-events: none;
}

.upload-area__icon {
    font-size: 48px;
    color: var(--primary-green);
    margin-bottom: 15px;
    opacity: 0.7;
}

.upload-area__title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.upload-area__subtitle {
    color: var(--text-light);
    font-size: 14px;
    margin-bottom: 15px;
}

.upload-area__button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
}

/* Предпросмотр */
.preview-container {
    margin-top: 25px;
    display: none;
}

.preview-header {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.preview-header::before {
    content: "👁️";
    font-size: 20px;
}

.preview-image-wrapper {
    position: relative;
    display: inline-block;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: 2px solid var(--border-color);
}

.preview-image {
    max-width: 300px;
    max-height: 300px;
    display: block;
}

.preview-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

/* Статус загрузки */
.upload-status {
    margin-top: 20px;
    padding: 15px;
    border-radius: 12px;
    display: none;
}

.upload-status--loading {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.upload-status--success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.upload-status--error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

/* Список отзывов */
.reviews-list {
    margin-top: 40px;
}

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 25px;
}

.review-card {
    background: var(--card-bg);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: all 0.3s;
    position: relative;
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-green);
}

.review-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    background: var(--light-bg);
}

.review-actions {
    padding: 20px;
    display: flex;
    justify-content: center;
}

/* Состояние пустого списка */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-light);
}

.empty-state__icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state__title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-dark);
}

.empty-state__description {
    font-size: 16px;
    margin-bottom: 30px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

/* Кнопки */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    min-height: 44px;
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

.btn--success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn--danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
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
}

.btn--secondary:hover {
    border-color: var(--primary-green);
    color: var(--primary-green);
}

.btn--sm {
    padding: 10px 20px;
    font-size: 13px;
    min-height: 40px;
}

/* Адаптивность */
@media (max-width: 768px) {
    .reviews-page {
        padding: 15px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .upload-container {
        padding: 20px;
    }
    
    .reviews-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .preview-image {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .reviews-grid {
        grid-template-columns: 1fr;
    }
    
    .preview-actions {
        flex-direction: column;
    }
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.review-card {
    animation: fadeIn 0.5s ease-out;
}

/* Тёмная тема */
@media (prefers-color-scheme: dark) {
    .upload-container,
    .review-card {
        background: #1e293b;
        border-color: #334155;
    }
    
    .upload-area {
        background: #0f172a;
        border-color: #334155;
    }
    
    .upload-area:hover {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }
    
    .preview-image-wrapper {
        border-color: #334155;
    }
    
    .btn--secondary {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }
}
</style>

<div class="reviews-page">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div>
            <h1 class="page-header__title">⭐ Управление отзывами</h1>
            <p class="page-header__subtitle">Модерация и управление отзывами клиентов</p>
        </div>
        <div class="page-header__stats">
            Всего отзывов: <?= count($reviews) ?>
        </div>
    </div>

    <!-- Форма загрузки отзыва -->
    <div class="upload-container">
        <div class="upload-header">
            <span class="upload-icon">📤</span>
            <h2>Загрузка нового отзыва</h2>
        </div>
        
        <div class="upload-area" id="upload-area">
            <input type="file" id="review_image" name="review_image" accept="image/*" required>
            <div class="upload-area__content">
                <div class="upload-area__icon">📁</div>
                <div class="upload-area__title">Перетащите файл или нажмите для выбора</div>
                <div class="upload-area__subtitle">Поддерживаемые форматы: JPG, PNG, WebP</div>
                <div class="upload-area__button">
                    <span>📁</span> Выбрать файл
                </div>
            </div>
        </div>

        <!-- Область предпросмотра -->
        <div id="preview-container" class="preview-container">
            <div class="preview-header">Предпросмотр изображения</div>
            <div class="preview-image-wrapper">
                <img id="preview-image" class="preview-image" alt="Предпросмотр отзыва">
            </div>
            <div class="preview-actions">
                <button type="button" id="confirm-upload" class="btn btn--success">
                    <span>✅</span> Загрузить отзыв
                </button>
                <button type="button" id="cancel-upload" class="btn btn--secondary">
                    <span>❌</span> Отмена
                </button>
            </div>
        </div>

        <!-- Статус загрузки -->
        <div id="upload-status" class="upload-status"></div>
    </div>

    <!-- Список отзывов -->
    <div class="reviews-list">
        <div class="page-header">
            <div>
                <h2 class="page-header__title">📋 Список отзывов</h2>
                <p class="page-header__subtitle">Управление загруженными отзывами клиентов</p>
            </div>
        </div>

        <?php if (empty($reviews)): ?>
        <!-- Состояние пустого списка -->
        <div class="empty-state">
            <div class="empty-state__icon">⭐</div>
            <h2 class="empty-state__title">Отзывов пока нет</h2>
            <p class="empty-state__description">Загрузите первый отзыв, чтобы он появился на сайте</p>
        </div>
        <?php else: ?>
        <!-- Сетка отзывов -->
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <img src="<?= $review['src'] ?>" 
                     alt="Отзыв <?= $review['id'] ?>" 
                     class="review-image"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjMTA1RTM0IiBvcGFjaXR5PSIwLjEiLz4KPHBhdGggZD0iTTgwIDgwSDg0VjkwSDgwVjgwWk02NSA4MEg2N1Y5MEg2NVY4MFpNNjAgODBINjJWOTBINjBWODBaIiBmaWxsPSIjMTA1RTM0IiBvcGFjaXR5PSIwLjUiLz4KPC9zdmc+'; this.onerror=null;">
                <div class="review-actions">
                    <button onclick="deleteReview(<?= $review['id'] ?>)" 
                            class="btn btn--danger btn--sm"
                            title="Удалить отзыв">
                        <span>🗑️</span> Удалить
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('review_image');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const confirmBtn = document.getElementById('confirm-upload');
const cancelBtn = document.getElementById('cancel-upload');
const statusDiv = document.getElementById('upload-status');

let selectedFile = null;

// Обработка drag & drop
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--primary-green)';
    this.style.background = 'rgba(16, 94, 52, 0.1)';
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border-color)';
    this.style.background = 'var(--light-bg)';
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border-color)';
    this.style.background = 'var(--light-bg)';
    
    if (e.dataTransfer.files.length > 0) {
        selectedFile = e.dataTransfer.files[0];
        showPreview(selectedFile);
    }
});


// Предпросмотр при выборе файла
fileInput.addEventListener('change', function(e) {
    if (this.files.length > 0) {
        selectedFile = this.files[0];
        showPreview(selectedFile);
    }
});

// Функция показа превью
function showPreview(file) {
    // Проверка типа файла
    if (!file.type.match('image.*')) {
        showStatus('Пожалуйста, выберите изображение', 'error');
        return;
    }

    // Проверка размера файла (макс 5MB)
    if (file.size > 5 * 1024 * 1024) {
        showStatus('Файл слишком большой (макс. 5MB)', 'error');
        return;
    }

    // Показываем предпросмотр
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        previewContainer.style.display = 'block';
        
        // Плавно скроллим к превью
        previewContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }
    reader.readAsDataURL(file);
}

// Подтверждение загрузки
confirmBtn.addEventListener('click', function() {
    if (selectedFile) {
        uploadReview(selectedFile);
    }
});

// Отмена загрузки
cancelBtn.addEventListener('click', function() {
    previewContainer.style.display = 'none';
    fileInput.value = '';
    selectedFile = null;
});

// Функция загрузки отзыва
async function uploadReview(file) {
    showStatus('⏳ Загрузка отзыва...', 'loading');
    uploadArea.style.pointerEvents = 'none';
    fileInput.disabled = true;
    confirmBtn.disabled = true;

    const formData = new FormData();
    formData.append('review_image', file);

    try {
        const response = await fetch('/admin/reviews/upload', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showStatus('✅ Отзыв успешно загружен!', 'success');
            
            // Автоматическое обновление страницы через 1.5 секунды
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showStatus(`❌ Ошибка: ${result.message}`, 'error');
            resetUploadForm();
        }
    } catch (error) {
        showStatus('❌ Ошибка при загрузке отзыва', 'error');
        resetUploadForm();
    }
}

// Функция удаления отзыва
async function deleteReview(id) {
    if (!confirm('Вы уверены, что хотите удалить этот отзыв?\n\nОтзыв будет удалён безвозвратно.')) return;

    try {
        const response = await fetch('/admin/reviews/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        });

        const result = await response.json();

        if (result.success) {
            showNotification('✅ Отзыв успешно удалён', 'success');
            
            // Плавное удаление карточки
            const reviewCard = document.querySelector(`button[onclick="deleteReview(${id})"]`)?.closest('.review-card');
            if (reviewCard) {
                reviewCard.style.transition = 'all 0.3s';
                reviewCard.style.opacity = '0';
                reviewCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    reviewCard.remove();
                    
                    // Обновляем количество отзывов
                    const statsElement = document.querySelector('.page-header__stats');
                    if (statsElement) {
                        const currentCount = parseInt(statsElement.textContent.match(/\d+/)[0]);
                        statsElement.textContent = `Всего отзывов: ${currentCount - 1}`;
                    }
                    
                    // Если отзывов не осталось, показываем empty state
                    if (document.querySelectorAll('.review-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            } else {
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showNotification(`❌ Ошибка: ${result.message}`, 'error');
        }
    } catch (error) {
        showNotification('❌ Ошибка при удалении отзыва', 'error');
    }
}

// Вспомогательные функции
function showStatus(message, type = 'info') {
    statusDiv.style.display = 'block';
    statusDiv.className = `upload-status upload-status--${type}`;
    statusDiv.innerHTML = `<div style="display: flex; align-items: center; gap: 10px;">
        <span>${type === 'loading' ? '⏳' : type === 'success' ? '✅' : '❌'}</span>
        <span>${message}</span>
    </div>`;
}

function resetUploadForm() {
    uploadArea.style.pointerEvents = 'auto';
    fileInput.disabled = false;
    confirmBtn.disabled = false;
    previewContainer.style.display = 'none';
    fileInput.value = '';
    selectedFile = null;
}

// Функция для отображения уведомлений
function showNotification(message, type = 'info') {
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
            max-width: 400px;
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
</script>