<?php

class Router {
    private static $routes = [];

    public static function addRoute($method, $path, $controllerAction) {
        // Проверяем наличие первого слэша и корректируем путь
        $path = ltrim($path, '/');

        // Замещаем фигурные скобки на группирующие конструкции регулярного выражения
        $pathRegex = preg_replace_callback('/\{(\w+)\}/', function ($match) {
            return "(?P<{$match[1]}>[^/]+)";
        }, $path);

        // Добавляем проверку формата контроллера
        // if (!str_contains($controllerAction, '@')) {
        //     throw new InvalidArgumentException("Invalid controller action format: $controllerAction");
        // }

        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => "#^$pathRegex/?$#i",
            'action' => $controllerAction,
        ];
    }

    public static function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $parts = explode('?', $uri, 2);
        $path = trim($parts[0], '/');
        $queryString = isset($parts[1]) ? $parts[1] : '';

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path, $matches)) {
                list($controllerName, $actionMethod) = explode('@', $route['action']);

                require_once __DIR__ . "/controllers/" . $controllerName . ".php";
                $controllerInstance = new $controllerName();

                // Вырезаем нулевое совпадение (полный матч)
                unset($matches[0]);

                // Получаем GET-параметры из строки запроса
                $queryParams = [];
                if (!empty($queryString)) {
                    parse_str($queryString, $queryParams);
                }

                // Объединяем динамические параметры и GET-параметры
                $params = array_merge($matches, $queryParams);

                return call_user_func_array([$controllerInstance, $actionMethod], [$params]);
            }
        }

        render_error_page(404);
    }
}

// ===== ОСНОВНЫЕ МАРШРУТЫ =====
Router::addRoute('GET', '/', 'HomeController@index');
Router::addRoute('GET', '/catalog', 'CatalogController@index');
Router::addRoute('GET', '/about', 'AboutController@index');
Router::addRoute('GET', '/cart', 'CartController@index');
Router::addRoute('GET', '/checkout', 'CheckoutController@index');
Router::addRoute('GET', '/reviews', 'ReviewsController@index');
Router::addRoute('GET', '/sertificates', 'SertificateController@index');
Router::addRoute('GET', '/service', 'ServicesController@index');

// ===== API МАРШРУТЫ =====
Router::addRoute('GET', '/api/sertificates/load', 'SertificateController@load');
Router::addRoute('GET', '/api/reviews', 'ReviewsController@loadReviews');

// ===== ТОВАРЫ =====
Router::addRoute('GET', '/product/{id}', 'ProductController@index');
Router::addRoute('POST', '/product/{id}/add-review', 'ProductController@addReview');

// ===== КОРЗИНА =====
Router::addRoute('GET', '/cart/data', 'CartController@getCart');
Router::addRoute('POST', '/cart/update/{id}', 'CartController@addItem');
Router::addRoute('POST', '/cart/remove/{id}', 'CartController@removeItem');
Router::addRoute('POST', '/cart/clear', 'CartController@clearCart');
Router::addRoute('POST', '/cart/update-quantity', 'CartController@updateCartQuantity');
Router::addRoute('POST', '/cart/remove-from-cart', 'CartController@removeFromCart');
Router::addRoute('POST', '/cart/apply-promocode', 'CartController@applyPromocode');
Router::addRoute('GET', '/cart/checkout-data', 'CartController@getCheckoutData');

// ===== ОФОРМЛЕНИЕ ЗАКАЗА =====
Router::addRoute('POST', '/checkout/process', 'CheckoutController@processOrder');
Router::addRoute('POST', '/checkout/apply-promocode', 'CheckoutController@applyPromocode');
Router::addRoute('POST', '/checkout/remove-promocode', 'CheckoutController@removePromocode');

// ===== УСЛУГИ =====
Router::addRoute('POST', '/consultation/order', 'ServicesController@createOrder');

// ===== АДМИНКА - АВТОРИЗАЦИЯ =====
Router::addRoute('GET', '/admin/login', 'AdminAuthController@index');
Router::addRoute('POST', '/admin/login', 'AdminAuthController@login');
Router::addRoute('GET', '/admin/logout', 'AdminAuthController@logout');

// ===== АДМИНКА - ОСНОВНЫЕ РАЗДЕЛЫ =====
Router::addRoute('GET', '/admin', 'AdminController@index');
Router::addRoute('GET', '/admin/products', 'AdminController@products');
Router::addRoute('GET', '/admin/services', 'AdminController@services');
Router::addRoute('GET', '/admin/reviews', 'AdminController@reviews');
Router::addRoute('GET', '/admin/promocodes', 'AdminController@promocodes');
Router::addRoute('GET', '/admin/product-reviews', 'AdminController@productReviews');

// ===== АДМИНКА - ТОВАРЫ =====
Router::addRoute('POST', '/admin/products/save', 'AdminController@saveProduct');
Router::addRoute('POST', '/admin/products/delete', 'AdminController@deleteProduct');

// ===== АДМИНКА - УСЛУГИ =====
Router::addRoute('POST', '/admin/services/save', 'AdminController@saveService');
Router::addRoute('POST', '/admin/services/delete', 'AdminController@deleteService');
Router::addRoute('POST', '/admin/services/toggle-status', 'AdminController@toggleServiceStatus');

// ===== АДМИНКА - ПРОМОКОДЫ =====
Router::addRoute('POST', '/admin/promocodes/save', 'AdminController@savePromocode');
Router::addRoute('POST', '/admin/promocodes/delete', 'AdminController@deletePromocode');

// ===== АДМИНКА - ОТЗЫВЫ (СКРИНШОТЫ) =====
Router::addRoute('POST', '/admin/reviews/upload', 'AdminController@uploadReview');
Router::addRoute('POST', '/admin/reviews/delete', 'AdminController@deleteReview');

// ===== АДМИНКА - ТЕКСТОВЫЕ ОТЗЫВЫ К ТОВАРАМ =====
Router::addRoute('POST', '/admin/product-reviews/create', 'AdminController@createProductReview');
Router::addRoute('GET', '/admin/product-reviews/approve/{id}', 'AdminController@approveProductReview');
Router::addRoute('GET', '/admin/product-reviews/reject/{id}', 'AdminController@rejectProductReview');
Router::addRoute('GET', '/admin/product-reviews/delete/{id}', 'AdminController@deleteProductReview');

// После других маршрутов отзывов
Router::addRoute('GET', '/admin/product-reviews/edit/{id}', 'AdminController@editProductReview');
Router::addRoute('POST', '/admin/product-reviews/update', 'AdminController@updateProductReview');
// После других маршрутов заказов
Router::addRoute('GET', '/order/success/{id}', 'CheckoutController@orderSuccess');
// После других маршрутов корзины
Router::addRoute('POST', '/cart/clear-after-payment', 'CartController@clearAfterPayment');
Router::addRoute('GET', '/privacy-policy', 'PrivacyPolicyController@index');

Router::addRoute('GET', '/contacts', 'ContactsController@index');