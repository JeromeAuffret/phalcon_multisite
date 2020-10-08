<?php

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('SRC_PATH', BASE_PATH . "/src");
define('COMMON_PATH', SRC_PATH . "/common");
define('APPS_PATH', SRC_PATH . "/apps");

try {
    // Require composer's autoloader
    require_once BASE_PATH . '/vendor/autoload.php';

    // Handle mvc's application
    echo (new \Handler\ApplicationHandler())->handle();
}
catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
