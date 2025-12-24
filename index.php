<?php
session_start();

// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/debug.log');
// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL);

// error_log("🚀 Script started - logs are working!");

require_once 'autoload.php';
require_once 'config.php';
require_once 'database.php';

require_once __DIR__ . '/helpers.php';
// Загружаем роутер
require_once __DIR__ . '/router.php';

// Запускаем диспетчер маршрутов
Router::dispatch();