<?php

$basePath = \Phalcon\Di::getDefault()->get('application')->getBasePath();
$applicationPth = \Phalcon\Di::getDefault()->get('application')->getTenantPath();

return [
    'admin' => [
        'className' => 'Base\Modules\Admin\Module',
        'path' => $basePath . '/modules/admin/Module.php'
    ],
    'api' => [
        'className' => 'Base\Modules\Api\Module',
        'path' => $basePath . '/modules/api/Module.php'
    ],
    'dashboard' => [
        'className' => 'Base\Modules\Dashboard\Module',
        'path' => $applicationPth . '/modules/dashboard/Module.php'
    ],
    'order' => [
        'className' => 'Base\Modules\Order\Module',
        'path' => $applicationPth . '/modules/order/Module.php'
    ],
    'product' => [
        'className' => 'Base\Modules\Product\Module',
        'path' => $applicationPth . '/modules/product/Module.php'
    ]
];
