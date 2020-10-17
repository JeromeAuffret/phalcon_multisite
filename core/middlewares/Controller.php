<?php

namespace Middleware;

use Component\Config;
use Component\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

/**
 * Class Controller
 *
 * @property Application application
 * @property Config config
 * @package Middleware
 */
class Controller extends Injectable
{

    /**
     * Dispatch controllers between common and application folder
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // Register correct controller in dispatcher
        $this->application->dispatchController($dispatcher);
    }

}