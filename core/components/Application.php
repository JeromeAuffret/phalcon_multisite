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
     * @var string $common_namespace
     */
    private $common_namespace = 'Common';
    /**
     * @var string $common_namespace
     */
    private $common_path = COMMON_PATH;

    /**
     * @var string $application
     */
    private $application_slug;

    /**
     * @var string $application_namespace
     */
    private $application_namespace;

    /**
     * @var string $application_path
     */
    private $application_path;


    /**********************************************************
     *
     *                        APPLICATION
     *
     **********************************************************/

    /**
     * @param string $application_slug
     */
    public function setupApplication(string $application_slug): void
    {
        $this->application_slug = $application_slug;
        $this->application_namespace = Str::camelize($this->application_slug);
        $this->application_path = APPS_PATH . '/' . $this->application_slug;
    }

    /**
     * Register specific application's services for a given application
     *
     * @param string|null $application_slug
     */
    public function registerApplicationServices(string $application_slug)
    {
        // Register Application Config
        $this->setupApplication($application_slug);

        // Register Application Config
        $this->config->registerApplicationConfig();

        // Register Application Namespaces
        $this->loader->registerApplicationNamespaces();

        // Register Application Database
        $this->database->registerApplicationDatabase();

        // Register Application Database
        $this->acl->registerApplicationAcl();
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
        return !!$this->application_slug;
    }

    /**
     * @return string
     */
    public function getApplicationSlug(): string
    {
        return $this->application_slug;
    }

    /**
     * @return string
     */
    public function getApplicationNamespace(): ?string
    {
        return $this->application_namespace;
    }

    /**
     * @return string
     */
    public function getApplicationPath(): ?string
    {
        return $this->application_path;
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
        return $this->common_namespace;
    }

    /**
     * @return string
     */
    public function getCommonPath(): string
    {
        return $this->common_path;
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