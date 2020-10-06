<?php

$application_path = \Phalcon\Di::getDefault()->get('session')->getApplicationPath();

return [
    'dashboard' => [
        'className' => 'Demo1\Modules\Dashboard\Module',
        'path' => $application_path . '/modules/dashboard/Module.php'
    ],
    'order' => [
        'className' => 'Demo1\Modules\Order\Module',
        'path' => $application_path . '/modules/order/Module.php'
    ],
    'home' => [
        'className' => 'Demo1\Modules\Product\Module',
        'path' => $application_path . '/modules/home/Module.php'
    ]
];
