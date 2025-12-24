// scripts/pages/admin.js
document.addEventListener('DOMContentLoaded', function() {
    // Мобильное меню
    const menuToggle = document.getElementById('menu-toggle');
    const menuClose = document.getElementById('menu-close');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuOverlay = document.getElementById('menu-overlay');

    function toggleMenu() {
        mobileMenu.classList.toggle('mobile-menu--open');
        menuOverlay.classList.toggle('mobile-menu__overlay--visible');
        document.body.style.overflow = mobileMenu.classList.contains('mobile-menu--open') ? 'hidden' : '';
    }

    if (menuToggle && menuClose && mobileMenu && menuOverlay) {
        menuToggle.addEventListener('click', toggleMenu);
        menuClose.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);
    }

    // Закрытие меню при клике на ссылку
    document.querySelectorAll('.mobile-menu__link').forEach(link => {
        link.addEventListener('click', toggleMenu);
    });

    // Уведомления (глобальная функция)
    window.showNotification = function(message, type = 'success') {
        // Удаляем существующие уведомления
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        notification.innerHTML = `
            <span class="notification__icon">${type === 'success' ? '✓' : '✗'}</span>
            <span>${message}</span>
            <button class="notification__close">✕</button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('notification--show');
        }, 100);

        notification.querySelector('.notification__close').addEventListener('click', () => {
            notification.classList.remove('notification--show');
            setTimeout(() => notification.remove(), 300);
        });

        setTimeout(() => {
            notification.classList.remove('notification--show');
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    };
});