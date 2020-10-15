<?php

namespace Common\Modules\Auth;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;


class Module implements ModuleDefinitionInterface
{
    /**
     *  Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Common\Modules\Auth\Controllers' => $container->get('application')->getCommonModulePath('auth') . '/controllers/'
            ])
            ->register();
    }

    /**
     *  Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

}
