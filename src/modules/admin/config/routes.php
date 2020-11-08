<?php

use Phalcon\Di;

$modules = Di::getDefault()->get('config')->get('modules');
$namespace = preg_replace('/Module$/', 'Controllers', $modules->get('admin')->get('className'));

/**
 *  Add specific routes to the Reference Pages
 */
$router = Di::getDefault()->get('router');

$router->add('/admin/reference/{reference}/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 'reference',
    'action' => 'index',
    'params' => 2
]);

$router->add('/admin/reference/{reference}/:action/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 'reference',
    'action' => 2,
    'params' => 3
]);