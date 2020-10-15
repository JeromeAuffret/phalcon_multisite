<?php

namespace Service;

use Component\Config as ConfigComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Config
 *
 * @package Service
 */
class Config implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('config', function () {
            return new ConfigComponent();
        });

        // Register main configuration
        $container->get('config')->registerMainConfig();
    }

}