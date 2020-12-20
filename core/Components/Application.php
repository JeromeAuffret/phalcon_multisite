<?php

namespace Core\Components;

use Base\Models\Application as ApplicationModel;
use Libraries\NamespaceHelper;
use Phalcon\Application\AbstractApplication;
use Phalcon\Collection;
use Phalcon\Dispatcher\Exception as DispatcherException;
use Phalcon\Helper\Str;

/**
 * Class Database
 *
 * @package Component
 */
final class Application extends AbstractApplication
{
    /**
     * @var string $baseNamespace
     */
    private $baseNamespace = 'Base';

    /**
     * @var string $basePath
     */
    private $basePath = BASE_PATH . "/src/shared";

    /**
     * @var string $basePath
     */
    private $tenantBasePath = BASE_PATH . "/src/apps";

    /**
     * @var string $tenantClass
     */
    private $tenantClass = 'Tenant';

    /**
     * @var string $tenantClass
     */
    private $moduleClass = 'Module';

    /**
     * @var string $tenantClass
     */
    private $moduleBaseNamespace = 'Modules';

    /**
     * @var string $tenantClass
     */
    private $moduleBaseDir = 'modules';

    /**
     * @var Collection $tenant
     */
    private $tenant = null;

    /**
     * @var Collection $user
     */
    private $user = null;

    /**
     * @var String $userRole
     */
    private $userRole = null;

    /**
     * @var string $tenantSlug
     */
    private $tenantSlug = null;

    /**
     * @var string $tenantNamespace
     */
    private $tenantNamespace = null;

    /**
     * @var string $tenantPath
     */
    private $tenantPath = null;


    /**********************************************************
     *
     *                        AUTOLOADER
     *
     **********************************************************/

    /**
     * PSR-4 compliant autoloader for base folder
     * Initialize TenantProvider for base
     */
    public function registerBaseProvider()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([$this->getBaseNamespace() => $this->getBasePath()])
            ->register();

        $applicationProvider = (!empty($this->getBaseNamespace()) ? $this->getBaseNamespace().'\\' : '') . $this->getTenantClass();
        new $applicationProvider($this->container);
    }

    /**
     * PSR-4 compliant autoloader for tenant folder
     * Initialize TenantProvider for tenant
     */
    public function registerTenantProvider()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([$this->getTenantNamespace() => $this->getTenantPath()])
            ->register();

        $applicationProvider = $this->getTenantNamespace().'\\'.$this->getTenantClass();
        new $applicationProvider($this->container);
    }

    /**
     * PSR-4 compliant autoloader for module folder
     * Initialize ModuleProvider for each modules defined in configuration
     *
     * TODO This use the default module configuration use by phalcon. It could be improve to just use moduleName
     */
    public function registerModulesProviders()
    {
        $config = $this->container->get('config');

        // Do not load module providers in simple tenant context
        if ($config->get('tenantType') === 'simple') return;

        // Load tenant modules configuration
        if ($this->hasTenant()) {
            $config->mergeConfigFile($this->getTenantPath().'/config/modules.php', 'modules');
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
     *                         HANDLER
     *
     **********************************************************/

    /**
     * @return bool
     */
    public function isCli(): bool
    {
        return php_sapi_name() === "cli";
    }

    /**
     * @return bool
     */
    public function isMvc(): bool
    {
        return !(php_sapi_name() === "cli");
    }


    /**********************************************************
     *
     *                         TENANT
     *
     **********************************************************/

    /**
     * @param array $tenant
     */
    public function registerTenant(array $tenant)
    {
        $this->tenant = new Collection($tenant);

        if ($this->tenant->has('slug')) {
            $this->setTenantSlug($this->tenant->get('slug'));
        }
    }

    /**
     * @param string $tenantSlug
     * @throws DispatcherException
     */
    public function registerTenantBySlug(string $tenantSlug)
    {
        /** @var ApplicationModel $tenant */
        $tenant = NamespaceHelper::dispatchNamespace(ApplicationModel::class);
        $tenant = $tenant::getBySlug($tenantSlug);

        // Throw exception if tenant is not found in database
        if (!$tenant) throw new DispatcherException(
            'Tenant '.$tenantSlug.' not found',
            DispatcherException::EXCEPTION_HANDLER_NOT_FOUND
        );

        $this->registerTenant($tenant->toArray());
    }

    /**
     * @return bool
     */
    public function hasTenant(): bool
    {
        return !!$this->tenant;
    }

    /**
     * Return tenant collection o given key value
     *
     * @param mixed|null $key
     * @return Collection|mixed|null
     */
    public function getTenant($key = null)
    {
        if (!$key)
            return $this->tenant;
        elseif ($this->tenant && $this->tenant->has($key))
            return $this->tenant->get($key);
        else
            return null;
    }

    /**
     * Set tenant key value
     *
     * @param $key
     * @param $value
     */
    public function setTenant($key, $value)
    {
        if ($this->hasTenant()) {
            $tenant = $this->tenant;
            $tenant->set($key, $value);
            $this->tenant = $tenant;
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
     * @param string $tenantSlug
     */
    private function setTenantSlug(string $tenantSlug)
    {
        $this->tenantSlug = $tenantSlug;
    }

    /**
     * @param string $tenantPath
     */
    private function setTenantPath(string $tenantPath)
    {
        $this->tenantPath = $tenantPath;
    }

    /**
     * Change the default namespace used by the tenant
     *
     * @param string $tenantNamespace
     */
    public function setTenantNamespace(string $tenantNamespace)
    {
        $this->tenantNamespace = $tenantNamespace;
    }

    /**
     * @return string
     */
    public function getTenantClass(): string
    {
        return $this->tenantClass;
    }

    /**
     * @return string
     */
    public function getTenantSlug(): ?string
    {
        return $this->tenantSlug;
    }

    /**
     * @return string
     */
    public function getTenantNamespace(): ?string
    {
        return $this->tenantNamespace ?: Str::camelize($this->tenantSlug);
    }

    /**
     * @return string
     */
    public function getTenantPath(): ?string
    {
        return $this->tenantPath ?: ($this->tenantBasePath.'/'.$this->tenantSlug);
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getTenantModulePath(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->getTenantPath().'/'.$this->moduleBaseDir.'/'.$moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getTenantModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->getTenantNamespace().'\\'.$this->moduleBaseNamespace.'\\'.Str::camelize($moduleName);
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
        return $this->getBasePath().'/'.$this->moduleBaseDir.'/'.$moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getBaseModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return (!empty($this->getBaseNamespace()) ? $this->getBaseNamespace().'\\' : '') . $this->moduleBaseNamespace.'\\'.Str::camelize($moduleName);
    }

}