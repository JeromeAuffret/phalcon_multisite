<?php

namespace Core\Middlewares;

use Exception;
use Phalcon\Cli\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Core\Components\Console as ConsoleComponent;

/**
 * Class Controller
 *
 * @package Middleware
 */
class Task extends Injectable
{

    /**
     * Log start script
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        echo '['.date('Y-m-d H:i:s').'] Dispatch task'.PHP_EOL.PHP_EOL;
    }

    /**
     * Log start module
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        echo '['.date('Y-m-d H:i:s').'] Execute route'.PHP_EOL.PHP_EOL;
    }


}