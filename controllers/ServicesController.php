<?php
// controllers/ServicesController.php

require_once __DIR__.'/../models/Admin.php';
require_once __DIR__.'/../models/Order.php';
require_once 'BaseController.php';

class ServicesController extends BaseController {
    public function index($params) {

        $adminModel = new Admin($this->pdo);
        $service = $adminModel->getService(); // Получаем одну услугу

        $resources = [
            'styles' => [
                '/styles/pages/services/services.css'
            ],
            'scripts' => [
                '/scripts/pages/service.js',
                // '/scripts/libs/alfa-payment-proxy.js',
                '/scripts/libs/alfa-proxy-interceptor.js'
            ]
        ];

        return render_view('pages/services', [
            'service' => $service,
            'resources' => $resources
        ]);
    }

    // В методе createOrder ЗАМЕНИ редирект на JSON ответ:
    public function createOrder($params) {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем данные формы
            $name = $_POST['client_name'] ?? '';
            $phone = $_POST['client_phone'] ?? '';
            $email = $_POST['client_email'] ?? '';
            $agreement = $_POST['agree'] ?? '';
            
            // Валидация
            if (empty($name) || empty($phone) || empty($email) || !$agreement) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Заполните все обязательные поля и согласие'
                ]);
                return;
            }
            
            // Получаем данные услуги
            $adminModel = new Admin($this->pdo);
            $service = $adminModel->getService();
            
            if (!$service || $service['is_stock'] != '1') {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Услуга недоступна'
                ]);
                return;
            }
            
            // Создаем заказ в базе данных
            $orderModel = new Order($this->pdo);
            
            $orderData = [
                'customer_name' => $name,
                'customer_phone' => $phone,
                'customer_email' => $email,
                'total_amount' => $service['price'],
                'status' => 'pending',
                'service_name' => $service['name']
            ];
            
            $orderId = $orderModel->createServiceOrder($orderData);
            
            if ($orderId) {
                // ✅ ВОЗВРАЩАЕМ JSON С ДАННЫМИ ДЛЯ ВИДЖЕТА
                echo json_encode([
                    'success' => true,
                    'message' => 'Заказ создан',
                    'order_id' => $orderId,
                    'service' => $service,
                    'client_data' => [
                        'name' => $name,
                        'phone' => $phone,
                        'email' => $email
                    ]
                ]);
                return;
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Ошибка при создании заказа'
                ]);
                return;
            }
        }
        
        echo json_encode([
            'success' => false, 
            'message' => 'Неверный метод запроса'
        ]);
    }

    // ✅ МЕТОД ДЛЯ ПОКАЗА СТРАНИЦЫ ОПЛАТЫ УСЛУГИ
    private function showServicePaymentPage($orderId, $service, $clientData = []) {
        $resources = [
            'scripts' => [
                'https://web.rbsuat.com/ab/websdk/websdk.js'
            ]
        ];
        
        render_view('pages/services', [
            'order_id' => $orderId,
            'service' => $service,
            'client_data' => $clientData,
            'show_payment' => true,
            'resources' => $resources
        ]);
    }
    
}