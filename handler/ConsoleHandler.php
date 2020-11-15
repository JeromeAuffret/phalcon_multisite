<?php

namespace Handler;

use Exception;
use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Service\Config as ConfigService;
use Service\Database as DbService;
use Service\Dispatcher as DispatcherService;
use Service\Router as RouterService;

/**
 * Class ConsoleHandler
 *
 * @package Handler
 */
final class ConsoleHandler
{
    /**
     * @var FactoryDefault
     */
    protected $container;

    /**
     * Setup Console application
     *
     * @throws Exception
     */
    public function __construct()
    {
        // Start Di container
        $this->container = new FactoryDefault();

        // Register core namespaces
        $this->registerConsoleNamespaces();

        // Register main services in DI container
        $this->registerConsoleServices();

        // Bind event to mvc application
        $this->registerEvents();
    }

    /**
     * Handle application's response
     *
     * @return string
     * @throws Exception
     */
    public function handle(): string
    {
        $console = new Console();

        $arguments = [];
        foreach ($argv as $k => $arg) {
            if ($k === 1) {
                $arguments['task'] = $arg;
            } elseif ($k === 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }

        return (string) $console->handle($arguments);
    }

    /**
     * Register core namespaces
     */
    public function registerConsoleNamespaces()
    {
        // Register core namespaces
        (new Loader())
            ->registerNamespaces([
                'Component'   => BASE_PATH . '/core/components',
                'Error'       => BASE_PATH . '/core/errors',
                'Middleware'  => BASE_PATH . '/core/middlewares',
                'Provider'    => BASE_PATH . '/core/providers',
                'Service'     => BASE_PATH . '/core/services',
                'Mvc'         => BASE_PATH . '/core/mvc',
                'Libraries'   => BASE_PATH . '/libraries',
            ])
            ->register();
    }

    /**
     * Register core services
     */
    public function registerConsoleServices()
    {
        (new DispatcherService)
            ->register($this->container);

        (new ConfigService)
            ->register($this->container);

        (new DbService)
            ->register($this->container);

        (new RouterService)
            ->register($this->container);
    }

    /**
     * Register application events
     */
    public function registerEvents() {}
}
