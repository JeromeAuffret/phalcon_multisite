<?php

namespace Component;

use Phalcon\Di;
use Phalcon\Di\Injectable;
use Phalcon\Helper\Str;
use Phalcon\Assets\Collection;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Router;

/**
 * Class Loader
 *
 * @property Session session
 * @property Config config
 * @property Database database
 * @property Loader loader
 * @package Component
 */
final class Loader extends Injectable
{
    /* @var string $application_slug */
    private $application_slug;

    /* @var string $application_path */
    private $application_path;

    /* @var string $application */
    private $application;

    /* @var Config $modules */
    private $modules;

    /**
     *
     */
    public function __construct()
    {
        $this->application_slug = $this->session->getApplication('slug');

        $this->application_path = $this->getApplicationPath($this->application_slug);
        $this->application = $this->getApplicationNamespace($this->application_slug);

        $this->modules = $this->config->get('modules');
    }


    /**********************************************************
     *
     *                          BASE
     *
     **********************************************************/

    /**
     * Register common namespace
     */
    public function registerMainNamespaces()
    {
        (new \Phalcon\Loader())
            ->registerNamespaces([
                'Controllers' => BASE_PATH . '/src/controllers',
                'Models'      => BASE_PATH . '/src/models',
                'Forms'       => BASE_PATH . '/src/forms',

                'Common'              => COMMON_PATH,
                'Common\\Models'      => COMMON_PATH . '/models/',
                'Common\\Traits'      => COMMON_PATH . '/traits/',
                'Common\\Controllers' => COMMON_PATH . '/controllers/',
                'Common\\Forms'       => COMMON_PATH . '/forms/'
            ])
            ->register();
    }


    /**********************************************************
     *
     *                        APPLICATION
     *
     **********************************************************/

    /**
     * Register services for registered or given application
     *
     * @param string|null $application_slug
     */
    public function registerApplicationServices(string $application_slug = null)
    {
        // Register Application Config
        $this->config->registerApplicationConfig($application_slug);

        // Register Application Namespaces
        $this->loader->registerApplicationNamespaces($application_slug);

        // Register Application Database
        $this->database->registerApplicationDatabase();
    }

    /**
     * Loader Application loader and load namespaces in di
     *
     * @param string|null $application_slug
     * @return void
     */
    public function registerApplicationNamespaces(string $application_slug = null)
    {
        $application_path = $this->getApplicationPath($application_slug);
        $application = $this->getApplicationNamespace($application_slug);

        // Register application's namespaces
        (new \Phalcon\Loader())
            ->registerNamespaces([
                $application => $application_path,
                "$application\\Models"      => $application_path.'/models',
                "$application\\Controllers" => $application_path.'/controllers',
                "$application\\Traits"      => $application_path.'/traits',
                "$application\\Forms"       => $application_path.'/forms',
                "$application\\Console"     => $application_path.'/console',
            ])
            ->register();
    }


    /**********************************************************
     *
     *                     BEFORE DISPATCH
     *
     **********************************************************/

