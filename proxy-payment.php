<?php
// proxy-payment.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:8000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Разрешаем preflight запросы
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Включаем логирование ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Логируем все входящие данные
error_log("=== ALFA BANK PROXY CALLED ===");
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Content Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'not set'));
error_log("HTTP Headers:");
foreach (getallheaders() as $name => $value) {
    error_log("  $name: $value");
}

// Получаем raw данные
$input = file_get_contents('php://input');
error_log("Raw input data: " . $input);
error_log("POST data: " . print_r($_POST, true));

// Пробуем разные способы получить данные
$requestData = null;

if (!empty($input)) {
    $requestData = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        // Может быть form data?
        parse_str($input, $requestData);
    }
}

// Если все еще нет данных, используем POST
if (empty($requestData) && !empty($_POST)) {
    $requestData = $_POST;
    error_log("Using POST data");
}

// Логируем полученные данные
error_log("Final request data: " . print_r($requestData, true));

if (!$requestData) {
    error_log("ERROR: No valid data received");
    http_response_code(400);
    echo json_encode([
        'error' => 'No valid data received', 
        'debug' => [
            'input' => $input,
            'post' => $_POST,
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set'
        ]
    ]);
    exit;
}

// URL Альфа-Банка (тестовый)
$alfaUrl = 'https://testpay.alfabank.ru/api/widget/register';
error_log("Forwarding to: " . $alfaUrl);

// Подготавливаем данные для отправки
$postData = json_encode($requestData);
error_log("Data to send: " . $postData);

// Настройка cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $alfaUrl,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData),
        'Accept: application/json'
    ],
    CURLOPT_HEADER => true // Получаем заголовки ответа
]);

// Выполнение запроса
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$curlError = curl_error($ch);
$curlErrno = curl_errno($ch);

// Разделяем заголовки и тело ответа
$responseHeaders = substr($response, 0, $headerSize);
$responseBody = substr($response, $headerSize);

curl_close($ch);

// Логируем ответ
error_log("=== ALFA BANK RESPONSE ===");
error_log("HTTP Code: " . $httpCode);
error_log("cURL Error: " . $curlError);
error_log("cURL Errno: " . $curlErrno);
error_log("Response Headers: " . $responseHeaders);
error_log("Response Body: " . $responseBody);

// Если есть ошибка cURL
if ($curlErrno) {
    error_log("cURL failed: " . $curlError);
    http_response_code(500);
    echo json_encode([
        'error' => 'Gateway error', 
        'message' => $curlError,
        'code' => $curlErrno
    ]);
    exit;
}

// Передаем ответ как есть
http_response_code($httpCode);

// Добавляем CORS заголовки к ответу Альфа-Банка
$corsHeaders = [
    'Access-Control-Allow-Origin: http://localhost:8000',
    'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE',
    'Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'
];

foreach ($corsHeaders as $header) {
    header($header);
}

echo $responseBody;
?>