<?php

class ProductReview {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Создать новый отзыв
    public function create($productId, $userName, $rating, $text) {
        $sql = "INSERT INTO product_reviews (product_id, user_name, rating, text, status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', datetime('now'))";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$productId, $userName, $rating, $text]);
    }
    
    // Получить одобренные отзывы для товара
    public function getApprovedByProductId($productId) {
        $sql = "SELECT * FROM product_reviews WHERE product_id = ? AND status = 'approved' ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить все отзывы для модерации
    public function getAllPending() {
        $sql = "SELECT pr.*, p.name as product_name 
                FROM product_reviews pr 
                LEFT JOIN products p ON pr.product_id = p.id 
                WHERE pr.status = 'pending' 
                ORDER BY pr.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Одобрить отзыв
    public function approve($id) {
        $sql = "UPDATE product_reviews SET status = 'approved' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    // Отклонить отзыв
    public function reject($id) {
        $sql = "UPDATE product_reviews SET status = 'rejected' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    // Удалить отзыв
    public function delete($id) {
        $sql = "DELETE FROM product_reviews WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    // Получить отзыв по ID
    public function findById($id) {
        $sql = "SELECT pr.*, p.name as product_name 
                FROM product_reviews pr 
                LEFT JOIN products p ON pr.product_id = p.id 
                WHERE pr.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Получить статистику по отзывам
    public function getStatsByProductId($productId) {
        $sql = "SELECT 
                COUNT(*) as total_reviews,
                AVG(rating) as avg_rating,
                COUNT(CASE WHEN rating = 5 THEN 1 END) as five_stars,
                COUNT(CASE WHEN rating = 4 THEN 1 END) as four_stars,
                COUNT(CASE WHEN rating = 3 THEN 1 END) as three_stars,
                COUNT(CASE WHEN rating = 2 THEN 1 END) as two_stars,
                COUNT(CASE WHEN rating = 1 THEN 1 END) as one_stars
                FROM product_reviews 
                WHERE product_id = ? AND status = 'approved'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // В класс ProductReview добавь:

    // Получить все отзывы для модерации
    public function getAllForModeration() {
        $sql = "SELECT pr.*, p.name as product_name 
                FROM product_reviews pr 
                LEFT JOIN products p ON pr.product_id = p.id 
                ORDER BY 
                    CASE WHEN pr.status = 'pending' THEN 1 ELSE 2 END,
                    pr.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получить отзывы по статусу
    public function getByStatus($status) {
        $sql = "SELECT pr.*, p.name as product_name 
                FROM product_reviews pr 
                LEFT JOIN products p ON pr.product_id = p.id 
                WHERE pr.status = ? 
                ORDER BY pr.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Обновить отзыв
    public function update($id, $userName, $rating, $text, $status) {
        $sql = "UPDATE product_reviews 
                SET user_name = ?, rating = ?, text = ?, status = ?, updated_at = datetime('now')
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userName, $rating, $text, $status, $id]);
    }

    // Создать отзыв с указанным статусом (для админки)
    public function createWithStatus($productId, $userName, $rating, $text, $status = 'approved') {
        $sql = "INSERT INTO product_reviews (product_id, user_name, rating, text, status, created_at) 
                VALUES (?, ?, ?, ?, ?, datetime('now'))";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$productId, $userName, $rating, $text, $status]);
    }

}