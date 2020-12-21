<?php

$basePath = \Phalcon\Di::getDefault()->get('application')->getBasePath();

return [
    'api' => [
        'className' => 'Base\Modules\Api\Module',
        'path' => $basePath . '/modules/api/Module.php'
    ],
    'auth' => [
        'className' => 'Base\Modules\Auth\Module',
        'path' => $basePath . '/modules/auth/Module.php',
        'defaultController' => 'login'
    ]
];
