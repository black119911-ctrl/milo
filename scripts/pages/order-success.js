// scripts/pages/order-success.js
document.addEventListener('DOMContentLoaded', function() {
    // Дополнительная очистка на клиенте
    localStorage.removeItem('cart');
    localStorage.removeItem('promocode');
    
    // Показываем анимацию успеха
    const successIcon = document.querySelector('.success-icon');
    if (successIcon) {
        successIcon.style.animation = 'bounce 0.6s ease-in-out';
    }
    
    console.log('✅ Заказ успешно завершен, корзина очищена');
});