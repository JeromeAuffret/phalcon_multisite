<?php

namespace Middleware;

use Component\Dispatcher as DispatcherComponent;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;
use ReflectionException;

/**
 * Class Dispatch
 *
 * @property DispatcherComponent dispatcher
 * @package Middleware
 */
class Dispatch extends Injectable
{

    /**
     * Check if user can access to the resource
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     * @throws ReflectionException
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // We destroy session on login page
        if ($this->dispatcher->getActionName() === '_logout') {
            $this->session->destroy();
            $this->response->redirect('auth/login');
            $this->response->send();
        }

        $this->dispatcher->dispatchControllers();
    }

}