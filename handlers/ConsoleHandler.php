<?php

namespace Handlers;

use Core\Services\Application as ApplicationService;
use Exception;
use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Core\Services\Config as ConfigService;
use Core\Services\Database as DbService;
use Core\Services\DispatcherCli as DispatcherService;
use Core\Services\RouterCli as RouterService;

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
     * @var
     */
    protected $arguments;

    /**
     * Setup Console application
     *
     * @param $arguments
     */
    public function __construct($arguments)
    {
        // Start Di container
        $this->container = new FactoryDefault();

        // Register arguments
        $this->arguments = $arguments;

        // Register core namespaces
        $this->registerConsoleNamespaces();

        // Register main Services in DI container
        $this->registerConsoleServices();

        // Bind event to mvc application
        $this->registerEvents();
    }

    /**
     * Handle application's response
     *
     * @throws Exception
     */
    public function handle()
    {
        $console = new Console();

        $console->setDi($this->container);

        $arguments = [
            'task' => 'main',
            'action' => 'main',
            'params' => []
        ];

        foreach ($this->arguments as $k => $arg) {
            if ($k === 1) {
                $arguments['task'] = $arg;
            } elseif ($k === 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }

        $console->handle($arguments);
    }

    /**
     * Register core namespaces
     */
    public function registerConsoleNamespaces()
    {
        // Register core namespaces
        (new Loader())
            ->registerNamespaces([
                'Core'        => BASE_PATH . '/core',
                'Libraries'   => BASE_PATH . '/libraries',
            ])
            ->register();
    }

    /**
     * Register core Services
     */
    public function registerConsoleServices()
    {
        (new ApplicationService)
            ->register($this->container);

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
