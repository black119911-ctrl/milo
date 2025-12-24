<?php
// require_once __DIR__ . '/../database.php'; // подключаем базу данных
require_once __DIR__.'/../models/Sertificate.php';
require_once 'BaseController.php';

class SertificateController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->model = new Sertificate($this->pdo);
    }

    public function index($params) {

        $sertificates = $this->model->firstSix(); // получаем продукты
        $showBasket = $this->authenticate($params);
        $resources = [
            'styles' => ['/styles/pages/sertificates/sertificates.css'],
            'scripts' => ['/scripts/pages/sertificates.js']
        ];

        return render_view('pages/sertificates', [
            'sertificates' => $sertificates,
            'showBasket' => $showBasket,
            'resources' => $resources
        ]);
    }

    
    public function load(array $params) {
        header('Content-Type: application/json');

        $limit = 3; // количество сертификатов за подгрузку
        $offset = isset($params['offset']) ? intval($params['offset']) : 0; // смещение
        $additionalCertifications = $this->model->paginate($limit, $offset); // выбираем ограниченно

        if ($additionalCertifications) {
            echo json_encode([
                'success' => true,
                'data' => $additionalCertifications
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Больше сертификатов нет.'
            ]);
        }
    }

}