<?php
//
//use Phalcon\Di;
//
//$router = Di::getDefault()->get('router');
//$config = Di::getDefault()->get('config');
//
//if ($config->get('applicationType') === 'modules')
//{
//    $router->add('/order/:params', [
//        'namespace' => 'Demo1\Modules\Order',
//        'module' => 'order',
//        'controller' => 'index',
//        'action' => 'index',
//        'params' => 1
//    ]);
//
//    foreach ($config->get('modules') as $key => $module)
//    {
//        $namespace = preg_replace('/Module$/', 'Controllers', $module->get("className"));
//
//        $router->add('/'.$key.'/:params', [
//            'namespace' => $namespace,
//            'module' => $key,
//            'controller' => $module->get('defaultController') ?? $config->get('defaultController'),
//            'action' => $module->get('defaultAction') ?? $config->get('defaultAction'),
//            'params' => 1
//        ]);
//
//        $router->add('/'.$key.'/:controller/:params', [
//            'namespace' => $namespace,
//            'module' => $key,
//            'controller' => 1,
//            'action' => $module->get('defaultAction') ?? $config->get('defaultAction'),
//            'params' => 2
//        ]);
//
//        $router->add('/'.$key.'/:controller/:action/:params', [
//            'namespace' => $namespace,
//            'module' => $key,
//            'controller' => 1,
//            'action' => 2,
//            'params' => 3
//        ]);
//    }
//}
