<?php

namespace Core\Middlewares;

use Core\Components\Application;
use Core\Components\Console;
use Core\Helpers\NamespaceHelper;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Dispatcher\Exception as DispatcherException;
use Phalcon\Cli\Router;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Helper\Str;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use ReflectionException;

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
     * Dispatch correct task namespace in dispatcher
     * Throw a dispatch exception in case of invalid arguments
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     * @throws DispatcherException
     * @throws ReflectionException
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // Dispatch task between base and tenant namespace
        $taskClass = Str::camelize($this->dispatcher->getTaskName()).$this->dispatcher->getTaskSuffix();
        if ($this->dispatcher->getModuleName()) {
            $taskNamespace = NamespaceHelper::dispatchModuleClass($taskClass, $this->dispatcher->getModuleName(),'Tasks');
        } else {
            $taskNamespace = NamespaceHelper::dispatchClass($taskClass,'Tasks');
        }

        echo '['.date('Y-m-d H:i:s').'] Tenant : '.($this->application->getTenant('name') ?: 'Base').PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Task : '.$this->dispatcher->getTaskName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Action : '.$this->dispatcher->getActionName().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Params : '.$this->console->getParams()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Options : '.$this->console->getOptions()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Namespace : '.$taskNamespace.PHP_EOL;
        echo '=========================================================='.PHP_EOL;

        // Throw exception if task namespace is not correctly registered
        if (!$taskNamespace) throw new DispatcherException(
            'Task '.$this->dispatcher->getTaskName().' not found '.($this->dispatcher->getModuleName() ? 'in module ' . $this->dispatcher->getModuleName() : ''),
            DispatchException::EXCEPTION_HANDLER_NOT_FOUND
        );

        // Then register correct namespace in dispatcher service
        $this->dispatcher->setNamespaceName((new \ReflectionClass($taskNamespace))->getNamespaceName());
    }

    /**
     * Close task prompt section
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher) {
        echo '=========================================================='.PHP_EOL;
    }

}