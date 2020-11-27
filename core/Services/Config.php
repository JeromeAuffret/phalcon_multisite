<?php

namespace Core\Services;

use Core\Components\Config as ConfigComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Config
 *
 * @package Core\Services
 */
class Config implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('config', function () use ($container) {
            $config = new ConfigComponent();
            $config->registerMainConfig($container);

            return $config;
        });
    }

}