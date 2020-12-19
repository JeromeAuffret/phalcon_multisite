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
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('config', function () use ($di) {
            $config = new ConfigComponent();
            $config->registerMainConfig($di);

            return $config;
        });
    }

}