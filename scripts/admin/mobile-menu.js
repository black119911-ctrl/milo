// scripts/admin/mobile-menu.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('mobile-menu.js загружен на странице:', window.location.pathname);
    
    const menuToggle = document.getElementById('menuToggle');
    const mobileNav = document.getElementById('mobileNav');
    const navClose = document.getElementById('navClose');
    const navOverlay = document.getElementById('navOverlay');
    
    if (!menuToggle || !mobileNav) {
        console.error('❌ Не найдены элементы меню!');
        return;
    }
    
    console.log('✅ Элементы меню найдены');
    
    // Открытие меню
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        console.log('🟢 Открываем меню');
        mobileNav.classList.toggle('open');
        if (navOverlay) navOverlay.classList.toggle('show');
        menuToggle.classList.toggle('active');
        document.body.style.overflow = document.body.style.overflow === 'hidden' ? '' : 'hidden';
    });
    
    // Закрытие меню
    if (navClose) {
        navClose.addEventListener('click', function() {
            console.log('🔴 Закрываем меню');
            closeMenu();
        });
    }
    
    if (navOverlay) {
        navOverlay.addEventListener('click', function() {
            console.log('🔴 Закрываем меню (клик по overlay)');
            closeMenu();
        });
    }
    
    // Закрытие по ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });
    
    function closeMenu() {
        mobileNav.classList.remove('open');
        if (navOverlay) navOverlay.classList.remove('show');
        menuToggle.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Swipe для мобильных
    let touchStartX = 0;
    
    mobileNav.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    mobileNav.addEventListener('touchend', function(e) {
        const touchEndX = e.changedTouches[0].screenX;
        const swipeDistance = touchEndX - touchStartX;
        
        if (swipeDistance < -50) {
            closeMenu();
        }
    }, { passive: true });
});