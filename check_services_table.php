<?php
// check_services_table.php
require_once 'database.php';
$pdo = getPdoConnection();

try {
    $result = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='services'");
    $sql = $result->fetchColumn();
    echo "Структура таблицы services:\n";
    echo $sql ?: "Таблица services не существует";
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>