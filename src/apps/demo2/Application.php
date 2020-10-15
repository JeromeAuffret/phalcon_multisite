<?php

namespace Demo2;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;


class Application implements ModuleDefinitionInterface
{

    /**
     *  Registers an autoloader related to the application
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null)
    {
        $applicationPath = $container->get('application')->getApplicationPath();

        // Register application's namespaces
        (new \Phalcon\Loader())
            ->registerNamespaces([
                "Demo2\\Controllers" => $applicationPath.'/controllers'
            ])
            ->register();
    }

    /**
     *  Registers services related to the application
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

}
