<?php

$basePath = \Phalcon\Di::getDefault()->get('application')->getBasePath();
$applicationPth = \Phalcon\Di::getDefault()->get('application')->getTenantPath();

return [
    'api' => [
        'className' => 'Base\Modules\Api\Module',
        'path' => $basePath . '/modules/api/Module.php'
    ],
    'dashboard' => [
        'className' => 'Demo1\Modules\Dashboard\Module',
        'path' => $applicationPth . '/modules/dashboard/Module.php'
    ],
    'order' => [
        'className' => 'Demo1\Modules\Order\Module',
        'path' => $applicationPth . '/modules/order/Module.php'
    ],
    'product' => [
        'className' => 'Demo1\Modules\Product\Module',
        'path' => $applicationPth . '/modules/product/Module.php'
    ]
];
