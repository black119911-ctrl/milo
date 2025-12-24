// scripts/components/global-cart.js
class GlobalCartManager {
    constructor() {
        this.init();
    }

    init() {
        console.log('🔄 GlobalCartManager инициализирован');
        this.setupEventListeners();
        this.checkBasketElement();
    }

    setupEventListeners() {
        // Слушаем события обновления корзины
        document.addEventListener('cartUpdated', (event) => {
            console.log('🔄 Событие cartUpdated:', event.detail);
            this.updateBasketCount(event.detail.count);
        });
    }

    checkBasketElement() {
        // Проверяем что элемент корзины существует
        const basket = document.querySelector('.basket__product-qty');
        if (!basket) {
            console.warn('❌ Элемент корзины не найден');
        } else {
            console.log('✅ Элемент корзины найден');
        }
    }

    updateBasketCount(count) {
        // Ищем все элементы счетчика корзины
        const basketCounters = document.querySelectorAll('.basket__product-qty, #global-cart-count');
        
        if (basketCounters.length === 0) {
            console.error('❌ Не найдены элементы счетчика корзины');
            return;
        }

        basketCounters.forEach(counter => {
            const oldCount = parseInt(counter.textContent) || 0;
            counter.textContent = count;
            
            // Анимация при изменении
            if (oldCount !== count) {
                this.animateBasketCounter(counter, count, oldCount);
            }
        });
        
        console.log('✅ Обновлен счетчик корзины:', count);
    }

    animateBasketCounter(element, newCount, oldCount) {
        // Анимация пульсации
        element.style.transform = 'scale(1.4)';
        element.style.transition = 'all 0.3s ease';
        
        // Временное изменение цвета
        if (newCount > oldCount) {
            element.style.backgroundColor = '#28a745'; // зеленый при добавлении
        } else if (newCount < oldCount) {
            element.style.backgroundColor = '#dc3545'; // красный при удалении
        }
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
            // Возвращаем исходный цвет
            setTimeout(() => {
                element.style.backgroundColor = '';
            }, 1000);
        }, 300);
    }

    // Публичный метод для обновления извне
    updateCart(count) {
        this.updateBasketCount(count);
    }
}

// Автоматическая инициализация
document.addEventListener('DOMContentLoaded', () => {
    window.globalCartManager = new GlobalCartManager();
});