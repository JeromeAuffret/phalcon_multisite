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
     * @var string $applicationClass
     */
    private $moduleClass = 'Module';


    /**********************************************************
     *
     *                        APPLICATION
     *
     **********************************************************/

    /**
     * Initialize application
     *
     * @param string|null $applicationSlug
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

        // Register Application Autoloader
        $this->registerApplicationProvider();
    }


    /**********************************************************
     *
     *                        AUTOLOADER
     *
     **********************************************************/

    /**
     * PSR-4 compliant autoloader for common folder
     * Call ApplicationProvider for common
     */
    public function registerCommonProvider()
    {
        $commonPath = $this->getCommonPath();
        $commonNamespace = $this->getCommonNamespace();

        (new \Phalcon\Loader())
            ->registerNamespaces([$commonNamespace => $commonPath])
            ->register();

        $applicationClass = $commonNamespace.'\\'.$this->applicationClass;
        $applicationClass = new $applicationClass;

        $applicationClass->initialize($this->container);
    }

    /**
     * PSR-4 compliant autoloader for application folder
     * Call ApplicationProvider for application
     */
    public function registerApplicationProvider()
    {
        $applicationPath = $this->getApplicationPath();
        $applicationNamespace = $this->getApplicationNamespace();

        (new \Phalcon\Loader())
            ->registerNamespaces([$applicationNamespace => $applicationPath])
            ->register();

        $applicationClass = $applicationNamespace.'\\'.$this->applicationClass;
        $applicationClass = new $applicationClass;

        $applicationClass->initialize($this->container);
    }

    /**
     * PSR-4 compliant autoloader for application folder
     * Call ModuleProvider for each modules defined in configuration
     *
     * TODO this use the default module configuration use by phalcon. This could be improve to just use moduleName
     */
    public function registerModulesProvider()
    {
        foreach ($this->container->get('config')->get('modules') as $moduleName => $module)
        {
            $this->registerModules([
                $moduleName => [
                    'className' => $module->get('className'),
                    'path' => $module->get('path')
                ],
            ], true );

            $moduleNamespace = preg_replace('/\\\Module$/', '', $module->get('className'));
            $modulePath = preg_replace('/\/Module.php$/', '', $module->get('path'));

            (new \Phalcon\Loader())
                ->registerNamespaces([$moduleNamespace => $modulePath])
                ->register();

            $moduleClass = $module->get('className');
            $moduleClass = new $moduleClass;

            $moduleClass->initialize($this->container, $moduleName);
        }
    }


    /**********************************************************
     *
     *                        NAMESPACES
     *
     **********************************************************/

    /**
     * Dispatch a namespace between common and application folder
     *
     * @param string $className
     * @param string $baseNamespace
     * @return string|null
     */
    public function dispatchNamespace(string $className, string $baseNamespace)
    {
        // Get class path base on namespace. This use a lowercase version of PSR-4 standard for folder's name
        $basePath = [];
        foreach (explode('\\', $baseNamespace) as $namespace_folder) {
            if (!empty($namespace_folder)) $basePath[] = Str::uncamelize($namespace_folder, '_');
        }
        $basePath = implode('/', $basePath);

        $appPath = $this->application->getApplicationPath().'/'.$basePath.'/';
        $commonPath = $this->application->getCommonPath().'/'.$basePath.'/';

        $namespace = $path = null;
        if (file_exists($appPath.$className.'.php')) {
            $namespace = $this->application->getApplicationNamespace()."\\$baseNamespace\\$className";
        }
        elseif (file_exists($commonPath.$className.'.php')) {
            $namespace = $this->application->getCommonNamespace()."\\$baseNamespace\\$className";
        }
        elseif ($this->config->get('applicationType') === 'modules')
        {
            foreach ($this->config->get('modules') as $moduleName => $definition)
            {
                $appModulePath = $this->application->getApplicationModulePath($moduleName).'/'.$basePath.'/';
                $commonModulePath = $this->application->getCommonModulePath($moduleName).'/'.$basePath.'/';

                if (file_exists($appModulePath.$className.'.php')) {
                    $namespace = $this->application->getApplicationModuleNamespace($moduleName)."\\$baseNamespace\\$className";
                    $path = $appModulePath.$className.'.php';
                    break;
                }
                elseif (file_exists($commonModulePath.$className.'.php')) {
                    $namespace = $this->application->getCommonModuleNamespace($moduleName)."\\$baseNamespace\\$className";
                    $path = $commonModulePath.$className.'.php';
                    break;
                }
            }
        }

        // Register namespace before return it
        (new \Phalcon\Loader())->registerClasses([$namespace => $path])->register();

        return $namespace;
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
        $this->applicationPath = $applicationPath ?: ($this->applicationBasePath . '/' . $this->applicationSlug);
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
        return $this->getApplicationPath() . '/modules/' . $moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getApplicationModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->getApplicationNamespace() . '\\Modules\\' . Str::camelize($moduleName);
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
        return $this->getCommonPath() . '/modules/' . $moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function getCommonModuleNamespace(?string $moduleName): ?string
    {
        if (!$moduleName) return null;
        return $this->getCommonNamespace().'\\Modules\\' . Str::camelize($moduleName);
    }

}