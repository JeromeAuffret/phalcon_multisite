<?php

namespace Service;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Component\Dispatcher as DispatcherComponent;

/**
 * Class Url
 *
 * @package Service
 */
class Dispatcher implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('dispatcher', function () {
            return new DispatcherComponent();
        });
    }

}