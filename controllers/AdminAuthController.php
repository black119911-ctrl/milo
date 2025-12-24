<?php
// controllers/AdminAuthController.php

// НЕ наследуем от BaseController, чтобы избежать зависимостей
class AdminAuthController {
    
    public function index($params) {
        // Если уже авторизован, перенаправляем в админку
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: /admin');
            exit;
        }
        
        $error = $_GET['error'] ?? '';

        $resources = [
            'styles' => ['/styles/admin/login.css'],
        ];
        
        return render_view('admin/login', [
            'title' => 'Вход в админ-панель',
            'error' => $error,
            'resources' => $resources
        ]);
    }

    public function login($params) {
        // Простая проверка - без базы данных
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Простая проверка логина/пароля
            if ($this->checkCredentials($username, $password)) {
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                header('Location: /admin');
                exit;
            } else {
                $error = 'Неверные учетные данные';
            }
        }

        render_view('admin/login', ['error' => $error ?? '']);
        
    }

    public function logout($params) {
        session_start();
        session_destroy();
        header('Location: /admin/login');
        exit;
    }

    private function checkCredentials($username, $password) {
        // Простая проверка - замените на свою логику
        $validUsers = [
            'admin' => 'admin123',
            'milovan4ik' => 'milovan4ik123'
        ];
        
        return isset($validUsers[$username]) && $validUsers[$username] === $password;
    }
}