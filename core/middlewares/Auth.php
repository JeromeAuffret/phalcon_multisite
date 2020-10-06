<?php

namespace Middleware;

use Component\Acl;
use Component\Session;
use Error\AuthException;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

/**
 * Class Auth
 *
 * @property Session session
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
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // Allow access to public components
        if ($this->acl->isPublicComponent()) {
            return true;
        }
        // For the auth module, we allow a registered user
        else if ($dispatcher->getModuleName() === 'auth' || $this->session->hasUser()) {
            return true;
        }
        // Verify session otherwise
        else if ($this->session->hasUser() && $this->session->hasApplication()) {
            return true;
        }

        // Throw Unauthorized Exception
        throw new AuthException('Unauthorized');
    }

}