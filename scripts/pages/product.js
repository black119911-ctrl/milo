// Класс для управления корзиной на странице продукта
class ProductPageManager {
    constructor() {
        this.productId = this.getProductIdFromUrl();
        this.addToCartBtn = document.querySelector('.product__main-info-addtocart');
        this.initCart();
    }

    initCart() {
        console.log('🔄 ProductPageManager инициализирован');
        this.bindCartEvents();
        this.loadCartState();
    }

    getProductIdFromUrl() {
        const path = window.location.pathname;
        const match = path.match(/\/product\/(\d+)/);
        return match ? match[1] : null;
    }

    bindCartEvents() {
        // Кнопка добавления в корзину
        if (this.addToCartBtn) {
            this.addToCartBtn.addEventListener('click', () => {
                this.addToCart();
            });
        }

        // Слушаем события обновления корзины от других компонентов
        document.addEventListener('cartUpdated', (event) => {
            this.onCartUpdate(event.detail);
        });
    }

    loadCartState() {
        // Проверяем начальное состояние корзины
        if (window.initialCartData && this.productId) {
            const quantity = window.initialCartData[this.productId] || 0;
            this.updateButtonState(quantity);
        } else {
            // Загружаем данные корзины
            this.fetchCartData();
        }
    }

    async fetchCartData() {
        try {
            const response = await fetch('/cart/get-cart-data');
            const data = await response.json();

            if (data.success && this.productId) {
                const cartItem = data.items.find(item => item.id == this.productId);
                const quantity = cartItem ? cartItem.quantity : 0;
                this.updateButtonState(quantity);
            }
        } catch (error) {
            console.error('Error fetching cart data:', error);
        }
    }

    updateButtonState(quantity) {
        if (!this.addToCartBtn) return;

        if (quantity > 0) {
            // Показываем счётчик
            this.showQuantityControls(quantity);
        } else {
            // Показываем кнопку "Добавить в корзину"
            this.showAddButton();
        }
    }
    
    showAddButton() {
        if (!this.addToCartBtn) return;

        // Полностью пересоздаем кнопку
        const newButton = document.createElement('button');
        newButton.className = 'btn product__main-info-addtocart';
        newButton.textContent = 'Добавить в корзину';
        newButton.style.cssText = 'display: flex; justify-content: center;';
        
        this.addToCartBtn.replaceWith(newButton);
        this.addToCartBtn = newButton;

        // Перепривязываем события
        this.bindCartEvents();
    }


    showQuantityControls(quantity) {
        if (!this.addToCartBtn) return;

        // Полностью заменяем кнопку новой
        const newButton = this.addToCartBtn.cloneNode(false);
        newButton.innerHTML = `
        <button class="quantity-btn minus-btn" type="button">-</button>
        <span class="current-quantity">${quantity}</span>
        <button class="quantity-btn plus-btn" type="button">+</button>
    `;
        newButton.classList.add('quantity-controls');

        this.addToCartBtn.replaceWith(newButton);
        this.addToCartBtn = newButton;

        this.bindQuantityEvents();
    }


    unbindQuantityEvents() {
        // Очищаем все обработчики
        const minusBtn = this.addToCartBtn?.querySelector('.minus-btn');
        const plusBtn = this.addToCartBtn?.querySelector('.plus-btn');

        if (minusBtn) {
            minusBtn.onclick = null;
            minusBtn.replaceWith(minusBtn.cloneNode(true));
        }
        if (plusBtn) {
            plusBtn.onclick = null;
            plusBtn.replaceWith(plusBtn.cloneNode(true));
        }
    }

