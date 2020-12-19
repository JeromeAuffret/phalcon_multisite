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
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('mvc', function () use ($di) {
            return new \Phalcon\Mvc\Application($di);
        });
    }

}