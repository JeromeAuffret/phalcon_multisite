<?php

namespace Service;

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
        $container->setShared('router', function () {
            return new \Phalcon\Mvc\Router();
        });
    }

}