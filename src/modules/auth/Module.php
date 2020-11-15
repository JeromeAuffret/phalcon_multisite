<?php

namespace Base\Modules\Auth;

use Phalcon\Di\DiInterface;
use Provider\ModuleProvider;

/**
 * Class Module
 *
 * @package Modules\Auth
 */
class Module extends ModuleProvider
{

    /**
     * Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Base\Modules\Auth\Controllers' => __DIR__ . '/controllers/'
            ])
            ->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        $container->get('router')->add('/'.$this->moduleName.'/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => $this->defaultController,
            'action' => $this->defaultAction,
            'params' => 1
        ]);

        $container->get('router')->add('/'.$this->moduleName.'/:controller/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => 1,
            'action' => $this->defaultAction,
            'params' => 2
        ]);

        $container->get('router')->add('/'.$this->moduleName.'/:controller/:action/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ]);
    }

    /**
     * Register events related to the module
     * This method is call only in the after start module event
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
