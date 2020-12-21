<?php

namespace Handlers;

use Core\Middlewares\Cli as CliMiddleware;
use Core\Services\Application as ApplicationService;
use Exception;
use Phalcon\Collection;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Core\Services\Console as ConsoleService;
use Core\Services\Config as ConfigService;
use Core\Services\Database as DbService;
use Core\Services\ConsoleDispatcher as DispatcherService;
use Core\Services\ConsoleRouter as RouterService;

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
     * @var array
     */
    protected $argv;

    /**
     * Setup Cli application
     *
     * @param $argv
     */
    public function __construct($argv)
    {
        // Start Di container
        $this->container = new FactoryDefault();

        // Register arguments
        $this->argv = $argv;

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
        $this->container->get('console')->handle(
            $this->parseArguments()
        );
    }

    /**
     * Register core namespaces
     */
    public function registerConsoleNamespaces()
    {
        // Register core namespaces
        (new Loader())
            ->registerNamespaces(['Core' => BASE_PATH . '/core'])
            ->register();
    }

    /**
     * Register core Services
     */
    public function registerConsoleServices()
    {
        (new ConsoleService)
            ->register($this->container);

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
    public function registerEvents()
    {
        $eventsManager = $this->container->get('eventsManager');
        $eventsManager->attach('console', new CliMiddleware());

        $this->container->get('console')->setEventsManager($eventsManager);
    }

    /**
     * @return array
     */
    public function parseArguments(): array
    {
        $arguments = [
            'task' => null,
            'action' => null,
            'params' => [],
            'options' => []
        ];

        $i = 0;
        foreach ($this->argv as $arg)
        {
            // Detect options syntax
            // Escape loop to prevent increment
            $optionDelimiter = '--';
            $len = strlen($optionDelimiter);
            if (substr($arg, 0, $len) === $optionDelimiter) {
                $explodeValue = explode('=', str_replace($optionDelimiter, '', $arg)); // Remove $optionDelimiter and explode by key / value
                $arguments['options'][trim($explodeValue[0])] = trim($explodeValue[1] ?? '');
                continue;
            }

            // Controller arguments for cli router
            if ($i === 1) {
                $arguments['task'] = $arg;
            } elseif ($i === 2) {
                $arguments['action'] = $arg;
            } elseif ($i >= 3) {
                $arguments['params'][] = $arg;
            }

            $i++;
        }

        // Register arguments in console service
        $this->container->get('console')->registerArguments($arguments);

        return $arguments;
    }

}
