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
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('application', function () use ($di) {
            return new ApplicationComponent($di);
        });
    }

}