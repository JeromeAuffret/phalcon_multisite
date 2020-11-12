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
     * @var DiInterface
     */
    protected $container;

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
    protected $controllerNamespace;

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
        if (!($container && $moduleName)) return;

        $this->container = $container;
        $this->moduleName = $moduleName;

        $config = $container->get('config');
        $application = $container->get('application');
        $router = $container->get('router');

        $this->moduleDefinition = $config->get('modules')->get($moduleName);
        $this->moduleNamespace = preg_replace('/\\\Module$/', '', $this->moduleDefinition->get('className'));
        $this->controllerNamespace = $this->moduleNamespace.'\\Controllers';
        $this->defaultController = $this->moduleDefinition->get('defaultController') ?? $config->get('defaultController');
        $this->defaultAction = $this->moduleDefinition->get('defaultAction') ?? $config->get('defaultController');

        $application->registerModules([
            $moduleName => [
                'className' => $this->moduleDefinition->get('className'),
                'path' => $this->moduleDefinition->get('path')
            ],
        ], true);

        // Register router defaults for the given module
        $router->initModuleDefaults($moduleName, $this->controllerNamespace, $this->defaultController, $this->defaultAction);

        $this->registerRouter($container);
        $this->registerAcl($container);
    }

    /**
     * Initialize module providers.
     */
    public function initialize() {}

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    public function registerAutoloaders(DiInterface $container = null) {}

    /**
     * Registers services related to the module
     *
     * @param DiInterface $container
     */
    public function registerServices(DiInterface $container) {}

    /**
     * Register router related to the module
     *
     * @param DiInterface $container
     */
    public function registerRouter(DiInterface $container)
    {
        $router = $container->get('router');

        // Register a generic routing for modules
        $router->add('/'.$this->moduleName.'/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => $this->defaultController,
            'action' => $this->defaultAction,
            'params' => 1
        ]);

        $router->add('/'.$this->moduleName.'/:controller/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => 1,
            'action' => $this->defaultAction,
            'params' => 2
        ]);

        $router->add('/'.$this->moduleName.'/:controller/:action/:params', [
            'namespace' => $this->controllerNamespace,
            'module' => $this->moduleName,
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ]);
    }

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    public function registerAcl(DiInterface $container) {}

    /**
     * Register events related to the module
     * Events are bind only in module dispatch loop
     *
     * @param DiInterface $container
     */
    public function registerEvents(DiInterface $container) {}

}
