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

        $this->dispatchTenantTask();

        echo '['.date('Y-m-d H:i:s').'] End console'.PHP_EOL;
        echo '=========================================================='.PHP_EOL;

        // As we dispatch task manually, we prevent other task to be dispatch
        return false;
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
     *
     * @throws Exception
     */
    private function registerTenantByOptions()
    {
        // Register tenancy in console service
        $tenancy = $this->console->getOptions('tenant');
        if ($tenancy && $tenancy === '*') {
            $applicationList = [];
            foreach (Application::find() as $application) {
                $applicationList[] = $application->getSlug();
            }
            $this->console->setTenancy($applicationList);
        }
        elseif ($tenancy) {
            $this->console->setTenancy(explode(',', $tenancy));
        }
    }

    /**
     * Setup application options
     *
     * @throws Exception
     */
    private function registerModuleByOptions()
    {
        // Register tenancy in console service
        $module = $this->console->getOptions('module');
        if ($module) {
            $this->dispatcher->setModuleName($module);
        }
    }

    /**
     * Setup application options
     *
     * We reset dispatcher values for each tenant
     * It allow to correctly dispatch namespaces in beforeDispatch event
     *
     * @throws Exception
     */
    private function dispatchTenantTask()
    {
        // Dispatch task for each registered tenant
        foreach ($this->console->getTenancy() as $tenant)
        {
            try {
                $this->application->registerTenantBySlug($tenant);
                $this->application->registerTenantProvider();

                // Register tenant modules to allow module interdependence
                $this->application->registerModulesProviders();

                // Bind defaultNamespace to dispatcher currentNamespace
                $this->dispatcher->setNamespaceName(
                    $this->application->getTenantNamespace()
                );

                $this->dispatcher->setTaskName($this->console->getArguments('task'));
                $this->dispatcher->setActionName($this->console->getArguments('action'));

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
                continue;
            }
        }
    }

}