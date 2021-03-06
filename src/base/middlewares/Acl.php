<?php

namespace Base\Middlewares;

use Core\Components\Acl as AclComponent;
use Core\Errors\AclException;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

/**
 * Class Acl
 *
 * @property AclComponent acl
 * @package Middleware
 */
class Acl extends Injectable
{

    /**
     * Check if user can access to the resource
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     * @throws AclException
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $moduleName = $this->dispatcher->getModuleName();
        $controllerName = $this->dispatcher->getControllerName();
        $actionName = $this->dispatcher->getActionName();
        $params = $this->dispatcher->getParams();

        try {
            if (!$this->acl->userAllowed($moduleName, $controllerName, $actionName, $params)) {
                throw new AclException("Unauthorized by acl middleware : $moduleName, $controllerName, $actionName, ".implode(',', $params));
            }
        } catch (Exception $e) {
            throw new AclException($e->getMessage(), $e->getCode(), $e);
        }
    }

}