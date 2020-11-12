<?php

namespace Service;

use Mvc\Application as ApplicationMvc;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Application
 *
 * @package Service
 */
class Application implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('application', function () use ($container) {
            return new ApplicationMvc($container);
        });
    }

}