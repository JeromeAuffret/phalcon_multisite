<?php

namespace Component;

use Phalcon\Di\Injectable;
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
final class Application extends Injectable
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
     *
     */
    public function __construct()
    {
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
     *  PSR-4 compliant autoloader for common folder
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

}