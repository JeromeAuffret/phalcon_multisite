<?php

$commonPath = \Phalcon\Di::getDefault()->get('application')->getCommonPath();

return [
    'auth' => [
        'className' => 'Common\Modules\Auth\Module',
        'path' => $commonPath . '/modules/auth/Module.php',
        'defaultController' => 'login'
    ]
];