    bindQuantityEvents() {
        const minusBtn = this.addToCartBtn?.querySelector('.minus-btn');
        const plusBtn = this.addToCartBtn?.querySelector('.plus-btn');
        const quantityDisplay = this.addToCartBtn?.querySelector('.current-quantity');

        // Запрещаем клики по цифре
        if (quantityDisplay) {
            quantityDisplay.style.pointerEvents = 'none';
        }

        if (minusBtn) {
            minusBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.changeQuantity(-1);
            });
        }
        if (plusBtn) {
            plusBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.changeQuantity(1);
            });
        }
    }

    async changeQuantity(change) {
        if (!this.productId) return;

        // Блокируем кнопки на время запроса
        this.setButtonsState(true);

        try {
            const response = await fetch(`/cart/update/${this.productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ change })
            });

            if (!response.ok) throw new Error('Network error');

            const result = await response.json();

            if (result.success) {
                // Обновляем состояние только после успешного ответа
                if (result.quantity === 0) {
                    this.showAddButton();
                } else {
                    this.updateButtonState(result.quantity);
                }

                document.dispatchEvent(new CustomEvent('cartUpdated', {
                    detail: {
                        count: result.cart_count,
                        productId: this.productId,
                        quantity: result.quantity
                    }
                }));

                if (change > 0) {
                    this.showNotification('Товар добавлен в корзину!', 'success');
                }
            } else {
                // Если ошибка сервера, возвращаем предыдущее состояние
                this.loadCartState();
            }

        } catch (error) {
            console.error('❌ Ошибка при изменении количества:', error);
            this.showNotification('Произошла ошибка', 'error');
            // Восстанавливаем состояние при ошибке
            this.loadCartState();
        } finally {
            this.setButtonsState(false);
        }
    }

    setButtonsState(disabled) {
        const minusBtn = this.addToCartBtn?.querySelector('.minus-btn');
        const plusBtn = this.addToCartBtn?.querySelector('.plus-btn');

        [minusBtn, plusBtn].forEach(btn => {
            if (btn) {
                btn.disabled = disabled;
                btn.style.opacity = disabled ? '0.5' : '1';
                btn.style.cursor = disabled ? 'not-allowed' : 'pointer';
            }
        });
    }

    async addToCart() {
        await this.changeQuantity(1);
    }

    onCartUpdate(detail) {
        if (detail.productId === this.productId) {
            this.updateButtonState(detail.quantity);
        }
    }

    showNotification(message, type = 'success') {
        // Используем существующую систему уведомлений, если доступна
        if (window.cartPageManager && window.cartPageManager.showNotification) {
            window.cartPageManager.showNotification(message, type);
            return;
        }

        if (window.globalCartManager && window.globalCartManager.showNotification) {
            window.globalCartManager.showNotification(message, type);
            return;
        }

        // Простая реализация уведомлений
        this.showSimpleNotification(message, type);
    }

    showSimpleNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `product-notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            bottom: 20px; /* ← ИЗМЕНИЛ С top НА bottom */
            right: 20px;
            background: ${type === 'error' ? '#ff4444' : '#4CAF50'};
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            animation: slideInUp 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            max-width: 300px;
            word-wrap: break-word;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutDown 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Функции для табов (перенесены в начало)
function renderMobileTabs() {
    const mobileTabs = document.querySelectorAll('.product__tabs-item');
    const mobileTabsContent = document.querySelectorAll('.product__content-item');
    const newTabs = document.querySelector('.product__tabs-mobile');

    if (!newTabs) return; // Добавь эту проверку

    // Очищаем контейнер перед добавлением
    while (newTabs.firstChild) {
        newTabs.removeChild(newTabs.firstChild);
    }

    for (let i = 0; i < Math.max(mobileTabs.length, mobileTabsContent.length); i++) {
        if (i < mobileTabs.length && mobileTabs[i]) {
            newTabs.appendChild(mobileTabs[i]);
        }
        if (i < mobileTabsContent.length && mobileTabsContent[i]) {
            newTabs.appendChild(mobileTabsContent[i]);
        }
    }
}

function restoreOriginalStructure() {
    const originalTabsContainer = document.querySelector('.product__tabs');
    const originalContentsContainer = document.querySelector('.product__content');

    if (!originalTabsContainer || !originalContentsContainer) return;

    const mobileTabs = document.querySelectorAll('.product__tabs-item');
    const mobileTabsContent = document.querySelectorAll('.product__content-item');

    // Очищаем контейнеры
    while (originalTabsContainer.firstChild) {
        originalTabsContainer.removeChild(originalTabsContainer.firstChild);
    }
    while (originalContentsContainer.firstChild) {
        originalContentsContainer.removeChild(originalContentsContainer.firstChild);
    }

    // Восстанавливаем оригинальную структуру
    mobileTabs.forEach(tab => {
        if (tab) originalTabsContainer.appendChild(tab);
    });
    mobileTabsContent.forEach(content => {
        if (content) originalContentsContainer.appendChild(content);
    });
}

function checkScreenSize() {
    const newTabs = document.querySelector('.product__tabs-mobile');
    if (!newTabs) return; // Добавь эту проверку
    
    if (window.innerWidth <= 750) {
        renderMobileTabs();
    } else {
        restoreOriginalStructure();
    }
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function () {
    // Инициализация табов
    const tabButtons = document.querySelectorAll('.product__tabs-item');
    const panels = document.querySelectorAll('.product__content-item');

    tabButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Сброс активных состояний
            tabButtons.forEach(btn => btn.classList.remove('active'));
            panels.forEach(panel => panel.classList.remove('active'));

            // Активируем выбранную вкладку и соответствующую панель
            this.classList.add('active');
            const target = this.dataset.target;
            if (target) {
                document.querySelector(target)?.classList.add('active');
            }
        });
    });

    // Первоначальная проверка размера экрана
    checkScreenSize();

    // Обработчик события resize для отслеживания изменений размера окна
    window.addEventListener('resize', checkScreenSize);

    // Инициализация менеджера продукта
    window.productPageManager = new ProductPageManager();
});

// Добавляем стили для анимаций уведомлений и кнопок
const style = document.createElement('style');
style.textContent = `
    @keyframes notificationSlideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes notificationSlideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .product__main-info-addtocart {
        transition: all 0.3s ease;
        display: block;
    }
    
    .product__main-info-addtocart:disabled {
        cursor: not-allowed;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        min-width: 120px;
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: #4361ee;
        color: white;
        border-radius: 50%;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover {
        background: #3a56d4;
        transform: scale(1.1);
    }

    .quantity-btn:active {
        transform: scale(0.95);
    }

    .current-quantity {
        font-weight: 600;
        font-size: 16px;
        min-width: 20px;
        text-align: center;
    }
`;
document.head.appendChild(style);


style.textContent += `
    .quantity-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin: 10px 0;
    }

    .quantity-btn {
        background: #fff;
        color: black;
        border: 1px solid green;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .quantity-btn:hover:not(:disabled) {
        background: #f0f0f0;
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .current-quantity {
        font-weight: bold;
        font-size: 18px;
        min-width: 20px;
        text-align: center;
    }
`;


// Добавьте в конец файла product.js
function initReviewAnimations() {
    const reviewItems = document.querySelectorAll('.review-item');
    
    reviewItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Вызовите после загрузки отзывов
document.addEventListener('DOMContentLoaded', function() {
    initReviewAnimations();
});