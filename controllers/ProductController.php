<?php

require_once __DIR__.'/../models/Product.php';
require_once __DIR__.'/../models/ProductReview.php';
require_once 'BaseController.php';

class ProductController extends BaseController{
    public function index($params) {
        // Получаем ID товара из параметров
        $productId = $params['id'] ?? null;
        
        if (!$productId) {
            render_error_page(404);
            return;
        }

        // Проверяем аутентификацию для корзины
        $showBasket = $this->authenticate($params);
        
        // Загружаем модель товара
        $productModel = new Product($this->pdo);
        $product = $productModel->findByIdWithArticle($productId);

        // Если товар не найден - 404
        if (!$product) {
            render_error_page(404);
            return;
        }

        // Получаем корзину из сессии
        $cart = $_SESSION['cart'] ?? [];

        // Загружаем одобренные отзывы для товара
        $reviewModel = new ProductReview($this->pdo);
        $reviews = $reviewModel->getApprovedByProductId($productId);

        // Подготавливаем ресурсы для страницы
        $resources = [
            'styles' => ['/styles/pages/product/product.css'],
            'scripts' => ['/scripts/pages/product.js']
        ];

        // Рендерим страницу товара
        render_view('pages/product', [
            'product' => $product,
            'showBasket' => $showBasket,
            'resources' => $resources,
            'initialCartData' => $cart,
            'reviews' => $reviews
        ]);
    }

    public function addReview($params) {
        header('Content-Type: application/json');

        // Получаем ID товара из параметров
        $productId = $params['id'] ?? null;
        
        if (!$productId) {
            echo json_encode([
                'success' => false,
                'message' => 'Неверный ID товара'
            ]);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['user_name'] ?? '';
            $rating = (int) ($_POST['rating'] ?? 0);
            $text = $_POST['text'] ?? '';
            
            // Валидация данных
            if (empty(trim($userName)) || empty(trim($text)) || $rating < 1 || $rating > 5) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Заполните все поля правильно'
                ]);
                exit;
            }
            
            // Очищаем данные
            $userName = trim($userName);
            $text = trim($text);
            
            // Сохраняем отзыв
            $reviewModel = new ProductReview($this->pdo);
            $result = $reviewModel->create($productId, $userName, $rating, $text);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Спасибо! Ваш отзыв отправлен на модерацию'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ошибка при отправке отзыва'
                ]);
            }
            exit;
        }
        
        echo json_encode([
            'success' => false,
            'message' => 'Неверный метод запроса'
        ]);
        exit;
    }
}