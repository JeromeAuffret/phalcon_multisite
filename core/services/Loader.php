<?php

namespace Service;

use Component\Loader as LoaderComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Loader
 *
 * @package Service
 */
class Loader implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('loader', function () {
            return new LoaderComponent();
        });
    }

}