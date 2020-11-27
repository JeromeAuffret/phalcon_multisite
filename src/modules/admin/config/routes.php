<?php

use Phalcon\Di;

$modules = Di::getDefault()->get('config')->get('modules');
$namespace = preg_replace('/Module$/', 'Controllers', $modules->get('admin')->get('className'));

/**
 *  Add specific routes to the Reference Pages
 */
$router = Di::getDefault()->get('router');

