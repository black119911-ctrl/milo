<?php

class Product {

    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getAllForSelect() {
        try {
            // Только ID и название, отсортированные по имени
            $stmt = $this->pdo->prepare("SELECT id, name FROM products ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Product::getAllForSelect() error: " . $e->getMessage());
            return [];
        }
    }

    public function findById($id) {
        try {
            $query = "SELECT * FROM products WHERE id = :id";
            $id = (int) $id;
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Отдельный метод для товара со статьей (для страницы товара)
    public function findByIdWithArticle($id) {
        try {
            $query = "SELECT 
                        products.*,
                        articles.art_description as article_description,
                        articles.composition as article_composition,
                        articles.application as article_application,
                        articles.recommendations as article_recommendations
                    FROM products 
                    LEFT JOIN articles ON products.id = articles.product_id 
                    WHERE products.id = :id";
            
            $id = (int) $id;
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Найти товар по ID
     */
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Проверить существование товара
     */
    public function exists($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE id = ? AND stock = 'true'");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function allWithDiscount($discountPercent = 0) {
        $products = $this->all();
        
        if ($discountPercent > 0) {
            foreach ($products as &$product) {
                $discountAmount = ($product['price'] * $discountPercent) / 100;
                $product['original_price'] = $product['price'];
                $product['price'] = $product['price'] - $discountAmount;
                $product['discount'] = $discountPercent;
                $product['discount_amount'] = $discountAmount;
            }
        }
        
        return $products;
    }

    public function findByIdWithDiscount($id, $discountPercent = 0) {
        $product = $this->findById($id);
        
        if ($product && $discountPercent > 0) {
            $discountAmount = ($product['price'] * $discountPercent) / 100;
            $product['original_price'] = $product['price'];
            $product['price'] = $product['price'] - $discountAmount;
            $product['discount'] = $discountPercent;
            $product['discount_amount'] = $discountAmount;
        }
        
        return $product;
    }

    public function allWithClubDiscount($clubDiscount = false) {
        $products = $this->all();
        
        if ($clubDiscount) {
            foreach ($products as &$product) {
                // Используем индивидуальную скидку товара
                $discount = $product['private_discount'] ?? 0;
                if ($discount > 0) {
                    $discountAmount = ($product['price'] * $discount) / 100;
                    $product['original_price'] = $product['price'];
                    $product['price'] = $product['price'] - $discountAmount;
                    $product['discount'] = $discount;
                    $product['discount_amount'] = $discountAmount;
                }
            }
        }
        
        return $products;
    }

    public function findByIdWithClubDiscount($id, $clubDiscount = false) {
        $product = $this->findById($id);
        
        if ($product && $clubDiscount) {
            $discount = $product['private_discount'] ?? 0;
            if ($discount > 0) {
                $discountAmount = ($product['price'] * $discount) / 100;
                $product['original_price'] = $product['price'];
                $product['price'] = $product['price'] - $discountAmount;
                $product['discount'] = $discount;
                $product['discount_amount'] = $discountAmount;
            }
        }
        
        return $product;
    }

    public function updatePrivateDiscount($id, $discount) {
        $stmt = $this->pdo->prepare("UPDATE products SET private_discount = ? WHERE id = ?");
        return $stmt->execute([$discount, $id]);
    }

}