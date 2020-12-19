<?php

namespace Core\Services;

use Core\Middlewares\Dispatch as DispatchMiddleware;
use Core\Middlewares\Error as ErrorMiddleware;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Url
 *
 * @package Core\Services
 */
class Dispatcher implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('dispatcher', function () use ($di) {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();

            // Register core events in dispatcher
            $eventManager = $di->get('eventsManager');

            $eventManager->attach('dispatch', new DispatchMiddleware);
            $eventManager->attach('dispatch', new ErrorMiddleware);

            $dispatcher->setEventsManager($eventManager);

            return $dispatcher;
        });
    }

}