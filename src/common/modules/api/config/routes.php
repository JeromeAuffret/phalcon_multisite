<?php

use Phalcon\Di;

$modules = Di::getDefault()->get('config')->get('modules');
$namespace = preg_replace('/Module$/', 'Controllers', $modules->get('api')->get('className'));

/**
 *      Update Reference Module
 */
$router = Di::getDefault()->get('router');

$router->add('/api/{reference}/:controller/:action/:params', [
    'namespace' => $namespace,
    'module' => 'api',
    'controller' => 2,
    'action' => 3,
    'params' => 4
]);