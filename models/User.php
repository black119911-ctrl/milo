<?php
require_once __DIR__ . '/../database.php'; // подключаем базу данных

class User {
    protected $table = 'users';

    public function all() {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM {$this->table}");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function findById($id) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}