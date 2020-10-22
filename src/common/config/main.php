<?php

$serverName = $_SERVER['SERVER_NAME'];
$baseUri = preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]);
$requestUri = '/'.str_replace($baseUri, '', $_SERVER['REQUEST_URI']);

return [
    'version' => '0.1',

    'applicationType' => 'modules', // simple , modules
    
    'maintenance' => false,

    'serverName' => $serverName,
    'baseUri'    => $baseUri,
    'requestUri' => $requestUri,

    'defaultModule'     => 'admin',
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
        'auth_login',
        '_error'
    ]
];
