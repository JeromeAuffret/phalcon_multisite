<?php

namespace Core\Services;

use Core\Mvc\Router as RouterMvc;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Router
 *
 * @package Core\Services
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
            $router =  new RouterMvc();

            $router->setDI($container);
            $router->initDefaults();

            return $router;
        });
    }

}