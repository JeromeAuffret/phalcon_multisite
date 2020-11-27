<?php

$basePath = \Phalcon\Di::getDefault()->get('application')->getBasePath();

return [
    'auth' => [
        'className' => 'Base\Modules\Auth\Module',
        'path' => $basePath . '/modules/auth/Module.php',
        'defaultController' => 'login'
    ]
];
