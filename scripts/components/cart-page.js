// scripts/components/cart-page.js
class CartPageManager {
    constructor() {
        this.init();
        setTimeout(() => this.addItemAnimations(), 100);
    }

    init() {
        console.log('🔄 CartPageManager инициализирован');
        this.bindEvents();
    }

    bindEvents() {
        document.addEventListener('cartUpdated', (event) => {
            this.updateCartDisplay(event.detail.count);
        });
    }

    async changeQuantity(productId, change) {
        try {
            const response = await fetch(`/cart/update/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ change })
            });

            if (!response.ok) throw new Error('Network error');

            const result = await response.json();

            if (result.success) {
                this.handleCartUpdate(result, productId);
                this.showNotification(result.message, 'success');
            } else {
                this.showNotification(result.message, 'error');
            }
        } catch (error) {
            console.error('Ошибка при обновлении корзины:', error);
            this.showNotification('Произошла ошибка при обновлении корзины', 'error');
        }
    }

    async removeItem(productId) {
        if (confirm('Удалить товар из корзины?')) {
            try {
                // Отправляем запрос на удаление
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Network error');

                const result = await response.json();

                if (result.success) {
                    // Обновляем глобальную корзину
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: { count: result.cart_count }
                    }));

                    // Удаляем элемент из DOM
                    this.removeCartItem(productId);
                    this.updateCartSummary();
                    this.showNotification(result.message, 'success');
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Ошибка при удалении товара:', error);
                this.showNotification('Произошла ошибка при удалении товара', 'error');
            }
        }
    }

    handleCartUpdate(result, productId) {
        // Обновляем глобальную корзину
        document.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { count: result.cart_count }
        }));

        // Обновляем интерфейс страницы корзины
        this.updateCartItem(productId, result.quantity);
        this.updateCartSummary();

        // Если товар удален, убираем его из списка
        if (result.quantity === 0) {
            this.removeCartItem(productId);
        }
    }

    updateCartItem(productId, quantity) {
        const cartItem = document.querySelector(`.cart-item[data-id="${productId}"]`);
        if (!cartItem) return;

        const quantityDisplay = cartItem.querySelector('.current-quantity');
        const totalDisplay = cartItem.querySelector('.cart-item__total');

        if (quantityDisplay) quantityDisplay.textContent = quantity;

        // Обновляем общую стоимость товара
        const price = this.getItemPrice(productId);
        if (totalDisplay && price) {
            totalDisplay.textContent = (price * quantity) + ' ₽';
        }
    }

    removeCartItem(productId) {
        const cartItem = document.querySelector(`.cart-item[data-id="${productId}"]`);
        if (cartItem) {
            // Анимация удаления
            cartItem.style.transition = 'all 0.3s ease';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(-100px)';

            setTimeout(() => {
                cartItem.remove();
                this.updateCartSummary();
                this.checkEmptyCart();
            }, 300);
        }
    }

    updateCartSummary() {
        // Пересчитываем итоги на основе текущих данных в DOM
        let totalItems = 0;
        let totalPrice = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            const quantity = parseInt(item.querySelector('.current-quantity').textContent) || 0;
            const price = this.getItemPrice(item.dataset.id);
            totalItems += quantity;
            totalPrice += price * quantity;
        });

        // Обновляем отображение
        this.updateSummaryDisplay(totalItems, totalPrice);
    }

    updateSummaryDisplay(totalItems, totalPrice) {
        const itemsCountElement = document.getElementById('cart-items-count');
        const summaryItemsElement = document.getElementById('summary-items-count');
        const totalPriceElement = document.getElementById('summary-total-price');
        const finalPriceElement = document.getElementById('summary-final-price');

        if (itemsCountElement) itemsCountElement.textContent = totalItems;
        if (summaryItemsElement) summaryItemsElement.textContent = totalItems + ' шт.';
        if (totalPriceElement) totalPriceElement.textContent = totalPrice + ' ₽';
        if (finalPriceElement) finalPriceElement.textContent = totalPrice + ' ₽';
    }

    getItemPrice(productId) {
        // Получаем цену из данных или из DOM
        if (window.cartPageData && window.cartPageData.items) {
            const item = window.cartPageData.items.find(i => i.id == productId);
            return item ? item.price : 0;
        }

        const cartItem = document.querySelector(`.cart-item[data-id="${productId}"]`);
        if (cartItem) {
            const priceText = cartItem.querySelector('.cart-item__price').textContent;
            return parseInt(priceText.replace(/[^\d]/g, '')) || 0;
        }

        return 0;
    }

    checkEmptyCart() {
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            this.showEmptyCart();
        }
    }

    showEmptyCart() {
        const cartContent = document.querySelector('.cart-page__content');
        if (cartContent) {
            cartContent.innerHTML = `
                <div class="cart-empty">
                    <div class="cart-empty__icon">🛒</div>
                    <h2 class="cart-empty__title">Корзина пуста</h2>
                    <p class="cart-empty__text">Добавьте товары из каталога</p>
                    <a href="/catalog" class="btn btn--primary">Перейти в каталог</a>
                </div>
            `;
        }
    }

    async clearCart() {
        if (confirm('Очистить всю корзину?')) {
            try {
                const response = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Network error');

                const result = await response.json();

                if (result.success) {
                    // Обновляем глобальную корзину
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: { count: 0 }
                    }));

                    // Показываем пустую корзину
                    this.showEmptyCart();
                    this.showNotification(result.message, 'success');
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Ошибка при очистке корзины:', error);
                this.showNotification('Произошла ошибка при очистке корзины', 'error');
            }
        }
    }

    // ДОБАВЬТЕ ЭТОТ МЕТОД
    checkout() {
        console.log('🛒 Переход к оформлению заказа');

        // Проверяем, есть ли товары в корзине
        if (window.cartPageData.totalItems === 0) {
            this.showNotification('Корзина пуста', 'error');
            return;
        }

        // Простой переход на страницу оформления заказа
        window.location.href = '/checkout';
    }

    updateCartDisplay(count) {
        // Обновляем счетчик в хедере
        if (window.globalCartManager) {
            window.globalCartManager.updateCart(count);
        }
    }

    showNotification(message, type = 'success') {
        let notificationContainer = document.getElementById('cart-notifications');

        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'cart-notifications';
            notificationContainer.style.cssText = `
            position: fixed;
            bottom: 20px; /* ← ИЗМЕНИЛ С top НА bottom */
            right: 20px;
            z-index: 10000;
            max-width: calc(100vw - 40px);
        `;
            document.body.appendChild(notificationContainer);
        }

        const notification = document.createElement('div');
        notification.className = `cart-notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
        background: ${type === 'error' ? '#ff4444' : '#4CAF50'};
        color: white;
        padding: 16px 20px;
        margin-bottom: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        animation: slideInUp 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        max-width: 300px;
        word-wrap: break-word;
    `;

        notificationContainer.appendChild(notification);

        // Автоудаление через 3 секунды
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutDown 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    }

    addItemAnimations() {
        const cartItems = document.querySelectorAll('.cart-item');

        cartItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-50px)';

            setTimeout(() => {
                item.style.transition = 'all 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 100);
        });
    }
}

// Замени существующие анимации на эти:
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from { 
            transform: translateY(100px); 
            opacity: 0; 
        }
        to { 
            transform: translateY(0); 
            opacity: 1; 
        }
    }
    @keyframes slideOutDown {
        from { 
            transform: translateY(0); 
            opacity: 1; 
        }
        to { 
            transform: translateY(100px); 
            opacity: 0; 
        }
    }
    
    /* Адаптивность для мобильных устройств */
    @media (max-width: 768px) {
        #cart-notifications {
            bottom: 10px !important;
            right: 10px !important;
            left: 10px !important;
            max-width: none !important;
        }
        
        .cart-notification {
            max-width: none !important;
            font-size: 13px !important;
            padding: 14px 16px !important;
        }
    }
    
    /* Для очень маленьких экранов */
    @media (max-width: 480px) {
        #cart-notifications {
            bottom: 5px !important;
            right: 5px !important;
            left: 5px !important;
        }
        
        .cart-notification {
            padding: 12px 14px !important;
            font-size: 12px !important;
        }
    }
`;
document.head.appendChild(style);

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    window.cartPageManager = new CartPageManager();
});