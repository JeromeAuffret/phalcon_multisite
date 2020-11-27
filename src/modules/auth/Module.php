<?php

namespace Base\Modules\Auth;

use Phalcon\Di\DiInterface;
use Core\Providers\ModuleProvider;

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
     * Registers Services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container)
    {
        $this->registerDefaultRoutes($container);
    }

    /**
     * Register events related to the module
     * This method is call only in the module's afterStart event
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
