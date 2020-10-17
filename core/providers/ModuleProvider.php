<?php

namespace Provider;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;


class ModuleProvider implements ModuleDefinitionInterface
{
    /**
     * @param DiInterface $container
     * @param $moduleName
     * @param $module
     */
    public function initialize(DiInterface $container, $moduleName, $module)
    {
        $this->registerRouter($container, $moduleName, $module);
        $this->registerAcl($container);
    }

    /**
     *  Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null) {}

    /**
     *  Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register router related to the module
     *
     * @param DiInterface $container
     * @param $moduleName
     * @param $module
     */
    public function registerRouter(DiInterface $container, $moduleName, $module)
    {
        if ($container->get('config')->get('applicationType') === 'modules')
        {
            $router = $container->get('router');
            $config = $container->get('config');

            $namespace = preg_replace('/Module$/', 'Controllers', $module->get("className"));

            $router->add('/'.$moduleName.'/:params', [
                'namespace' => $namespace,
                'module' => $moduleName,
                'controller' => $module->get('defaultController') ?? $config->get('defaultController'),
                'action' => $module->get('defaultAction') ?? $config->get('defaultAction'),
                'params' => 1
            ]);

            $router->add('/'.$moduleName.'/:controller/:params', [
                'namespace' => $namespace,
                'module' => $moduleName,
                'controller' => 1,
                'action' => $module->get('defaultAction') ?? $config->get('defaultAction'),
                'params' => 2
            ]);

            $router->add('/'.$moduleName.'/:controller/:action/:params', [
                'namespace' => $namespace,
                'module' => $moduleName,
                'controller' => 1,
                'action' => 2,
                'params' => 3
            ]);
        }
    }

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container) {}

}
