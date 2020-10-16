<?php

namespace Service;

use Component\Application as ApplicationComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Acl
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
            return new ApplicationComponent($container);
        });
    }

}