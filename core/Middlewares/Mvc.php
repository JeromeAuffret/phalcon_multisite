<?php

namespace Core\Middlewares;

use Core\Components\Config;
use Core\Components\Application as ApplicationComponent;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Application;
use Core\Providers\ModuleProvider;

/**
 * Class Controller
 *
 * @property ApplicationComponent application
 * @property Config config
 * @package Middleware
 */
class Mvc extends Injectable
{

    /**
     * Controller applications on MVC boot event
     * Try to register specific application and initialise provider
     * By default, register base's provider
     *
     * @param Event $event
     * @param  $application
     * @return void
     * @throws Exception
     */
    public function boot(Event $event, Application $application)
    {
        $this->application->registerBaseProvider();

        $this->dispatchTenantBySession();
        $this->dispatchTenantByHost();
        $this->dispatchTenantByHash();

        if ($this->application->hasTenant()) {
            $this->application->registerTenantProvider();
        }
    }

    /**
     * Register specific module events from moduleProvider
     *
     * @param Event $event
     * @param Application $application
     * @return void
     * @throws Exception
     */
    public function afterStartModule(Event $event, Application $application)
    {
        $moduleName = $this->router->getModuleName();
        $module = $application->getModule($moduleName);

        /* @var ModuleProvider $moduleClass */
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
    private function dispatchTenantBySession()
    {
        if ($this->di->has('session') && $this->session->exists())
        {
            // Register application information in service if exist in session
            if ($this->session->has('application')) {
                $tenant = $this->session->get('application')->toArray();
                $this->application->registerTenant($tenant);
            }

            // Register user information in service if exist in session
            if ($this->session->has('user')) {
                $user = $this->session->get('user')->toArray();
                $this->application->registerUser($user);
            }

            // Register userRole information in service if exist in session
            if ($this->session->has('user_role')) {
                $role = $this->session->get('user_role');
                $this->application->setUserRole($role);
            }
        }
    }

    /**
     * Setup application defined in host configuration
     *
     * @throws Exception
     */
    private function dispatchTenantByHost()
    {
        // Find serverName in main host configuration
        $serverName = $this->config->get('serverName');

        if ($this->config->has('host') && $this->config->get('host')->has($serverName)) {
            $this->application->registerTenantBySlug(
                $this->config->get('host')->get($serverName)
            );
        }
    }

    /**
     * Setup application defined in request parameters
     *
     * @throws Exception
     */
    private function dispatchTenantByHash()
    {
        if ($this->request->has('_app'))
        {
            $this->application->registerTenantBySlug(
                $this->request->get('_app')
            );
        }
    }

}