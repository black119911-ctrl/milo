<?php
// models/Review.php

class Review {
    protected $table = 'reviews';
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPaginated($page = 1, $perPage = 12) {
        try {
            $offset = ($page - 1) * $perPage;
            
            $stmt = $this->pdo->prepare("
                SELECT id, src 
                FROM {$this->table} 
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getTotal() {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table}");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    // В твоей модели для обычных отзывов добавь метод:
    public function getAllForModeration() {
        $sql = "SELECT * FROM reviews"; // замени reviews на твою таблицу
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}