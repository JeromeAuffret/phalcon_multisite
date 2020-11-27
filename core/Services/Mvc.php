<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Tenant
 *
 * @package Core\Services
 */
class Mvc implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('mvc', function () use ($container) {
            return new \Phalcon\Mvc\Application($container);
        });
    }

}