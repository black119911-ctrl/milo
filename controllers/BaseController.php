<?php

require_once __DIR__ . '/../database.php'; // подключаем базу данных

abstract class BaseController {

    protected $pdo;

    public function __construct() {
        $this->pdo = getPdoConnection();
    }

    protected function authenticate($params) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        $token = isset($_SESSION['shop_token']) ? $_SESSION['shop_token'] : ($params['token'] ?? null);

        if (empty($token)) return false;
        
        $stmt = $this->pdo->prepare("SELECT shop_token FROM settings WHERE shop_token = :token");
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверяем, нашли ли токен в базе данных
        if ($result && !empty($result['shop_token']) && $result['shop_token'] === $token) {

            if (!isset($_SESSION['shop_token'])) {
                $_SESSION['shop_token'] = $token;
            }

            return true; // Токен валиден, возвращаем true

        } else {
            unset($_SESSION['shop_token']);
            return false; // Токен не найден или недействителен
        }

    }

    abstract public function index($params);
}