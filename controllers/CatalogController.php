<?php
require_once __DIR__.'/../models/Product.php';
require_once 'BaseController.php';

class CatalogController extends BaseController {
    public function index($params) {
        // Начинаем сессию для получения корзины
        session_start();
        
        $showBasket = $this->authenticate($params);
        $productModel = new Product($this->pdo);
        
        // Получаем только товары в наличии (stock = 1)
        $products = $productModel->getAllAvailable();

        // Получаем корзину из сессии
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        $resources = [
            'styles' => ['/styles/pages/catalog/catalog.css'],
            'scripts' => ['/scripts/components/catalog.js']
        ];

        return render_view('pages/catalog', [
            'products' => $products,
            'showBasket' => $showBasket,
            'resources' => $resources,
            'cart' => $cart // добавляем корзину в данные шаблона
        ]); 
    }
}