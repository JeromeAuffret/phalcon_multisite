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
     * Dispatch application on first application hanf
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

        // Dispatch application class
        $this->dispatchCommonClass();
        $this->dispatchApplicationClass();

        // Disable view for call ajax or external call
        $this->isViewDisabled();

        // Set default module based on default configuration
        $this->setDefaultModuleName($this->dispatcher);
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
     *
     * @param Dispatcher $dispatcher
     */
    private function setDefaultModuleName(Dispatcher $dispatcher)
    {
        if ($this->config->get('applicationType') === 'modules' && !$dispatcher->getModuleName()) {
            $dispatcher->setModuleName($this->config->get('defaultModule'));
        }
    }

    /**
     * Setup application loader if application is defined in session
     *
     * @throws Exception
     */
    private function dispatchApplicationBySession()
    {
        // Register application service if it is store in session
        if ($this->session && $this->session->hasApplication())
        {
            $this->application->registerApplicationServices(
                $this->session->getApplication('slug')
            );
        }
    }

    /**
     * Setup application loader if application is defined in host configuration
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
     * Setup application loader if application is defined in request parameters
     *
     * @throws Exception
     */
    private function dispatchApplicationByHash()
    {
        if ($this->request->has('_app') && $application = Application::getBySlug($this->request->get('_app'))) {
            $this->application->registerApplicationServices($application->getSlug());
        }
    }

    /**
     *
     */
    private function dispatchCommonClass()
    {
        $applicationClass = $this->application->getCommonNamespace().'\\'.$this->application->getApplicationClass();

        /** @var \Common\Application $application */
        $application = new $applicationClass();

        $application->registerAutoloaders($this->getDI());
        $application->registerServices($this->getDI());
    }

    /**
     *
     */
    private function dispatchApplicationClass()
    {
        if (!$this->application->hasApplication()) return;

        $applicationClass = $this->application->getApplicationNamespace().'\\'.$this->application->getApplicationClass();

        /** @var \Common\Application $application */
        $application = new $applicationClass();

        $application->registerAutoloaders($this->getDI());
        $application->registerServices($this->getDI());
    }

}