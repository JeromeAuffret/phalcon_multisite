<?php

namespace Service;

use Component\SessionManager;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Manager;

/**
 * Class Session
 *
 * @package Service
 */
class Session implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('session', function ()
        {
            $manager = new Manager();
            $manager
                ->setAdapter(new Stream())
                ->setName('auth-session')
                ->start();

            return $manager;
        });

        $container->setShared('sessionManager', function ()
        {
            return new SessionManager();
        });
    }

}