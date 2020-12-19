<?php

namespace Core\Services;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Manager;

/**
 * Class Session
 *
 * @package Core\Services
 */
class Session implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('session', function () {
            $manager = new Manager();
            $manager
                ->setAdapter(new Stream([
                    'savePath' => '/tmp',
                ]))
                ->setName('auth-session')
                ->start();

            return $manager;
        });
    }

}