<?php

namespace Component;

use Phalcon\Helper\Str;

/**
 * Class Application
 *
 * @property Acl acl
 * @property Config config
 * @property Database database
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
    private $commonPath = BASE_PATH . "/src/common";

    /**
     * @var string $commonPath
     */
    private $applicationBasePath = BASE_PATH . "/src/apps";

    /**
     * @var string $applicationClass
     */
    private $applicationClass = 'Application';

    /**
     * @var string $applicationClass
     */
    private $moduleClass = 'Module';

    /**
     * @var string $applicationClass
     */
    private $moduleBaseNamespace = 'Modules';

    /**
     * @var string $applicationClass
     */
    private $moduleBaseDir = 'modules';

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


    /**********************************************************
     *
     *                        APPLICATION
     *
     **********************************************************/

    /**
     * Setup application in service
     *
     * @param string $applicationSlug
     * @param string|null $applicationNamespace
     * @param string|null $applicationPath
     */
    public function registerApplication(string $applicationSlug, ?string $applicationNamespace = null, ?string $applicationPath = null)
    {
        // Register Application Slug
        $this->setApplicationSlug($applicationSlug);

        // Register Application Namespace
        $this->setApplicationNamespace($applicationNamespace);

        // Register Application Path
        $this->setApplicationPath($applicationPath);
    }


    /**********************************************************
     *
     *                        AUTOLOADER
     *
     **********************************************************/

    /**
     * PSR-4 compliant autoloader for common folder
     * Initialize ApplicationProvider for common
     */
    public function registerCommonProvider()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([$this->commonNamespace => $this->commonPath])
            ->register();

        $applicationProvider = $this->commonNamespace.'\\'.$this->applicationClass;
        $applicationProvider = new $applicationProvider;

        $applicationProvider->initialize($this->container);
    }

    /**
     * PSR-4 compliant autoloader for application folder
     * Initialize ApplicationProvider for application
     */
    public function registerApplicationProvider()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([$this->applicationNamespace => $this->applicationPath])
            ->register();

        $applicationProvider = $this->applicationNamespace.'\\'.$this->applicationClass;
        $applicationProvider = new $applicationProvider;

        $applicationProvider->initialize($this->container);
    }

    /**
     * PSR-4 compliant autoloader for application folder
     * Initialize ModuleProvider for each modules defined in configuration
     *
     * TODO this use the default module configuration use by phalcon. This could be improve to just use moduleName
     */
    public function registerModulesProvider()
    {
        $config = $this->container->get('config');
        if ($config->get('applicationType') !== 'modules') return;

        foreach ($config->get('modules') as $moduleName => $module)
        {
            // TODO Adapt regex to use $this->moduleClass
            $moduleNamespace = preg_replace('/\\\Module$/', '', $module->get('className'));
            $modulePath = preg_replace('/\/Module.php$/', '', $module->get('path'));

            (new \Phalcon\Loader())
                ->registerNamespaces([$moduleNamespace => $modulePath])
                ->register();

            $moduleProvider = $module->get('className');
            $moduleProvider = new $moduleProvider;

            $moduleProvider->initialize($this->container, $moduleName);
        }
    }


    /**********************************************************
     *
     *                     GETTERS / SETTERS
     *
     **********************************************************/

    /**
     * @param string $applicationSlug
     */
    private function setApplicationSlug(string $applicationSlug)
    {
        $this->applicationSlug = $applicationSlug;
    }

    /**
     * @param null $applicationNamespace
     */
    private function setApplicationNamespace($applicationNamespace = null)
    {
        $this->applicationNamespace = $applicationNamespace ?: Str::camelize($this->applicationSlug);
    }

    /**
     * @param null $applicationPath
     */
    private function setApplicationPath($applicationPath = null)
    {
        $this->applicationPath = $applicationPath ?: ($this->applicationBasePath.'/'.$this->applicationSlug);
    }

    /**
     * @return bool
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
    public function getApplicationSlug(): ?string
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
     * @param string|null $moduleName
     * @return string
     */
    public function getApplicationModulePath(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->applicationPath.'/'.$this->moduleBaseDir.'/'.$moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getApplicationModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->applicationNamespace.'\\'.$this->moduleBaseNamespace.'\\'.Str::camelize($moduleName);
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
     * @param string|null $moduleName
     * @return string
     */
    public function getCommonModulePath(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->commonPath.'/'.$this->moduleBaseDir.'/'.$moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getCommonModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->commonNamespace.'\\'.$this->moduleBaseNamespace.'\\'.Str::camelize($moduleName);
    }

}