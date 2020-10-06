<?php

namespace Middleware;

use Component\Acl as AclComponent;
use Error\AclException;
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
class Loader extends Injectable
{

    /**
     * Check if user can access to the resource
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     * @throws AclException
     */
    public function beforeCheckClass(Event $event, Dispatcher $dispatcher)
    {
        $moduleName = $dispatcher->getModuleName();
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();
        $params = $dispatcher->getParams();

        try {
            if (!$this->acl->userAllowed($moduleName, $controllerName, $actionName, $params)) {
                throw new AclException('Unauthorized');
            }
        } catch (Exception $e) {
            throw new AclException($e->getMessage(), $e->getCode(), $e);
        }
    }

}