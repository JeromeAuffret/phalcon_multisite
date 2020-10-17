<?php

namespace Middleware;

use Component\Application as ApplicationComponent;
use Component\Config;
use Component\Session;
use Exception;
use Models\Application as ApplicationModel;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;

/**
 * Class Dispatch
 *
 * @property ApplicationComponent application
 * @property Config config
 * @property Session session
 * @package Middleware
 */
class Application extends Injectable
{
    /**
     * Dispatch apps when the application handles its first request
     *
     * @param Event $event
     * @param ApplicationComponent $application
     * @return void
     * @throws Exception
     */
    public function boot(Event $event, ApplicationComponent $application)
    {
        // Defined application service
        $this->dispatchApplicationBySession();
        $this->dispatchApplicationByHost();
        $this->dispatchApplicationByHash();

        // Register common provider if no application is defined
        if (!$this->application->hasApplication()) {
            $this->application->registerCommonProvider();
        }
    }

    /**
     * Setup application if defined in session
     *
     * @throws Exception
     */
    private function dispatchApplicationBySession()
    {
        // Register application service if it is store in session
        if ($this->session && $this->session->hasApplication()) {
            $this->application->registerApplication($this->session->getApplication('slug'));
        }
    }

    /**
     * Setup application defined in host configuration
     *
     * @throws Exception
     */
    private function dispatchApplicationByHost()
    {
        // Find server_name in main host configuration
        $server_name = $this->config->get('serverName');
        if ($this->config->has('host') && $this->config->get('host')->has($server_name))
        {
            if ($application = ApplicationModel::getBySlug($this->config->get('host')->get($server_name))) {
                $this->application->registerApplication($application->getSlug());
            }
        }
    }

    /**
     * Setup application defined in request parameters
     *
     * @throws Exception
     */
    private function dispatchApplicationByHash()
    {
        if ($this->request->has('_app') && $application = ApplicationModel::getBySlug($this->request->get('_app'))) {
            $this->application->registerApplication($application->getSlug());
        }
    }

}