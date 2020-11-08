<?php

namespace Middleware;

use Component\Application as ApplicationComponent;
use Component\Config;
use Exception;
use Models\Application as ApplicationModel;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;

/**
 * Class Dispatch
 *
 * @property ApplicationComponent application
 * @property Config config
 * @package Middleware
 */
class Application extends Injectable
{

    /**
     * Dispatch applications on MVC boot event
     * Try to register specific application and initialise provider
     * By default, register common's provider
     *
     * @param Event                $event
     * @param ApplicationComponent $application
     * @return void
     * @throws Exception
     */
    public function boot(Event $event, ApplicationComponent $application)
    {
        $this->dispatchApplicationBySession();
        $this->dispatchApplicationByHost();
        $this->dispatchApplicationByHash();

        if ($application->hasApplication()) {
            $application->registerApplicationProvider();
        } else {
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


    /*************************************************************
     *
     *                     DISPATCH APPLICATIONS
     *
     *************************************************************/

    /**
     * Setup application if defined in session
     *
     * @throws Exception
     */
    private function dispatchApplicationBySession()
    {
        if ($this->di->has('session') && $this->session->exists())
        {
            // Register application information in service if exist in session
            if ($this->session->has('application')) {
                $this->application->registerApplication(
                    $this->session->get('application')->toArray()
                );
            }

            // Register user information in service if exist in session
            if ($this->session->has('user')) {
                $this->application->registerUser(
                    $this->session->get('user')->toArray()
                );
            }

            // Register userRole information in service if exist in session
            if ($this->session->has('user_role')) {
                $this->application->setUserRole(
                    $this->session->get('user_role')
                );
            }
        }
    }

    /**
     * Setup application defined in host configuration
     *
     * @throws Exception
     */
    private function dispatchApplicationByHost()
    {
        // Find serverName in main host configuration
        $serverName = $this->config->get('serverName');

        if ($this->config->has('host') && $this->config->get('host')->has($serverName))
        {
            $application = $this->dispatcher->dispatchNamespace(ApplicationModel::class);
            $application = $application::getBySlug(
                $this->config->get('host')->get($serverName)
            );

            if ($application)
            {
                $this->application->registerApplication(
                    $application->toArray()
                );
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
        if ($this->request->has('_app'))
        {
            $application = $this->dispatcher->dispatchNamespace(ApplicationModel::class);
            $application = $application::getBySlug(
                $this->request->get('_app')
            );

            if ($application)
            {
                $this->application->registerApplication(
                    $application->toArray()
                );
            }
        }
    }

}