<?php
// controllers/AdminController.php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__.'/../models/ProductReview.php';


class AdminController extends BaseController {
    
    private $adminModel;
    private $productModel;

    public function __construct() {
        parent::__construct();
        $this->adminModel = new Admin($this->pdo);
        $this->productModel = new Product($this->pdo);
    }

    public function services($params) {
        $this->checkAdminAuth();
        
        $service = $this->adminModel->getService();

        $resources = [
            'styles' => [
                '/styles/admin/services.css',
            ],
            'scripts' => [
                '/scripts/pages/admin.js' // добавляем логику корзины
            ]
        ];
        
        return render_view('admin/services', [
            'resources' => $resources,
            'service' => $service,
            'title' => 'Управление услугой',
            'currentPage' => 'services'
        ]);
    }

    public function saveService($params) {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

            // ОТЛАДКА ДЛЯ ЧЕКБОКСА
        error_log("🔘 is_stock in POST: " . (isset($_POST['is_stock']) ? 'PRESENT (checked)' : 'MISSING (unchecked)'));
        error_log("🔘 _POST contents: " . print_r($_POST, true));

        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'is_stock' => isset($_POST['is_stock']) ? '1' : '0'
        ];

        error_log("📦 Processed is_stock value: " . $data['is_stock']);

        try {
            $success = $this->adminModel->createOrUpdateService($data);

            if ($success) {
                error_log("🎉 Service saved successfully! is_stock = " . $data['is_stock']);
                echo json_encode(['success' => true, 'message' => 'Услуга сохранена']);
            } else {
                error_log("💥 Service save failed!");
                echo json_encode(['success' => false, 'message' => 'Ошибка сохранения']);
            }
        } catch (Exception $e) {
            error_log("Admin service save error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }


    public function index($params) {
        $this->checkAdminAuth();
        
        $stats = ['products' => count($this->productModel->all())];
        
        $resources = [
            'styles' => ['/styles/admin/dashboard.css'],
            'scripts' => ['/scripts/pages/admin.js']
        ];
        
        return render_view('admin/dashboard', [
            'resources' => $resources,
            'action' => $action,
            'title' => 'Дашборд',
            'currentPage' => 'dashboard'
        ]);
    }


    public function products($params) {
        $this->checkAdminAuth();
        
        $products = $this->productModel->all();
        $action = $_GET['action'] ?? 'list';
        $product = null;
        
        if ($action === 'edit' && isset($_GET['id'])) {
            $product = $this->productModel->findByIdWithArticle($_GET['id']);
        }


        $productReviewModel = new ProductReview($this->pdo);
        $reviewModel = new Review($this->pdo);

        $productReviewsCount = count($productReviewModel->getAllForModeration());
        $reviewsCount = count($reviewModel->getAllForModeration());



        $resources = [
            'styles' => [
                '/styles/admin/products.css',
            ],
            'scripts' => [
                '/scripts/pages/admin.js' // добавляем логику корзины
            ]
        ];
        
        return render_view('admin/products', [
            'resources' => $resources,
            'products' => $products,
            'action' => $action,
            'product' => $product,
            'title' => 'Управление товарами',
            'currentPage' => 'products',

            'reviews_count' => $reviewsCount,
            'product_reviews_count' => $productReviewsCount,
        ]);
    }
    
    public function reviews($params) {
        $this->checkAdminAuth();
        
        $reviews = $this->adminModel->getAllReviews();

        $resources = [
            'styles' => [
                '/styles/admin/reviews.css',
            ],
            'scripts' => [
                '/scripts/pages/admin.js' // добавляем логику корзины
            ]
        ];
        
        return render_view('admin/reviews', [
            'resources' => $resources,
            'reviews' => $reviews,
            'title' => 'Управление отзывами',
            'currentPage' => 'reviews'
        ]);
    }

    public function saveProduct($params) {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            $imagePath = null;
            
            // Обработка загрузки изображения
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // ... существующий код загрузки изображения ...
            } elseif (isset($_POST['existing_image']) && !empty($_POST['existing_image'])) {
                $imagePath = $_POST['existing_image'];
            }

            // Данные для products
            $productData = [
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'image' => $imagePath,
                'private_discount' => $_POST['private_discount'] ?? 0,
                'stock' => isset($_POST['stock']) ? '1' : '0' // Добавляем поле stock
            ];

            // Данные для articles
            $articleData = [
                'art_description' => $_POST['art_description'] ?? '',
                'composition' => $_POST['composition'] ?? '',
                'application' => $_POST['application'] ?? '',
                'recommendations' => $_POST['recommendations'] ?? ''
            ];

            $productId = $_POST['id'] ?? null;

            if ($productId) {
                // Обновляем продукт и статью
                $productSuccess = $this->adminModel->updateProduct($productId, $productData);
                $articleSuccess = $this->adminModel->createOrUpdateArticle($productId, $articleData);
                $message = 'Товар успешно обновлен';
            } else {
                // Создаем новый продукт
                if (!$imagePath) {
                    throw new Exception('Изображение товара обязательно');
                }
                $productSuccess = $this->adminModel->createProduct($productData);
                $productId = $this->pdo->lastInsertId();
                $articleSuccess = $this->adminModel->createOrUpdateArticle($productId, $articleData);
                $message = 'Товар успешно создан';
            }

            if ($productSuccess && $articleSuccess) {
                echo json_encode(['success' => true, 'message' => $message]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка сохранения']);
            }
        } catch (Exception $e) {
            error_log("Admin product save error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function deleteProduct($params) {
        $this->checkAdminAuth();

        $productId = $params['id'] ?? $_POST['id'] ?? null;
        
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'ID товара не указан']);
            return;
        }

        try {
            $success = $this->adminModel->deleteProduct($productId);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Товар удален']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка удаления']);
            }
        } catch (Exception $e) {
            error_log("Admin product delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function uploadReview($params) {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../images/reviews/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['review_image']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['review_image']['tmp_name'], $filePath)) {
                $src = '/images/reviews/' . $fileName;
                $success = $this->adminModel->createReview($src);
                
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Отзыв загружен', 'src' => $src]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения в БД']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка загрузки файла']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Файл не загружен']);
        }
    }


    public function deleteReview($params) {
        $this->checkAdminAuth();

        // Получаем ID из JSON тела запроса
        $input = json_decode(file_get_contents('php://input'), true);
        $reviewId = $input['id'] ?? null;
        
        if (!$reviewId) {
            echo json_encode(['success' => false, 'message' => 'ID отзыва не указан']);
            return;
        }

        try {
            $success = $this->adminModel->deleteReview($reviewId);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Отзыв удален']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка удаления']);
            }
        } catch (Exception $e) {
            error_log("Admin review delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function deleteService($params) {
        $this->checkAdminAuth();

        try {
            $success = $this->adminModel->deleteService();
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Услуга удалена']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка удаления']);
            }
        } catch (Exception $e) {
            error_log("Admin service delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function toggleServiceStatus($params) {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            $isStock = $_POST['is_stock'] ?? 0;
            $success = $this->adminModel->toggleServiceStatus($isStock);
            
            if ($success) {
                $status = $isStock ? 'включена' : 'выключена';
                echo json_encode(['success' => true, 'message' => "Услуга {$status}"]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка обновления статуса']);
            }
        } catch (Exception $e) {
            error_log("Admin service toggle error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    private function checkAdminAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: /admin/login');
            exit;
        }
    }


    public function promocodes($params) {
        $this->checkAdminAuth();
        
        $promocode = $this->adminModel->getPromocode();

        $resources = [
            'styles' => [
                '/styles/admin/promocodes.css',
            ],
            'scripts' => [
                '/scripts/pages/admin.js' // добавляем логику корзины
            ]
        ];
        
        return render_view('admin/promocodes', [
            'resources' => $resources,
            'promocode' => $promocode,
            'title' => 'Управление промокодами',
            'currentPage' => 'promocodes'
        ]);
    }

    public function savePromocode($params) {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $promocode = $_POST['promocode'] ?? '';
        // Фиксированная скидка 10% для участников клуба
        $discount = 10;

        // Валидация - только латиница и цифры
        if (!preg_match('/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]*$/', $promocode)) {
            echo json_encode(['success' => false, 'message' => 'Промокод должен содержать только латиницу и цифры']);
            return;
        }

        if (empty($promocode)) {
            echo json_encode(['success' => false, 'message' => 'Введите промокод']);
            return;
        }

        try {
            $success = $this->adminModel->createOrUpdatePromocode($promocode, $discount);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Промокод сохранен']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка сохранения']);
            }
        } catch (Exception $e) {
            error_log("Admin promocode save error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function deletePromocode($params) {
        $this->checkAdminAuth();

        try {
            $success = $this->adminModel->deletePromocode();
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Промокод удален']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка удаления']);
            }
        } catch (Exception $e) {
            error_log("Admin promocode delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }


    public function productReviews() {
        $this->checkAdminAuth();
        
        require_once __DIR__.'/../models/ProductReview.php';
        require_once __DIR__.'/../models/Product.php';
        
        $reviewModel = new ProductReview($this->pdo);
        $productModel = new Product($this->pdo);
        
        $reviews = $reviewModel->getAllForModeration();
        $products = $productModel->getAllForSelect();
        
        $resources = [
            'scripts' => ['/scripts/pages/admin.js']
        ];
        
        render_view('admin/product-reviews', [
            'reviews' => $reviews,
            'products' => $products,
            'currentPage' => 'product-reviews',
            'title' => 'Управление отзывами к товарам',
            'resources' => $resources
        ]);
    }


    public function createProductReview($params) {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $userName = $_POST['user_name'] ?? '';
            $rating = $_POST['rating'] ?? 0;
            $text = $_POST['text'] ?? '';
            $status = $_POST['status'] ?? 'approved';
            
            if ($productId && $userName && $rating && $text) {
                require_once __DIR__.'/../models/ProductReview.php';
                $reviewModel = new ProductReview($this->pdo);
                
                // Для админки сразу сохраняем с нужным статусом
                $reviewModel->createWithStatus($productId, $userName, $rating, $text, $status);
            }
        }
        
        header('Location: /admin/product-reviews');
        exit;
    }


    public function approveProductReview($params) {
        $this->checkAdminAuth();
        
        $id = $params[0] ?? $params['id'] ?? null;
        if ($id) {
            require_once __DIR__.'/../models/ProductReview.php';
            $reviewModel = new ProductReview($this->pdo);
            $reviewModel->approve($id);
        }
        
        header('Location: /admin/product-reviews');
        exit;
    }

    public function rejectProductReview($params) {
        $this->checkAdminAuth();
        
        $id = $params[0] ?? $params['id'] ?? null;
        if ($id) {
            require_once __DIR__.'/../models/ProductReview.php';
            $reviewModel = new ProductReview($this->pdo);
            $reviewModel->reject($id);
        }
        
        header('Location: /admin/product-reviews');
        exit;
    }

    public function deleteProductReview($params) {
        $this->checkAdminAuth();
        
        $id = $params[0] ?? $params['id'] ?? null;
        if ($id) {
            require_once __DIR__.'/../models/ProductReview.php';
            $reviewModel = new ProductReview($this->pdo);
            $reviewModel->delete($id);
        }
        
        header('Location: /admin/product-reviews');
        exit;
    }


    public function editProductReview($params) {
        $this->checkAdminAuth();
        
        $reviewId = $params['id'] ?? null;
        
        if (!$reviewId) {
            $_SESSION['error_message'] = 'ID отзыва не указан';
            header('Location: /admin/product-reviews');
            exit;
        }
        
        require_once __DIR__.'/../models/ProductReview.php';
        require_once __DIR__.'/../models/Product.php';
        
        $reviewModel = new ProductReview($this->pdo);
        $productModel = new Product($this->pdo);
        
        // Получаем отзыв для редактирования
        $review = $reviewModel->findById($reviewId);
        
        if (!$review) {
            $_SESSION['error_message'] = 'Отзыв не найден';
            header('Location: /admin/product-reviews');
            exit;
        }
        
        // Получаем список товаров для выпадающего списка
        $products = $productModel->getAllForSelect();
        
        $resources = [
            'scripts' => ['/scripts/pages/admin.js']
        ];
        
        render_view('admin/edit-product-review', [
            'review' => $review,
            'products' => $products,
            'currentPage' => 'product-reviews',
            'title' => 'Редактирование отзыва',
            'resources' => $resources
        ]);
    }

    public function updateProductReview($params) {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewId = $_POST['review_id'] ?? null;
            $productId = $_POST['product_id'] ?? null;
            $userName = $_POST['user_name'] ?? '';
            $rating = $_POST['rating'] ?? 0;
            $text = $_POST['text'] ?? '';
            $status = $_POST['status'] ?? 'pending';
            
            if (!$reviewId || !$productId || empty($userName) || empty($text) || $rating < 1 || $rating > 5) {
                $_SESSION['error_message'] = 'Заполните все обязательные поля';
                header('Location: /admin/product-reviews');
                exit;
            }
            
            require_once __DIR__.'/../models/ProductReview.php';
            $reviewModel = new ProductReview($this->pdo);
            
            if ($reviewModel->update($reviewId, $userName, $rating, $text, $status)) {
                $_SESSION['success_message'] = 'Отзыв успешно обновлен';
            } else {
                $_SESSION['error_message'] = 'Ошибка при обновлении отзыва';
            }
        }
        
        header('Location: /admin/product-reviews');
        exit;
    }
    
}