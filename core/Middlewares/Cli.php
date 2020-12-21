<?php

namespace Core\Middlewares;

use Core\Components\Application as ApplicationComponent;
use Core\Components\Config;
use Core\Components\Database;
use Base\Models\Application;
use Core\Components\Console;
use Exception;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Throwable;

/**
 * Class Controller
 *
 * @property ApplicationComponent $application
 * @property Config $config
 * @property Console $console
 * @property Database $database
 * @property Dispatcher $dispatcher
 * @property Router $router
 * @package Middleware
 */
class Cli extends Injectable
{
    /**
     * Log start script
     *
     * @param Event $event
     * @param Console $console
     * @throws Exception
     */
    public function boot(Event $event, Console $console)
    {
        $this->application->registerBaseProvider();

        $this->registerTenantByOptions();
        $this->registerModuleByOptions();

        echo '=========================================================='.PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Start console'.PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Arguments : '.$this->console->getArguments()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Tenant : '.($this->console->getOptions('tenant') ?? '').PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Module : '.($this->dispatcher->getModuleName() ?? '').PHP_EOL;
        echo '=========================================================='.PHP_EOL;

        // Dispatch task based on console arguments
        $this->dispatchTask();

        echo '['.date('Y-m-d H:i:s').'] End console'.PHP_EOL;
        echo '=========================================================='.PHP_EOL;

        // Prevent using default dispatch as we trigger dispatcher manually
        return false;
    }

    /**
     * Dispatch task based on console arguments
     *
     * Catch every throwable error/exceptions if throw while running scripts
     */
    private function dispatchTask()
    {
        try {
            // Display help task if no task defined or help option is defined
            if (!$this->console->getTask() || $this->console->getOptions('help')) {
                $this->dispatcher->setTaskName('help');
                $this->dispatcher->dispatch();
            }
            // Run task for every registered tenants
            else {
                foreach ($this->console->getTenancy() as $tenantSlug) {
                    $this->dispatchTenantTask($tenantSlug);
                }
            }
        }
        catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
            echo '==========================================================' . PHP_EOL;
        }
    }


    /*************************************************************
     *
     *                     DISPATCH APPLICATIONS
     *
     *************************************************************/

    /**
     * Setup tenancy options
     *
     * Tenancy is defined by a list of tenant separated by comma
     * '*' define all tenant
     */
    private function registerTenantByOptions()
    {
        // Register tenancy in console service
        $tenants = $this->console->getOptions('tenant');

        if ($tenants) {
            if ($tenants === '*') {
                $applicationList = [];
                foreach (Application::find() as $application) {
                    $applicationList[] = $application->getSlug();
                }
                $this->console->setTenancy($applicationList);
            }
            else {
                $this->console->setTenancy(explode(',', $tenants));
            }
        }
    }

    /**
     * Register tenancy in console service
     */
    private function registerModuleByOptions()
    {
        $module = $this->console->getOptions('module');
        if ($module) {
            $this->dispatcher->setModuleName($module);
        }
    }

    /**
     * Dispatch task for each registered tenant
     *
     * We reset dispatcher values for each tenant
     * It allow to correctly dispatch namespaces in beforeDispatch event
     *
     * @param $tenantSlug
     */
    private function dispatchTenantTask($tenantSlug)
    {
        try {
            $this->application->registerTenantBySlug($tenantSlug);
            $this->application->registerTenantProvider();

            // Register tenant modules to allow module interdependence
            $this->application->registerModulesProviders();

            // Reset dispatcher value with options registered arguments

            $this->dispatcher->setNamespaceName(
                $this->application->getTenantNamespace()
            );

            if ($this->console->getTask()) {
                $this->dispatcher->setTaskName($this->console->getTask());
            }

            if ($this->console->getAction()) {
                $this->dispatcher->setActionName($this->console->getAction());
            }

            $this->dispatcher->setParams(
                $this->console->getParams()->toArray()
            );

            $this->dispatcher->setOptions(
                $this->console->getOptions()->toArray()
            );

            // Dispatch task for each tenant
            $this->dispatcher->dispatch();
        }
        catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
            echo '==========================================================' . PHP_EOL;
        }
    }

}