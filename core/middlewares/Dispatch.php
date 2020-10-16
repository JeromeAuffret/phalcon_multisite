<?php

namespace Middleware;

use Component\Application as ApplicationComponent;
use Component\Config;
use Component\Session;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

/**
 * Class Dispatch
 *
 * @property Config config
 * @property Session session
 * @package Middleware
 */
class Dispatch extends Injectable
{
    /**
     * Dispatch apps when the application handles its first request
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // Disable view for call ajax or external call
        $this->isViewDisabled();

        // Set default module based on default configuration
        $this->setDefaultModuleName();
    }

    /**
     * If request is ajax or does not have session cookie, we disable views
     */
    private function isViewDisabled()
    {
        if ($this->request->isAjax() || !$this->cookies->has($this->session->getName())) {
            $this->view->disable();
        }
    }

    /**
     * Set default dispatcher
     */
    private function setDefaultModuleName()
    {
        if ($this->config->get('applicationType') === 'modules' && !$this->dispatcher->getModuleName()) {
            $this->dispatcher->setModuleName($this->config->get('defaultModule'));
        }
    }

}