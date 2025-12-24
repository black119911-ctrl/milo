<?php
// models/Order.php

class Order {
    protected $table = 'orders';
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // В models/Order.php добавь метод для заказов услуг
    public function createServiceOrder($orderData) {
        try {
            $sql = "INSERT INTO orders (
                customer_name, customer_phone, customer_email, 
                total_amount, status, service_name, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, datetime('now'))";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $orderData['customer_name'],
                $orderData['customer_phone'],
                $orderData['customer_email'],
                $orderData['total_amount'],
                $orderData['status'],
                $orderData['service_name'] ?? null
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("Order::createServiceOrder error: " . $e->getMessage());
            return false;
        }
    }

    public function create($orderData) {
        try {
            $this->pdo->beginTransaction();

            // Вставляем основной заказ
            $query = "INSERT INTO {$this->table} 
                     (customer_name, customer_phone, customer_email, delivery_type, delivery_address, 
                      payment_method, order_comment, subtotal, delivery_price, total, status, created_at) 
                     VALUES (:customer_name, :customer_phone, :customer_email, :delivery_type, :delivery_address, 
                             :payment_method, :order_comment, :subtotal, :delivery_price, :total, :status, datetime('now'))";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'customer_name' => $orderData['customer_name'],
                'customer_phone' => $orderData['customer_phone'],
                'customer_email' => $orderData['customer_email'],
                'delivery_type' => $orderData['delivery_type'],
                'delivery_address' => $orderData['delivery_address'],
                'payment_method' => $orderData['payment_method'],
                'order_comment' => $orderData['order_comment'],
                'subtotal' => $orderData['subtotal'],
                'delivery_price' => $orderData['delivery_price'],
                'total' => $orderData['total'],
                'status' => $orderData['status']
            ]);

            $order_id = $this->pdo->lastInsertId();

            // Вставляем товары заказа
            $this->createOrderItems($order_id, $orderData['items']);

            $this->pdo->commit();
            return $order_id;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("💥 Order::create ERROR: " . $e->getMessage());
            throw new Exception('Не удалось создать заказ');
        }
    }

    private function createOrderItems($order_id, $items) {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total) 
                  VALUES (:order_id, :product_id, :product_name, :price, :quantity, :total)";
        
        $stmt = $this->pdo->prepare($query);
        
        foreach ($items as $item) {
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['total']
            ]);
        }
    }

    public function findById($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}