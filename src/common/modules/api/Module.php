<?php

namespace Common\Modules\Api;

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
                'Common\Modules\Api\Controllers'      => __DIR__ . '/controllers/',
                'Common\Modules\Api\Controllers\Data' => __DIR__ . '/controllers/data/',
                'Common\Modules\Api\Controllers\Form' => __DIR__ . '/controllers/form/',
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
        $dispatcher = $container->get('dispatcher');
        $eventsManager = $dispatcher->getEventsManager();

        $eventsManager->attach('dispatch:beforeDispatch', function () use($container, $dispatcher) {
            $container->get('loader')->dispatchApiController($dispatcher, $container->get('router'));
        });

        $dispatcher->setEventsManager($eventsManager);
        $container->setShared('dispatcher', $dispatcher);
    }

}
