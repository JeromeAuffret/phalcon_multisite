<?php

namespace Core\Middlewares;

use Core\Components\Acl;
use Core\Components\Config;
use Core\Errors\AclException;
use Core\Errors\AuthException;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
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
class Error extends Injectable
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
        error_log($exception->getMessage());
        error_log($exception->getTraceAsString());

        // Add default error status code
        $this->response->setStatusCode(500, 'Internal Server Error');

        // Catch dispatch exception and render error page
        if ($exception instanceof DispatchException || $exception instanceof ReflectionException)
        {
            switch ($exception->getCode())
            {
                case -1: // ReflectionException
                case DispatchException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatchException::EXCEPTION_ACTION_NOT_FOUND:
                    $this->forwardNotFound();
                    return false;

                case DispatchException::EXCEPTION_INVALID_HANDLER:
                case DispatchException::EXCEPTION_INVALID_PARAMS:
                    $this->forwardInternalError();
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
                $this->response->redirect('auth');
            }

            $this->sendResponse();
        }

        // Redirect to the main page in case of Acl Exception
        if ($exception instanceof AclException)
        {
            if ($this->view->isDisabled()) {
                $this->response->setStatusCode(403, $exception->getMessage());
            }
            // Redirect to the default module or controller if the user is allowed to
            else {
                $tenantType = $this->config->get('tenantType');
                $defaultModule = $this->config->get('defaultModule');
                $defaultController = $this->config->get('defaultController');

                if ($this->acl && $tenantType === 'simple' && $this->acl->userAllowed(null, $defaultController)) {
                    $this->response->redirect('');
                }
                elseif ($this->acl && $tenantType === 'modules' && $this->acl->userAllowed($defaultModule)) {
                    $this->response->redirect('');
                }
                else {
                    $this->session->destroy();
                    $this->response->redirect('auth');
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

    /**
     *
     */
    private function forwardNotFound()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->dispatcher->forward([
            'namespace'  => 'Base\Controllers',
            'controller' => 'error',
            'action'     => 'NotFound',
        ]);
    }

    /**
     *
     */
    private function forwardInternalError()
    {
        $this->response->setStatusCode(500, 'Internal Server Error');
        $this->dispatcher->forward([
            'namespace'  => 'Base\Controllers',
            'controller' => 'error',
            'action'     => 'InternalError',
        ]);
    }

}