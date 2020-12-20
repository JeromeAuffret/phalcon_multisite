<?php

namespace Core\Middlewares;

use Base\Models\Application;
use Core\Components\Console;
use Exception;
use Libraries\NamespaceHelper;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;

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

        $this->dispatchTenantByOptions();

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
    private function dispatchTenantByOptions()
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
     * @throws Exception
     */
    private function dispatchTenantTask()
    {
        foreach ($this->console->getTenancy() as $tenant)
        {
            $this->application->registerTenantBySlug($tenant);
            $this->application->registerTenantProvider();

            // We reset namespace for each tenancy, that allow to correctly dispatch namespace
            $this->dispatcher->setNamespaceName(
                $this->dispatcher->getDefaultNamespace()
            );

            // Run task for each tenant
            $this->dispatcher->dispatch();
        }
    }

}