<?php

namespace Mvc;

use Phalcon\Collection;
use Phalcon\Helper\Str;
use Service\Acl;
use Service\Config;
use Service\Database;

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
     * @var string $baseNamespace
     */
    private $baseNamespace = 'Base';

    /**
     * @var string $basePath
     */
    private $basePath = BASE_PATH . "/src";

    /**
     * @var string $basePath
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
     * PSR-4 compliant autoloader for base folder
     * Initialize ApplicationProvider for base
     */
    public function registerBaseProvider()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([$this->baseNamespace => $this->basePath])
            ->register();

        $applicationProvider = (!empty($this->baseNamespace) ? $this->baseNamespace.'\\' : '') . $this->applicationClass;
        new $applicationProvider($this->container);
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
        new $applicationProvider($this->container);
    }

    /**
     * PSR-4 compliant autoloader for module folder
     * Initialize ModuleProvider for each modules defined in configuration
     *
     * TODO this use the default module configuration use by phalcon. This could be improve to just use moduleName
     */
    public function registerModulesProvider()
    {
        $config = $this->container->get('config');

        // Do not load provider in simple application context
        if ($config->get('applicationType') === 'simple') return;

        // Load application or base modules configuration
        if ($this->hasApplication()) {
            $config->mergeConfigFile($this->getApplicationPath().'/config/modules.php', 'modules');
        } else {
            $config->mergeConfigFile($this->getBasePath().'/config/modules.php', 'modules');
        }

        // Initialize moduleProvider for each module
        foreach ($config->get('modules') as $moduleName => $module)
        {
            $moduleNamespace = preg_replace('/\\\\'.$this->moduleClass.'$/', '', $module->get('className'));
            $modulePath = preg_replace('/\/'.$this->moduleClass.'.php$/', '', $module->get('path'));

            (new \Phalcon\Loader())
                ->registerNamespaces([$moduleNamespace => $modulePath])
                ->register();

            $moduleProviderNamespace = $module->get('className');
            new $moduleProviderNamespace($this->container, $moduleName);
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
    private function setApplicationSlug(string $applicationSlug)
    {
        $this->applicationSlug = $applicationSlug;
    }

    /**
     * @param string $applicationPath
     */
    private function setApplicationPath(string $applicationPath)
    {
        $this->applicationPath = $applicationPath;
    }

    /**
     * Change the default namespace used by the application
     *
     * @param string $applicationNamespace
     */
    public function setApplicationNamespace(string $applicationNamespace)
    {
        $this->applicationNamespace = $applicationNamespace;
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
    public function getBaseNamespace(): string
    {
        return $this->baseNamespace;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getBaseModulePath(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->basePath.'/'.$this->moduleBaseDir.'/'.$moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getBaseModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return (!empty($this->baseNamespace) ? $this->baseNamespace.'\\' : '') . $this->moduleBaseNamespace.'\\'.Str::camelize($moduleName);
    }

}