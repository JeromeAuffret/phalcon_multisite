<?php

namespace Service;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

use Middleware\Dispatch as DispatchMiddleware;
use Middleware\Auth as AuthMiddleware;
use Middleware\Error as ErrorMiddleware;
use Middleware\Controller as ControllerMiddleware;
use Middleware\Acl as AclMiddleware;

/**
 * Class Dispatcher
 *
 * @package Service
 */
class Dispatcher implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('dispatcher', function() {
            return new MvcDispatcher();
        });

        $dispatcher = $container->get('dispatcher');

        $eventsManager = $container->get('eventsManager');

        $eventsManager->attach('dispatch:beforeDispatch', new DispatchMiddleware());
        $eventsManager->attach('dispatch:beforeDispatch', new AuthMiddleware());
        $eventsManager->attach("dispatch:beforeDispatch", new AclMiddleware());
        $eventsManager->attach('dispatch:beforeDispatch', new ControllerMiddleware());
        $eventsManager->attach("dispatch:beforeException", new ErrorMiddleware());

        $dispatcher->setEventsManager($eventsManager);
    }

}