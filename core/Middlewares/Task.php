<?php

namespace Core\Middlewares;

use Core\Components\Application;
use Core\Components\Console;
use Core\Helpers\NamespaceHelper;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Dispatcher\Exception as DispatcherException;
use Phalcon\Cli\Router;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Helper\Str;
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
     * @throws DispatcherException
     * @throws ReflectionException
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        echo '['.date('Y-m-d H:i:s').'] Start Tenant : '.$this->application->getTenant('name').PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Task : '.$this->dispatcher->getTaskName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Action : '.$this->dispatcher->getActionName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Params : '.$this->console->getParams()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Options : '.$this->console->getOptions()->toJson().PHP_EOL;

        $taskClass = Str::camelize($this->dispatcher->getTaskName()).$this->dispatcher->getTaskSuffix();
        $taskNamespace = NamespaceHelper::dispatchClass($taskClass,'Tasks');

        echo '['.date('Y-m-d H:i:s').'] Namespace : '.$taskNamespace.PHP_EOL;
        echo '=========================================================='.PHP_EOL;

        // Throw exception if task namespace is not correctly registered
        if (!$taskNamespace) throw new DispatcherException(
            'Task '.$this->dispatcher->getHandlerClass().' not found ',
            DispatchException::EXCEPTION_HANDLER_NOT_FOUND
        );

        $this->dispatcher->setNamespaceName((new \ReflectionClass($taskNamespace))->getNamespaceName());
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