<?php
// views/admin/products.php
$products = $products ?? [];
$action = $action ?? 'list';
$product = $product ?? null;
?>

<!-- Основные стили для страницы товаров -->
<style>
/* Стили для страницы товаров */
.products-page {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Заголовок страницы */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding-bottom: 25px;
    border-bottom: 2px solid var(--border-color);
}

.page-header__title {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary-green);
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.page-header__subtitle {
    color: var(--text-light);
    font-size: 16px;
}

/* Карточки товаров для мобильных */
.products-grid {
    display: none;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.product-card {
    background: var(--card-bg);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-green);
}

.product-card__image {
    /* width: 100%; */
    height: 200px;
    object-fit: cover;
    background: var(--light-bg);
}

.product-card__content {
    padding: 20px;
}

.product-card__name {
    font-weight: 600;
    font-size: 18px;
    color: var(--text-dark);
    margin-bottom: 10px;
    line-height: 1.4;
}

.product-card__meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.product-card__price {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-green);
}

.product-card__id {
    font-size: 14px;
    color: var(--text-light);
    background: var(--light-bg);
    padding: 4px 10px;
    border-radius: 20px;
}

.product-card__actions {
    display: flex;
    gap: 10px;
}

/* Таблица товаров для десктопов */
.table-container {
    background: var(--card-bg);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    margin-bottom: 40px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 20px;
    color: white;
    font-weight: 600;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tr:hover {
    background: rgba(16, 94, 52, 0.05);
}

.product-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

/* Форма редактирования */
.form-container {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 40px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    max-width: 1000px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 30px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 16px;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="file"],
.form-group textarea {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s;
    background: var(--light-bg);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(16, 94, 52, 0.1);
}

.form-group small {
    display: block;
    margin-top: 8px;
    color: var(--text-light);
    font-size: 14px;
    line-height: 1.5;
}

/* Кнопки */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 28px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    min-height: 52px;
}

.btn--primary {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
    color: white;
    border: 2px solid transparent;
}

.btn--primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, var(--primary-dark) 0%, #0a3c1f 100%);
}

.btn--secondary {
    background: var(--light-bg);
    color: var(--text-dark);
    border: 2px solid var(--border-color);
}

.btn--secondary:hover {
    border-color: var(--primary-green);
    color: var(--primary-green);
}

.btn--success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn--danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn--sm {
    padding: 10px 20px;
    font-size: 14px;
    min-height: 44px;
}

.btn--block {
    width: 100%;
}

.btn--fab {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    padding: 0;
    box-shadow: var(--shadow-lg);
    z-index: 1000;
}

/* Тулбар редактора */
.toolbar {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    padding: 15px;
    background: var(--light-bg);
    border-radius: 12px;
    border: 1px solid var(--border-color);
    flex-wrap: wrap;
}

.toolbar button {
    padding: 10px 16px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: white;
    color: var(--text-dark);
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    min-height: 44px;
}

.toolbar button:hover {
    border-color: var(--primary-green);
    color: var(--primary-green);
    background: rgba(16, 94, 52, 0.05);
}

/* Богатый текстовый редактор */
.rich-editor {
    border: 2px solid var(--border-color);
    padding: 20px;
    min-height: 300px;
    border-radius: 12px;
    background: white;
    margin-bottom: 1rem;
    font-size: 16px;
    line-height: 1.6;
    outline: none;
    transition: border-color 0.3s;
}

.rich-editor:focus {
    border-color: var(--primary-green);
}

.rich-editor h2 {
    color: var(--primary-green);
    margin: 20px 0 10px 0;
    font-size: 24px;
    font-weight: 700;
}

.rich-editor p {
    margin-bottom: 15px;
}

.rich-editor strong {
    color: var(--text-dark);
    font-weight: 700;
}

.rich-editor em {
    color: var(--text-light);
    font-style: italic;
}

/* Иконки */
.icon {
    font-size: 20px;
    line-height: 1;
}

.icon-plus::before { content: "+"; }
.icon-edit::before { content: "✏️"; }
.icon-delete::before { content: "🗑️"; }

/* Адаптивность */
@media (max-width: 768px) {
    .products-page {
        padding: 15px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }
    
    .table-container {
        display: none;
    }
    
    .products-grid {
        display: grid;
    }
    
    .form-container {
        padding: 25px;
    }
    
    .btn--fab {
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
    }
}

@media (min-width: 769px) {
    .products-grid {
        display: none;
    }
    
    .table-container {
        display: block;
    }
}

