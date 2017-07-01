<?php
// Errors
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT', dirname(__FILE__));

// Connecting the autoloader and initialisation session
require_once "./vendor/autoload.php";
require_once "./config/init.php";

// Startup of the router
$router = new MyClasses\components\Router();
$router->run();

function d($value = null, $die = 1){
    echo 'Debug: <br /> <pre>';
    var_dump($value);
    echo '</pre>';
    if ($die) die;
}
d($_SESSION);