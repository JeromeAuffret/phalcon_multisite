<?php

namespace Service;

use Middleware\Dispatch as DispatchMiddleware;
use Middleware\Error as ErrorMiddleware;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Component\Dispatcher as DispatcherComponent;

/**
 * Class Url
 *
 * @package Service
 */
class Dispatcher implements ServiceProviderInterface
{

    /**
     * @param DiInterface $container
     *
     * @return void
     */
    public function register(DiInterface $container): void
    {
        $container->setShared('dispatcher', function () use ($container) {
            $dispatcher =  new DispatcherComponent();

            // Register core events in dispatcher
            $eventManager = $container->get('eventsManager');

            $eventManager->attach('dispatch', new DispatchMiddleware);
            $eventManager->attach("dispatch", new ErrorMiddleware);

            $dispatcher->setEventsManager($eventManager);

            return $dispatcher;
        });
    }

}