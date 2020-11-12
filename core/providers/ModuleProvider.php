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
    protected $modulePath;

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
        $this->modulePath = $modulePath = preg_replace('/\/Module.php$/', '', $this->moduleDefinition->get('path'));;
        $this->controllerNamespace = $this->moduleNamespace.'\\Controllers';
        $this->defaultController = $this->moduleDefinition->get('defaultController') ?? $config->get('defaultController');
        $this->defaultAction = $this->moduleDefinition->get('defaultAction') ?? $config->get('defaultController');

        $application->registerModules([
            $this->moduleName => [
                'className' => $this->moduleDefinition->get('className'),
                'path' => $this->moduleDefinition->get('path')
            ],
        ], true);

        // Register router defaults for the given module
        $router->initModuleDefaults($this->moduleName, $this->controllerNamespace, $this->defaultController, $this->defaultAction);

        $this->registerRouter($this->container);
        $this->registerAcl($this->container);
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
     * Register router related to the module
     *
     * @param DiInterface $container
     */
    abstract public function registerRouter(DiInterface $container);

    /**
     * Register acl rules related to the module
     *
     * @param DiInterface $container
     */
    abstract public function registerAcl(DiInterface $container);

    /**
     * Register events related to the module
     * Events are bind only in module dispatch loop
     *
     * @param DiInterface $container
     */
    abstract public function registerEvents(DiInterface $container);

}
