<?php

namespace Component;

use Phalcon\Collection;
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
     * @var string $applicationClass-
     */
    private $moduleBaseDir = 'modules';

    /**
     * @var Collection $application
     */
    private $application = null;

    /**
     * @var Collection $user
     */
    private $user = null;

    /**
     * @var String $userRole
     */
    private $userRole = null;

    /**
     * @var string $application
     */
    private $applicationSlug = null;

    /**
     * @var string $applicationNamespace
     */
    private $applicationNamespace = null;

    /**
     * @var string $applicationPath
     */
    private $applicationPath = null;


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
            // TODO Adapt regex to use moduleClass variable
            $moduleNamespace = preg_replace('/\\\\'.$this->moduleClass.'$/', '', $module->get('className'));
            $modulePath = preg_replace('/\/'.$this->moduleClass.'.php$/', '', $module->get('path'));

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
     *                     APPLICATION
     *
     **********************************************************/

    /**
     * @param array $application
     */
    public function registerApplication(array $application)
    {
        $this->application = new Collection($application);

        if ($this->application->has('slug')) {
            $this->setApplicationSlug($this->application->get('slug'));
        }
    }

    /**
     * @return bool
     */
    public function hasApplication(): bool
    {
        return !!$this->application;
    }

    /**
     * Return application collection o given key value
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getApplication($key = null)
    {
        if (!$key)
            return $this->application;
        elseif ($this->application && $this->application->has($key))
            return $this->application->get($key);
        else
            return null;
    }

    /**
     * Set application key value
     *
     * @param $key
     * @param $value
     */
    public function setApplication($key, $value)
    {
        if ($this->hasApplication()) {
            $application = $this->application;
            $application->set($key, $value);

            $this->application = $application;
        }
    }


    /**********************************************************
     *
     *                          USER
     *
     **********************************************************/

    /**
     * @param array $user
     */
    public function registerUser(array $user)
    {
        $this->user = new Collection($user);
    }

    /**
     * @return boolean
     */
    public function hasUser(): bool
    {
        return !!$this->user;
    }

    /**
     * Return user collection or given key value
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getUser($key = null)
    {
        if (!$key)
            return $this->user;
        elseif ($this->user && $this->user->has($key))
            return $this->user->get($key);
        else
            return null;
    }

    /**
     * Set user key value
     *
     * @param $key
     * @param $value
     */
    public function setUser($key, $value)
    {
        if ($this->user) {
            $user = $this->user;
            $user->set($key, $value);

            $this->user = $user;
        }
    }


    /*******************************************************
     *
     *                         ROLE
     *
     *******************************************************/

    /**
     * @return boolean
     */
    public function hasUserRole(): bool
    {
        return !!$this->userRole;
    }

    /**
     * @param string $userRole
     */
    public function setUserRole(string $userRole)
    {
        $this->userRole = $userRole;
    }

    /**
     * @return String|null
     */
    public function getUserRole(): ?string
    {
        return $this->userRole;
    }


    /**********************************************************
     *
     *                     GETTERS / SETTERS
     *
     **********************************************************/

    /**
     * @param string $applicationSlug
     */
    public function setApplicationSlug(string $applicationSlug)
    {
        $this->applicationSlug = $applicationSlug;
    }

    /**
     * @param string $applicationNamespace
     */
    public function setApplicationNamespace(string $applicationNamespace)
    {
        $this->applicationNamespace = $applicationNamespace;
    }

    /**
     * @param string $applicationPath
     */
    public function setApplicationPath(string $applicationPath)
    {
        $this->applicationPath = $applicationPath;
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
        return $this->applicationNamespace ?: Str::camelize($this->applicationSlug);
    }

    /**
     * @return string
     */
    public function getApplicationPath(): ?string
    {
        return $this->applicationPath ?: ($this->applicationBasePath.'/'.$this->applicationSlug);
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