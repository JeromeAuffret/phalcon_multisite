<?php

namespace Middleware;

use Component\Application as ApplicationComponent;
use Component\Config;
use Component\Session;
use Exception;
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
     * Dispatch applications on MVC boot event
     * Register Common applicationProvider by default
     *
     * @param Event                $event
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
        if (!$application->hasApplication()) {
            $application->registerCommonProvider();
        }
    }

    /**
     * Register specific module events from moduleProvider
     *
     * @param Event $event
     * @param ApplicationComponent $application
     * @return void
     * @throws Exception
     */
    public function afterStartModule(Event $event, ApplicationComponent $application)
    {
        $moduleName = $this->router->getModuleName();
        $module = $application->getModule($moduleName);

        $moduleClass = $module['className'];
        $moduleClass = new $moduleClass;

        $moduleClass->registerEvents($this->getDI());
    }

    /**
     * Setup application if defined in session
     *
     * @throws Exception
     */
    private function dispatchApplicationBySession()
    {
        if ($this->session && $this->session->hasApplication())
        {
            $this->application->registerApplication(
                $this->session->getApplication('slug')
            );
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
        $serverName = $this->config->get('serverName');

        if ($this->config->has('host') && $this->config->get('host')->has($serverName))
        {
            $this->application->registerApplication(
                $this->config->get('host')->get($serverName)
            );
        }
    }

    /**
     * Setup application defined in request parameters
     *
     * @throws Exception
     */
    private function dispatchApplicationByHash()
    {
        if ($this->request->has('_app'))
        {
            $this->application->registerApplication(
                $this->request->get('_app')
            );
        }
    }

}