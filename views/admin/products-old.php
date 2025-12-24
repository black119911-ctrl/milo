<?php
// views/admin/products.php
$products = $products ?? [];
$action = $action ?? 'list';
$product = $product ?? null;

?>

<style>
.rich-text-editor {
    border: 1px solid #ddd;
    padding: 10px;
    min-height: 200px;
    border-radius: 4px;
    background: white;
}
</style>

<!-- Подключение редактора -->
<!-- <script src="https://cdn.tiny.cloud/1/your-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->



<?php if ($action === 'list'): ?>
<div class="page-header">
    <h1 class="page-header__title">Управление товарами</h1>
    <div class="page-header__actions">
        <a href="?action=create" class="btn btn--primary">
            <span class="icon icon-plus"></span>
            Добавить товар
        </a>
    </div>
</div>

<?php if (empty($products)): ?>
<div class="text-center" style="padding: 3rem; color: var(--gray-color);">
    <p style="font-size: 1.25rem; margin-bottom: 1rem;">📦</p>
    <h3>Товаров пока нет</h3>
    <p>Добавьте первый товар, чтобы начать</p>
    <a href="?action=create" class="btn btn--primary mt-2">Добавить товар</a>
</div>
<?php else: ?>
<!-- Таблица для планшетов и десктопов -->
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
                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjRjBGMEYwIi8+CjxwYXRoIGQ9Ik0zMCAyMEgzMlYyNkgzMFYyMFpNMjUgMjBIMjdWMjZIMjVWMjBaTTIwIDIwSDIyVjI2SDIwVjIwWiIgZmlsbD0iIzk5OTk5OSIvPgo8L3N2Zz4K'">
                </td>
                <td>
                    <div style="font-weight: 600;"><?= htmlspecialchars($product['name']) ?></div>
                    <div style="font-size: 0.8rem; color: var(--gray-color);">ID: <?= $product['id'] ?></div>
                </td>
                <td style="font-weight: 600; color: var(--primary-color);">
                    <?= number_format($product['price'], 0, '', ' ') ?> ₽
                </td>
                <td>
                    <?php if (($product['private_discount'] ?? 0) > 0): ?>
                    <span style="color: var(--success-color); font-weight: 600;">
                        <?= $product['private_discount'] ?>%
                    </span>
                    <?php else: ?>
                    <span style="color: var(--gray-color);">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <a href="?action=edit&id=<?= $product['id'] ?>" class="btn btn--success btn--sm">
                            <span class="icon icon-edit"></span>
                            Редакт.
                        </a>
                        <button onclick="deleteProduct(<?= $product['id'] ?>)" class="btn btn--danger btn--sm">
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
        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-card__image"
            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjRjBGMEYwIi8+CjxwYXRoIGQ9Ik00MCA0MEg0NFY0NEg0MFY0MFpNMzUgNDBIMzdWNDRIMzVWNDBaTTMwIDQwSDMyVjQ0SDMwVjQwWiIgZmlsbD0iIzk5OTk5OSIvPgo8L3N2Zz4K'">
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
<a href="?action=create" class="btn btn--primary btn--fab">
    <span class="icon icon-plus" style="font-size: 1.25rem;"></span>
</a>

<script>
// Удаление товара
async function deleteProduct(id) {
    if (!confirm('Удалить этот товар?')) return;

    try {
        const response = await fetch('/admin/products/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Товар удален', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Ошибка: ' + result.message, 'error');
        }
    } catch (error) {
        showNotification('Ошибка при удалении', 'error');
    }
}
</script>
<?php endif; ?>

<?php elseif ($action === 'create' || $action === 'edit'): ?>
<!-- Форма редактирования/создания -->
<div class="page-header">
    <h1 class="page-header__title">
        <?= $action === 'create' ? 'Добавить товар' : 'Редактировать товар' ?>
    </h1>
    <a href="/admin/products" class="btn btn--secondary">
        ← Назад
    </a>
</div>

