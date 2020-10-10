<?php

namespace Middleware;

use Component\Application as ApplicationComponent;
use Component\Config;
use Component\Loader;
use Component\Session;
use Exception;
use Models\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

/**
 * Class Dispatch
 *
 * @property ApplicationComponent application
 * @property Config config
 * @property Session session
 * @property Loader  loader
 * @package Middleware
 */
class Dispatch extends Injectable
{

    /**
     * Dispatch application before setup
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return void
     * @throws Exception
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // Set default module if not exist in dispatcher
        if (!$dispatcher->getModuleName()) {
            $dispatcher->setModuleName($this->config->get('defaultModule'));
        }

        // Disable view for call ajax or external call
        $this->isViewDisabled();

        // Check if an application can be identified
        $this->dispatchApplicationByHost();
        $this->dispatchApplicationByHash();
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
     * Setup application loader in case of server_name matching host
     *
     * @throws Exception
     */
    private function dispatchApplicationByHost()
    {
        // Find server_name in main host configuration
        $server_name = $this->config->get('serverName');
        if ($this->config->has('host') && $this->config->get('host')->has($server_name))
        {
            if ($application = Application::getBySlug($this->config->get('host')->get($server_name))) {
                $this->application->registerApplicationServices($application->getSlug());
            }
        }
    }

    /**
     * Setup application loader in case of request has application_slug in parameters
     *
     * @throws Exception
     */
    private function dispatchApplicationByHash()
    {
        if ($this->request->has('_app') && $application = Application::getBySlug($this->request->get('_app'))) {
            $this->application->registerApplicationServices($application->getSlug());
        }
    }

}