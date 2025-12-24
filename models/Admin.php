<?php
// models/Admin.php

class Admin {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

 
    public function createOrUpdateService($data) {
        // Проверяем, есть ли уже услуга
        $existing = $this->getService();

            // ОТЛАДКА
        error_log("🔄 createOrUpdateService called");
        error_log("📦 Data received: " . print_r($data, true));
        error_log("🔍 Existing service: " . print_r($existing, true));

        
        if ($existing) {
            // Обновляем существующую
            $stmt = $this->pdo->prepare("
                UPDATE services SET 
                name = ?, price = ?, is_stock = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $data['name'],
                $data['price'],
                $data['is_stock'],
                $existing['id']
            ]);
        } else {
            // Создаем новую
            $stmt = $this->pdo->prepare("
                INSERT INTO services (name, price, is_stock) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([
                $data['name'],
                $data['price'],
                $data['is_stock']
            ]);
        }
    }

    public function getService() {
        $stmt = $this->pdo->prepare("SELECT * FROM services LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Продукты
    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduct($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProduct($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (name, price, image, private_discount) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['image'],
            $data['private_discount'] ?? 0
        ]);
    }

    public function updateProduct($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE products SET 
            name = ?, price = ?, image = ?, private_discount = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['image'],
            $data['private_discount'] ?? 0,
            $id
        ]);
    }

    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Отзывы
    public function getAllReviews() {
        $stmt = $this->pdo->query("SELECT * FROM reviews ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReview($src) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (src) VALUES (?)");
        return $stmt->execute([$src]);
    }

    public function deleteReview($id) {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Статистика
    public function getStats() {
        $productsCount = $this->pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $reviewsCount = $this->pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        $servicesCount = $this->pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
        // $ordersCount = $this->pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

        return [
            'products' => $productsCount,
            'reviews' => $reviewsCount,
            'services' => $servicesCount,
            // 'orders' => $ordersCount
        ];
    }

    // Промокоды
    public function getPromocode() {
        $stmt = $this->pdo->query("SELECT * FROM promocodes LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrUpdatePromocode($code, $discount = 0) {
        // Проверяем, есть ли уже промокод
        $existing = $this->getPromocode();
        
        if ($existing) {
            // Обновляем существующий
            $stmt = $this->pdo->prepare("UPDATE promocodes SET promocode = ?, discount = ? WHERE id = ?");
            return $stmt->execute([$code, $discount, $existing['id']]);
        } else {
            // Создаем новый
            $stmt = $this->pdo->prepare("INSERT INTO promocodes (promocode, discount) VALUES (?, ?)");
            return $stmt->execute([$code, $discount]);
        }
    }

    public function deletePromocode() {
        $stmt = $this->pdo->prepare("DELETE FROM promocodes");
        return $stmt->execute();
    }

    public function getPromocodeByCode($code) {
        $stmt = $this->pdo->prepare("SELECT * FROM promocodes WHERE promocode = ? LIMIT 1");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrUpdateArticle($productId, $data) {
        // Проверяем, есть ли уже статья для этого продукта
        $existing = $this->getArticleByProductId($productId);
        
        if ($existing) {
            // Обновляем существующую статью
            $stmt = $this->pdo->prepare("
                UPDATE articles SET 
                art_description = ?, composition = ?, application = ?, recommendations = ?
                WHERE product_id = ?
            ");
            return $stmt->execute([
                $data['art_description'],
                $data['composition'],
                $data['application'],
                $data['recommendations'],
                $productId
            ]);
        } else {
            // Создаем новую статью
            $stmt = $this->pdo->prepare("
                INSERT INTO articles (product_id, art_description, composition, application, recommendations) 
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $productId,
                $data['art_description'],
                $data['composition'],
                $data['application'],
                $data['recommendations']
            ]);
        }
    }

    public function getArticleByProductId($productId) {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}