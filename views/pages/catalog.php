<?php require_once 'views/partials/catalog.php' ?>

<script>
// Передаем данные корзины из PHP в JavaScript
window.initialCartData = <?= json_encode($cart ?? []) ?>;
console.log('Корзина на главной:', window.initialCartData);
</script>