<?php
// controllers/CheckoutController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Admin.php';

class CheckoutController extends BaseController {
    
    private $productModel;
    private $adminModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = new Product($this->pdo);
        $this->adminModel = new Admin($this->pdo);
    }

    // Убедись что этот метод существует
    private function getCartDataForPayment() {
        $cart = $_SESSION['cart'] ?? [];
        $total_price = 0;
        $total_items = 0;
        $items = [];
        
        $clubDiscount = isset($_SESSION['promocode']);
        
        foreach ($cart as $productId => $quantity) {
            if ($clubDiscount) {
                $product = $this->productModel->findByIdWithClubDiscount($productId, true);
            } else {
                $product = $this->productModel->findById($productId);
            }
            
            if ($product) {
                $item_total = $product['price'] * $quantity;
                $total_price += $item_total;
                $total_items += $quantity;
                
                $items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'alt_name' => $product['alt_name'] ?? '',
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'total' => $item_total
                ];
            }
        }
        
        // Рассчитываем скидку если есть промокод
        $discount = 0;
        $discountAmount = 0;
        $final_total = $total_price;
        
        if ($clubDiscount && isset($_SESSION['promocode'])) {
            $promoData = $this->adminModel->getPromocodeByCode($_SESSION['promocode']);
            if ($promoData && $promoData['discount'] > 0) {
                $discount = $promoData['discount'];
                $discountAmount = ($total_price * $discount) / 100;
                $final_total = $total_price - $discountAmount;
            }
        }
        
        return [
            'items' => $items,
            'total_price' => $total_price,
            'total_items' => $total_items,
            'discount' => $discount,
            'discount_amount' => $discountAmount,
            'final_total' => $final_total,
            'promocode' => $_SESSION['promocode'] ?? null
        ];
    }


    public function index($params) {
        session_start();
        
        $cart = $_SESSION['cart'] ?? [];
        $total_price = 0;
        $total_items = 0;
        $items = [];
        
        $clubDiscount = isset($_SESSION['promocode']);

        foreach ($cart as $productId => $quantity) {
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
                    'alt_name' => $product['alt_name'] ?? $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'] ?? '/images/products/default.jpg',
                    'quantity' => $quantity,
                    'total' => $item_total
                ];
                
                if ($clubDiscount && isset($product['original_price']) && isset($product['discount'])) {
                    $item_data['original_price'] = $product['original_price'];
                    $item_data['discount'] = $product['discount'];
                    $item_data['discount_amount'] = $product['discount_amount'];
                    $item_data['saved'] = $product['discount_amount'] * $quantity;
                }
                
                $items[] = $item_data;
            }
        }

        // Рассчитываем скидку если есть промокод
        $discount = 0;
        $discountAmount = 0;
        $final_total = $total_price;
        
        if ($clubDiscount && isset($_SESSION['promocode'])) {
            $promoData = $this->adminModel->getPromocodeByCode($_SESSION['promocode']);
            if ($promoData && $promoData['discount'] > 0) {
                $discount = $promoData['discount'];
                $discountAmount = ($total_price * $discount) / 100;
                $final_total = $total_price - $discountAmount;
            }
        }

        $resources = [
            'styles' => [
                '/styles/pages/checkout/checkout.css'
            ],
            'scripts' => [
                '/scripts/pages/checkout.js'
            ]
        ];
        
        return render_view('pages/checkout', [
            'title' => 'Оформление заказа',
            'cart_details' => [
                'items' => $items,
                'total_price' => $total_price,
                'total_items' => $total_items,
                'discount' => $discount,
                'discount_amount' => $discountAmount,
                'final_total' => $final_total,
                'club_discount' => $clubDiscount,
                'promocode' => $_SESSION['promocode'] ?? null
            ],
            // ✅ ДОБАВЛЯЕМ cart_data для JavaScript
            'cart_data' => [
                'items' => $items,
                'total_price' => $final_total, // Используем final_total с учетом скидки
                'total_items' => $total_items
            ],
            'resources' => $resources
        ]);
    }


    public function getCartDetails($params) {
    
        session_start();
        
        $cart = $_SESSION['cart'] ?? [];
        $total_price = 0;
        $total_items = 0;
        $items = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $item_total = $product['price'] * $quantity;
                $total_price += $item_total;
                $total_items += $quantity;
                
                // АДАПТИРУЕМ ПОД СТРУКТУУ ВЁРСТКИ
                $items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'] ?? '/images/products/default.jpg',
                    'quantity' => $quantity,
                    'total' => $item_total
                ];
            }
        }
        
        // ДОБАВЛЯЕМ СКИДКУ ЕСЛИ НУЖНО
        $discount = 0;
        $discountAmount = 0;
        $promocode = $_SESSION['promocode'] ?? null;
        
        if ($promocode) {
            $promoData = $this->adminModel->getPromocodeByCode($promocode);
            if ($promoData && $promoData['discount'] > 0) {
                $discount = $promoData['discount'];
                $discountAmount = ($total_price * $discount) / 100;
            }
        }
        
        $final_total = $total_price - $discountAmount;
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'items' => $items,
            'total_price' => $total_price,
            'total_items' => $total_items,
            'discount' => $discount,
            'discountAmount' => $discountAmount,
            'final_total' => $final_total,
            'promocode' => $promocode
        ], JSON_UNESCAPED_UNICODE);
        exit;
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
        
        // ✅ ВОЗВРАЩАЕМ ОБНОВЛЕННЫЕ ДАННЫЕ КОРЗИНЫ БЕЗ ПЕРЕЗАГРУЗКИ
        $cartData = $this->getCartDataForPayment();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Промокод успешно применен!',
            'discount' => $promoData['discount'],
            'cartData' => $cartData // ← ДОБАВЛЯЕМ ОБНОВЛЕННЫЕ ДАННЫЕ
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function removePromocode($params) {
        session_start();
        unset($_SESSION['promocode']);
        
        // ✅ ВОЗВРАЩАЕМ ОБНОВЛЕННЫЕ ДАННЫЕ КОРЗИНЫ
        $cartData = $this->getCartDataForPayment();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Промокод удален',
            'cartData' => $cartData
        ]);
        exit;
    }
    
   
    public function processOrder($params) {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем данные формы (только обязательные поля)
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $agreement = $_POST['agreement'] ?? '';

            if (!$agreement) {
                $_SESSION['checkout_error'] = 'Необходимо согласие с политикой обработки персональных данных';
                header('Location: /checkout');
                exit;
            }
            
            // Сохраняем данные формы в сессию для платежного виджета
            $_SESSION['checkout_data'] = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email
            ];
            
            // Валидация данных (только обязательные поля)
            if (empty($name) || empty($phone) || empty($email)) {
                $_SESSION['checkout_error'] = 'Заполните все обязательные поля: имя, телефон и email';
                header('Location: /checkout');
                exit;
            }
            
            // Получаем корзину
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                $_SESSION['checkout_error'] = 'Корзина пуста';
                header('Location: /checkout');
                exit;
            }
            
            // Создаем заказ в базе данных
            require_once __DIR__.'/../models/Order.php';
            require_once __DIR__.'/../models/Product.php';
            
            $orderModel = new Order($this->pdo);
            $productModel = new Product($this->pdo);
            
            // Рассчитываем итоговую сумму
            $totalAmount = 0;
            $orderItems = [];
            
            foreach ($cart as $productId => $quantity) {
                $product = $productModel->findById($productId);
                if ($product) {
                    $itemTotal = $product['price'] * $quantity;
                    $totalAmount += $itemTotal;
                    $orderItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'total' => $itemTotal
                    ];
                }
            }
            
            // Создаем заказ (только с обязательными полями)
            $orderId = $orderModel->create([
                'customer_name' => $name,
                'customer_phone' => $phone,
                'customer_email' => $email,
                // ❌ УБРАНЫ: address и comment
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ], $cart);
            
            if ($orderId) {
                // Генерируем описание заказа для банка
                $orderDescription = $this->generateOrderDescription($orderItems);
                
                // Показываем страницу с виджетом банка
                $this->showPaymentPage($orderId, $totalAmount, $orderDescription, $orderItems);
                return;
            } else {
                $_SESSION['checkout_error'] = 'Ошибка при создании заказа';
                header('Location: /checkout');
                exit;
            }
        }
        
        header('Location: /checkout');
        exit;
    }

    private function generateOrderDescription($orderItems) {
        $description = "Заказ товаров: ";
        $items = [];
        
        foreach ($orderItems as $item) {
            $items[] = $item['product']['name'] . ' (' . $item['quantity'] . ' шт.)';
        }
        
        $description .= implode(', ', $items);
        
        // Ограничиваем длину описания (банки обычно имеют лимит)
        if (strlen($description) > 200) {
            $description = substr($description, 0, 197) . '...';
        }
        
        return $description;
    }

    private function showPaymentPage($orderId, $totalAmount, $orderDescription, $orderItems) {
        $resources = [
            'scripts' => [
                'https://web.rbsuat.com/ab/websdk/websdk.js' // Замени на актуальный URL виджета Альфа-Банка
            ]
        ];
        
        render_view('pages/payment', [
            'order_id' => $orderId,
            'total_amount' => $totalAmount,
            'order_description' => $orderDescription,
            'order_items' => $orderItems,
            'resources' => $resources
        ]);
    }

    private function prepareOrderData() {
        $order_items = [];
        $total_price = 0;
        $total_items = 0;

        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                if ($quantity > 0) {
                    $product = $this->productModel->findById($product_id);
                    
                    if ($product && !empty($product)) {
                        $price = $this->extractPrice($product);
                        $name = $this->extractName($product);
                        $image = $this->extractImage($product);
                        
                        $item_total = $price * $quantity;
                        $order_items[] = [
                            'id' => $product_id,
                            'name' => $name,
                            'price' => $price,
                            'image' => $image,
                            'quantity' => $quantity,
                            'total' => $item_total
                        ];
                        $total_price += $item_total;
                        $total_items += $quantity;
                    }
                }
            }
        }

        return [
            'items' => $order_items,
            'total_price' => $total_price,
            'total_items' => $total_items
        ];
    }

    private function validateOrderData($data) {
        $errors = [];

        if (empty($data['client_name'])) {
            $errors['client_name'] = 'Укажите ФИО';
        }

        if (empty($data['client_phone'])) {
            $errors['client_phone'] = 'Укажите телефон';
        }

        if (empty($data['client_email'])) {
            $errors['client_email'] = 'Укажите email';
        } elseif (!filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['client_email'] = 'Неверный формат email';
        }

        if (empty($data['agreement'])) {
            $errors['agreement'] = 'Необходимо согласие с правилами';
        }

        return $errors;
    }

    private function extractPrice($product) {
        if (isset($product['price'])) return (float) $product['price'];
        if (isset($product['cost'])) return (float) $product['cost'];
        if (isset($product['Price'])) return (float) $product['Price'];
        return 0;
    }

    private function extractName($product) {
        if (isset($product['name'])) return $product['name'];
        if (isset($product['title'])) return $product['title'];
        if (isset($product['Name'])) return $product['Name'];
        return 'Товар без названия';
    }

    private function extractImage($product) {
        if (isset($product['image']) && !empty($product['image'])) return $product['image'];
        if (isset($product['image_url']) && !empty($product['image_url'])) return $product['image_url'];
        return '/images/products/default.jpg';
    }

    public function orderSuccess($params) {
        session_start();
        
        $orderId = $params['id'] ?? null;
        
        // Дополнительная очистка на всякий случай
        unset($_SESSION['cart']);
        unset($_SESSION['promocode']);
        unset($_SESSION['checkout_data']);
        
        $resources = [
            'scripts' => ['/scripts/pages/order-success.js']
        ];
        
        render_view('pages/order-success', [
            'order_id' => $orderId,
            'resources' => $resources
        ]);
    }

}