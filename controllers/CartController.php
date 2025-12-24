<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php'; // Если есть модель заказа
require_once __DIR__ . '/../models/Admin.php'; // Для работы с услугами


class CartController extends BaseController {

    protected $productModel;
    protected $adminModel;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/Product.php';
        $this->productModel = new Product($this->pdo);
        $this->adminModel = new Admin($this->pdo);
    }

    public function index($params) {
        session_start();
        
        $cart = $_SESSION['cart'] ?? [];
        $total_price = 0;
        $total_items = 0;
        $items = [];
        
        // Проверяем активирован ли клубный доступ
        $clubDiscount = isset($_SESSION['promocode']);
        
        foreach ($cart as $productId => $quantity) {
            // Получаем товар с клубной скидкой если есть промокод
            if ($clubDiscount) {
                $product = $this->productModel->findByIdWithClubDiscount($productId, true);
            } else {
                $product = $this->productModel->findById($productId);
            }
            
            if ($product) {
                $item_total = $product['price'] * $quantity;
                $total_price += $item_total;
                $total_items += $quantity;
                
                $item_data = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'] ?? '/images/products/default.jpg',
                    'quantity' => $quantity,
                    'total' => $item_total
                ];
                
                // Добавляем информацию о скидке если есть
                if ($clubDiscount && isset($product['original_price']) && isset($product['discount'])) {
                    $item_data['original_price'] = $product['original_price'];
                    $item_data['discount'] = $product['discount'];
                    $item_data['discount_amount'] = $product['discount_amount'];
                    $item_data['saved'] = $product['discount_amount'] * $quantity;
                }
                
                $items[] = $item_data;
            }
        }

        $resources = [
            'styles' => ['/styles/pages/cart/cart.css'],
            'scripts' => ['/scripts/components/cart-page.js'],
        ];

        
        return render_view('pages/cart', [
            'title' => 'Корзина',
            'cart_details' => [
                'items' => $items,
                'total_price' => $total_price,
                'total_items' => $total_items,
                'club_discount' => $clubDiscount,
                'promocode' => $_SESSION['promocode'] ?? null
            ],
            'resources' => $resources
        ]);
    }


    public function addItem($params = []) {
        try {
            $productId = $params['id'] ?? null;
            $input = json_decode(file_get_contents('php://input'), true);
            $change = $input['change'] ?? 1;

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Инициализируем корзину
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Добавляем товар
            $currentQuantity = $_SESSION['cart'][$productId] ?? 0;
            $newQuantity = max(0, $currentQuantity + $change);

            if ($newQuantity > 0) {
                $_SESSION['cart'][$productId] = $newQuantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Корзина обновлена',
                'quantity' => $newQuantity,
                'cart_count' => $this->getCartCount()
            ]);

        } catch (Exception $e) {
            error_log("💥 Ошибка в addItem: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при обновлении корзины'
            ]);
        }
    }

    // В методе getCartDetails или аналогичном, где выводится JSON:
    public function getCartDetails($params) {
        session_start();
        
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        $items = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                // ДЕКОДИРУЕМ ТЕКСТ ПРОДУКТА
                $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');
                $product['description'] = html_entity_decode($product['description'] ?? '', ENT_QUOTES, 'UTF-8');
                
                $itemTotal = $product['price'] * $quantity;
                $total += $itemTotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'itemTotal' => $itemTotal
                ];
            }
        }
        
        $discount = 0;
        $discountAmount = 0;
        $promocode = $_SESSION['promocode'] ?? null;
        
        if ($promocode) {
            $promoData = $this->adminModel->getPromocodeByCode($promocode);
            if ($promoData && $promoData['discount'] > 0) {
                $discount = $promoData['discount'];
                $discountAmount = ($total * $discount) / 100;
            }
        }
        
        $finalTotal = $total - $discountAmount;
        
        // ИСПРАВЬТЕ ЭТУ СТРОКУ - ДОБАВЬТЕ JSON_UNESCAPED_UNICODE
        echo json_encode([
            'success' => true,
            'items' => $items,
            'total' => $total,
            'discount' => $discount,
            'discountAmount' => $discountAmount,
            'finalTotal' => $finalTotal,
            'promocode' => $promocode
        ], JSON_UNESCAPED_UNICODE);
    }

    private function getTotalItemsCount()
    {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $quantity) {
                $count += $quantity;
            }
        }
        return $count;
    }

    private function productExists($productId) {
        // Используем модель для проверки существования товара в БД
        $productModel = new Product($this->pdo);
        return $productModel->exists($productId);
    }

    public function removeItem($params) {
        try {
            // Получаем ID из параметров
            $productId = $params['id'] ?? null;
            
            if (!$productId) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID товара не указан'
                ]);
                return;
            }

            // Начинаем или продолжаем сессию
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Инициализируем корзину, если ее нет
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            error_log("🔄 Удаляем товар ID: " . $productId);
            error_log("📦 Корзина до удаления: " . print_r($_SESSION['cart'], true));

            // Удаляем товар из корзины
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
                
                error_log("✅ Товар удален. Корзина после: " . print_r($_SESSION['cart'], true));
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Товар удален из корзины',
                    'cart_count' => $this->getCartCount()
                ]);
            } else {
                error_log("❌ Товар не найден в корзине");
                echo json_encode([
                    'success' => false,
                    'message' => 'Товар не найден в корзине'
                ]);
            }
        } catch (Exception $e) {
            error_log("💥 Ошибка в removeItem: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Внутренняя ошибка сервера: ' . $e->getMessage()
            ]);
        }
    }

    public function clearCart() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['cart'] = [];
            
            echo json_encode([
                'success' => true,
                'message' => 'Корзина очищена',
                'cart_count' => 0
            ]);
        } catch (Exception $e) {
            error_log("💥 Ошибка в clearCart: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при очистке корзины: ' . $e->getMessage()
            ]);
        }
    }

    private function getCartCount() {
        if (isset($_SESSION['cart'])) {
            return array_sum($_SESSION['cart']);
        }
        return 0;
    }

    public function updateQuantity($params) {
        try {
            $productId = $params['id'] ?? null;
            $input = json_decode(file_get_contents('php://input'), true);
            $change = $input['change'] ?? 0;

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Инициализируем корзину, если ее нет
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $currentQuantity = $_SESSION['cart'][$productId] ?? 0;
            $newQuantity = max(0, $currentQuantity + $change);

            if ($newQuantity > 0) {
                $_SESSION['cart'][$productId] = $newQuantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Корзина обновлена',
                'quantity' => $newQuantity,
                'cart_count' => $this->getCartCount()
            ]);

        } catch (Exception $e) {
            error_log("💥 Ошибка в updateQuantity: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при обновлении корзины'
            ]);
        }
    }

    public function update($productId) {
        $input = json_decode(file_get_contents('php://input'), true);
        $change = $input['change'] ?? 0;
        
        // Логика должна быть примерно такой:
        $currentQuantity = $this->getCurrentQuantity($productId);
        $newQuantity = $currentQuantity + $change;
        
        // Не позволяем количеству стать отрицательным
        if ($newQuantity < 0) {
            $newQuantity = 0;
        }
        
        // Обновляем корзину
        $this->updateCartItem($productId, $newQuantity);
        
        return json_encode([
            'success' => true,
            'quantity' => $newQuantity,
            'cart_count' => $this->getTotalItemsCount()
        ]);
    }


    public function getCart() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Инициализируем корзину, если ее нет
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $cart_details = $this->prepareCartData();
            
            // Загружаем view корзины
            require_once __DIR__ . '/../views/pages/cart.php';
            
        } catch (Exception $e) {
            error_log("💥 Ошибка в getCart: " . $e->getMessage());
            http_response_code(500);
            echo "Ошибка при загрузке корзины";
        }
    }

    private function prepareCartData() 
    {
        $cart_items = [];
        $total_price = 0;
        $total_items = 0;

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            if ($quantity > 0) {
                // Получаем информацию о товаре из модели Product
                $product = $this->productModel->findById($product_id);
                
                if ($product && !empty($product)) {
                    // Адаптируем под структуру вашей базы данных
                    $price = $this->extractPrice($product);
                    $name = $this->extractName($product);
                    $image = $this->extractImage($product);
                    
                    $item_total = $price * $quantity;
                    $cart_items[] = [
                        'id' => $product_id,
                        'name' => $name,
                        'price' => $price,
                        'image' => $image,
                        'quantity' => $quantity,
                        'total' => $item_total
                    ];
                    $total_price += $item_total;
                    $total_items += $quantity;
                } else {
                    error_log("❌ Товар с ID {$product_id} не найден в базе данных");
                    // Удаляем несуществующий товар из корзины
                    unset($_SESSION['cart'][$product_id]);
                }
            }
        }

        return [
            'items' => $cart_items,
            'total_price' => $total_price,
            'total_items' => $total_items
        ];
    }
    
    private function extractPrice($product) {
        // Пробуем разные возможные названия полей цены
        if (isset($product['price'])) {
            return (float) $product['price'];
        } elseif (isset($product['cost'])) {
            return (float) $product['cost'];
        } elseif (isset($product['Price'])) {
            return (float) $product['Price'];
        }
        
        error_log("⚠️ Цена не найдена для товара: " . print_r($product, true));
        return 0;
    }

    /**
     * Извлекает название товара из данных модели
     */
    private function extractName($product) {
        if (isset($product['name'])) {
            return $product['name'];
        } elseif (isset($product['title'])) {
            return $product['title'];
        } elseif (isset($product['Name'])) {
            return $product['Name'];
        } elseif (isset($product['product_name'])) {
            return $product['product_name'];
        }
        
        return 'Товар без названия';
    }

    /**
     * Извлекает изображение товара из данных модели
     */
    private function extractImage($product) {
        if (isset($product['image']) && !empty($product['image'])) {
            return $product['image'];
        } elseif (isset($product['image_url']) && !empty($product['image_url'])) {
            return $product['image_url'];
        } elseif (isset($product['img']) && !empty($product['img'])) {
            return $product['img'];
        }
        
        // Изображение по умолчанию
        return '/images/products/default.jpg';
    }

        /**
     * Обновление количества товара в корзине (для AJAX запросов со страницы оформления)
     */
    public function updateCartQuantity($params) {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $productId = $input['product_id'] ?? null;
            $quantity = $input['quantity'] ?? 1;

            if (!$productId) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID товара не указан'
                ]);
                return;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Инициализируем корзину
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Обновляем количество
            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }

            // Получаем обновленные данные корзины
            $cartDetails = $this->getCartDetails($_SESSION['cart']);

            echo json_encode([
                'success' => true,
                'message' => 'Количество обновлено',
                'cart' => $cartDetails['items'],
                'cart_count' => $this->getCartCount(),
                'total_price' => $cartDetails['total_price']
            ]);

        } catch (Exception $e) {
            error_log("💥 Ошибка в updateCartQuantity: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при обновлении количества'
            ]);
        }
    }

    public function removeFromCart($params) {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $productId = $input['product_id'] ?? null;

            if (!$productId) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID товара не указан'
                ]);
                return;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Удаляем товар из корзины
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
                
                // Получаем обновленные данные корзины
                $cartDetails = $this->getCartDetails($_SESSION['cart']);

                echo json_encode([
                    'success' => true,
                    'message' => 'Товар удален из корзины',
                    'cart' => $cartDetails['items'],
                    'cart_count' => $this->getCartCount(),
                    'total_price' => $cartDetails['total_price']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Товар не найден в корзине'
                ]);
            }

        } catch (Exception $e) {
            error_log("💥 Ошибка в removeFromCart: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при удалении товара'
            ]);
        }
    }


    public function applyPromocode($params) {
        session_start();
        
        $input = json_decode(file_get_contents('php://input'), true);
        $promocode = $input['promocode'] ?? '';
        
        if (empty($promocode)) {
            echo json_encode(['success' => false, 'message' => 'Введите промокод'], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        $promoData = $this->adminModel->getPromocodeByCode($promocode);
        
        if (!$promoData) {
            echo json_encode(['success' => false, 'message' => 'Промокод не найден'], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        $_SESSION['promocode'] = $promocode;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Промокод применен',
            'discount' => $promoData['discount']
        ], JSON_UNESCAPED_UNICODE);
    }

    public function removePromocode($params) {
        session_start();
        unset($_SESSION['promocode']);
        
        echo json_encode(['success' => true, 'message' => 'Промокод удален'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Оформление заказа (упрощенная версия)
     */
    public function processCheckout($params) {
        try {
            session_start();
            
            // Получаем данные из формы
            $name = $_POST['client_name'] ?? '';
            $phone = $_POST['client_phone'] ?? '';
            $email = $_POST['client_email'] ?? '';
            $comment = $_POST['client_comment'] ?? '';
            $promocode = $_POST['promocode'] ?? '';
            
            // Валидация
            if (empty($name) || empty($phone) || empty($email)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Заполните все обязательные поля'
                ]);
                return;
            }
            
            $cartDetails = $this->getCartDetails($_SESSION['cart'] ?? []);
            
            if (empty($cartDetails['items'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Корзина пуста'
                ]);
                return;
            }
            
            // Рассчитываем итоговую сумму с учетом промокода
            $totalAmount = $cartDetails['total_price'];
            $discountAmount = 0;
            
            if (!empty($promocode) && isset($_SESSION['applied_promocode'])) {
                $discountData = $_SESSION['applied_promocode']['discount_data'];
                if ($discountData['type'] === 'percentage') {
                    $discountAmount = round($totalAmount * $discountData['value'] / 100);
                } elseif ($discountData['type'] === 'fixed') {
                    $discountAmount = $discountData['value'];
                }
                $totalAmount -= $discountAmount;
            }
            
            // Создаем заказ
            $orderId = $this->createOrder([
                'name' => $name,
                'phone' => $phone,
                'email' => $email
            ], $cartDetails['items'], $totalAmount, $comment, $promocode, $discountAmount);
            
            if ($orderId) {
                // Очищаем корзину и промокод
                $_SESSION['cart'] = [];
                unset($_SESSION['applied_promocode']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Заказ успешно оформлен',
                    'redirect_url' => "/order-success/{$orderId}"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ошибка при создании заказа'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("💥 Ошибка в processCheckout: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при оформлении заказа'
            ]);
        }
    }

    /**
     * Страница оформления заказа (только просмотр)
     */
    public function checkout($params) {
        session_start();
        
        $cart = $_SESSION['cart'] ?? [];
        $cartDetails = $this->getCartDetails($cart);
        
        $resources = [
            'styles' => [
                '/styles/pages/checkout/checkout.css' // если создадите отдельный файл
            ]
        ];

        return render_view('pages/checkout', [
            'cart' => $cart,
            'cart_details' => $cartDetails,
            'resources' => $resources,
        ]);
    }

    
    /**
     * Создание заказа в базе данных
     */
    private function createOrder($customerData, $products, $totalAmount, $promocode = null) {
        try {
            // Если есть модель Order, используем ее
            if (class_exists('Order')) {
                $orderModel = new Order($this->pdo);
                
                $orderData = [
                    'customer_name' => $customerData['name'],
                    'customer_email' => $customerData['email'],
                    'customer_phone' => $customerData['phone'],
                    'total_amount' => $totalAmount,
                    'promocode' => $promocode,
                    'status' => 'new'
                ];
                
                $orderId = $orderModel->create($orderData, $products);
                return $orderId;
            } else {
                // Простая реализация без модели Order
                $stmt = $this->pdo->prepare("
                    INSERT INTO orders (customer_name, customer_email, customer_phone, total_amount, promocode, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'new', datetime('now'))
                ");
                
                $stmt->execute([
                    $customerData['name'],
                    $customerData['email'],
                    $customerData['phone'],
                    $totalAmount,
                    $promocode
                ]);
                
                $orderId = $this->pdo->lastInsertId();
                
                // Сохраняем товары заказа
                $this->saveOrderItems($orderId, $products);
                
                return $orderId;
            }

        } catch (Exception $e) {
            error_log("💥 Ошибка при создании заказа: " . $e->getMessage());
            return false;
        }
    }

        /**
     * Сохранение товаров заказа
     */
    private function saveOrderItems($orderId, $products) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            foreach ($products as $product) {
                $stmt->execute([
                    $orderId,
                    $product['id'],
                    $product['name'],
                    $product['quantity'] ?? 1,
                    $product['price']
                ]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("💥 Ошибка при сохранении товаров заказа: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Получение деталей корзины для страницы оформления заказа
     */
    public function getCheckoutData($params) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $cart = $_SESSION['cart'] ?? [];
            $cartDetails = $this->getCartDetails($cart);
            
            // Получаем услугу если есть
            $service = null;
            if (class_exists('Admin')) {
                $adminModel = new Admin($this->pdo);
                $service = $adminModel->getService();
            }

            echo json_encode([
                'success' => true,
                'cart' => $cartDetails['items'],
                'total_price' => $cartDetails['total_price'],
                'service' => $service,
                'applied_promocode' => $_SESSION['applied_promocode'] ?? null
            ]);

        } catch (Exception $e) {
            error_log("💥 Ошибка в getCheckoutData: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при получении данных корзины'
            ]);
        }
    }

    public function clearAfterPayment($params) {
        session_start();
        
        // Очищаем корзину и промокод
        unset($_SESSION['cart']);
        unset($_SESSION['promocode']);
        
        // Можно также записать в лог об успешной оплате
        error_log("✅ Корзина очищена после успешной оплаты");
        
        echo json_encode([
            'success' => true,
            'message' => 'Корзина очищена'
        ]);
        exit;
    }
}