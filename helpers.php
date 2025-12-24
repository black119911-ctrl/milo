<?php

require_once __DIR__ . '/classes/MenuManager.php';

function render_view(string $templateName, array $data = []) {
    // Специальная обработка для логина
    if ($templateName === 'admin/login') {
        extract($data);
        include __DIR__ . "/views/$templateName.php";
        return;
    }

    // var_dump($templateName);
    
    // Для админских страниц (кроме логина)
    if (strpos($templateName, 'admin/') === 0) {
        // Рендерим ТОЛЬКО контент (без HTML/HEAD/BODY)
        ob_start();
        extract($data);
        include __DIR__ . "/views/$templateName.php";
        $content = ob_get_clean();
    
        // Рендерим layout с контентом
        $data['content'] = $content;
        extract($data);
        include __DIR__ . "/views/admin/layout.php";
        return;
    }
    
    // Для обычных страниц
    ob_start();
    extract($data);

    include __DIR__ . "/views/$templateName.php";
    $content = ob_get_clean();
    
    $data['content'] = $content;
    extract($data);
    include __DIR__ . "/views/layout.php";
}


function is_active_class($path)
{
    $request_uri = $_SERVER['REQUEST_URI'];
    $url_parts = parse_url($request_uri);
    $clean_request_uri = $url_parts['path'];
    return ($clean_request_uri === $path && strpos($clean_request_uri, $path) === 0) ? 'active' : '';
}


function get_menu_items()
{
    $menuManager = new MenuManager();
    return $menuManager->getMenuItems();
}


function getValidShopToken() {
    return '987';
}


// function render_error_page(int $code = 404, string $message = 'Страница не найдена') {
function render_error_page($code) {

    http_response_code($code); // Убедимся, что сервер вернёт верный HTTP-код
    render_view('errors/' . $code); // Рендерим шаблон ошибки
    exit;
}


// function generateCsrfToken() {
//     session_start();

//     // Генерируем токен CSRF при отсутствии существующего
//     if (!isset($_SESSION['csrf_token'])) {
//         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//     }

//     // Устанавливаем куку с токеном
//     setcookie('csrf_token', $_SESSION['csrf_token'], time() + 3600); // Срок жизни токена 1 час
// }