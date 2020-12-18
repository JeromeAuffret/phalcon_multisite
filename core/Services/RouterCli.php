<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Router
 *
 * @package Core\Services
 */
class RouterCli implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('router', function () use ($container) {
            $router =  new \Phalcon\Cli\Router();
            $router->setDI($container);

            return $router;
        });
    }

}