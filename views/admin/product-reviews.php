<?php
$reviews = $reviews ?? [];
$products = $products ?? [];
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<style>
/* Стили для страницы отзывов к товарам */
.product-reviews-page {
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

.page-header__stats {
    background: rgba(16, 94, 52, 0.1);
    padding: 12px 20px;
    border-radius: 12px;
    color: var(--primary-green);
    font-weight: 600;
    font-size: 16px;
    border: 1px solid rgba(16, 94, 52, 0.2);
}

/* Уведомления */
.notification {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    font-size: 14px;
    font-weight: 500;
    animation: slideIn 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid transparent;
}

.notification-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.2);
}

.notification-error {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.2);
}

@keyframes slideIn {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Форма добавления отзыва */
.add-review-section {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 30px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(16, 94, 52, 0.1);
}

.section-header h2 {
    color: var(--primary-green);
    font-size: 24px;
    font-weight: 700;
}

.section-icon {
    font-size: 28px;
}

/* Форма */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 14px;
    font-family: inherit;
    background: var(--light-bg);
    color: var(--text-dark);
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(16, 94, 52, 0.1);
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
    line-height: 1.5;
}

/* Рейтинг */
.rating-select {
    width: 100%;
}

.rating-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
}

.stars-preview {
    color: #ffc107;
    font-size: 16px;
    letter-spacing: 2px;
}

/* Список отзывов */
.reviews-section {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 30px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
}

.reviews-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 25px;
}

/* Карточка отзыва */
.review-card {
    background: var(--light-bg);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.review-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-green);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.review-product {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-green);
    margin: 0;
}

.review-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--text-light);
}

.meta-item strong {
    color: var(--text-dark);
    font-weight: 600;
}

/* Рейтинг в карточке */
.review-rating {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-stars {
    color: #ffc107;
    font-size: 18px;
    letter-spacing: 1px;
}

/* Текст отзыва */
.review-text {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    color: var(--text-dark);
    line-height: 1.6;
    margin: 20px 0;
    border-left: 4px solid var(--primary-green);
    font-size: 15px;
}

/* Статус отзыва */
.review-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: rgba(248, 180, 0, 0.1);
    color: #f8b400;
    border: 1px solid rgba(248, 180, 0, 0.2);
}

