<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$error = '';
$username  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $username = $_POST['password'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель | MILOVAN4IK.RU</title>
    
    <!-- Основные стили сайта -->
    <link rel="stylesheet" href="/styles/common.css">
    
    <!-- Стили страницы логина -->
    <link rel="stylesheet" href="/styles/admin/login-new.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            
            <!-- Тело формы -->
            <div class="login-body">
                <?php if ($error): ?>
                    <div class="error-message" id="errorMessage">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="login-title">
                    <i class="fas fa-lock"></i>
                    Вход в админку
                </div>
          
                <form method="POST" action="/admin/login" id="loginForm">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                placeholder="Логин"
                                value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                                required
                                autocomplete="username"
                                autofocus
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="fas fa-key input-icon"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Пароль"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="remember-row">
                        <div class="checkbox-wrapper" id="rememberCheckbox">
                            <div class="checkbox-input" id="rememberCheckboxInput"></div>
                            <span class="checkbox-label">Запомнить меня</span>
                            <input type="hidden" name="remember" id="rememberInput" value="0">
                        </div>
                    </div>
                    
                    <button type="submit" class="login-button">
                        <i class="fas fa-sign-in-alt"></i>
                        Войти
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Скрипты -->
    <script src="/scripts/pages/admin-login.js"></script>
</body>
</html>