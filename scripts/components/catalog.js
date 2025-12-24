// scripts/components/catalog.js
class CartManager {
    constructor() {
        this.init();
    }

    init() {
        console.log('🔄 CartManager инициализирован');
        this.setupEventListeners();
        this.loadCartState();
    }

    setupEventListeners() {
        document.addEventListener('click', (event) => {
            this.handleClick(event);
        });
    }

    handleClick(event) {
        const target = event.target;
        
        // Кнопка "Добавить в корзину" - использует классы из catalog.php
        if (target.classList.contains('catalog__list-product-addcart') && !target.disabled) {
            const productId = this.getProductIdFromElement(target);
            if (productId) {
                this.addToCart(productId);
            }
            event.preventDefault();
            return;
        }

        // Кнопки количества - используют классы из catalog.php
        if (target.matches('.quantity-btn:first-child') && !target.disabled) {
            const productId = this.getProductIdFromElement(target);
            if (productId) {
                this.changeQuantity(productId, -1);
            }
            event.preventDefault();
            return;
        }

        if (target.matches('.quantity-btn:last-child')) {
            const productId = this.getProductIdFromElement(target);
            if (productId) {
                this.changeQuantity(productId, 1);
            }
            event.preventDefault();
            return;
        }
    }

    getProductIdFromElement(element) {
        const productElement = element.closest('.catalog__list-product');
        return productElement ? productElement.dataset.id : null;
    }

    async addToCart(productId) {
        await this.changeQuantity(productId, 1);
    }

    async changeQuantity(productId, change) {
        const productElement = this.getProductElement(productId);
        if (!productElement) return;

        try {
            this.setLoadingState(productElement, true);
            
            const response = await fetch(`/cart/update/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ change })
            });

            if (!response.ok) return;

            const result = await response.json();

            if (result.success) {
                this.updateProductUI(productId, result.quantity);
                this.updateGlobalBasket(result.cart_count);
                
                if (change > 0 || result.quantity === 0) {
                    this.showNotification(result.message, 'success');
                }
            } else {
                this.showNotification(result.message, 'error');
            }
        } catch (error) {
            console.error('Ошибка при обновлении корзины:', error);
            this.showNotification('Произошла ошибка при обновлении корзины', 'error');
        } finally {
            this.setLoadingState(productElement, false);
        }
    }

    getProductElement(productId) {
        return document.querySelector(`.catalog__list-product[data-id="${productId}"]`);
    }

    setLoadingState(element, isLoading) {
        element.classList.toggle('loading', isLoading);
    }

    updateProductUI(productId, quantity) {
        const productElement = this.getProductElement(productId);
        if (!productElement) return;

        const quantityControls = productElement.querySelector('.quantity-controls');
        const quantityDisplay = productElement.querySelector('.current-quantity');
        const addButton = productElement.querySelector('.catalog__list-product-addcart');

        if (!quantityControls || !quantityDisplay || !addButton) return;

        quantityDisplay.textContent = quantity;

        if (quantity > 0) {
            quantityControls.style.display = 'flex';
            addButton.style.display = 'none';
            
            const minusButton = quantityControls.querySelector('.quantity-btn:first-child');
            if (minusButton) {
                minusButton.disabled = quantity < 1;
            }
        } else {
            quantityControls.style.display = 'none';
            addButton.style.display = 'block';
        }
    }

    updateGlobalBasket(count) {
        document.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { count: count }
        }));
        
        if (window.globalCartManager) {
            window.globalCartManager.updateCart(count);
        }
    }

    loadCartState() {
        if (window.initialCartData) {
            console.log('📦 Загрузка корзины:', window.initialCartData);
            this.updateAllProducts(window.initialCartData);
        }
    }

    updateAllProducts(cartData) {
        const productElements = document.querySelectorAll('.catalog__list-product');
        
        productElements.forEach(productElement => {
            const productId = productElement.dataset.id;
            if (!productId) return;
            
            const quantity = cartData[productId] || 0;
            this.updateProductUI(productId, quantity);
        });
    }

    showNotification(message, type = 'success') {
        let notificationContainer = document.getElementById('cart-notifications');
        
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'cart-notifications';
            document.body.appendChild(notificationContainer);
        }

        const notification = document.createElement('div');
        notification.className = `cart-notification ${type === 'error' ? 'error' : ''}`;
        notification.textContent = message;
        notificationContainer.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CartManager();
});