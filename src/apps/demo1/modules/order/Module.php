<?php

namespace Demo1\Modules\Order;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;


class Module implements ModuleDefinitionInterface
{

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Demo1\Modules\Order\Controllers' => __DIR__ . '/controllers/'
            ])
            ->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di) {}

}
