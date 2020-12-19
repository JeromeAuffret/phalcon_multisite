<?php

namespace Core\Middlewares;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Cli\Console as CliConsole;

/**
 * Class Dispatch
 *
 * @package Middleware
 */
class Console extends Injectable
{

    /**
     * Log start script
     *
     * @param Event $event
     * @param CliConsole $console
     * @return void
     */
    public function boot(Event $event, CliConsole $console)
    {
        echo '['.date('Y-m-d H:i:s').'] Start console'.PHP_EOL.PHP_EOL;
    }

    /**
     * Log start module
     *
     * @param Event   $event
     * @param Console $console
     * @return void
     * @throws Exception
     */
    public function beforeStartModule(Event $event, Console $console)
    {
        echo '['.date('Y-m-d H:i:s').'] Start module'.PHP_EOL.PHP_EOL;
    }


}