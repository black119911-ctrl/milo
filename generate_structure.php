<?php
function generateProjectStructure($directory = '.', $indent = '') {
    $structure = '';
    $items = scandir($directory);
    
    // Исключаем системные файлы
    $items = array_diff($items, ['.', '..']);
    
    foreach ($items as $item) {
        $path = $directory . '/' . $item;
        
        if (is_dir($path)) {
            // Папка
            $structure .= $indent . "- {$item}/\n";
            $structure .= generateProjectStructure($path, $indent . '  ');
        } else {
            // Файл
            $structure .= $indent . "- {$item}\n";
            
            // Для PHP файлов можно добавить основное содержание
            if (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
                $structure .= generateFileSummary($path, $indent . '  ');
            }
        }
    }
    
    return $structure;
}

function generateFileSummary($filePath, $indent) {
    $content = file_get_contents($filePath);
    $summary = '';
    
    // Ищем классы
    if (preg_match('/class\s+(\w+)/', $content, $matches)) {
        $summary .= $indent . "(класс: {$matches[1]})\n";
    }
    
    // Ищем функции
    preg_match_all('/function\s+(\w+)\s*\(/', $content, $matches);
    if (!empty($matches[1])) {
        $functions = array_slice($matches[1], 0, 3); // Показываем первые 3 функции
        $summary .= $indent . "(функции: " . implode(', ', $functions);
        if (count($matches[1]) > 3) {
            $summary .= " ... еще " . (count($matches[1]) - 3);
        }
        $summary .= ")\n";
    }
    
    return $summary;
}

// Использование:
echo "Структура проекта:\n";
// echo generateProjectStructure('/');

// Или для текущей директории:
echo generateProjectStructure(__DIR__);
?>