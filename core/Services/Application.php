<?php

namespace Core\Services;

use Core\Components\Application as ApplicationComponent;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Tenant
 *
 * @package Core\Services
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
            $application = new ApplicationComponent($container);

            return $application;
        });
    }

}