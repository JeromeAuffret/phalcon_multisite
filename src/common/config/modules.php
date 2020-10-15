<?php

return [
    'auth' => [
        'className' => 'Common\Modules\Auth\Module',
        'path' => COMMON_PATH . '/modules/auth/Module.php',
        'defaultController' => 'login'
    ],
    'admin' => [
        'className' => 'Common\Modules\Admin\Module',
        'path' => COMMON_PATH . '/modules/admin/Module.php'
    ],
    'api' => [
        'className' => 'Common\Modules\Api\Module',
        'path' => COMMON_PATH . '/modules/api/Module.php'
    ],
    'order' => [
        'className' => 'Demo1\Modules\Order\Module',
        'path' => BASE_PATH . '/src/apps/demo1/modules/order/Module.php'
    ],
];