/* Действия таблицы */
.actions-cell {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Скелетон загрузки */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 12px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Сообщение о пустом списке */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-light);
}

.empty-state__icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state__title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-dark);
}

.empty-state__description {
    font-size: 16px;
    margin-bottom: 30px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

/* Секция с существующим изображением */
.existing-image {
    margin-top: 15px;
}

.existing-image p {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 10px;
}

.existing-image img {
    max-width: 300px;
    height: auto;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 10px;
    background: white;
}

/* Форма действий */
.form-actions {
    display: flex;
    gap: 20px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--border-color);
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}

/* Тёмная тема */
@media (prefers-color-scheme: dark) {
    .product-card,
    .table-container,
    .form-container {
        background: #1e293b;
        border-color: #334155;
    }
    
    .rich-editor {
        background: #1e293b;
        color: #e2e8f0;
        border-color: #334155;
    }
    
    .toolbar {
        background: #0f172a;
        border-color: #334155;
    }
    
    .toolbar button {
        background: #1e293b;
        color: #e2e8f0;
        border-color: #334155;
    }
    
    .existing-image img {
        background: #1e293b;
        border-color: #334155;
    }
}
</style>

<div class="products-page">
    <?php if ($action === 'list'): ?>
    <!-- Список товаров -->
    <div class="page-header">
        <div>
            <h1 class="page-header__title">📦 Управление товарами</h1>
            <p class="page-header__subtitle">Всего товаров: <?= count($products) ?></p>
        </div>
        <div>
            <a href="?action=create" class="btn btn--primary">
                <span class="icon icon-plus"></span>
                Добавить товар
            </a>
        </div>
    </div>

    <?php if (empty($products)): ?>
    <!-- Состояние пустого списка -->
    <div class="empty-state">
        <div class="empty-state__icon">📦</div>
        <h2 class="empty-state__title">Товаров пока нет</h2>
        <p class="empty-state__description">Добавьте свой первый товар, чтобы начать продажи в магазине</p>
        <a href="?action=create" class="btn btn--primary">Добавить первый товар</a>
    </div>
    <?php else: ?>
    <!-- Таблица для десктопа -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Клубная скидка</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                            class="product-thumb"
                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjMTA1RTM0IiBvcGFjaXR5PSIwLjEiLz4KPHBhdGggZD0iTTMwIDIwSDMyVjI2SDMwVjIwWk0yNSAyMEgyN1YyNkgyNVYyMFpNMjAgMjBIMjJWMjZIMjBWMjBaIiBmaWxsPSIjMTA1RTM0IiBvcGFjaXR5PSIwLjUiLz4KPC9zdmc+'">
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-dark); font-size: 16px;">
                            <?= htmlspecialchars($product['name']) ?>
                        </div>
                        <div style="font-size: 14px; color: var(--text-light); margin-top: 4px;">
                            ID: <?= $product['id'] ?>
                        </div>
                    </td>
                    <td style="font-weight: 700; color: var(--primary-green); font-size: 18px;">
                        <?= number_format($product['price'], 0, '', ' ') ?> ₽
                    </td>
                    <td>
                        <?php if (($product['private_discount'] ?? 0) > 0): ?>
                        <span style="background: rgba(16, 94, 52, 0.1); color: var(--primary-green); 
                              padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                            -<?= $product['private_discount'] ?>%
                        </span>
                        <?php else: ?>
                        <span style="color: var(--text-light);">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="?action=edit&id=<?= $product['id'] ?>" class="btn btn--success btn--sm">
                                <span class="icon icon-edit"></span>
                                Редактировать
                            </a>
                            <button onclick="deleteProduct(<?= $product['id'] ?>)" 
                                    class="btn btn--danger btn--sm">
                                <span class="icon icon-delete"></span>
                                Удалить
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Карточки для мобильных -->
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                 class="product-card__image"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjMTA1RTM0IiBvcGFjaXR5PSIwLjEiLz4KPHBhdGggZD0iTTgwIDgwSDg0VjkwSDgwVjgwWk02NSA4MEg2N1Y5MEg2NVY4MFpNNjAgODBINjJWOTBINjBWODBaIiBmaWxsPSIjMTA1RTM0IiBvcGFjaXR5PSIwLjUiLz4KPC9zdmc+'">
            <div class="product-card__content">
                <div class="product-card__name"><?= htmlspecialchars($product['name']) ?></div>
                <div class="product-card__meta">
                    <div class="product-card__price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
                    <div class="product-card__id">ID: <?= $product['id'] ?></div>
                </div>
                <div class="product-card__actions">
                    <a href="?action=edit&id=<?= $product['id'] ?>" class="btn btn--success btn--sm">
                        <span class="icon icon-edit"></span>
                        Редакт.
                    </a>
                    <button onclick="deleteProduct(<?= $product['id'] ?>)" class="btn btn--danger btn--sm">
                        <span class="icon icon-delete"></span>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- FAB для мобильных -->
    <a href="?action=create" class="btn btn--primary btn--fab" title="Добавить товар">
        <span class="icon icon-plus" style="font-size: 1.5rem;"></span>
    </a>
    <?php endif; ?>

    <?php elseif ($action === 'create' || $action === 'edit'): ?>
    <!-- Форма редактирования/создания -->
    <div class="page-header">
        <div>
            <h1 class="page-header__title">
                <?php if ($action === 'create'): ?>
                🆕 Добавить новый товар
                <?php else: ?>
                ✏️ Редактировать товар
                <?php endif; ?>
            </h1>
            <p class="page-header__subtitle">
                <?php if ($action === 'create'): ?>
                Заполните информацию о новом товаре
                <?php else: ?>
                Внесите изменения в информацию о товаре
                <?php endif; ?>
            </p>
        </div>
        <a href="/admin/products" class="btn btn--secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="form-container">
        <form id="product-form" method="POST" enctype="multipart/form-data"
            action="/admin/products/save<?= $action === 'edit' ? '?id=' . $product['id'] : '' ?>">
            <?php if ($action === 'edit'): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <?php endif; ?>

            <!-- Основная информация -->
            <div class="form-section">
                <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 20px;">Основная информация</h3>
                <div class="form-group">
                    <label for="name">Название товара *</label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                           placeholder="Например: Натуральный шампунь для волос" required>
                </div>

                <div class="form-group">
                    <label for="price">Цена (в рублях) *</label>
                    <input type="number" id="price" name="price" value="<?= $product['price'] ?? '' ?>" 
                           placeholder="1990" min="0" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="private_discount">Клубная скидка (%)</label>
                    <input type="number" id="private_discount" name="private_discount"
                           value="<?= $product['private_discount'] ?? 0 ?>" placeholder="0" min="0" max="100" step="1">
                    <small>Специальная скидка для участников клуба. Применяется автоматически при вводе промокода</small>
                </div>
            </div>

            <!-- Изображение -->
            <div class="form-section">
                <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 20px;">Изображение товара</h3>
                <div class="form-group">
                    <label for="image">Загрузить изображение</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small>Рекомендуемый размер: 800×800px. Форматы: JPG, PNG, WebP</small>

                    <?php if ($action === 'edit' && isset($product['image']) && !empty($product['image'])): ?>
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['image']) ?>">
                    <div class="existing-image">
                        <p>Текущее изображение:</p>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Текущее изображение товара">
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Описание и характеристики -->
            <div class="form-section">
                <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 20px;">Описание и характеристики</h3>

                <!-- Описание (статья) -->
                <div class="form-group">
                    <label>Описание товара</label>
                    <div class="toolbar">
                        <button type="button" onclick="formatText('bold', 'art_description')"><b>Жирный</b></button>
                        <button type="button" onclick="formatText('italic', 'art_description')"><i>Курсив</i></button>
                        <button type="button" onclick="insertTag('<h2>', '</h2>', 'art_description')">Заголовок</button>
                        <button type="button" onclick="insertTag('<p>', '</p>', 'art_description')">Параграф</button>
                    </div>
                    <div class="rich-editor" contenteditable="true" id="art_description_editor" 
                         onclick="setActiveEditor('art_description')">
                        <?= $product['article_description'] ?? '' ?>
                    </div>
                    <textarea name="art_description" id="art_description" style="display:none;"></textarea>
                </div>

                <!-- Состав -->
                <div class="form-group">
                    <label>Состав продукта</label>
                    <div class="toolbar">
                        <button type="button" onclick="formatText('bold', 'composition')"><b>Жирный</b></button>
                        <button type="button" onclick="formatText('italic', 'composition')"><i>Курсив</i></button>
                        <button type="button" onclick="insertTag('<h2>', '</h2>', 'composition')">Заголовок</button>
                    </div>
                    <div class="rich-editor" contenteditable="true" id="composition_editor"
                         onclick="setActiveEditor('composition')">
                        <?= $product['article_composition'] ?? '' ?>
                    </div>
                    <textarea name="composition" id="composition" style="display:none;"></textarea>
                </div>

                <!-- Применение -->
                <div class="form-group">
                    <label>Способ применения</label>
                    <div class="toolbar">
                        <button type="button" onclick="formatText('bold', 'application')"><b>Жирный</b></button>
                        <button type="button" onclick="formatText('italic', 'application')"><i>Курсив</i></button>
                        <button type="button" onclick="insertTag('<h2>', '</h2>', 'application')">Заголовок</button>
                    </div>
                    <div class="rich-editor" contenteditable="true" id="application_editor"
                         onclick="setActiveEditor('application')">
                        <?= $product['article_application'] ?? '' ?>
                    </div>
                    <textarea name="application" id="application" style="display:none;"></textarea>
                </div>

                <!-- Рекомендации -->
                <div class="form-group">
                    <label>Рекомендации</label>
                    <div class="toolbar">
                        <button type="button" onclick="formatText('bold', 'recommendations')"><b>Жирный</b></button>
                        <button type="button" onclick="formatText('italic', 'recommendations')"><i>Курсив</i></button>
                        <button type="button" onclick="insertTag('<h2>', '</h2>', 'recommendations')">Заголовок</button>
                    </div>
                    <div class="rich-editor" contenteditable="true" id="recommendations_editor"
                         onclick="setActiveEditor('recommendations')">
                        <?= $product['article_recommendations'] ?? '' ?>
                    </div>
                    <textarea name="recommendations" id="recommendations" style="display:none;"></textarea>
                </div>
            </div>

            <!-- Действия формы -->
            <div class="form-actions">
                <button type="submit" class="btn btn--primary btn--block">
                    <?php if ($action === 'create'): ?>
                    Создать товар
                    <?php else: ?>
                    Сохранить изменения
                    <?php endif; ?>
                </button>
                <a href="/admin/products" class="btn btn--secondary btn--block">
                    Отмена
                </a>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
