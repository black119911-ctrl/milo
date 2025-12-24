<?php
$reviews = $reviews ?? [];
$products = $products ?? [];
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<style>
.admin-reviews {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.page-title {
    color: #1a1a2e;
    margin-bottom: 30px;
    font-size: 2rem;
    font-weight: 700;
}

/* Стили для формы добавления */
.add-review-section {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.section-title {
    color: #1a1a2e;
    margin: 0 0 25px 0;
    font-size: 1.5rem;
    font-weight: 600;
    border-bottom: 3px solid #4361ee;
    padding-bottom: 10px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1a1a2e;
    font-size: 0.95rem;
}

.form-group select,
.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    box-sizing: border-box;
    transition: all 0.3s ease;
    background: white;
}

.form-group select:focus,
.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.rating-select {
    width: 100%;
}

.rating-option {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stars-preview {
    color: #ffc107;
    font-size: 1.1rem;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #4361ee;
    color: white;
}

.btn-primary:hover {
    background: #3a56d4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

/* Стили для списка отзывов */
.reviews-section {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.review-moderation-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.review-moderation-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.review-info h3 {
    margin: 0 0 15px 0;
    color: #1a1a2e;
    font-size: 1.2rem;
}

.review-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.review-meta p {
    margin: 0;
    color: #495057;
    font-size: 0.95rem;
}

.review-text {
    background: white;
    padding: 15px;
    border-radius: 6px;
    border-left: 4px solid #4361ee;
    margin: 15px 0;
    color: #495057;
    line-height: 1.5;
}

.review-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.review-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-approved {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #b8daff;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.no-reviews {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-reviews h3 {
    margin: 0 0 10px 0;
    color: #495057;
}

/* Уведомления */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 0.95rem;
    border: 1px solid;
    animation: slideIn 0.3s ease;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

@keyframes slideIn {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Адаптивность */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .review-meta {
        grid-template-columns: 1fr;
    }
    
    .review-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .admin-reviews {
        padding: 15px;
    }
    
    .add-review-section,
    .reviews-section {
        padding: 20px;
    }
}
</style>

<div class="admin-reviews">
    <h1 class="page-title">Управление отзывами к товарам</h1>
    
    <!-- Уведомления -->
    <?php if ($success_message): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error">❌ <?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    
    <!-- Форма добавления отзыва -->
    <div class="add-review-section">
        <h2 class="section-title">Добавить отзыв</h2>
        <form method="POST" action="/admin/product-reviews/create">
            <div class="form-grid">
                <div class="form-group">
                    <label for="product_id">Товар:</label>
                    <select id="product_id" name="product_id" required>
                        <option value="">Выберите товар</option>
                        <?php foreach($products as $product): ?>
                            <option value="<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="user_name">Имя пользователя:</label>
                    <input type="text" id="user_name" name="user_name" required 
                           placeholder="Введите имя пользователя">
                </div>
                
                <div class="form-group">
                    <label for="rating">Оценка:</label>
                    <select id="rating" name="rating" class="rating-select" required>
                        <option value="">Выберите оценку</option>
                        <option value="5">
                            <span class="rating-option">5 <span class="stars-preview">★★★★★</span></span>
                        </option>
                        <option value="4">
                            <span class="rating-option">4 <span class="stars-preview">★★★★</span></span>
                        </option>
                        <option value="3">
                            <span class="rating-option">3 <span class="stars-preview">★★★</span></span>
                        </option>
                        <option value="2">
                            <span class="rating-option">2 <span class="stars-preview">★★</span></span>
                        </option>
                        <option value="1">
                            <span class="rating-option">1 <span class="stars-preview">★</span></span>
                        </option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Статус:</label>
                    <select id="status" name="status">
                        <option value="approved">Одобрен</option>
                        <option value="pending">На модерации</option>
                        <option value="rejected">Отклонен</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="text">Текст отзыва:</label>
                <textarea id="text" name="text" rows="4" required 
                          placeholder="Введите текст отзыва..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <span>➕</span>
                Добавить отзыв
            </button>
        </form>
    </div>
    
    <!-- Список отзывов для модерации -->
    <div class="reviews-section">
        <h2 class="section-title">Модерация отзывов</h2>
        
        <?php if (empty($reviews)): ?>
            <div class="no-reviews">
                <h3>Нет отзывов для модерации</h3>
                <p>Все отзывы обработаны или ожидают добавления</p>
            </div>
        <?php else: ?>
            <?php foreach($reviews as $review): ?>
            <div class="review-moderation-item">
                <div class="review-info">
                    <h3><?= htmlspecialchars($review['product_name'] ?? 'Товар не найден') ?></h3>
                    
                    <div class="review-meta">
                        <p><strong>👤 Имя:</strong> <?= htmlspecialchars($review['user_name']) ?></p>
                        <p><strong>⭐ Оценка:</strong> <span style="color: #ffc107;"><?= str_repeat('★', $review['rating']) ?></span></p>
                        <p><strong>📅 Дата:</strong> <?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></p>
                        <p><strong>📊 Статус:</strong> 
                            <span class="review-status status-<?= $review['status'] ?>">
                                <?= $review['status'] === 'pending' ? 'Ожидает' : 
                                     ($review['status'] === 'approved' ? 'Одобрен' : 'Отклонен') ?>
                            </span>
                        </p>
                    </div>
                    
                    <div class="review-text">
                        <?= nl2br(htmlspecialchars($review['text'])) ?>
                    </div>
                </div>
                
                <div class="review-actions">
                    <?php if ($review['status'] !== 'approved'): ?>
                        <a href="/admin/product-reviews/approve/<?= $review['id'] ?>" class="btn btn-success">
                            ✅ Одобрить
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($review['status'] !== 'rejected'): ?>
                        <a href="/admin/product-reviews/reject/<?= $review['id'] ?>" class="btn btn-danger">
                            ❌ Отклонить
                        </a>
                    <?php endif; ?>

                    <a href="/admin/product-reviews/edit/<?= $review['id'] ?>" class="btn btn-primary">
                        ✏️ Редактировать
                    </a>
                    
                    <a href="/admin/product-reviews/delete/<?= $review['id'] ?>" class="btn btn-warning"
                       onclick="return confirm('Вы уверены что хотите удалить этот отзыв?')">
                        🗑️ Удалить
                    </a>
                    
                    <?php if ($review['status'] === 'pending'): ?>
                        <span class="btn btn-secondary" style="cursor: default;">
                            ⏳ Ожидает модерации
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>