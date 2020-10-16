<?php

namespace Component;

use Phalcon\Application\AbstractApplication;
use Phalcon\Di\DiInterface;
use Phalcon\Helper\Str;

/**
 * Class Application
 *
 * @property Acl acl
 * @property Config config
 * @property Database database
 * @property Loader loader
 *
 * @package Component
 */
final class Application extends \Phalcon\Mvc\Application
{
    /**
     * @var string $commonNamespace
     */
    private $commonNamespace = 'Common';
    /**
     * @var string $commonPath
     */
    private $commonPath = COMMON_PATH;

    /**
     * @var string $application
     */
    private $applicationSlug;

    /**
     * @var string $applicationNamespace
     */
    private $applicationNamespace;

    /**
     * @var string $applicationPath
     */
    private $applicationPath;

    /**
     * @var string $applicationClass
     */
    private $applicationClass = 'Application';


    /**
     * @override
     *
     * Application constructor.
     *
     * @param DiInterface|null $container
     */
    public function __construct(DiInterface $container = null)
    {
        parent::__construct($container);

        $this->registerCommonNamespace();
    }


    /**********************************************************
     *
     *                        AUTOLOADER
     *
     **********************************************************/

    /**
     *  PSR-4 compliant autoloader for common folder
     */
    private function registerCommonNamespace()
    {
        $commonPath = $this->getCommonPath();
        $commonNamespace = $this->getCommonNamespace();

        // Register application's namespaces
        (new \Phalcon\Loader())
            ->registerNamespaces([$commonNamespace => $commonPath])
            ->register();
    }

    /**
     *  PSR-4 compliant autoloader for application folder
     */
    private function registerApplicationNamespace()
    {
        $applicationPath = $this->getApplicationPath();
        $applicationNamespace = $this->getApplicationNamespace();

        // Register application's namespaces
        (new \Phalcon\Loader())
            ->registerNamespaces([$applicationNamespace => $applicationPath])
            ->register();
    }


    /**********************************************************
     *
     *                        APPLICATION
     *
     **********************************************************/

    /**
     * Register specific application's services for a given application
     *
     * @param string|null $applicationSlug
     */
    public function registerApplicationServices(string $applicationSlug)
    {
        // Register Application Config
        $this->setupApplication($applicationSlug);

        // Register Application Autoloader
        $this->registerApplicationNamespace();
    }

    /**
     * @param string $applicationSlug
     */
    public function setupApplication(string $applicationSlug): void
    {
        $this->applicationSlug = $applicationSlug;
        $this->applicationNamespace = Str::camelize($this->applicationSlug);
        $this->applicationPath = APPS_PATH . '/' . $this->applicationSlug;
    }


    /**********************************************************
     *
     *                     GETTERS / SETTERS
     *
     **********************************************************/

    /**
     *
     */
    public function hasApplication(): bool
    {
        return !!$this->applicationSlug;
    }

    /**
     * @return string
     */
    public function getApplicationClass(): string
    {
        return $this->applicationClass;
    }

    /**
     * @return string
     */
    public function getApplicationSlug(): string
    {
        return $this->applicationSlug;
    }

    /**
     * @return string
     */
    public function getApplicationNamespace(): ?string
    {
        return $this->applicationNamespace;
    }

    /**
     * @return string
     */
    public function getApplicationPath(): ?string
    {
        return $this->applicationPath;
    }

    /**
     * @param string $module_name
     * @return string
     */
    public function getApplicationModulePath(string $module_name): string
    {
        return $this->getApplicationPath() . '/modules/' . $module_name;
    }

    /**
     * @param string $module_name
     * @return string
     */
    public function getApplicationModuleNamespace(string $module_name): string
    {
        $module_namespace = Str::camelize($module_name);
        return $this->getApplicationNamespace() . '\\Modules\\' . $module_namespace;
    }

    /**
     * @return string
     */
    public function getCommonNamespace(): string
    {
        return $this->commonNamespace;
    }

    /**
     * @return string
     */
    public function getCommonPath(): string
    {
        return $this->commonPath;
    }

    /**
     * @param string $module_name
     * @return string
     */
    public function getCommonModulePath(string $module_name): string
    {
        return $this->getCommonPath() . '/modules/' . $module_name;
    }

    /**
     * @param string $module_name
     * @return string
     */
    public function getCommonModuleNamespace(string $module_name): string
    {
        $module_namespace = Str::camelize($module_name);
        return $this->getCommonNamespace().'\\Modules\\' . $module_namespace;
    }


    /**********************************************************
     *
     *                        MODULES
     *
     **********************************************************/

    /**
     * @param array $modules
     * @param bool $merge
     *
     * @return AbstractApplication|void
     */
    public function registerModules(array $modules, $merge = false): AbstractApplication
    {
        parent::registerModules($modules, $merge);

        foreach ($this->container->get('config')->get('modules') as $moduleName => $module)
        {
            $namespace = preg_replace('/\\\Module$/', '', $module['className']);
            $path = preg_replace('/\/Module.php$/', '', $module['path']);

            (new \Phalcon\Loader())
                ->registerNamespaces([$namespace => $path])
                ->register();

            $moduleClass = new $module['className'];
            $moduleClass->registerAutoloaders($this->container);
            $moduleClass->registerServices($this->container);

            $this->registerRoutes($moduleName, $module);
        }

        return $this;
    }

    /**
     * @param $moduleName
     * @param $module
     */
    public function registerRoutes($moduleName, $module)
    {
        if ($this->container->get('config')->get('applicationType') === 'modules')
        {
            $router = $this->container->get('router');
            $config = $this->container->get('config');

            $namespace = preg_replace('/Module$/', 'Controllers', $module->get("className"));

            $router->add('/'.$moduleName.'/:params', [
                'namespace' => $namespace,
                'module' => $moduleName,
                'controller' => $module->get('defaultController') ?? $config->get('defaultController'),
                'action' => $module->get('defaultAction') ?? $config->get('defaultAction'),
                'params' => 1
            ]);

            $router->add('/'.$moduleName.'/:controller/:params', [
                'namespace' => $namespace,
                'module' => $moduleName,
                'controller' => 1,
                'action' => $module->get('defaultAction') ?? $config->get('defaultAction'),
                'params' => 2
            ]);

            $router->add('/'.$moduleName.'/:controller/:action/:params', [
                'namespace' => $namespace,
                'module' => $moduleName,
                'controller' => 1,
                'action' => 2,
                'params' => 3
            ]);
        }
    }

}