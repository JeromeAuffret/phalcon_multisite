<?php

$application_path = \Phalcon\Di::getDefault()->get('application')->getApplicationPath();
$common_path = \Phalcon\Di::getDefault()->get('application')->getCommonPath();

return [
    'admin' => [
        'className' => 'Common\Modules\Admin\Module',
        'path' => $common_path . '/modules/admin/Module.php'
    ],
    'api' => [
        'className' => 'Common\Modules\Api\Module',
        'path' => $common_path . '/modules/api/Module.php'
    ],
    'dashboard' => [
        'className' => 'Demo1\Modules\Dashboard\Module',
        'path' => $application_path . '/modules/dashboard/Module.php'
    ],
    'order' => [
        'className' => 'Demo1\Modules\Order\Module',
        'path' => $application_path . '/modules/order/Module.php'
    ],
    'product' => [
        'className' => 'Demo1\Modules\Product\Module',
        'path' => $application_path . '/modules/product/Module.php'
    ]
];
