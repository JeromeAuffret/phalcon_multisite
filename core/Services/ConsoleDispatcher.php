<?php

namespace Core\Services;

use Core\Middlewares\Task as TaskMiddleware;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class Url
 *
 * @package Core\Services
 */
class ConsoleDispatcher implements ServiceProviderInterface
{

    /**
     * @param DiInterface $di
     *
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('dispatcher', function () use ($di) {
            $dispatcher = new \Phalcon\Cli\Dispatcher();

            // Register defaults for console dispatch
            $dispatcher->setDefaultNamespace('Base\Tasks');
            $dispatcher->setTaskSuffix('');

            // Register task events in dispatcher
            $eventManager = $di->get('eventsManager');
            $eventManager->attach('dispatch', new TaskMiddleware);

            $dispatcher->setEventsManager($eventManager);

            return $dispatcher;
        });
    }

}