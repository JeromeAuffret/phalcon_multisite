<?php

namespace Provider;

use Phalcon\Collection;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * Class ModuleProvider
 *
 * @package Provider
 */
abstract class ModuleProvider implements ModuleDefinitionInterface
{

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $moduleNamespace;

    /**
     * @var string
     */
    protected $modulePath;

    /**
     * @var string
     */
    protected $controllerNamespace;

    /**
     * @var string
     */
    protected $controllerPath;

    /**
     * @var Collection
     */
    protected $moduleDefinition;

    /**
     * @var string
     */
    protected $defaultController;

    /**
     * @var string
     */
    protected $defaultAction;

    /**
     * Register a module in application service
     * Register specific namespaces and services for a module
     *
     * Events are registered in afterModuleStart event ( ref : Application Middleware )
     *
     * registerAutoloaders and registerServices are call internally by phalcon on registerModules
     *
     * @param DiInterface|null $container
     * @param string|null $moduleName
     */
    public function __construct(?DiInterface $container = null, ?string $moduleName = null)
    {
        if (!$container) return;

        $this->moduleName = $moduleName;

        $this->moduleDefinition = $container->get('config')->get('modules')->get($this->moduleName);

        $this->moduleNamespace = preg_replace('/\\\Module$/', '', $this->moduleDefinition->get('className'));
        $this->modulePath = $modulePath = preg_replace('/\/Module.php$/', '', $this->moduleDefinition->get('path'));;

        $this->controllerNamespace = $this->moduleNamespace.'\\Controllers';
        $this->controllerPath = $this->modulePath.'/controllers';

        $this->defaultController = $this->moduleDefinition->get('defaultController') ?? $container->get('config')->get('defaultController');
        $this->defaultAction = $this->moduleDefinition->get('defaultAction') ?? $container->get('config')->get('defaultController');

        // Register module in mvc application
        $application = $container->get('application');
        $application->registerModules([
            $this->moduleName => [
                'className' => $this->moduleDefinition->get('className'),
                'path' => $this->moduleDefinition->get('path')
            ],
        ], true);

        // Register default namespaces
        // Register router defaults for the given module
        if ($container->get('config')->get('defaultModule') === $this->moduleName) {
            $container->get('router')->setDefaultModule($this->moduleName);
        }

        $container->get('router')->setDefaultNamespace($this->controllerNamespace);
        $container->get('router')->setDefaultController($this->defaultController);
        $container->get('router')->setDefaultAction($this->defaultAction);
    }

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    abstract public function registerAutoloaders(DiInterface $container = null);

    /**
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    abstract public function registerServices(DiInterface $container);

    /**
     * Register events related to the module
     * This method is call only in the after start module event
     *
     * @param DiInterface $container
     */
    abstract public  function registerEvents(DiInterface $container);

}
