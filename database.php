<?php
// Настройки подключения к SQLite
define('DB_FILE', __DIR__ . '/database/database.sqlite');

// Вынесем получение PDO в отдельную функцию
function getPdoConnection(): PDO {
    try {
        $pdo = new PDO("sqlite:" . DB_FILE);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}