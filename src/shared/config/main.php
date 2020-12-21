<?php

$serverName = $_SERVER['SERVER_NAME'] ?? null;
$baseUri = preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]);
$requestUri = '/'.str_replace($baseUri, '', ($_SERVER['REQUEST_URI'] ?? null));

return [
    'version' => '0.1',

    'tenantType' => 'modules', // simple , modules
    
    'maintenance' => false,

    'serverName' => $serverName,
    'baseUri'    => $baseUri,
    'requestUri' => $requestUri,

    'defaultModule'     => 'auth',
    'defaultController' => 'index',
    'defaultAction'     => 'index',

    'main_database' => [
        'adapter'  => 'Postgresql',
        'host'     => '127.0.0.1',
        'username' => 'postgres',
        'password' => '',
        'dbname'   => '',
        'port'     => '5432',
        'charset'  => 'utf8'
    ],

    'database' => [
        'adapter'  => 'Postgresql',
        'host'     => '127.0.0.1',
        'username' => 'postgres',
        'password' => '',
        'dbname'   => '',
        'port'     => '5432',
        'charset'  => 'utf8'
    ],

    'host' => [
        'demo1.localhost' => 'demo1',
        'demo2.localhost' => 'demo2',
    ],

    // publicComponents define an AclComponent's list where ACL and Auth middleware are disabled
    'publicComponents' => [
        '_error', '_logout', '_assets',
        'auth_index' // login page
    ]
];
