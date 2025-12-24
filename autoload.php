<?php
// autoload.php
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
        __DIR__ . '/classes/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});