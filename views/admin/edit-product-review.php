<?php
$review = $review ?? [];
$products = $products ?? [];
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<style>
.edit-review-section {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 30px;
    margin: 20px auto;
    max-width: 800px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.page-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 3px solid #4361ee;
}

.page-title {
    color: #1a1a2e;
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #4361ee;
    text-decoration: none;
    font-weight: 600;
    padding: 8px 16px;
    border: 2px solid #4361ee;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.back-link:hover {
    background: #4361ee;
    color: white;
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
    min-height: 120px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
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
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.review-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #4361ee;
}

.review-info p {
    margin: 5px 0;
    color: #495057;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 0.95rem;
    border: 1px solid;
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

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .edit-review-section {
        padding: 20px;
        margin: 10px;
    }
}
</style>

<div class="edit-review-section">
    <div class="page-header">
        <h1 class="page-title">Редактирование отзыва</h1>
        <a href="/admin/product-reviews" class="back-link">
            ← Назад к списку
        </a>
    </div>
    
    <!-- Уведомления -->
    <?php if ($success_message): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error">❌ <?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    
    <!-- Информация об отзыве -->
    <div class="review-info">
        <p><strong>ID отзыва:</strong> <?= htmlspecialchars($review['id']) ?></p>
        <p><strong>Дата создания:</strong> <?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></p>
        <?php if ($review['updated_at']): ?>
            <p><strong>Дата обновления:</strong> <?= date('d.m.Y H:i', strtotime($review['updated_at'])) ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Форма редактирования -->
    <form method="POST" action="/admin/product-reviews/update">
        <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['id']) ?>">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="product_id">Товар:</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Выберите товар</option>
                    <?php foreach($products as $product): ?>
                        <option value="<?= $product['id'] ?>" 
                            <?= $product['id'] == $review['product_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($product['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="user_name">Имя пользователя:</label>
                <input type="text" id="user_name" name="user_name" required 
                       value="<?= htmlspecialchars($review['user_name']) ?>"
                       placeholder="Введите имя пользователя">
            </div>
            
            <div class="form-group">
                <label for="rating">Оценка:</label>
                <select id="rating" name="rating" required>
                    <option value="">Выберите оценку</option>
                    <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>5 ★★★★★</option>
                    <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>4 ★★★★</option>
                    <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>3 ★★★</option>
                    <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>2 ★★</option>
                    <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>1 ★</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status">Статус:</label>
                <select id="status" name="status">
                    <option value="pending" <?= $review['status'] == 'pending' ? 'selected' : '' ?>>На модерации</option>
                    <option value="approved" <?= $review['status'] == 'approved' ? 'selected' : '' ?>>Одобрен</option>
                    <option value="rejected" <?= $review['status'] == 'rejected' ? 'selected' : '' ?>>Отклонен</option>
                </select>
            </div>
        </div>
        
        <div class="form-group full-width">
            <label for="text">Текст отзыва:</label>
            <textarea id="text" name="text" rows="6" required 
                      placeholder="Введите текст отзыва..."><?= htmlspecialchars($review['text']) ?></textarea>
        </div>
        
        <div class="form-actions">
            <a href="/admin/product-reviews" class="btn btn-secondary">
                ❌ Отмена
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Сохранить изменения
            </button>
        </div>
    </form>
</div>