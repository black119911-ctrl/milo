// scripts/pages/reviews.js
class ReviewsManager {
    constructor() {
        this.currentPage = 1;
        this.itemsPerPage = 12;
        this.isLoading = false;
        this.hasMore = true;

        this.init();
    }

    async init() {
        await this.loadReviews();
        this.bindEvents();
    }

    async loadReviews() {
        if (this.isLoading || !this.hasMore) return;

        console.log(this.isLoading);

        try {
            this.isLoading = true;
            this.showLoading();

            const response = await fetch(`/api/reviews?page=${this.currentPage}`);
            const result = await response.json();

            if (result.success) {
                this.renderReviews(result.reviews);
                this.updateLoadMoreButton(result.pagination);
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Ошибка загрузки отзывов:', error);
            this.showNotification('Ошибка загрузки отзывов', 'error');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    renderReviews(reviews) {
        const grid = document.getElementById('reviews-grid');

        const reviewsHTML = reviews.map(review => this.createReviewCard(review)).join('');

        if (this.currentPage === 1) {
            grid.innerHTML = reviewsHTML;
        } else {
            grid.innerHTML += reviewsHTML;
        }
    }

    createReviewCard(review) {
        return `
            <div class="review-card" data-id="${review.id}">
                <img src="${review.src}" 
                     alt="Отзыв клиента" 
                     class="review-image"
                     loading="lazy">
            </div>
        `;
    }

    updateLoadMoreButton(pagination) {
        const loadMoreBtn = document.getElementById('load-more');
        this.hasMore = pagination.has_more;

        if (!this.hasMore) {
            loadMoreBtn.style.display = 'none';
        } else {
            loadMoreBtn.textContent = `Загрузить еще (${pagination.total_reviews - (this.currentPage * this.itemsPerPage)})`;
        }
    }

    bindEvents() {
        // Загрузка еще
        document.getElementById('load-more').addEventListener('click', () => {
            this.currentPage++;
            this.loadReviews();
        });

        // Модальное окно
        document.getElementById('review-modal').addEventListener('click', (e) => {
            if (e.target.id === 'review-modal' || e.target.classList.contains('modal-close')) {
                this.closeModal();
            }
        });

        // Клик по карточке отзыва
        document.getElementById('reviews-grid').addEventListener('click', (e) => {
            const card = e.target.closest('.review-card');
            if (card) {
                const image = card.querySelector('.review-image');
                this.openModal(image.src);
            }
        });

        // Закрытие по ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeModal();
        });
    }

    openModal(imageSrc) {
        const modal = document.getElementById('review-modal');
        const modalImage = document.getElementById('modal-image');

        modalImage.src = imageSrc;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    closeModal() {
        const modal = document.getElementById('review-modal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    showLoading() {
        document.getElementById('load-more').disabled = true;
        document.getElementById('load-more').textContent = 'Загрузка...';
    }

    hideLoading() {
        document.getElementById('load-more').disabled = false;
    }

    showNotification(message, type = 'error') {
        // Простая реализация уведомлений
        alert(message);
    }
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    new ReviewsManager();
});