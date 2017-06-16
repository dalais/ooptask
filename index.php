<?php
session_start();
// Вывод ошибок
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT', dirname(__FILE__));

// Подключение автозагрузчика классов composer
require_once "./vendor/autoload.php";

require_once "./config/DbConnection.php";

// Вызов роутера

$router = new MyClasses\components\Router();

$router->run();

function d($value = null, $die = 1){
    echo 'Debug: <br /> <pre>';
    print_r($value);
    echo '</pre>';
    if ($die) die;
}