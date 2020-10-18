<?php

$commonPath = \Phalcon\Di::getDefault()->get('application')->getCommonPath();

return [
    'auth' => [
        'className' => 'Common\Modules\Auth\Module',
        'path' => $commonPath . '/modules/auth/Module.php',
        'defaultController' => 'login'
    ],
    'admin' => [
        'className' => 'Common\Modules\Admin\Module',
        'path' => $commonPath . '/modules/admin/Module.php'
    ],
    'api' => [
        'className' => 'Common\Modules\Api\Module',
        'path' => $commonPath . '/modules/api/Module.php'
    ]
];
