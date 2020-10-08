<?php

namespace Component;

use Phalcon\Di\Injectable;
use Phalcon\Helper\Str;

/**
 * Class Application
 *
 * @package Component
 */
final class Application extends Injectable
{
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
    public function getApplicationNamespace(): string
    {
        return $this->application_namespace;
    }

    /**
     * @return string
     */
    public function getApplicationPath(): string
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
        return 'Common';
    }

    /**
     * @return string
     */
    public function getCommonPath(): string
    {
        return COMMON_PATH;
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