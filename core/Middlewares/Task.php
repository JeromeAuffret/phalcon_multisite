<?php

namespace Core\Middlewares;

use Core\Components\Application;
use Core\Components\Console;
use Libraries\NamespaceHelper;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use ReflectionException;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

/**
 * Class Controller
 *
 * @property Application $application
 * @property Dispatcher $dispatcher
 * @property Router $router
 * @property Console $console
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
     * @throws DispatchException
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $taskClass = NamespaceHelper::dispatchNamespace(
            $this->dispatcher->getHandlerClass()
        );

        try {
            $this->dispatcher->setNamespaceName((new \ReflectionClass($taskClass))->getNamespaceName());
        }
        catch (ReflectionException $e) {
            throw new DispatchException('Not found', DispatchException::EXCEPTION_HANDLER_NOT_FOUND, $e);
        }

        echo '['.date('Y-m-d H:i:s').'] Start Tenant : '.$this->application->getTenant('name').PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Namespace : '.$dispatcher->getNamespaceName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Task : '.$dispatcher->getTaskName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Action : '.$dispatcher->getActionName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Params : '.$this->console->getParams()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Options : '.$this->console->getOptions()->toJson().PHP_EOL;
        echo '=========================================================='.PHP_EOL;
    }

    /**
     * Log start module
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher) {
        echo '=========================================================='.PHP_EOL;
    }


}