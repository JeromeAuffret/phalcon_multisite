<?php

namespace Core\Providers;

use Phalcon\Collection;
use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * Class ModuleProvider
 *
 * @package Core\Providers
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
     * Register specific namespaces and Services for a module
     *
     * Events are registered in afterModuleStart event ( ref : Tenant Middleware )
     *
     * registerAutoloaders and registerServices are call internally by phalcon on registerModules
     *
     * @param DiInterface|null $container
     * @param string|null $moduleName
     */
    public function __construct(?DiInterface $container = null, ?string $moduleName = null)
    {
        if (!($container && $moduleName)) return;

        $config = $container->get('config');
        $router = $container->get('router');

        $this->setModuleName($moduleName);

        $moduleDefinition = $container->get('config')->get('modules')->get($this->getModuleName());
        $this->setModuleDefinition($moduleDefinition);

        $moduleNamespace = preg_replace('/\\\Module$/', '', $moduleDefinition->get('className'));
        $this->setModuleNamespace($moduleNamespace);

        $modulePath = $modulePath = preg_replace('/\/Module.php$/', '', $moduleDefinition->get('path'));;
        $this->setModulePath($modulePath);

        $controllerNamespace = $this->getModuleNamespace().'\\Controllers';
        $this->setControllerNamespace($controllerNamespace);

        $controllerPath = $this->getModulePath().'/controllers';
        $this->setControllerPath($controllerPath);

        $defaultController = $this->getModuleDefinition()->get('defaultController') ;
        $this->setDefaultController($defaultController);

        $defaultAction = $this->getModuleDefinition()->get('defaultAction');
        $this->setDefaultAction($defaultAction);

        // Register module in mvc application
        $mvc = $container->get('mvc');
        $mvc->registerModules([
            $this->moduleName => [
                'className' => $this->getModuleDefinition()->get('className'),
                'path' => $this->getModuleDefinition()->get('path')
            ],
        ], true);

        // Register router defaults
        if ($config->get('defaultModule') === $this->getModuleName()) {
            $router->setDefaultModule($this->getModuleName());
        }

        $router->setDefaultNamespace($this->getControllerNamespace());
        $router->setDefaultController($this->getDefaultController());
        $router->setDefaultAction($this->getDefaultAction());
    }

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface|null $container
     */
    abstract public function registerAutoloaders(DiInterface $container = null);

    /**
     * Registers Services related to the module
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
    abstract public function registerEvents(DiInterface $container);

    /**
     * @return string
     */
    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    protected function setModuleName(string $moduleName) {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return $this->moduleNamespace;
    }

    /**
     * @param string $moduleNamespace
     */
    public function setModuleNamespace(string $moduleNamespace): void
    {
        $this->moduleNamespace = $moduleNamespace;
    }

    /**
     * @return string
     */
    public function getModulePath(): string
    {
        return $this->modulePath;
    }

    /**
     * @param string $modulePath
     */
    public function setModulePath(string $modulePath): void
    {
        $this->modulePath = $modulePath;
    }

    /**
     * @return string
     */
    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    /**
     * @param string $controllerNamespace
     */
    public function setControllerNamespace(string $controllerNamespace): void
    {
        $this->controllerNamespace = $controllerNamespace;
    }

    /**
     * @return string
     */
    public function getControllerPath(): string
    {
        return $this->controllerPath;
    }

    /**
     * @param string $controllerPath
     */
    public function setControllerPath(string $controllerPath): void
    {
        $this->controllerPath = $controllerPath;
    }

    /**
     * @return Collection
     */
    public function getModuleDefinition(): Collection
    {
        return $this->moduleDefinition;
    }

    /**
     * @param Collection $moduleDefinition
     */
    public function setModuleDefinition(Collection $moduleDefinition): void
    {
        $this->moduleDefinition = $moduleDefinition;
    }

    /**
     * @return string
     */
    public function getDefaultController(): string
    {
        $config = Di::getDefault()->get('config');
        return $this->defaultController ?: $config->get('defaultController');
    }

    /**
     * @param string|null $defaultController
     */
    public function setDefaultController(string $defaultController): void
    {
        $this->defaultController = $defaultController;
    }

    /**
     * @return string
     */
    public function getDefaultAction(): string
    {
        $config = Di::getDefault()->get('config');
        return $this->defaultAction ?: $config->get('defaultAction');
    }

    /**
     * @param string|null $defaultAction
     */
    public function setDefaultAction(string $defaultAction = null): void
    {
        $this->defaultAction = $defaultAction;
    }

}
