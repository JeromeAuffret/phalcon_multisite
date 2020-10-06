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
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        try {
            if (!$this->acl->userAllowed()) {
                throw new AclException('Unauthorized');
            }
        } catch (Exception $e) {
            throw new AclException($e->getMessage(), $e->getCode(), $e);
        }
    }

}