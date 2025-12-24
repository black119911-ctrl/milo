<!-- views/pages/reviews.php -->
<style>
<style>.reviews-page {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.reviews-header {
    text-align: center;
    margin-bottom: 40px;
}

.reviews-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.reviews-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
}

/* Сетка отзывов */
.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.review-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.review-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    display: block;
}

/* Кнопка загрузки */
.load-more-container {
    text-align: center;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn--secondary {
    background: #3498db;
    color: white;
}

.btn--secondary:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Модальное окно */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 1000;
}

.modal-content {
    position: relative;
    margin: 2% auto;
    max-width: 90%;
    max-height: 90%;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 35px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    z-index: 1001;
}

.modal-body {
    display: flex;
    flex-direction: column;
    height: 100%;
}

#modal-image {
    width: 100%;
    height: auto;
    max-height: 80vh;
    object-fit: contain;
}

/* Адаптивность */
@media (max-width: 768px) {
    .reviews-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }

    .reviews-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .reviews-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</style>
<section class="reviews-page">
    <div class="container">
        <div class="reviews-header" style="margin-top:45px">
            <div class="section__title">
                <h2>Отзывы</h2>
            </div>
            <!-- <h1 class="section_title">Отзывы наших клиентов</h1> -->
        </div>

        <!-- Сетка отзывов -->
        <div class="reviews-grid" id="reviews-grid">
            <!-- Отзывы будут подгружаться через JavaScript -->
        </div>

        <!-- Кнопка загрузки еще -->
        <div class="load-more-container">
            <button class="btn btn--secondary" id="load-more">Загрузить еще отзывы</button>
        </div>

        <!-- Модальное окно для просмотра -->
        <div class="modal" id="review-modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <div class="modal-body">
                    <img src="" alt="Отзыв" id="modal-image">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Передаем начальные данные
window.reviewsData = {
    totalReviews: <?= $reviewsData['total_reviews'] ?>
};
</script>