    /**
     * Dispatch controller namespace between common and application folders
     *
     * @param Dispatcher $dispatcher
     */
    public function dispatchController(Dispatcher $dispatcher)
    {
        $module_name = $dispatcher->getModuleName();
        $module = Str::camelize($module_name);

        $controller_class = explode('\\', $dispatcher->getControllerClass());
        $controller_file = end($controller_class).'.php';

        $app_controller_module_path = $this->application_path.'/modules/'.$module_name.'/controllers';

        if (end($controller_class) === 'ErrorController') {
            $dispatcher->setNamespaceName('Controllers\\');
        }
        else if($this->application && file_exists($app_controller_module_path.'/'.$controller_file)) {
            $namespace = $this->application.'\Modules\\'.$module.'\Controllers';

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $app_controller_module_path])->register();
            $dispatcher->setNamespaceName($namespace);
        }
    }

    /**
     * Register correct controller in dispatcher based on application defined in session
     *
     * @param Dispatcher $dispatcher
     * @param Router $router
     * @return void
     */
    public function dispatchApiController(Dispatcher $dispatcher, Router $router)
    {
        $module_name = $router->getModuleName();
        $module = Str::camelize($module_name);

        $controller_name = $router->getControllerName();

        $reference_name = $router->getParams()['reference'];
        $reference_controller_file = Str::camelize($reference_name).'Controller.php';

        $app_controller_module_path = $this->application_path.'/modules/'.$module_name.'/controllers/'.$controller_name;
        $common_controller_module_path = COMMON_PATH.'/modules/'.$module_name.'/controllers/'.$controller_name;

        if ($controller_name === 'error') {
            $dispatcher->setNamespaceName('Controllers\\');
        }
        else if ($this->application && file_exists($app_controller_module_path.'/'.$reference_controller_file)) {
            $namespace = $this->application.'\Modules\\'.$module.'\Controllers\\'.$controller_name;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $app_controller_module_path])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($reference_name);
        }
        else if (file_exists($common_controller_module_path.'/'.$reference_controller_file)) {
            $namespace = 'Common\Modules\\'.$module.'\Controllers\\'.$controller_name;

            (new \Phalcon\Loader())->registerNamespaces([$namespace => $common_controller_module_path])->register();
            $dispatcher->setNamespaceName($namespace);
            $dispatcher->setControllerName($reference_name);
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
     * @param string $class_name
     * @param string $base_namespace
     * @param null $application_slug
     * @return string|null
     */
    public function dispatchNamespace(string $class_name, string $base_namespace, $application_slug = null)
    {
        // Get class path base on namespace. This use a lowercase version of PSR-4 standard for folder's name
        $base_path = [];
        foreach (explode('\\', $base_namespace) as $namespace_folder) {
            if (!empty($namespace_folder)) $base_path[] = Str::uncamelize($namespace_folder, '_');
        }
        $base_path = implode('/', $base_path);

        $application_path = $this->getApplicationPath($application_slug);
        $application = $this->getApplicationNamespace($application_slug);

        $app_path = $application_path.'/'.$base_path.'/';
        $common_path = COMMON_PATH.'/'.$base_path.'/';

        $namespace = $path = null;
        if ($application && file_exists($app_path.$class_name.'.php')) {
            $namespace = "$application\\$base_namespace\\$class_name";
        }
        elseif (file_exists($common_path.$class_name.'.php')) {
            $namespace = "Common\\$base_namespace\\$class_name";
        }
        else {
            foreach ($this->modules as $module => $definition) {
                $module_camelize = Str::camelize($module);

                $app_module_path = $application_path.'/modules/'.$module.'/'.$base_path.'/';
                $common_module_path = COMMON_PATH.'/modules/'.$module.'/'.$base_path.'/';

                if ($application && file_exists($app_module_path.$class_name.'.php')) {
                    $namespace = "$application\\Modules\\$module_camelize\\$base_namespace\\$class_name";
                    $path = $app_module_path.$class_name.'.php';
                    break;
                }
                elseif (file_exists($common_module_path.$class_name.'.php')) {
                    $namespace = "Common\\Modules\\$module_camelize\\$base_namespace\\$class_name";
                    $path = $common_module_path.$class_name.'.php';
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
     *                         ASSETS
     *
     **********************************************************/

    /**
     * Register Application general assets
     *
     * @param Collection $collection
     * @param                            $type
     *
     * @return Collection
     */
    public function registerApplicationAssetsCollection(Collection $collection, $type)
    {
        $app_path = $this->getApplicationPath().'/assets/';

        // Load each files in $app_path by type
        $asset_path = [];
        foreach (glob($app_path.'/*.'.$type) as $file_path) {
            array_push($asset_path, $file_path);
        }

        // Then register asset file if exist
        foreach ($asset_path as $path) {
            if ($type === 'css') {
                $collection->addCss($path);
            } elseif($type === 'js') {
                $collection->addJs($path);
            }
        }

        return $collection;
    }

    /**
     * Register Application assets
     * Load assets based on module / controller / action path
     *
     * @param Collection $collection
     * @param string     $type
     * @return Collection
     */
    public function registerViewAssetsCollection(Collection $collection, string $type)
    {
        /* @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        // Common and application assets roots paths
        $app_module_path = $this->getApplicationPath().'/modules/'.$module.'/assets/';
        $common_module_path = COMMON_PATH.'/modules/'.$module.'/assets/';

        // Assets path
        $asset_file_path = $controller.'/'.$action.'.'.$type;

        // Load assets from app module path if exist. If not, use the common path if exist
        $asset_path = [];
        if (file_exists($app_module_path.$asset_file_path)) {
            array_push($asset_path,$app_module_path.$asset_file_path);
        }
        else if (file_exists($common_module_path.$asset_file_path)) {
            array_push($asset_path,$common_module_path.$asset_file_path);
        }

        // Then register asset file if exist
        foreach ($asset_path as $path) {
            if ($type === 'css') {
                $collection->addCss($path);
            } elseif($type === 'js') {
                $collection->addJs($path);
            }
        }

        return $collection;
    }


    /*******************************************************
     *
     *                        HELPERS
     *
     *******************************************************/

    /**
     * @param string|null $application_slug
     * @return null|string
     */
    private function getApplicationPath(string $application_slug = null): ?string
    {
        return $this->session->getApplicationPath($application_slug);
    }

    /**
     * Camelize application_name
     *
     * @param null $application_slug
     * @param string $delimiter
     * @return string|null
     */
    private function getApplicationNamespace($application_slug = null, $delimiter = '_')
    {
        return $this->session->getApplicationNamespace($application_slug, $delimiter);
    }

}