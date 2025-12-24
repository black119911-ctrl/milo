<?php

require_once __DIR__.'/../models/Product.php';
require_once 'BaseController.php';

class HomeController extends BaseController {
    public function index($params) {
        // Добавляем сессию для корзины
        session_start();
        
        $showBasket = $this->authenticate($params);
        $productModel = new Product($this->pdo);
        $products = $productModel->all();

        // Получаем корзину из сессии
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        $resources = [
            'styles' => [
                '/styles/pages/main/main.css',
                '/styles/components/cart.css' // добавляем стили корзины
            ],
            'scripts' => [
                '/scripts/components/firstScreen.js',
                '/scripts/components/catalog.js' // добавляем логику корзины
            ]
        ];

        return render_view('pages/home', [
            'products' => $products,
            'showBasket' => $showBasket,
            'resources' => $resources,
            'cart' => $cart // передаем корзину в шаблон
        ]);
    }
}