<?php

namespace Core\Middlewares;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Core\Components\Console as ConsoleComponent;

/**
 * Class Controller
 *
 * @package Middleware
 */
class Cli extends Injectable
{

    /**
     * Log start script
     *
     * @param Event $event
     * @param ConsoleComponent $console
     * @return void
     */
    public function boot(Event $event, ConsoleComponent $console)
    {
        echo '['.date('Y-m-d H:i:s').'] Start console'.PHP_EOL.PHP_EOL;
    }

    /**
     * Log start module
     *
     * @param Event   $event
     * @param ConsoleComponent $console
     * @return void
     * @throws Exception
     */
    public function beforeStartModule(Event $event, ConsoleComponent $console)
    {
        echo '['.date('Y-m-d H:i:s').'] Start module'.PHP_EOL.PHP_EOL;
    }


}