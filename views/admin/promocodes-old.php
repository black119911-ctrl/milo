<?php
// views/admin/promocodes.php
$promocode = $promocode ?? null;
?>

<div class="page-header">
    <h1 class="page-header__title">Управление промокодами</h1>
</div>

<div class="admin-consult__wrap">
    <div class="admin-title">
        <h2>Скидки и промокод</h2>
    </div>
    <form class="admin-consult" id="promocode-form" method="POST" action="/admin/promocodes/save">
        <div class="admin-products__item">
            <div class="admin-products__item-edit">
                <div class="admin-products__item-edit-row admin-products__item-edit-row--column">
                    <label class="admin-discount__row admin-discount__label">Промокод (только латиница и цифры)</label>
                    <div class="admin-products__item-edit-input">
                        <input type="text" name="promocode"
                            value="<?= htmlspecialchars($promocode['promocode'] ?? '') ?>" class="admin-discount__row"
                            autocomplete="off" required title="Только латиница, цифры и специальные символы">
                    </div>
                </div>

            </div>

            <button type="submit" class="btn admin-consult__savebtn" id="discount-savebtn">
                Сохранить изменения
            </button>

            <?php if ($promocode): ?>
            <button type="button" onclick="deletePromocode()" class="btn btn--danger"
                style="margin-top: 10px; width: 100%;">
                Удалить промокод
            </button>
            <?php endif; ?>
    </form>
</div>

<script>
// Обработка формы промокода
document.getElementById('promocode-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Сохранение...';
    submitBtn.disabled = true;

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = '/admin/promocodes';
            }, 1500);
        } else {
            showNotification('Ошибка: ' + result.message, 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Ошибка при сохранении', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});

// Удаление промокода
async function deletePromocode() {
    if (!confirm('Удалить промокод?')) return;

    try {
        const response = await fetch('/admin/promocodes/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Промокод удален', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Ошибка: ' + result.message, 'error');
        }
    } catch (error) {
        showNotification('Ошибка при удалении', 'error');
    }
}
</script>