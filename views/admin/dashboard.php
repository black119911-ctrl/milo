<?php
// Получаем данные из контроллера
$stats = $stats ?? [];
$username = $_SESSION['admin_username'] ?? 'Администратор';

?>

<div class="dashboard-container">
    <!-- Хедер дашборда (без общего хедера, он в layout) -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1>Добро пожаловать, <?php echo htmlspecialchars($username); ?>!</h1>
            <p>Панель управления магазином MILOVAN4IK.RU</p>
        </div>
        <div class="admin-actions">
            <a href="/" class="action-btn" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                На сайт
            </a>
            <a href="/admin/logout" class="action-btn logout">
                <i class="fas fa-sign-out-alt"></i>
                Выйти
            </a>
        </div>
    </div>

    <!-- Карточки статистики -->
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo $stats['products'] ?? 0; ?></div>
                    <div class="stat-label">Всего товаров</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+3 сегодня</span>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo number_format(125400, 0, '.', ' '); ?> ₽</div>
                    <div class="stat-label">Общая выручка</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12% за месяц</span>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-header">
                <div>
                    <div class="stat-value">5</div>
                    <div class="stat-label">Ожидают обработки</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                <span>-2 с вчера</span>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-value">37</div>
                    <div class="stat-label">Выполнено</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+5 за неделю</span>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="content-grid">
        <!-- График -->
        <div class="chart-container" style="display:none">
            <div class="chart-header">
                <h2>Продажи за последние 7 дней</h2>
                <div class="chart-period">
                    <button class="period-btn active">Неделя</button>
                    <button class="period-btn">Месяц</button>
                    <button class="period-btn">Год</button>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Последние заказы -->
        <div class="recent-orders">
            <div class="orders-header">
                <h2>Последние заказы</h2>
                <a href="/admin/orders" class="view-all">
                    Все заказы
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Клиент</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-light);">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                                <p>Заказов пока нет</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Быстрые ссылки -->
    <div class="content-grid">
        <div class="quick-links">
            <a href="/admin/products" class="quick-link-card">
                <div class="link-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="link-title">Товары</div>
                <div class="link-desc">Управление каталогом</div>
            </a>

            <a href="/admin/orders" class="quick-link-card">
                <div class="link-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="link-title">Заказы</div>
                <div class="link-desc">Обработка заказов</div>
            </a>

            <a href="/admin/reviews" class="quick-link-card">
                <div class="link-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="link-title">Отзывы</div>
                <div class="link-desc">Модерация отзывов</div>
            </a>

            <a href="/admin/services" class="quick-link-card">
                <div class="link-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="link-title">Услуги</div>
                <div class="link-desc">Настройка услуг</div>
            </a>

            <a href="/admin/product-reviews" class="quick-link-card">
                <div class="link-icon">
                    <i class="fas fa-comment"></i>
                </div>
                <div class="link-title">Отзывы к товарам</div>
                <div class="link-desc">Модерация отзывов</div>
            </a>

            <a href="/admin/promocodes" class="quick-link-card">
                <div class="link-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="link-title">Промокоды</div>
                <div class="link-desc">Управление скидками</div>
            </a>
        </div>
    </div>

    <!-- Футер дашборда -->
    <div class="dashboard-footer">
        <p>Панель управления MILOVAN4IK.RU • <?php echo date('Y'); ?> •
            <span id="currentTime"></span>
        </p>
        <p style="margin-top: 8px; font-size: 13px; opacity: 0.8;">
            <i class="fas fa-server"></i>
            Версия системы: 2.1.0 •
            <i class="fas fa-user-shield"></i>
            Активность: <?php echo date('H:i'); ?>
        </p>
    </div>
</div>

<script>
// График продаж
document.addEventListener('DOMContentLoaded', function() {
    console.log('Дашборд загружен');

    // График продаж
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        const salesChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
                datasets: [{
                    label: 'Продажи (₽)',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                    backgroundColor: 'rgba(16, 94, 52, 0.1)',
                    borderColor: '#105E34',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#105E34',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#e2e8f0',
                        bodyColor: '#e2e8f0',
                        borderColor: '#334155',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return 'Сумма: ' + context.parsed.y.toLocaleString('ru-RU') + ' ₽';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(226, 232, 240, 0.2)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('ru-RU') + ' ₽';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(226, 232, 240, 0.2)'
                        }
                    }
                }
            }
        });

        // Переключение периода графика
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.period-btn').forEach(b => b.classList.remove(
                    'active'));
                this.classList.add('active');

                console.log('Выбран период:', this.textContent);
            });
        });
    }

    // Обновление времени в футере
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('ru-RU', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElement = document.getElementById('currentTime');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    updateTime();
    setInterval(updateTime, 1000);
});
</script>