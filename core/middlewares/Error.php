<?php

namespace Middleware;

use Component\Acl;
use Component\Config;
use Component\Session;
use Error\AclException;
use Error\AuthException;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

/**
 * Class Error
 *
 * @property Acl    acl
 * @property Config config
 * @property Session session
 * @package Middleware
 */
class Error extends Injectable
{

    /**
     * Dispatch errors messages and status code
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     * @param            $exception
     * @return false|void
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {
        /* @var Exception $exception */
//        error_log($exception->getMessage());
//        error_log($exception->getTraceAsString());

        // Add default error status code
        $this->response->setStatusCode(500, 'Internal Server Error');

        // Catch dispatch exception and render error page
        if ($exception instanceof DispatchException)
        {
            switch ($exception->getCode())
            {
                case DispatchException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatchException::EXCEPTION_ACTION_NOT_FOUND:
                    $this->response->setStatusCode(404, 'Not Found');
                    $dispatcher->forward([
                        'namespace'  => 'Controllers',
                        'controller' => 'error',
                        'action'     => 'NotFound',
                    ]);
                    return false;

                case DispatchException::EXCEPTION_INVALID_HANDLER:
                case DispatchException::EXCEPTION_INVALID_PARAMS:
                    $dispatcher->forward([
                        'namespace'  => 'Controllers',
                        'controller' => 'error',
                        'action'     => 'InternalError',
                    ]);
                    return false;
            }

            $this->sendResponse();
        }

        // Redirect to the login page in case of Auth Exception
        if ($exception instanceof AuthException)
        {
            if ($this->view->isDisabled()) {
                $this->response->setStatusCode(401, $exception->getMessage());
            }
            else {
                $this->session->destroy();
                $this->response->redirect('auth/login');
            }

            $this->sendResponse();
        }

        // Redirect to the main page in case of Acl Exception
        if ($exception instanceof AclException)
        {
            if ($this->view->isDisabled()) {
                $this->response->setStatusCode(403, $exception->getMessage());
            }
            else {
                // Redirect to the default module if the user is allowed to
                if ($this->acl && $this->acl->userAllowed($this->config->get('defaultModule'))) {
                    $this->response->redirect('');
                } else {
                    $this->response->redirect('auth/login');
                }
            }

            $this->sendResponse();
        }
    }

    /**
     * Send response if not already sent
     */
    private function sendResponse()
    {
        if (!$this->response->isSent()) {
            $this->response->send();
            exit();
        }
    }

}