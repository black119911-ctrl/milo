<?php
// views/admin/services.php
$service = $service ?? null;
?>

<div class="page-header">
    <h1 class="page-header__title">Управление услугой</h1>
</div>

<div class="admin-consult__wrap">
    <div class="admin-title">
        <h2>Консультация</h2>
    </div>
    <form class="admin-consult" id="service-form" method="POST" action="/admin/services/save">
        <div class="admin-products__item">
            <div class="admin-products__item-edit">
                <div class="admin-products__item-edit-row admin-products__item-edit-row--column">
                    <label class="admin-products__item-edit-label">Название консультации</label>
                    <div class="admin-products__item-edit-input admin-products__item-edit-input--consult">
                        <input type="text" name="name" value="<?= htmlspecialchars($service['name'] ?? '') ?>"
                            placeholder="Уход за кожей Консультация" required>
                    </div>
                </div>

                <div class="admin-products__item-edit-row">
                    <label class="admin-products__item-edit-label">В наличии?</label>
                    <label class="checkbox-ios">
                        <input type="checkbox" name="is_stock" value="1"
                            <?= (($service['is_stock'] ?? '1') == '1') ? 'checked' : '' ?>>
                        <span class="checkbox-ios-switch"></span>
                    </label>
                </div>

                <div class="admin-products__item-edit-row">
                    <label class="admin-products__item-edit-label">Цена</label>
                    <div class="admin-products__item-edit-input">
                        <input type="number" name="price" value="<?= $service['price'] ?? '' ?>" placeholder="0" min="0"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn admin-consult__savebtn">
            <?= $service ? 'Обновить услугу' : 'Создать услугу' ?>
        </button>
    </form>
</div>

<style>
.checkbox-ios {
    display: inline-block;
    height: 28px;
    position: relative;
    width: 50px;
}

.checkbox-ios input {
    display: none;
}

.checkbox-ios-switch {
    background-color: #ccc;
    border-radius: 20px;
    bottom: 0;
    cursor: pointer;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    transition: .4s;
}

.checkbox-ios-switch:before {
    background-color: #fff;
    border-radius: 50%;
    bottom: 4px;
    content: "";
    height: 20px;
    left: 4px;
    position: absolute;
    transition: .4s;
    width: 20px;
}

.checkbox-ios input:checked+.checkbox-ios-switch {
    background-color: #4CAF50;
}

.checkbox-ios input:checked+.checkbox-ios-switch:before {
    transform: translateX(22px);
}

.admin-consult__wrap {
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.admin-title h2 {
    margin: 0 0 1.5rem 0;
    color: #333;
    font-size: 1.5rem;
    font-weight: 600;
}

.admin-consult {
    max-width: 600px;
}

.admin-products__item {
    margin-bottom: 1.5rem;
}

.admin-products__item-edit {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.admin-products__item-edit-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.admin-products__item-edit-row--column {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.admin-products__item-edit-label {
    font-weight: 500;
    color: #333;
    min-width: 160px;
}

.admin-products__item-edit-input {
    flex: 1;
}

.admin-products__item-edit-input input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.admin-products__item-edit-input input:focus {
    border-color: var(--primary-color);
    outline: none;
}

.admin-consult__savebtn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
    margin-top: 1rem;
}

.admin-consult__savebtn:hover {
    background: var(--primary-dark);
}

@media (max-width: 768px) {
    .admin-consult__wrap {
        padding: 1rem;
        margin: 1rem;
    }

    .admin-products__item-edit-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .admin-products__item-edit-label {
        min-width: auto;
    }
}
</style>

<script>
// Обработка формы
// Обработка формы
document.getElementById('service-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Сохранение...';
    submitBtn.disabled = true;

    // ✅ ПРОСТО ИСПОЛЬЗУЕМ FormData БЕЗ ПРЕОБРАЗОВАНИЙ
    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData // ← просто передаем FormData
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = '/admin/services';
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
</script>