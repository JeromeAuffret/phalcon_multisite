<?php

namespace Core\Middlewares;

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
 * @property \Core\Components\Application $application
 * @property Console $console
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

        echo '=========================================================='.PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Start console'.PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Arguments : '.$this->console->getArguments()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Tenant : '.($this->console->getOptions('tenant') ?? 'all').PHP_EOL;
        echo '=========================================================='.PHP_EOL;

        $this->dispatchTenantTask();

        echo '['.date('Y-m-d H:i:s').'] End console'.PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Arguments : '.$this->console->getArguments()->toJson().PHP_EOL;
        echo '['.date('Y-m-d H:i:s').'] Tenant : '.($this->console->getOptions('tenant') ?? 'all').PHP_EOL;
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
     * Setup application options
     *
     * @throws Exception
     */
    private function registerTenantByOptions()
    {
        // Register tenancy in console service
        $tenancy = $this->console->getOptions('tenant');
        if ($tenancy) {
            $this->console->setTenancy(explode(',', $tenancy));
        }
        else {
            $applicationList = [];
            foreach (Application::find() as $application) {
                $applicationList[] = $application->getSlug();
            }
            $this->console->setTenancy($applicationList);
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
        foreach ($this->console->getTenancy() as $tenant)
        {
            try {
                $this->application->registerTenantBySlug($tenant);
                $this->application->registerTenantProvider();

                // Bind defaultNamespace to dispatcher currentNamespace
                $this->dispatcher->setNamespaceName(
                    $this->dispatcher->getDefaultNamespace()
                );

                // Bind console task to dispatcher service
                $this->dispatcher->setTaskName($this->console->getArguments('task'));

                // Bind console action to dispatcher service
                $this->dispatcher->setActionName($this->console->getArguments('action'));

                // Bind console params to dispatcher service
                $this->dispatcher->setParams(
                    $this->console->getParams()->toArray()
                );

                // Bind console params to dispatcher service
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