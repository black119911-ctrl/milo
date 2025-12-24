<?php
function printDirectoryTree($dir, $prefix = '') {
    $files = scandir($dir);
    
    foreach ($files as $key => $file) {
        if ($file == '.' || $file == '..') continue;
        
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        $isLast = ($key === count($files) - 1);
        
        echo $prefix . ($isLast ? '└── ' : '├── ') . $file . "\n";
        
        if (is_dir($path)) {
            printDirectoryTree($path, $prefix . ($isLast ? '    ' : '│   '));
        }
    }
}

// Использование
echo "Структура проекта:\n";
printDirectoryTree(__DIR__);