// Удаление товара
async function deleteProduct(id) {
    if (!confirm('Вы уверены, что хотите удалить этот товар?')) return;

    try {
        const response = await fetch('/admin/products/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Товар успешно удалён', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Ошибка: ' + (result.message || 'неизвестная ошибка'), 'error');
        }
    } catch (error) {
        showNotification('Ошибка при удалении товара', 'error');
        console.error('Delete error:', error);
    }
}

// Управление редактором
let activeEditor = 'art_description';

function setActiveEditor(editorId) {
    activeEditor = editorId;
}

function formatText(command, editorId) {
    setActiveEditor(editorId);
    document.execCommand(command, false, null);
}

function insertTag(startTag, endTag, editorId) {
    setActiveEditor(editorId);
    const editor = document.getElementById(editorId + '_editor');
    const selection = window.getSelection();

    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        if (editor.contains(range.commonAncestorContainer)) {
            const selectedText = range.toString();
            const newHtml = startTag + selectedText + endTag;
            range.deleteContents();
            range.insertNode(document.createRange().createContextualFragment(newHtml));
        }
    }
}

// Устанавливаем активный редактор при клике
document.querySelectorAll('.rich-editor').forEach(editor => {
    editor.addEventListener('click', function() {
        activeEditor = this.id.replace('_editor', '');
    });
});

// Копирует HTML во все hidden textarea перед отправкой
document.getElementById('product-form')?.addEventListener('submit', function() {
    document.getElementById('art_description').value =
        document.getElementById('art_description_editor').innerHTML;
    document.getElementById('composition').value =
        document.getElementById('composition_editor').innerHTML;
    document.getElementById('application').value =
        document.getElementById('application_editor').innerHTML;
    document.getElementById('recommendations').value =
        document.getElementById('recommendations_editor').innerHTML;
});

// Обработка формы
document.getElementById('product-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Сохранение...';
    submitBtn.disabled = true;

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message || 'Товар успешно сохранён', 'success');
            setTimeout(() => {
                window.location.href = '/admin/products';
            }, 1500);
        } else {
            showNotification('Ошибка: ' + (result.message || 'неизвестная ошибка'), 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Ошибка при сохранении товара', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        console.error('Form submit error:', error);
    }
});

// Функция для отображения уведомлений
function showNotification(message, type = 'info') {
    // Используйте вашу существующую систему уведомлений
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        alert(message);
    }
}
</script>