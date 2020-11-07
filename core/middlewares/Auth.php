<?php

namespace Middlewares;

use Common\Acl\AclComponent;
use Component\Acl;
use Component\SessionManager;
use Error\AuthException;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

/**
 * Class Auth
 *
 * @property SessionManager sessionManager
 * @property Acl acl
 * @package Middleware
 */
class Auth extends Injectable
{

    /**
     * Check the connexion validity of the current user
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     * @throws AuthException
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $moduleName = $this->router->getModuleName();
        $controllerName = $this->router->getControllerName();
        $actionName = $this->router->getActionName();
        $params = $this->router->getParams();

        $aclComponentClass = $this->dispatcher->dispatchNamespace(AclComponent::class);
        $AclComponent = new $aclComponentClass($moduleName, $controllerName, $actionName, $params);

        // Allow access to public components
        if ($AclComponent->isPublicComponent()) {
            return true;
        }
        // For the auth module, we allow a registered user
        else if ($moduleName === 'auth' && $this->sessionManager->hasUser()) {
            return true;
        }
        // Verify user and application otherwise
        else if ($this->sessionManager->hasUser() && $this->sessionManager->hasApplication()) {
            return true;
        }

        // Throw Unauthorized Exception
        throw new AuthException('Unauthorized');
    }

}