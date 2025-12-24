<?php
// check_reviews.php
require_once 'database.php';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Проверяем существование таблицы
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='reviews'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "❌ Таблица 'reviews' не существует\n";
        
        // Создаем таблицу
        $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            src TEXT NOT NULL
        )");
        echo "✅ Таблица 'reviews' создана\n";
    } else {
        echo "✅ Таблица 'reviews' существует\n";
    }
    
    // Проверяем данные
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM reviews");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📊 Количество отзывов в базе: " . $count['count'] . "\n";
    
    if ($count['count'] == 0) {
        echo "⚠️ В таблице нет данных. Добавляем тестовые...\n";
        $this->addTestData($pdo);
    }
    
    // Показываем несколько записей
    $stmt = $pdo->query("SELECT * FROM reviews LIMIT 5");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📝 Примеры записей:\n";
    foreach ($reviews as $review) {
        echo " - ID: {$review['id']}, SRC: {$review['src']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}

function addTestData($pdo) {
    $testImages = [
        '/images/reviews/review1.jpg',
        '/images/reviews/review2.jpg', 
        '/images/reviews/review3.jpg',
        '/images/reviews/review4.jpg',
        '/images/reviews/review5.jpg'
    ];
    
    $stmt = $pdo->prepare("INSERT INTO reviews (src) VALUES (?)");
    
    foreach ($testImages as $image) {
        $stmt->execute([$image]);
    }
    
    echo "✅ Добавлено " . count($testImages) . " тестовых записей\n";
}