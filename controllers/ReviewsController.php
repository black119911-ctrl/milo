<?php
// controllers/ReviewsController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Review.php';

class ReviewsController extends BaseController {
    
    private $reviewModel;
    private $reviewsPerPage = 12;

    public function __construct() {
        parent::__construct();
        $this->reviewModel = new Review($this->pdo);
    }

    public function index($params) {
        try {

            // Получаем общее количество для отображения
            $totalReviews = $this->reviewModel->getTotal();

            // Передаем данные в view
            $reviewsData = [
                'total_reviews' => $totalReviews
            ];

            $resources = [
                'styles' => [
                    '/styles/pages/main/main.css',
                ],
                'scripts' => [
                    '/scripts/pages/reviews.js',
                ]
            ];

            return render_view('pages/reviews', [
                'resources' => $resources,
            ]);

            // // Загружаем view
            // require_once __DIR__ . '/../views/pages/reviews.php';
            
        } catch (Exception $e) {
            error_log("💥 Ошибка в ReviewsController::index: " . $e->getMessage());
            http_response_code(500);
            echo "Ошибка при загрузке страницы отзывов";
        }
    }

    public function loadReviews($params) {
        try {

            $page = (int) ($_GET['page'] ?? 1);

            // Получаем отзывы через модель
            $reviews = $this->reviewModel->getPaginated($page, $this->reviewsPerPage);
            $totalReviews = $this->reviewModel->getTotal();

            echo json_encode([
                'success' => true,
                'reviews' => $reviews, // Просто массив с id и src
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => ceil($totalReviews / $this->reviewsPerPage),
                    'total_reviews' => $totalReviews,
                    'has_more' => ($page * $this->reviewsPerPage) < $totalReviews
                ]
            ]);

        } catch (Exception $e) {
            error_log("💥 Ошибка в ReviewsController::loadReviews: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Ошибка загрузки отзывов']);
        }
    }
}