.status-approved {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-rejected {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Действия с отзывом */
.review-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

/* Кнопки */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
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

.btn--success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
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

.btn--warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn--warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
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

.btn--outline {
    background: transparent;
    color: var(--primary-green);
    border: 2px solid var(--primary-green);
}

.btn--outline:hover {
    background: rgba(16, 94, 52, 0.1);
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

/* Статистика статусов */
.status-stats {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.status-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    border-radius: 12px;
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    font-size: 14px;
    font-weight: 600;
}

.status-stat__count {
    font-size: 20px;
    font-weight: 700;
}

.status-stat--pending .status-stat__count { color: #f8b400; }
.status-stat--approved .status-stat__count { color: #10b981; }
.status-stat--rejected .status-stat__count { color: #ef4444; }

/* Адаптивность */
@media (max-width: 768px) {
    .product-reviews-page {
        padding: 15px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .add-review-section,
    .reviews-section {
        padding: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .review-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .review-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .review-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .status-stats {
        flex-direction: column;
    }
}

/* Тёмная тема */
@media (prefers-color-scheme: dark) {
    .review-text {
        background: #1e293b;
    }
    
    .status-stat {
        background: #0f172a;
        border-color: #334155;
    }
    
    .review-card {
        background: #0f172a;
        border-color: #334155;
    }
}
</style>

<div class="product-reviews-page">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div>
            <h1 class="page-header__title">⭐ Отзывы к товарам</h1>
            <p class="page-header__subtitle">Управление и модерация отзывов покупателей</p>
        </div>
        <div class="page-header__stats">
            Всего отзывов: <?= count($reviews) ?>
        </div>
    </div>

    <!-- Уведомления -->
    <?php if ($success_message): ?>
        <div class="notification notification-success">
            <span>✅</span> <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="notification notification-error">
            <span>❌</span> <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Форма добавления отзыва -->
    <div class="add-review-section">
        <div class="section-header">
            <span class="section-icon">➕</span>
            <h2>Добавить новый отзыв</h2>
        </div>
        
        <form method="POST" action="/admin/product-reviews/create" id="review-form">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">📦 Товар *</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Выберите товар</option>
                        <?php foreach($products as $product): ?>
                            <option value="<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">👤 Имя пользователя *</label>
                    <input type="text" name="user_name" class="form-control" required 
                           placeholder="Введите имя пользователя">
                </div>
                
                <div class="form-group">
                    <label class="form-label">⭐ Оценка *</label>
                    <select name="rating" class="form-control" required>
                        <option value="">Выберите оценку</option>
                        <option value="5">5 звёзд ★★★★★</option>
                        <option value="4">4 звезды ★★★★</option>
                        <option value="3">3 звезды ★★★</option>
                        <option value="2">2 звезды ★★</option>
                        <option value="1">1 звезда ★</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">📊 Статус</label>
                    <select name="status" class="form-control">
                        <option value="approved">✅ Одобрен</option>
                        <option value="pending">⏳ На модерации</option>
                        <option value="rejected">❌ Отклонён</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label class="form-label">📝 Текст отзыва *</label>
                <textarea name="text" class="form-control" rows="4" required 
                          placeholder="Введите текст отзыва..."></textarea>
            </div>
            
            <button type="submit" class="btn btn--primary">
                <span>➕</span>
                Добавить отзыв
            </button>
        </form>
    </div>
    
    <!-- Статистика статусов -->
    <?php if (!empty($reviews)): ?>
    <div class="status-stats">
        <?php
        $pending = array_filter($reviews, fn($r) => $r['status'] === 'pending');
        $approved = array_filter($reviews, fn($r) => $r['status'] === 'approved');
        $rejected = array_filter($reviews, fn($r) => $r['status'] === 'rejected');
        ?>
        <div class="status-stat status-stat--pending">
            <span>⏳</span>
            <div>
                <div class="status-stat__count"><?= count($pending) ?></div>
                <div>Ожидают</div>
            </div>
        </div>
        <div class="status-stat status-stat--approved">
            <span>✅</span>
            <div>
                <div class="status-stat__count"><?= count($approved) ?></div>
                <div>Одобрено</div>
            </div>
        </div>
        <div class="status-stat status-stat--rejected">
            <span>❌</span>
            <div>
                <div class="status-stat__count"><?= count($rejected) ?></div>
                <div>Отклонено</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Список отзывов для модерации -->
    <div class="reviews-section">
        <div class="section-header">
            <span class="section-icon">📋</span>
            <h2>Модерация отзывов</h2>
        </div>
        
        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">⭐</div>
                <h2 class="empty-state__title">Нет отзывов для модерации</h2>
                <p class="empty-state__description">Все отзывы обработаны или ожидают добавления</p>
                <a href="#review-form" class="btn btn--primary">
                    <span>➕</span>
                    Добавить первый отзыв
                </a>
            </div>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach($reviews as $review): ?>
                <div class="review-card">
                    <!-- Шапка отзыва -->
                    <div class="review-header">
                        <h3 class="review-product">
                            <?= htmlspecialchars($review['product_name'] ?? 'Товар не найден') ?>
                        </h3>
                        <span class="review-status status-<?= $review['status'] ?>">
                            <?= $review['status'] === 'pending' ? '⏳ Ожидает' : 
                                 ($review['status'] === 'approved' ? '✅ Одобрен' : '❌ Отклонён') ?>
                        </span>
                    </div>
                    
                    <!-- Мета-информация -->
                    <div class="review-meta">
                        <div class="meta-item">
                            <strong>👤 Имя:</strong> <?= htmlspecialchars($review['user_name']) ?>
                        </div>
                        <div class="meta-item review-rating">
                            <strong>⭐ Оценка:</strong>
                            <span class="rating-stars"><?= str_repeat('★', $review['rating']) ?></span>
                            <span>(<?= $review['rating'] ?>/5)</span>
                        </div>
                        <div class="meta-item">
                            <strong>📅 Дата:</strong> <?= date('d.m.Y H:i', strtotime($review['created_at'])) ?>
                        </div>
                    </div>
                    
                    <!-- Текст отзыва -->
                    <div class="review-text">
                        <?= nl2br(htmlspecialchars($review['text'])) ?>
                    </div>
                    
                    <!-- Действия -->
                    <div class="review-actions">
                        <?php if ($review['status'] !== 'approved'): ?>
                            <a href="/admin/product-reviews/approve/<?= $review['id'] ?>" class="btn btn--success">
                                <span>✅</span> Одобрить
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($review['status'] !== 'rejected'): ?>
                            <a href="/admin/product-reviews/reject/<?= $review['id'] ?>" class="btn btn--danger">
                                <span>❌</span> Отклонить
                            </a>
                        <?php endif; ?>

                        <a href="/admin/product-reviews/edit/<?= $review['id'] ?>" class="btn btn--primary">
                            <span>✏️</span> Редактировать
                        </a>
                        
                        <a href="/admin/product-reviews/delete/<?= $review['id'] ?>" class="btn btn--warning"
                           onclick="return confirm('Вы уверены что хотите удалить этот отзыв?')">
                            <span>🗑️</span> Удалить
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// AJAX отправка формы для лучшего UX
document.getElementById('review-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Показываем индикатор загрузки
    submitBtn.innerHTML = '<span>⏳</span> Сохранение...';
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            submitBtn.innerHTML = '<span>✅</span> Отзыв добавлен!';
            submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            
            // Очищаем форму
            form.reset();
            
            // Перезагружаем страницу через 1.5 секунды
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('Ошибка: ' + (result.message || 'Неизвестная ошибка'));
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        alert('Ошибка при отправке формы');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Плавная прокрутка к форме при клике на ссылку
document.querySelector('a[href="#review-form"]')?.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('review-form').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
});
</script>