<?php

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

try {
    // Require composer's autoloader
    require_once BASE_PATH . '/vendor/autoload.php';

    // Handle mvc's application
    echo (new \Handlers\ApplicationHandler())->handle();
}
catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
