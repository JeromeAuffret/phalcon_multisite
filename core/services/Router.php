<?php

namespace Service;

use Component\Router as RouterComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Router
 *
 * @package Service
 */
class Router implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('router', function () use ($container) {
            $router =  new RouterComponent();

            $router->setDI($container);
            $router->initDefaults();

            return $router;
        });
    }

}