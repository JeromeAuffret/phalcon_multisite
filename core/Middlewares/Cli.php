<?php

namespace Core\Middlewares;

use Core\Components\Config;
use Core\Components\Application as ApplicationComponent;
use Exception;
use Libraries\NamespaceHelper;
use Phalcon\Cli\Console;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;

/**
 * Class Dispatch
 *
 * @property ApplicationComponent application
 * @property Config config
 * @package Middleware
 */
class Cli extends Injectable
{

    /**
     * Dispatch applications on MVC boot event
     * Try to register specific application and initialise provider
     * By default, register base's provider
     *
     * @param Event   $event
     * @param Console $console
     * @return void
     * @throws Exception
     */
    public function boot(Event $event, Console $console)
    {
        echo '['.date('Y-m-d H:i:s').'] Start console'.PHP_EOL.PHP_EOL;
    }

    /**
     * Register specific module events from moduleProvider
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