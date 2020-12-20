<?php

namespace Core\Middlewares;

use Core\Components\Acl;
use Core\Components\Config;
use Core\Errors\AclException;
use Core\Errors\AuthException;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use ReflectionException;

/**
 * Class Error
 *
 * @property Acl    acl
 * @property Config config
 * @package Middleware
 */
class CliError extends Injectable
{

    /**
     * Controller errors messages and status code
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     * @param            $exception
     * @return false|void
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {
        /* @var Exception $exception */
        echo $exception->getMessage();
        echo $exception->getTraceAsString();
    }

}