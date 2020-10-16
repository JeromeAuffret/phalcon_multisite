<?php

namespace Component;

use Phalcon\Di;

/**
 * Class Config
 *
 * @package Component
 */
final class Router extends \Phalcon\Mvc\Router
{

    /**
     * @return void
     */
    public function registerRouter(): void
    {
        $container = Di::getDefault();
        $config = $container->get('config');
        $modules = $config->get('modules');

        $defaultModule = $config->get('defaultModule');

        if ($config->get('applicationType') === 'modules')
        {
            $module = $modules->get($defaultModule);
            $namespace = preg_replace('/Module$/', 'Controllers', $module->get('className'));

            $defaultController = $module->get('defaultController') ?? $config->get('defaultController');
            $defaultAction = $module->get('defaultAction') ?? $config->get('defaultAction');

            $container->get('router')->setDefaultNamespace($namespace);
            $container->get('router')->setDefaultModule($defaultModule);

            $container->get('router')->setDefaultController($defaultController);
            $container->get('router')->setDefaultAction($defaultAction);
        }
    }

}