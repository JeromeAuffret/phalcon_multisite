<?php

namespace Core\Providers;

use Core\Components\Application;
use Core\Components\Config;
use Phalcon\Application\AbstractApplication;
use Phalcon\Collection;
use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Dispatcher;
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
    public $moduleName;

    /**
     * @var string
     */
    public $moduleNamespace;

    /**
     * @var string
     */
    public $modulePath;

    /**
     * @var string
     */
    public $controllerNamespace;

    /**
     * @var string
     */
    public $controllerPath;

    /**
     * @var Collection
     */
    public $moduleDefinition;

    /**
     * @var string
     */
    public $defaultController;

    /**
     * @var string
     */
    public $defaultAction;

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
    final public function __construct(?DiInterface $container = null, ?string $moduleName = null)
    {
        if (!($container && $moduleName)) return;

        /** @var Application $applciation */
        $application = $container->get('application');
        /** @var Config $config */
        $config = $container->get('config');
        /** @var Dispatcher $dispatcher */
        $dispatcher = $container->get('dispatcher');
        /** @var Collection $modulesConfig */
        $modulesConfig = $config->get('modules');

        // Initialize module variables
        $this->setModuleName($moduleName);

        $moduleDefinition = $modulesConfig->get($this->getModuleName());
        $this->setModuleDefinition($moduleDefinition);

        $moduleNamespace = preg_replace('/\\\Module$/', '', $moduleDefinition->get('className'));
        $this->setModuleNamespace($moduleNamespace);

        $modulePath = preg_replace('/\/Module.php$/', '', $moduleDefinition->get('path'));;
        $this->setModulePath($modulePath);

        // Register Mvc defaults
        if ($container->get('application')->isMvc())
        {
            $controllerNamespace = $this->getModuleNamespace() . '\\Controllers';
            $this->setControllerNamespace($controllerNamespace);

            $controllerPath = $this->getModulePath() . '/controllers';
            $this->setControllerPath($controllerPath);

            $defaultController = $this->getModuleDefinition()->get('defaultController');
            $this->setDefaultController($defaultController);

            $defaultAction = $this->getModuleDefinition()->get('defaultAction');
            $this->setDefaultAction($defaultAction);

            if ($config->get('defaultModule') === $this->getModuleName()) {
                $dispatcher->setDefaultNamespace($this->getControllerNamespace());
                $dispatcher->setDefaultController($this->getDefaultController());
                $dispatcher->setDefaultAction($this->getDefaultAction());
            }
        }

        // Register Module in Mvc/Cli
        $this->registerModules($container);

        // Register namespaces relative to the module
        $this->registerAutoloaders($container);

        // Register Services in Mvc/Cli
        $this->registerServices($container);

        if ($application->isMvc())
        {
            // Register Routes relative to the module
            $this->registerRoutes($container);

            // Register Acl relative to the module
            $this->registerAcl($container);
        }
    }

    /**
     * Register module in mvc/console application
     *
     * @param DiInterface|null $container
     */
    public function registerModules(DiInterface $container)
    {
        /** @var AbstractApplication $handler */
        if ($container->get('application')->isCli()) {
            $handler = $container->get('console');
        } else {
            $handler = $container->get('mvc');
        }

        $handler->registerModules([
            $this->moduleName => [
                'className' => $this->getModuleDefinition()->get('className'),
                'path' => $this->getModuleDefinition()->get('path')
            ],
        ], true);
    }

    /**
     * @param DiInterface $container
     */
    public function registerRoutes(DiInterface $container)
    {
        $container->get('router')->add('/'.$this->getModuleName().'/:params', [
            'namespace' => $this->getControllerNamespace(),
            'module' => $this->getModuleName(),
            'controller' => $this->getDefaultController(),
            'action' => $this->getDefaultAction(),
            'params' => 1
        ]);

        $container->get('router')->add('/'.$this->getModuleName().'/:controller/:params', [
            'namespace' => $this->getControllerNamespace(),
            'module' => $this->getModuleName(),
            'controller' => 1,
            'action' => $this->getDefaultAction(),
            'params' => 2
        ]);

        $container->get('router')->add('/'.$this->getModuleName().'/:controller/:action/:params', [
            'namespace' => $this->getControllerNamespace(),
            'module' => $this->getModuleName(),
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ]);
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
     * This method is call only in the handled module afterStart
     *
     * @param DiInterface $container
     */
    abstract public function registerEvents(DiInterface $container);

    /**
     * Register acl related to the module
     *
     * @param DiInterface $container
     */
    abstract public function registerAcl(DiInterface $container);


    /**********************************************************
     *
     *                     GETTERS / SETTERS
     *
     **********************************************************/

    /**
     * @return string|null
     */
    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    protected function setModuleName(string $moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string|null
     */
    public function getModuleNamespace(): ?string
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
    public function getControllerNamespace(): ?string
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
    public function setDefaultController(?string $defaultController): void
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
    public function setDefaultAction(?string $defaultAction = null): void
    {
        $this->defaultAction = $defaultAction;
    }

}