<div class="form-container">
    <form id="product-form" method="POST" enctype="multipart/form-data"
        action="/admin/products/save<?= $action === 'edit' ? '?id=' . $product['id'] : '' ?>">
        <?php if ($action === 'edit'): ?>
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Название товара *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                placeholder="Введите название товара" required>
        </div>

        <div class="form-group">
            <label for="price">Цена *</label>
            <input type="number" id="price" name="price" value="<?= $product['price'] ?? '' ?>" placeholder="0" min="0"
                step="0.01" required>
        </div>

        <div class="form-group">
            <label for="private_discount">Клубная скидка (%)</label>
            <input type="number" id="private_discount" name="private_discount"
                value="<?= $product['private_discount'] ?? 0 ?>" placeholder="0" min="0" max="100" step="1">
            <small style="color: #666; font-size: 0.875rem;">
                Скидка для участников клуба. Применяется только при вводе промокода.
            </small>
        </div>

        <div class="form-group">
            <label for="image">Изображение товара</label>
            <input type="file" id="image" name="image" accept="image/*">

            <?php if ($action === 'edit' && isset($product['image']) && !empty($product['image'])): ?>
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['image']) ?>">
            <div style="margin-top: 0.5rem;">
                <p style="font-size: 0.875rem; color: #666; margin-bottom: 0.5rem;">Текущее изображение:</p>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Current image"
                    style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label>Описание (статья)</label>
            <div class="toolbar">
                <button type="button" onclick="formatText('bold', 'art_description')"><b>B</b></button>
                <button type="button" onclick="formatText('italic', 'art_description')"><i>I</i></button>
                <button type="button" onclick="formatText('underline', 'art_description')"><u>U</u></button>
                <button type="button" onclick="insertTag('<h2>', '</h2>', 'art_description')">H2</button>
                <button type="button" onclick="insertTag('<p>', '</p>', 'art_description')">P</button>
            </div>
            <div class="rich-editor" contenteditable="true" id="art_description_editor">
                <?= $product['article_description'] ?? '' ?>
            </div>
            <textarea name="art_description" id="art_description" style="display:none;"></textarea>
        </div>

        <div class="form-group">
            <label>Состав</label>
            <div class="toolbar">
                <button type="button" onclick="formatText('bold', 'composition')"><b>B</b></button>
                <button type="button" onclick="formatText('italic', 'composition')"><i>I</i></button>
                <button type="button" onclick="formatText('underline', 'composition')"><u>U</u></button>
                <button type="button" onclick="insertTag('<h2>', '</h2>', 'composition')">H2</button>
                <button type="button" onclick="insertTag('<p>', '</p>', 'composition')">P</button>
            </div>
            <div class="rich-editor" contenteditable="true" id="composition_editor">
                <?= $product['article_composition'] ?? '' ?>
            </div>
            <textarea name="composition" id="composition" style="display:none;"></textarea>
        </div>

        <div class="form-group">
            <label>Применение</label>
            <div class="toolbar">
                <button type="button" onclick="formatText('bold', 'application')"><b>B</b></button>
                <button type="button" onclick="formatText('italic', 'application')"><i>I</i></button>
                <button type="button" onclick="formatText('underline', 'application')"><u>U</u></button>
                <button type="button" onclick="insertTag('<h2>', '</h2>', 'application')">H2</button>
                <button type="button" onclick="insertTag('<p>', '</p>', 'application')">P</button>
            </div>
            <div class="rich-editor" contenteditable="true" id="application_editor">
                <?= $product['article_application'] ?? '' ?>
            </div>
            <textarea name="application" id="application" style="display:none;"></textarea>
        </div>

        <div class="form-group">
            <label>Рекомендации</label>
            <div class="toolbar">
                <button type="button" onclick="formatText('bold', 'recommendations')"><b>B</b></button>
                <button type="button" onclick="formatText('italic', 'recommendations')"><i>I</i></button>
                <button type="button" onclick="formatText('underline', 'recommendations')"><u>U</u></button>
                <button type="button" onclick="insertTag('<h2>', '</h2>', 'recommendations')">H2</button>
                <button type="button" onclick="insertTag('<p>', '</p>', 'recommendations')">P</button>
            </div>
            <div class="rich-editor" contenteditable="true" id="recommendations_editor">
                <?= $product['article_recommendations'] ?? '' ?>
            </div>
            <textarea name="recommendations" id="recommendations" style="display:none;"></textarea>
        </div>

        <script>
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
                if (range.commonAncestorContainer.parentNode === editor || editor.contains(range
                        .commonAncestorContainer)) {
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
        document.getElementById('product-form').addEventListener('submit', function() {
            document.getElementById('art_description').value =
                document.getElementById('art_description_editor').innerHTML;
            document.getElementById('composition').value =
                document.getElementById('composition_editor').innerHTML;
            document.getElementById('application').value =
                document.getElementById('application_editor').innerHTML;
            document.getElementById('recommendations').value =
                document.getElementById('recommendations_editor').innerHTML;
        });
        </script>

        <style>
        .toolbar {
            margin-bottom: 5px;
        }

        .toolbar button {
            padding: 5px 10px;
            margin-right: 5px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
        }

        .rich-editor {
            border: 1px solid #ddd;
            padding: 10px;
            min-height: 200px;
            border-radius: 4px;
            background: white;
            margin-bottom: 1rem;
        }
        </style>



        <div class="form-actions">
            <button type="submit" class="btn btn--primary btn--block">
                <?= $action === 'create' ? 'Создать товар' : 'Сохранить изменения' ?>
            </button>
            <a href="/admin/products" class="btn btn--secondary btn--block">
                Отмена
            </a>
        </div>
    </form>
</div>

<script>
// Обработка формы
document.getElementById('product-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Сохранение...';
    submitBtn.disabled = true;

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = '/admin/products';
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
<?php endif; ?>