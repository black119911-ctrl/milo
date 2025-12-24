<?php
session_start();
$review_success = $_SESSION['review_success'] ?? '';
$review_error = $_SESSION['review_error'] ?? '';
unset($_SESSION['review_success'], $_SESSION['review_error']);
?>

<div class="product-page">
    <div class="container">
        <div class="product">
            <div class="product__main">
                <div class="product__main-image">
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                </div>
                <div class="product__main-info">
                    <div class="product__main-info-title">
                        <h1><?= $product['name'] ?></h1>
                    </div>
                    <div class="product__main-info-subtitle"><?= $product['subtitle'] ?></div>
                    <div class="product__main-info-desc">
                        <p><?= $product['short_desc'] ?></p>
                    </div>
                    <div class="product__main-info-price"><?= $product['price'] ?> ₽</div>
                    <button class="btn product__main-info-addtocart">Добавить в корзину</button>
                </div>
            </div>

            <?php if (!empty($product['article_description'])): ?>
            <div class="product__tabs">
                <button class="product__tabs-item active" data-target="#tab1">Описание</button>
                <button class="product__tabs-item" data-target="#tab2">Состав</button>
                <button class="product__tabs-item" data-target="#tab3">Применение</button>
                <button class="product__tabs-item" data-target="#tab4">Рекомендации</button>
                <button class="product__tabs-item" data-target="#tab5">Отзывы</button>
            </div>
            <div class="product__content">
                <div class="product__content-item active" id="tab1">
                    <?= $product['article_description'] ?>
                </div>
                <div class="product__content-item" id="tab2">
                    <h2>Состав:</h2>
                    <p><?= $product['article_composition'] ?></p>
                </div>
                <div class="product__content-item" id="tab3">
                    <h2>Способ применения:</h2>
                    <?= $product['article_application'] ?>
                </div>
                <div class="product__content-item" id="tab4">
                    <h2>Общие рекомендации:</h2>
                    <?= $product['article_recommendations'] ?>
                </div>
                <div class="product__content-item" id="tab5">
                    <!-- Форма добавления отзыва -->
                    <div class="add-review-form">
                        <h3>Оставить отзыв</h3>
                        <form id="reviewForm" method="POST">
                            <div class="form-group">
                                <label>Ваше имя:</label>
                                <input type="text" name="user_name" id="user_name" required>
                            </div>

                            <div class="rating-group">
                                <label>Оценка:</label>
                                <div class="rating-stars">
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5" title="Отлично">★</label>
                                    <input type="radio" id="star4" name="rating" value="4" required>
                                    <label for="star4" title="Хорошо">★</label>
                                    <input type="radio" id="star3" name="rating" value="3" required>
                                    <label for="star3" title="Нормально">★</label>
                                    <input type="radio" id="star2" name="rating" value="2" required>
                                    <label for="star2" title="Плохо">★</label>
                                    <input type="radio" id="star1" name="rating" value="1" required>
                                    <label for="star1" title="Ужасно">★</label>
                                </div>
                                <div class="rating-value">Выберите оценку</div>
                            </div>

                            <div class="form-group">
                                <label>Текст отзыва:</label>
                                <textarea name="text" id="review_text" rows="4" required></textarea>
                            </div>

                            <button type="submit" id="submitReview">Отправить отзыв</button>
                        </form>
                    </div>

                    <!-- Список отзывов -->
                    <div class="reviews-list">
                        <h3>Отзывы о товаре</h3>
                        
                        <?php if (!empty($reviews)): ?>
                            <?php foreach($reviews as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                                    <div class="review-rating"><?= str_repeat('★', $review['rating']) ?></div>
                                </div>
                                <div class="review-text"><?= nl2br(htmlspecialchars($review['text'])) ?></div>
                                <div class="review-date"><?= date('d.m.Y', strtotime($review['created_at'])) ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Пока нет отзывов. Будьте первым!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="product__tabs-mobile"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Контейнер для AJAX уведомлений -->
<div id="reviewNotification"></div>

<script>
// Передаем данные корзины в JavaScript
window.initialCartData = <?= json_encode($initialCartData ?? [], JSON_UNESCAPED_UNICODE) ?>;

// AJAX для отправки отзывов
document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('reviewForm');
    const reviewNotification = document.getElementById('reviewNotification');
    const submitBtn = document.getElementById('submitReview');
    const ratingInputs = document.querySelectorAll('.rating-stars input');
    const ratingValue = document.querySelector('.rating-value');
    
    // Обработчик выбора оценки
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const value = this.value;
            let text = '';
            
            switch(value) {
                case '5': text = 'Отлично ★★★★★'; break;
                case '4': text = 'Хорошо ★★★★'; break;
                case '3': text = 'Нормально ★★★'; break;
                case '2': text = 'Плохо ★★'; break;
                case '1': text = 'Ужасно ★'; break;
                default: text = 'Выберите оценку';
            }
            
            ratingValue.textContent = text;
        });
    });
    
    // Обработчик отправки формы
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productId = <?= $product['id'] ?>;
            
            // Показываем загрузку
            submitBtn.disabled = true;
            submitBtn.textContent = 'Отправка...';
            
            fetch(`/product/${productId}/add-review`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    reviewForm.reset();
                    resetRating();
                } else {
                    showNotification(data.message, 'error');
                    // console.log(data.debug);
                }
            })
            .catch(error => {
                showNotification('Ошибка сети. Попробуйте еще раз.', 'error');
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Отправить отзыв';
            });
        });
    }
    
    function showNotification(message, type) {
        reviewNotification.innerHTML = `
            <div class="alert alert-${type}">
                ${type === 'success' ? '✅' : '❌'} ${message}
            </div>
        `;
        
        // Автоскрытие через 5 секунд
        setTimeout(() => {
            reviewNotification.innerHTML = '';
        }, 5000);
    }
    
    function resetRating() {
        document.querySelectorAll('input[name="rating"]').forEach(radio => {
            radio.checked = false;
        });
        document.querySelector('.rating-value').textContent = 'Выберите оценку';
    }
});
</script>


<script>
    // В AJAX запросе в product.php
const productId = <?= $product['id'] ?? 'null' ?>;

// Добавь проверку
console.log('Product ID from PHP:', productId);

if (!productId) {
    showNotification('Ошибка: ID товара не найден', 'error');
    // return;
}
</script>